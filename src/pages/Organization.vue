<template>
    <div class="section zbr-promo">
        <div class="columns is-centered">
            <div class="column is-two-thirds">
                <div class="panel">
                    <nav class="breadcrumb pt-5 pl-3 is-medium" aria-label="breadcrumbs">
                        <ul>
                            <li>
                                <router-link :to="{name: 'organizations'}">
                                    <button class="button is-primary is-inverted">
                                        <span class="icon is-small">
                                          <i class="fas fa-arrow-left"></i>
                                        </span>
                                        <span>Организации</span>
                                    </button>
                                </router-link>
                            </li>
                        </ul>
                    </nav>
                    <hr>
                    <template v-if="organization.name">
                        <div class="pl-5">
                            <h3 class="is-size-4">{{ organization.name }}</h3>
                        </div>
                        <div class="columns">
                            <div class="column pl-5">
                                <p class="pt-2 pl-3" v-if="organization.address">
                                    <b>Адрес:</b> {{ organization.address }}
                                </p>
                                <div class="pt-2 pb-2 pl-3">
                                    <rating :entity="organization.rating"
                                            @change="fetchOrganization"
                                            :type="'organization'"
                                            :id="organization.id"></rating>
                                </div>
                                <div v-if="links" class="pl-3">
                                    <ul>
                                        <li v-for="link of links" :key="link.value">
                                            <a :href="link.value">{{ link.name ? link.name : link.value }}</a>
                                        </li>
                                    </ul>
                                </div>
                                <p class="pl-3 pr-2" style="white-space: pre-wrap;">
                                    {{ organization.description }}
                                </p>
                            </div>
                            <div class="column is-two-fifths" v-if="organization.latitude">
                                <place :longitude="organization.longitude" :latitude="organization.latitude"></place>
                            </div>
                        </div>
                        <div class="pl-5 pt-3 pb-4 pr-5" style="min-height: 300px;">
                            <el-tabs v-model="activeName">
                                <el-tab-pane label="Люди относящиеся к организации" name="people" style="overflow-x: auto">
                                    <table class="table is-fullwidth is-striped">
                                        <tbody>
                                        <tr v-for="person of organization.people"
                                            :key="person.id"
                                            v-if="organization.people_count > 0">
                                            <td style="vertical-align: middle;width: 80px">
                                                <div class="grid-image">
                                                    <img :src="person.photo_url
                                        ? person.photo_url
                                        : 'https://zubr.in/assets/images/user.svg'">
                                                </div>

                                            </td>
                                            <td style="vertical-align: middle">
                                                <router-link :to="{name: 'person', params: {id: person.id}}">
                                                    {{ person.full_name }}
                                                </router-link>
                                            </td>
                                            <td style="vertical-align: middle">
                                                <template v-if="person.description">{{ person.description }}</template>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </el-tab-pane>
                                <el-tab-pane label="Комментарии"
                                             name="comments"
                                             v-if="organization.id">
                                    <comments :type="'organization'"
                                              :id="organization.id"></comments>
                                </el-tab-pane>
                            </el-tabs>
                        </div>
                    </template>
                </div>

            </div>
        </div>
    </div>
</template>

<script>

import {ElTabPane, ElTabs} from 'element-plus'
import place               from "../components/place.vue";
import Comments            from "../components/comments.vue";
import Rating              from "../components/rating.vue";
import handle              from './../http'

export default {
    components: {
        Rating,
        Comments,
        ElTabPane,
        ElTabs,
        place
    },
    created() {
        this.fetchOrganization();
    },
    data() {
        return {
            organization: {
                comments_count: 0
            },
            error       : null,
            map         : null,
            activeName  : 'people'
        }
    },
    computed: {
        comments() {
            return this.organization.comments_count ? this.organization.comments : [];
        },
        links() {
            if (!this.organization.attachments) {
                return [];
            }
            return this.organization.attachments.filter(i => i.type === 'link')
        }
    },
    methods : {
        fetchOrganization() {
            fetch(import.meta.env.VITE_TELEGRAM_API_URL + '/organization/' + this.$route.params.id,
                {
                    'credentials': 'include'
                }
            )
                .then(handle)
                .then(
                    r => {
                        if (r.error) {
                            return;
                        }
                        this.organization = r.data;
                        document.title    = this.organization.name + 'Лошица ZUBR.life'
                    }
                )
        },
    }
}
</script>

<style lang="scss">
.grid-image {

    width: 50px;
    height: 50px;
    border-radius: 50px;
    overflow: hidden;

    img {
        width: 50px;
        border-radius: 50px;
    }
}
</style>
