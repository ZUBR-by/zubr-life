<template>
    <div class="section zbr-promo">
        <div class="columns is-centered">
            <div class="column is-two-thirds">
                <div class="panel" v-if="ad.name">
                    <nav class="breadcrumb pt-5 pl-3 is-medium" aria-label="breadcrumbs">
                        <ul>
                            <li>
                                <router-link :to="{name: 'feed'}">
                                    <button class="button is-primary is-inverted">
                                        <span class="icon is-small">
                                          <i class="fas fa-arrow-left"></i>
                                        </span>
                                        <span>Лента новостей</span>
                                    </button>
                                </router-link>
                            </li>
                        </ul>
                    </nav>
                    <hr>
                    <h3 class="is-size-4 pl-3">{{ ad.name }}</h3>
                    <div class="columns pt-2 pl-3 pr-3">
                        <div class="column">
                            <el-carousel trigger="click" :arrow="'always'" :autoplay="false" v-if="images.length > 0">
                                <el-carousel-item v-for="item of images" :key="item.value">
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
                        </div>
                    </div>
                    <div class="pl-5 pt-3 pb-4 pr-5" style="min-height: 300px;">
                        <el-tabs v-model="activeName">
                            <el-tab-pane label="Место" name="place" v-if="ad.longitude">
                                <place :latitude="ad.latitude" :longitude="ad.longitude"></place>
                            </el-tab-pane>
                            <el-tab-pane :label="'Комментарии' + '(' + ad.comments_count +')'" name="comments">
                                <div class="card"
                                     v-for="comment of ad.comments"
                                     v-if="ad.comments_count > 0">
                                    <div class="card-content">
                                        <div class="content">
                                            {{ comment.text }}
                                        </div>
                                    </div>
                                    <footer class="card-footer">
                                        <a href="#" class="card-footer-item">Author</a>
                                        <a href="#" class="card-footer-item">Дата</a>
                                    </footer>
                                </div>

                            </el-tab-pane>
                        </el-tabs>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import place                                                    from "../components/place.vue";
import {ElCarousel, ElCarouselItem, ElImage, ElTabPane, ElTabs} from "element-plus";

export default {
    components: {
        place,
        ElTabPane,
        ElTabs,
        ElCarousel,
        ElCarouselItem,
        ElImage
    },
    created() {
        this.fetchAd();
    },
    computed: {
        images() {
            if (!this.ad.attachments) {
                return [];
            }
            return this.ad.attachments.filter(item => item.type === 'image')
        }
    },
    data() {
        return {
            ad: {},
            activeName: 'place'
        }
    },
    methods: {
        fetchAd() {
            fetch(import.meta.env.VITE_TELEGRAM_API_URL + '/ad/' + this.$route.params.id)
                .then(r => r.json())
                .then(
                    r => {
                        this.ad = r.data;
                    }
                )
        }
    }
}
</script>

<style scoped>

</style>
