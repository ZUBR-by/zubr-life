<template>
    <div>
        <button class="button" @click="dialogVisible = true">Добавить</button>
        <el-card class="box-card" v-for="comment of comments">
            <template #header>
                <div class="clearfix">
                    <el-button class="button"
                               style="padding: 3px 0"
                               type="text">Анонимный автор
                    </el-button>
                    <el-button class="button"
                               style="float: right; padding: 3px 0"
                               type="text">{{ comment.created_at }}
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
        <el-dialog
            title="Добавить комментарий"
            v-model="dialogVisible">
            <form @submit.prevent="save">
                <div class="field">
                    <label class="label">Описание</label>
                    <div class="control">
                        <textarea class="textarea" v-model="form.description"></textarea>
                    </div>
                </div>
                <div class="field">
                    <label class="label">Изображение</label>
                    <div class="control">
                        <input type="file"/>
                    </div>
                </div>
            </form>
            <template #footer>
                <span class="dialog-footer">
                  <el-button @click="dialogVisible = false">Cancel</el-button>
                  <el-button type="primary" @click="dialogVisible = false">Confirm</el-button>
                </span>
            </template>
        </el-dialog>
    </div>


</template>

<script>
import {ElButton, ElCard, ElDialog} from "element-plus";

export default {
    components: {
        ElCard, ElButton, ElDialog
    },
    props     : {
        comments: Array,
    },
    methods: {
        save() {

        }
    },
    data() {
        return {
            form: {
                description: ''
            },
            dialogVisible: false
        }
    }
}
</script>

<style scoped>

.clearfix:before,
.clearfix:after {
    display: table;
    content: "";
}

.clearfix:after {
    clear: both
}
</style>
