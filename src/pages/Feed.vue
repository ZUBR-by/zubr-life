<template>
    <div class="section zbr-promo">
        <div class="columns is-centered">
            <div class="column is-half">
                <table class="table">
                    <thead>
                    <tr>
                        <th><h3 class="content is-medium has-text-weight-bold">
                            Лента новостей
                        </h3></th>
                        <th>Дата</th>
                        <th>
                            <button class="button" @click="dialogVisible = true">Добавить</button>
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="item of feed">
                        <th>
                            <router-link :to="{name: item.type, params: {id: item.id}}">{{ item.name }}</router-link>
                        </th>
                        <th>{{ item.created_at }}</th>
                        <th style="text-align: center">
                            <div class="tag"
                                 :class="{'is-primary' : item.type === 'ad', 'is-danger': item.type === 'event'}">
                                {{ item.type === 'event' ? 'Событие' : 'Объявление' }}
                            </div>
                        </th>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <el-dialog v-model="dialogVisible" :append-to-body="true" width="30%">
        <form @submit.prevent="save">
            <article class="message is-danger" v-show="error">
                <div class="message-body">
                    {{ error }}
                </div>
            </article>
            <div class="field">
                <label class="label">Название</label>
                <div class="control">
                    <input class="input is-small"
                           type="text" v-model="form.name" required>
                </div>
            </div>
            <div class="field">
                <label class="label">Тип</label>
                <div>
                    <el-radio-group v-model="form.type" size="mini">
                        <el-radio-button :label="'event'">Событие</el-radio-button>
                        <el-radio-button :label="'ad'">Объявление</el-radio-button>
                    </el-radio-group>
                </div>
            </div>
            <div class="field calendar">
                <label class="label">Дата</label>
                <div>
                    <datepicker v-model="form.created_at"
                                class="input is-small"
                                :locale="locale" style="width: 140px"></datepicker>
                </div>
            </div>
            <div class="field">
                <label class="label">Описание</label>
                <div class="control">
                    <textarea class="textarea"
                              rows="3"
                              v-model="form.description"></textarea>
                </div>
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
                        accept="image/*,video/*,audio/*,application/pdf"
                        :on-exceed="handleExceed"
                        :file-list="fileList">
                        <button class="button is-inverted is-small" type="button">
                            <span class="icon">
                              <i class="fas fa-paperclip"></i>
                            </span>
                            <span>Прикрепить файл(ы)</span>
                        </button>
                    </el-upload>
                </div>
            </div>
            <div class="field is-grouped">
                <div class="control">
                    <el-button size="small" type="primary" native-type="submit">Сохранить</el-button>
                </div>
                <div class="control">
                    <el-button native-type="button"
                               size="small"
                               @click="dialogVisible = false">
                        Отмена
                    </el-button>
                </div>
            </div>
        </form>
    </el-dialog>
</template>

<script>

import {ElMessage, ElUpload, ElDialog, ElRadioButton, ElRadioGroup, ElButton} from 'element-plus';
import datepicker                                                   from 'vue3-datepicker'
import locale                                                       from 'date-fns/locale/ru'

let emptyForm = {name: '', description: '', type: 'event', attachments: []};

export default {
    components: {
        ElUpload, ElDialog, ElRadioButton, ElRadioGroup, datepicker, ElButton
    },
    created() {
        this.fetchFeed();
    },
    data() {
        return {
            feed         : [],
            error        : null,
            fileList     : [],
            dialogVisible: false,
            locale,
            form         : {
                text       : '',
                description: '',
                attachments: [],
                created_at : new Date(),
                type       : 'event'
            },
        }
    },
    methods: {
        handleExceed(files, fileList) {
            ElMessage.error('Максимум три файла!')
        },
        handleRemove(file) {
            this.form.attachments = this.form.attachments.filter(i => file.uid !== i.uid)
        },
        onChange(file) {
            this.form.attachments.push(file)
        },
        fetchFeed() {
            fetch(import.meta.env.VITE_TELEGRAM_API_URL + '/feed')
                .then(r => r.json())
                .then(
                    r => {
                        this.feed = r.data;
                    }
                )
        },
        save() {
            const formData = new FormData();
            formData.append('description', this.form.description);
            formData.append('name', this.form.name);
            formData.append('type', this.form.type);
            formData.append('created_at', this.form.created_at.toISOString());

            this.form.attachments.forEach((elem, index) => {
                formData.append('attachment' + index, elem.raw);
            })

            fetch(import.meta.env.VITE_TELEGRAM_API_URL + '/feed', {
                method     : 'POST',
                credentials: 'include',
                body       : formData
            }).then(r => r.json())
                .then(result => {
                        if (result.error) {
                            this.error = result.error;
                            return;
                        }
                        this.fetchFeed();
                        this.form = Object.assign({}, emptyForm)
                        this.$refs.upload.clearFiles()
                        this.dialogVisible = false
                        ElMessage.success({'message': 'Добавлено'})
                    }
                )
                .catch(e => {
                    ElMessage.error({'message': 'Произошла ошибка'})
                    throw e;
                })
        }
    }
}
</script>

<style>
.calendar {
    --vdp-hover-bg-color: #FF5C01;
    --vdp-selected-bg-color: #FF5C01;
}

@media (max-width: 820px) {
    .el-dialog {
        width: 80% !important;
    }
}
</style>
