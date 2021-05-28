<template>
    <div class="section zbr-promo">
        <div class="column is-centered">
            <el-row :gutter="12">
                <el-card shadow="always">
                    <el-col :span="4">
                        Test
                    </el-col>
                    <el-col :span="12">
                        <button @click="test">Click</button>
                    </el-col>
                </el-card>
            </el-row>
            <el-row :gutter="12">
                <el-card shadow="always">
                    Never
                </el-card>
            </el-row>
            <el-row :gutter="12">
                <el-card shadow="always">
                    Never
                </el-card>
            </el-row>
        </div>
    </div>
</template>

<script>
import {ElCard, ElRow, ElCol} from 'element-plus';

export default {
    name      : "People",
    components: {
        ElCard,
        ElRow,
        ElCol
    },
    methods: {
        test() {
            throw 'test exception'
        }
    }
}
</script>

<style scoped>

</style>
