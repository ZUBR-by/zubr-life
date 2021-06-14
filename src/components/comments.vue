<template>
    <el-card class="box-card" v-for="comment of comments">
        <template #header>
            <div class="clearfix">
                <el-button class="button"
                           style="padding: 3px 0"
                           type="text">Анонимный автор</el-button>
                <el-button class="button"
                           style="float: right; padding: 3px 0"
                           type="text">{{ comment.created_at }}</el-button>
            </div>
        </template>
        <div>
            {{ comment.text }}
            <template v-for="link of comment.attachments.filter(i => i.type === 'link')">
                <a :href="link.value">
                    {{link.name ? link.name : link.value}}
                </a>
                &nbsp;
            </template>

        </div>
    </el-card>
</template>

<script>
import {ElButton, ElCard} from "element-plus";

export default {
    components: {
        ElCard, ElButton
    },
    props     : {
        comments: Array,
    },
    name      : "comments"
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
