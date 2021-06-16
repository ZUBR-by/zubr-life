<template>
    <div>
        <el-card class="box-card mt-2" v-for="comment of list">
            <template #header>
                <div class="clearfix">
                    <el-button class="button"
                               type="text">Анонимный автор
                    </el-button>
                    <el-button class="button"
                               :title="comment.created_at"
                               style="float: right; padding: 0"
                               type="text">{{ comment.created_at_formatted }}
                    </el-button>
                </div>
            </template>
            <div>
                {{ comment.text }}
                <template v-for="link of comment.attachments.filter(i => i.type === 'link')">
                    <a :href="link.value">
                        {{ link.name ? link.name : link.value }}
                    </a>
                    &nbsp;
                </template>
            </div>
        </el-card>
        <form @submit.prevent="save" class="pt-3">
            <div class="field is-grouped">
                <p class="control is-expanded">
                    <textarea class="textarea" v-model="form.text" rows="1"></textarea>
                </p>
                <p class="control">
                    <button class="button is-outlined" type="submit">
                        <span class="icon">
                          <i class="fas fa-paper-plane fa-lg"></i>
                        </span>
                    </button>
                </p>
            </div>
            <div class="field">
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
                        accept="image/*,video/*,audio/*"
                        :on-exceed="handleExceed"
                        :file-list="fileList">
                        <button class="button is-inverted" type="button">
                            <span class="icon">
                              <i class="fas fa-paperclip"></i>
                            </span>
                            <span>Прикрепить файл(ы)</span>
                        </button>
                    </el-upload>
                </div>
            </div>
        </form>
    </div>


</template>

<script>
import {ElButton, ElCard, ElUpload, ElMessage} from "element-plus";

const emptyComment = {
    text       : '',
    attachments: [],
}

export default {
    components: {
        ElCard, ElButton, ElUpload
    },
    props     : {
        type: String,
        id  : Number
    },
    created() {
        this.fetchComments()
    },
    methods: {
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
                console.log(elem)
                formData.append('attachment' + index, elem.raw);
            })

            fetch(import.meta.env.VITE_TELEGRAM_API_URL + '/comment', {
                'method'     : 'POST',
                'body'       : formData,
                'credentials': 'include'
            }).then((r) => {
                this.fetchComments()
                Object.assign(this.form, emptyComment)
                this.$refs.upload.clearFiles()
            })
        },
        fetchComments() {
            fetch(import.meta.env.VITE_TELEGRAM_API_URL + '/comment/' + this.type + '/' + this.id)
                .then(r => r.json())
                .then(
                    r => {
                        this.list = r.data;
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
            fileList     : [],
            list         : [],
            dialogVisible: false
        }
    }
}
</script>

<style>
.el-card__header {
    padding: 0 10px !important;
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
