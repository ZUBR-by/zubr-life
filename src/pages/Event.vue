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
                    <article class="pl-5">
                        <h3 class="is-size-4">{{ event.name }}</h3>
                        <p> {{ event.created_at }} </p>
                        <p>
                            {{ event.description }}
                        </p>
                        <ul>
                            <li v-for="link of links" :key="link.url">
                                <a :href="link.url">{{ link.name ? link.name : link.url }}</a>
                            </li>
                        </ul>
                    </article>
                    <div class="pl-5 pt-3 pb-4 pr-5" style="min-height: 300px;">
                        <el-tabs v-model="activeName">
                            <el-tab-pane label="Галерея" name="media">
                                <gallery :collection="media"></gallery>
                            </el-tab-pane>
                            <el-tab-pane label="Место" name="place" v-if="event.longitude">
                                <place :latitude="event.latitude" :longitude="event.longitude"></place>
                            </el-tab-pane>
                            <el-tab-pane label="Комментарии" name="comments" v-if="event.id">
                                <comments :type="'event'" :id="event.id"></comments>
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
import gallery                      from "../components/gallery.vue";
import Comments                     from "../components/comments.vue";

export default {
    components: {
        Comments,
        gallery,
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
        links() {
            if (!this.event.attachments) {
                return [];
            }
            return this.event.attachments.filter(item => item.type === 'link')
        },
        media() {
            if (!this.event.attachments) {
                return [];
            }
            return this.event.attachments.filter(item => item.type !== 'link')
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
                        if (!this.event.longitude) {
                            this.activeName = 'media'
                        }
                        document.title = 'Событие "' + this.event.name + '"' + ' - Лошица ZUBR.life'
                    }
                )
        },
    },

}
</script>
