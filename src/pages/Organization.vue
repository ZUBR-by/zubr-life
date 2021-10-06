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
                    <template v-if="data && data.organization">
                        <div class="pl-5">
                            <h3 class="is-size-4">{{ data.organization.name }}</h3>
                        </div>
                        <div class="columns">
                            <div class="column pl-5">
                                <p class="pt-2 pl-3" v-if="data.organization.address">
                                    <b>Адрес:</b> {{ data.organization.address }}
                                </p>
                                <div class="pt-2 pb-2 pl-3">
                                    <!--                                    <rating :entity="data.organization.rating"-->
                                    <!--                                            @change="fetchOrganization"-->
                                    <!--                                            :type="'organization'"-->
                                    <!--                                            :id="organization.id"></rating>-->
                                </div>
                                <div class="pl-3">
                                    <ul>
                                        <li v-for="link of data.organization.attachments.filter(i => i.type === 'link')"
                                            :key="link.value">
                                            <a :href="link.value">{{ link.name ? link.name : link.value }}</a>
                                        </li>
                                    </ul>
                                </div>
                                <p class="pl-3 pr-2" style="white-space: pre-wrap;">
                                    {{ data.organization.description }}
                                </p>
                            </div>
                            <div class="column is-two-fifths" v-if="data.organization.coordinates">
                                <place :feature="data.organization.coordinates"></place>
                            </div>
                        </div>
                        <div class="pl-5 pt-3 pb-4 pr-5" style="min-height: 300px;">
                            <el-tabs v-model="activeName">
                                <el-tab-pane label="Лица относящиеся к организации" name="people" style="overflow-x: auto">
                                    <table class="table is-fullwidth is-striped">
                                        <tbody>
                                        <tr v-for="item of data.organization.people"
                                            :key="item.person.id">
                                            <td style="vertical-align: middle;width: 80px">
                                                <div class="grid-image">
                                                    <img :src="item.person.photo_url
                                                                    ? item.person.photo_url
                                                                    : 'https://zubr.in/assets/images/user.svg'">
                                                </div>

                                            </td>
                                            <td style="vertical-align: middle">
                                                <router-link :to="{name: 'person', params: {id: item.person.id}}">
                                                    {{ item.person.full_name }}
                                                </router-link>
                                            </td>
                                            <td style="vertical-align: middle">
                                                <template v-if="item.position">{{ item.position }}</template>
                                                <template v-if="item.extra.department">
                                                  ({{item.extra.department}})
                                                </template>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </el-tab-pane>
                                <el-tab-pane label="Комментарии"
                                             name="comments">
                                    <comments :type="'organization'"
                                              :id="data.organization.id"></comments>
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
import {useQuery}          from "@urql/vue";
import {useRoute}          from "vue-router";
import {computed, watch}   from "vue";

export default {
    setup() {
        const result = useQuery({
                // language=GraphQL
                query    : `
query ($id: Int!) {
    organization: organization_by_pk(
        id: $id,

    ) {
        attachments
        coordinates
        id
        address
        name
        description
        extra
        people: persons {
            position
            person {
                id
                photo_url
                extra
                full_name
                description
            }
        }
    }
}
      `,
                variables: {
                    id: useRoute().params.id
                }
            }
        )
        const links  = computed(() => {
            if (!result.data || !result.data.organization) {
                return [];
            }
            return result.data.organization.attachments.filter(i => i.type === 'link')
        })
        watch(result.data, (value) => {
            if (!value.organization) {
                return
            }
            document.title = value.organization.name + ' - Лошица ZUBR.life'
        })
        return {
            fetching  : result.fetching,
            data      : result.data,
            error     : result.error,
            activeName: 'people',
            map       : null,
            links
        }
    },
    components: {
        Rating,
        Comments,
        ElTabPane,
        ElTabs,
        place
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
