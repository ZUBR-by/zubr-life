<template>
    <el-carousel trigger="click" :arrow="'always'" :autoplay="false" v-if="collection.length > 0" style="max-height: 800px">
        <el-carousel-item v-for="item of collection" :key="item.value">
            <div class="columns is-centered">
                <el-image :src="item.value"
                          v-if="item.type === 'image'"
                          class="pl-6 pr-6"
                          :preview-src-list="[item.value]"
                          :append-to-body="true"
                          style="width: 600px;height: 600px;margin:auto"
                          :fit="'scale-down'"></el-image>
                <div v-else-if="item.type === 'video'">
                    <video controls style="height: auto;">
                        <source :src="item.value">
                    </video>
                </div>
                <template v-else>
                    {{item}}
                </template>
            </div>
        </el-carousel-item>
    </el-carousel>
</template>

<script>
import {ElCarousel, ElCarouselItem, ElImage} from "element-plus";

export default {
    components: {
        ElCarousel, ElCarouselItem, ElImage
    },
    props     : {
        collection: Array
    },

}
</script>

<style>
.el-carousel__container {
    min-height: 600px;
    max-height: 1000px;
    position: relative;
}
</style>
