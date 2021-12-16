import {ElMessage} from "element-plus";

function handle(response) {
    if (!response.ok) {
        ElMessage.error('Произошла ошибка');
        throw {
            'message': 'invalid_response',
            response
        }
    }
    return response.json();
}

export {handle}
export default handle
