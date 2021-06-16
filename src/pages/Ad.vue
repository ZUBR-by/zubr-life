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
                    <article class="pl-5">
                        <h3 class="is-size-4">{{ ad.name }}</h3>
                        <p> {{ ad.description }}</p>
                        <ul>
                            <li v-for="link of links" :key="link.value">
                                <a :href="link.value">{{ link.name ? link.name : link.value }}</a>
                            </li>
                        </ul>
                    </article>

                    <div class="pl-5 pt-3 pb-4 pr-5" style="min-height: 300px;">
                        <el-tabs v-model="activeName">
                            <el-tab-pane label="Галерея" name="media">
                                <gallery :collection="media"></gallery>
                            </el-tab-pane>
                            <el-tab-pane label="Место" name="place" v-if="ad.longitude">
                                <place :latitude="ad.latitude" :longitude="ad.longitude"></place>
                            </el-tab-pane>
                            <el-tab-pane :label="'Комментарии'" name="comments">
                                <comments :type="'ad'" :id="ad.id"></comments>
                            </el-tab-pane>
                        </el-tabs>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import place                        from "../components/place.vue";
import gallery                      from "../components/gallery.vue";
import {ElImage, ElTabPane, ElTabs} from "element-plus";
import Comments                     from "../components/comments.vue";

export default {
    components: {
        Comments,
        gallery,
        place,
        ElTabPane,
        ElTabs,
        ElImage
    },
    created() {
        this.fetchAd();
    },
    computed: {
        media() {
            if (!this.ad.attachments) {
                return [];
            }
            return this.ad.attachments.filter(item => item.type !== 'link')
        },
        links() {
            if (!this.ad.attachments) {
                return [];
            }
            return this.ad.attachments.filter(item => item.type === 'link')
        },
    },
    data() {
        return {
            ad        : {},
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
                        if (!this.ad.longitude) {
                            this.activeName = 'media'
                        }
                    }
                )
        }
    }
}
</script>

<style scoped>

</style>
