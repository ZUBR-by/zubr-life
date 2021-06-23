<template>
    <div>
        <el-card class="box-card mt-2 mb-2" v-for="comment of comments">
            <template #header>
                <div class="clearfix pl-2">
                    <el-button class="button"
                               type="text" style="font-size: 14px">Анонимный автор
                    </el-button>
                    <el-button class="button"
                               v-if="comment.can_delete"
                               @click="archiveComment(comment.id)"
                               style="float: right;padding-left: 10px;padding-right: 10px"
                               icon="el-icon-close"
                               type="text">
                    </el-button>
                    <el-button class="button"
                               :title="comment.created_at"
                               style="float: right; padding: 0;font-size: 14px"
                               type="text">{{ comment.created_at_formatted }}
                    </el-button>
                </div>
            </template>
            <p class="pr-2 comment-text" v-html="linkify(comment.text)">
            </p>
            <template v-for="link of comment.attachments.filter(i => i.type === 'link')">
                <a :href="link.value">
                    {{ link.name ? link.name : link.value }}
                </a>
                &nbsp;
            </template>
            <template v-if="comment.attachments && comment.attachments.filter(i => i.type !== 'link').length > 0">
                <hr style="margin-top:5px;margin-bottom: 5px">
                <span class="pr-3" v-for="(attachment, index) of comment.attachments.filter(i => i.type !== 'link')">
                    <a :href="attachment.value" target="_blank" style="font-size: 13px">
                        Прикрепленный файл {{ index + 1 }}
                    </a>
                </span>
            </template>
        </el-card>
        <a @click="showAll = true" v-if="!showAll" class="mt-3">
            Показать все комментарии({{ list.length }})...
        </a>
        <form @submit.prevent="save" class="pt-3">
            <div class="field is-grouped">
                <p class="control is-expanded">
                    <el-input
                        type="textarea"
                        :placeholder="''"
                        resize="vertical"
                        rows="3"
                        v-model="form.text"
                        maxlength="380"
                        show-word-limit
                    >
                    </el-input>
                </p>
            </div>
            <div class="field is-grouped">
                <div class="control">
                    <el-upload
                        class="upload-demo"
                        :auto-upload="false"
                        ref="upload"
                        action="https://jsonplaceholder.typicode.com/posts/"
                        :on-change="onChange"
                        :on-remove="handleRemove"
                        multiple
                        :limit="3"
                        accept="image/*,video/*,audio/*,application/pdf"
                        :on-exceed="handleExceed"
                        :file-list="fileList">
                        <button class="button is-inverted" ref="upload_btn" type="button">
                            <span class="icon">
                              <i class="fas fa-paperclip"></i>
                            </span>
                            <span>Прикрепить файл(ы)</span>
                        </button>
                    </el-upload>
                </div>
                <p class="control" >
                    <button class="button is-outlined" :class="{'is-loading': isLoading}"
                            :disabled="form.text.length === 0"
                            type="submit">
                        <span class="icon">
                          <i class="fas fa-paper-plane fa-lg"></i>
                        </span>
                        <span>Отправить</span>
                    </button>
                </p>

            </div>
        </form>
    </div>


</template>

<script>
import {ElButton, ElCard, ElUpload, ElMessage, ElIcon, ElInput} from "element-plus";
import linkifyHtml                                              from 'linkifyjs/html';

const emptyComment = {
    text       : '',
    attachments: [],
}

export default {
    components: {
        ElCard, ElButton, ElUpload, ElIcon, ElInput
    },
    props     : {
        type: String,
        id  : Number
    },
    created() {
        this.fetchComments()
    },
    computed: {
        comments() {
            return this.showAll ? this.list : this.list.slice(0, 2)
        }
    },
    methods : {
        linkify(text) {
            return linkifyHtml(text);
        },
        handleRemove(file) {
            this.form.attachments = this.form.attachments.filter(i => file.uid !== i.uid)
        },
        onChange(file) {
            this.form.attachments.push(file)
        },
        handleExceed(files, fileList) {
            ElMessage.error('Максимум три файла!')
        },
        save() {
            const formData = new FormData();
            formData.append('text', this.form.text);
            formData.append('type', this.type);
            formData.append('id', this.id + '');

            this.form.attachments.forEach((elem, index) => {
                formData.append('attachment' + index, elem.raw);
            })
            this.isLoading = true;
            fetch(import.meta.env.VITE_TELEGRAM_API_URL + '/comment', {
                'method'     : 'POST',
                'body'       : formData,
                'credentials': 'include'
            }).then((r) => r.json()).then((r) => {
                this.isLoading = false;
                if (r.error) {
                    ElMessage.error(r.error)
                    return;
                }
                this.fetchComments()
                Object.assign(this.form, emptyComment)
                this.$refs.upload.clearFiles()
            }).catch(e => {
                this.isLoading = false;
                ElMessage.error('Произошла ошибка')
                throw e;
            })
        },
        fetchComments() {
            fetch(import.meta.env.VITE_TELEGRAM_API_URL + '/comment/' + this.type + '/' + this.id,
                {credentials: 'include'}
            )
                .then(r => r.json())
                .then(
                    r => {
                        this.list = r.data;
                        if (this.list.length > 2) {
                            this.showAll = false
                        }
                    }
                )
        },
        archiveComment(id) {
            fetch(import.meta.env.VITE_TELEGRAM_API_URL + '/comment/' + id,
                {
                    credentials: 'include',
                    method     : 'DELETE'
                }
            )
                .then(r => r.json())
                .then(
                    () => {
                        this.fetchComments()
                    }
                )
        }
    },
    data() {
        return {
            form         : {
                text       : '',
                attachments: [],
            },
            isLoading    : false,
            fileList     : [],
            list         : [],
            showAll      : true,
            dialogVisible: false
        }
    }
}
</script>

<style>
.el-card__header {
    padding: 0 10px !important;
}

.comment-text {
    word-wrap: break-word;
    white-space: pre-wrap;
    font-size: 14px
}

.clearfix:before,
.clearfix:after {
    display: table;
    content: "";
}

.clearfix:after {
    clear: both
}
</style>
