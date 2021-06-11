<template>
    <div class="section zbr-promo">
        <div class="columns is-centered">
            <div class="column is-two-thirds">
                <div class="panel" v-if="event.name">
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
                    <h3 class="is-size-4 pl-5">{{ event.name }}</h3>
                    <div class="columns pt-2 pl-5 pr-3">
                        <div class="column">
                            <images :images="images"></images>
                        </div>
                    </div>
                    <div class="pl-5 pt-3 pb-4 pr-5" style="min-height: 300px;">
                        <el-tabs v-model="activeName">
                            <el-tab-pane label="Место" name="place" v-if="event.longitude">
                                <place :latitude="event.latitude" :longitude="event.longitude"></place>
                            </el-tab-pane>
                            <el-tab-pane :label="'Комментарии' + '(' + event.comments_count +')'" name="comments">
                                <comments :comments="comments"></comments>
                            </el-tab-pane>
                        </el-tabs>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

import {ElTabPane, ElTabs, ElImage} from "element-plus";
import Map                          from "../components/place.vue";
import Images                       from "../components/images.vue";
import Comments                     from "../components/comments.vue";

export default {
    components: {
        Comments,
        Images,
        'place': Map,
        ElTabPane,
        ElTabs,
        ElImage
    },
    created() {
        this.fetchEvent();
    },
    computed: {
        comments() {
            return this.event.comments_count ? this.event.comments : [];
        },
        images() {
            if (!this.event.attachments) {
                return [];
            }
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
