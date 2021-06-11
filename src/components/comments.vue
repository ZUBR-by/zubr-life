<template>
    <el-card class="box-card" v-for="comment of comments">
        <template #header>
            <div class="card-header">
                <span>Анонимный автор</span>
                <el-button class="button" type="text">{{ comment.created_at }}</el-button>
            </div>
        </template>
        <div>
            {{ comment.text }}
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

</style>
