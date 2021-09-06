<template>
    <div class="section zbr-promo">
        <div class="columns is-centered">
            <div class="column is-two-thirds">
                <div class="panel">
                    <nav class="breadcrumb pt-5 pl-3 is-medium" aria-label="breadcrumbs">
                        <ul>
                            <li>
                                <router-link :to="{name: 'people'}">
                                    <button class="button is-primary is-inverted">
                                        <span class="icon is-small">
                                          <i class="fas fa-arrow-left"></i>
                                        </span>
                                        <span>Люди</span>
                                    </button>
                                </router-link>
                            </li>
                        </ul>
                    </nav>
                    <hr>
                    <template v-if="data">
                        <div class="columns pl-3">
                            <div class="column pl-5">
                                <div class="person-photo">
                                    <el-image :src="data.person.photo_url ? data.person.photo_url: 'https://zubr.in/assets/images/user.svg'"
                                              :preview-src-list="[data.person.photo_url ? data.person.photo_url: 'https://zubr.in/assets/images/user.svg']"></el-image>
                                </div>
                            </div>
                            <div class="column is-four-fifths">
                                <h3 class="is-size-3">{{ data.person.full_name }}</h3>
                                <!--                                <rating :entity="data.person.rating"-->
                                <!--                                        @change="fetchPerson"-->
                                <!--                                        :type="'person'"-->
                                <!--                                        :id="person.id"></rating>-->
                                <div v-if="links" class="pt-4">
                                    <ul>
                                        <li v-for="link of links" :key="link.value">
                                            <a :href="link.value" target="_blank">
                                                {{ link.name ? link.name : link.value }}
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="pl-5 pt-3 pb-4 pr-5" style="min-height: 300px;">
                            <el-tabs v-model="activeName">
                                <el-tab-pane label="Организации" name="orgs" style="overflow-x: auto">
                                    <table class="table is-fullwidth is-striped">
                                        <tbody>
                                        <tr v-for="item of data.person.organizations"
                                            :key="item.organization.id">
                                            <td>
                                                {{ item.position }} в
                                                <router-link :to="{name: 'organization', params: {id: item.organization.id}}">
                                                    {{ item.organization.name }}
                                                </router-link>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </el-tab-pane>
                                <el-tab-pane label="Комментарии" name="comments" v-if="data.person.id">
                                    <comments :id="data.person.id" :type="'person'"></comments>
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

import {ElTabPane, ElTabs, ElCard, ElImage} from "element-plus";
import Comments                             from "../components/comments.vue";
import Rating                               from "../components/rating.vue";
import {useQuery}                           from "@urql/vue";
import {useRoute}                           from "vue-router";
import {computed, watch}                    from "vue";

export default {
    setup() {
        const result = useQuery({
                // language=GraphQL
                query    : `
query ($id: Int!, $community: String!) {
    person: person_by_pk(id: $id) {
        attachments
        id
        full_name
        photo_url
        description
        extra
        organizations: organizations(where: {organization: {
            communities: {community_id: {_eq: $community}}
        }}) {
            position
            organization {
                id
                name
            }
        }
    }
}
      `,
                variables: {
                    id       : useRoute().params.id,
                    community: slug
                }
            }
        )
        const links  = computed(() => {
            if (!result.data || !result.data.person) {
                return [];
            }
            return result.data.person.attachments.filter(i => i.type === 'link')
        })
        const photo  = computed(() => {
            if (!result.data || !result.data.person || !result.data.person.photo_url) {
                return 'https://zubr.in/assets/images/user.svg';
            }
            return result.data.person.photo_url
        })
        watch(result.data, (value) => {
            if (!value.person) {
                return
            }
            document.title = value.person.full_name + ' - Лошица ZUBR.life'
        })
        return {
            fetching  : result.fetching,
            data      : result.data,
            error     : result.error,
            activeName: 'orgs',
            map       : null,
            links,
            photo
        }
    },
    components: {
        Rating,
        Comments,
        ElTabPane,
        ElTabs,
        ElCard,
        ElImage
    },
}
</script>

<style scoped lang="scss">
.person-photo {
    width: 150px;
    height: 150px;
    border-radius: 150px;
    overflow: hidden;

    img {
        width: 150px;
        border-radius: 150px;
    }
}
</style>
