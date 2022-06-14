import {HandlerEvent, HandlerResponse, Handler} from "@netlify/functions";
import {verify} from "jsonwebtoken";
import Busboy from "busboy";
import fetch from 'node-fetch'
import AWS from 'aws-sdk';
import {v4 as uuidv4} from 'uuid';

const formatCookie = (raw: string[]): { [key: string]: string } => {
    let tmp: { [key: string]: string } = {};
    for (let item of raw) {
        const [key, value] = item.split('=')
        tmp[key] = value
    }
    return tmp
}

const s3 = new AWS.S3({
    region: process.env.AWS_S3_REGION,
    accessKeyId: process.env.AWS_S3_ACCESS_KEY,
    secretAccessKey: process.env.AWS_S3_SECRET_KEY,
})

const processImageUpload = async (event: HandlerEvent): Promise<any> => {
    return new Promise((resolve, reject) => {
        const busboy = Busboy({
            headers: {
                ...event.headers,
                "content-type":
                    event.headers["Content-Type"] ?? event.headers["content-type"],
            }
        });
        const result: any = {fields: {}, files: []};

        busboy.on("file",
            (
                _fieldname: any, file: any, fileName: any, encoding: any, contentType: any
            ) => {
                file.on("data", (data: any) => {
                    result.files.push({
                        file: data,
                        fileName,
                        encoding,
                        contentType,
                    });
                });
            });

        busboy.on("field", (fieldName: any, value: any) => {
            result.fields[fieldName] = value;
        });

        busboy.on("finish", () => resolve(result));
        busboy.on("error", (error: any) => reject(`Parse error: ${error}`));
        busboy.write(event.body, event.isBase64Encoded ? "base64" : "binary");

        busboy.end();
    });
};

const handler: Handler = async (event: HandlerEvent): Promise<HandlerResponse> => {
    if (!event.body || !event.isBase64Encoded) {
        return {
            statusCode: 400,
            body: JSON.stringify({
                message: "no data",
            }),
            headers: {
                'Content-Type': 'application/json;charset=utf8',
            }
        };
    }
    console.log(event.multiValueHeaders, event.headers)
    //todo better validation
    if (!event.multiValueHeaders.Cookie && !event.multiValueHeaders.cookie) {
        return {
            body: JSON.stringify({error: "not_found_cookies"}),
            statusCode: 400,
            headers: {
                'Content-Type': 'application/json;charset=utf8',
            }
        }
    }
    const cookies = formatCookie(event.multiValueHeaders.cookie || event.multiValueHeaders.Cookie)
    if (!cookies['AUTH']) {
        return {
            body: JSON.stringify({error: "not_found_cookies_auth"}),
            statusCode: 400,
            headers: {
                'Content-Type': 'application/json;charset=utf8',
            }
        }
    }
    let decoded;
    try {
        decoded = verify(cookies['AUTH'], process.env.PUBLIC_KEY.replace(/\\n/g, '\n'));
    } catch (err) {
        console.error(decoded)
        return {
            body: JSON.stringify({error: "invalid_cookie_auth"}),
            statusCode: 400,
            headers: {
                'Content-Type': 'application/json;charset=utf8',
            }
        }
    }
    const {files, fields} = await processImageUpload(event);
    const uploads: any[] = [];
    if (files.length > 0) {
        for (let item of files) {
            const fileName = uuidv4();
            //todo handling errors
            await s3.putObject(
                {
                    Bucket: process.env.S3_BUCKET,
                    Key: fileName,
                    Tagging: 'type=comments',
                    Metadata: {
                        [fields['type'] + '_id']: fields['id']
                    },
                    Body: item.file,
                    ContentType: item.fileName.mimeType,
                    ACL: 'public-read',
                }
            ).promise()
            uploads.push({
                type: item.fileName.mimeType.split('/')[0],
                mime: item.fileName.mimeType,
                url: `https://${process.env.S3_BUCKET}.s3.${process.env.AWS_S3_REGION}.amazonaws.com/${fileName}`
            })
        }
    }

    // language=GraphQL
    const query = `
        mutation($comment: comment_insert_input!) {
            insert_comment_one(object: $comment) {
                id
            }
        }
    `
    const response = await fetch(
        process.env.API_URL,
        {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Cookie': 'AUTH=' + cookies['AUTH']
            },
            body: JSON.stringify({
                query,
                variables: {
                    comment: {
                        text: fields['text'],
                        [fields['type'] + '_id']: parseInt(fields['id']),
                        attachments: uploads
                    }
                }
            })
        }
    )

    return {
        body: await response.text(),
        statusCode: 200,
        headers: {
            'Content-Type': 'application/json;charset=utf8',
        }
    }
}


export {handler}
