<template>
    <div class="section zbr-promo">
        <div class="columns is-centered">
            <div class="column is-two-thirds">
                <div class="panel" v-if="place.name">
                    <nav class="breadcrumb pt-5 pl-3 is-medium" aria-label="breadcrumbs">
                        <ul>
                            <li>
                                <router-link :to="{name: 'home'}">
                                    <button class="button is-primary is-inverted">
                                        <span class="icon is-small">
                                          <i class="fas fa-arrow-left"></i>
                                        </span>
                                        <span>Главная</span>
                                    </button>
                                </router-link>
                            </li>
                        </ul>
                    </nav>
                    <hr>
                    <article class="pl-5">
                        <h3 class="is-size-4">{{ place.name }}</h3>
                        <ul>
                            <li v-for="link of links" :key="link.value">
                                <a :href="link.value">{{ link.name ? link.name : link.value }}</a>
                            </li>
                        </ul>
                        <p class="pt-3 pr-5" style="white-space: pre-wrap;font-size: 18px">
                            {{ description }}
                            <a @click="fullText = !fullText">
                                {{ fullText ? 'Показать меньше' : 'Показать все' }}
                            </a>
                        </p>
                    </article>
                    <div class="pl-5 pt-3 pb-4 pr-5" style="min-height: 300px;">
                        <el-tabs v-model="activeName" name="media">
                            <el-tab-pane label="Расположение" name="place" v-if="place.longitude">
                                <place :latitude="place.latitude" :longitude="place.longitude"></place>
                            </el-tab-pane>
                            <el-tab-pane label="Медия" name="media">
                                <gallery :collection="media"></gallery>
                            </el-tab-pane>
                            <el-tab-pane :label="'Комментарии'" name="comments" v-if="place.id">
                                <comments :type="'place'" :id="place.id"></comments>
                            </el-tab-pane>
                        </el-tabs>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import place               from "../components/place.vue";
import {ElTabPane, ElTabs} from "element-plus";
import Comments            from "../components/comments.vue";
import Gallery             from "../components/gallery.vue";

export default {
    components: {
        Gallery,
        Comments,
        place,
        ElTabPane,
        ElTabs,
    },
    created() {
        this.fetchPlace();
    },
    computed: {
        description() {
            if (!this.fullText) {
                return this.place.description.substr(0, 800) + '...'
            }
            return this.place.description;
        },
        media() {
            if (!this.place.attachments) {
                return [];
            }
            return this.place.attachments.filter(item => item.type !== 'link')
        },
        links() {
            if (!this.place.attachments) {
                return [];
            }
            return this.place.attachments.filter(item => item.type === 'link')
        },
    },
    data() {
        return {
            fullText  : false,
            place     : {},
            activeName: 'place'
        }
    },
    methods: {
        fetchPlace() {
            fetch(import.meta.env.VITE_TELEGRAM_API_URL + '/place/' + this.$route.params.id)
                .then(r => r.json())
                .then(
                    r => {
                        this.place = r.data;
                    }
                )
        }
    }
}
</script>

<style scoped>

</style>
