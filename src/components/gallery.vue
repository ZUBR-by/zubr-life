<template>
    <el-image v-if="collection.length === 1"
              v-for="item of collection"
              :src="item.value"
              :preview-src-list="[item.value]"
              :append-to-body="true"
              style="width: 600px; height: 400px"
              :fit="'scale-down'"></el-image>
    <el-carousel trigger="click" :arrow="'always'" :autoplay="false" v-if="collection.length > 1">
        <el-carousel-item v-for="item of collection" :key="item.value">
            <div class="columns is-centered">
                <el-image :src="item.value"
                          class="pl-6 pr-6"
                          :preview-src-list="[item.value]"
                          :append-to-body="true"
                          style="width: 600px; height: 400px"
                          :fit="'scale-down'"></el-image>
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

<style scoped>

</style>
