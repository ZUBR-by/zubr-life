<template>
    <div class="section zbr-promo">
        <div class="columns is-centered">
            <div class="column is-two-thirds">
                <div class="panel" v-if="event.attachments">
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
                    <h3 class="is-size-4 pl-3">{{ event.name }}</h3>
                    <div class="columns pt-2 pl-3 pr-3">
                        <div class="column">
                            <el-carousel trigger="click" :arrow="'always'" :autoplay="false">
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
                            <el-tab-pane label="Место" name="place" v-if="event.longitude">
                                <place :latitude="event.latitude" :longitude="event.longitude"></place>
                            </el-tab-pane>
                            <el-tab-pane :label="'Комментарии' + '(' + event.comments_count +')'" name="comments">
                                <div class="card"
                                     v-for="comment of event.comments"
                                     v-if="event.comments_count > 0">
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

import {ElTabPane, ElTabs, ElCarousel, ElCarouselItem, ElImage} from "element-plus";
import Map                                                      from "../components/place.vue";

export default {
    components: {
        'place': Map,
        ElTabPane,
        ElTabs,
        ElCarousel,
        ElCarouselItem,
        ElImage
    },
    created() {
        this.fetchEvent();
    },
    computed: {
        images() {
            return this.event.attachments.filter(item => item.type === 'image')
        }
    },
    data() {
        return {
            event     : {},
            activeName: 'place'
        }
    },
    methods: {
        fetchEvent() {
            fetch(import.meta.env.VITE_TELEGRAM_API_URL + '/event/' + this.$route.params.id)
                .then(r => r.json())
                .then(
                    r => {
                        this.event = r.data;
                    }
                )
        },
    },

}
</script>

<style>
.el-carousel__container {
    height: 400px !important;
    position: relative;
}
</style>
