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
                    <div class="columns pl-3">
                        <div class="column pl-5">
                            <div class="person-photo">
                                <el-image :src="photo" :preview-src-list="[photo]"></el-image>
                            </div>
                        </div>
                        <div class="column is-four-fifths">
                            <h3 class="is-size-3">{{ person.full_name }}</h3>
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
                                    <tr v-for="org of person.organizations"
                                        :key="org.id"
                                        v-if="person.organizations_count > 0">
                                        <td>
                                            {{ person.description }} в
                                            <router-link :to="{name: 'organization', params: {id: org.id}}">
                                                {{ org.name }}
                                            </router-link>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </el-tab-pane>
                            <el-tab-pane label="Комментарии" name="comments" v-if="person.id">
                                <comments :id="person.id" :type="'person'" ></comments>
                            </el-tab-pane>
                        </el-tabs>
                    </div>
                </div>
            </div>
        </div>
    </div>


</template>

<script>

import {ElTabPane, ElTabs, ElCard, ElImage} from "element-plus";
import Comments                    from "../components/comments.vue";

export default {
    components: {
        Comments,
        ElTabPane,
        ElTabs,
        ElCard,
        ElImage
    },
    created() {
        this.fetchPerson();
    },
    computed: {
        photo() {
            return this.person.photo_url ? this.person.photo_url : 'https://zubr.in/assets/images/user.svg'
        },
        links() {
            if (!this.person.attachments) {
                return [];
            }
            return this.person.attachments.filter(i => i.type === 'link')
        }
    },
    data() {
        return {
            person    : {},
            error     : null,
            activeName: 'orgs'
        }
    },
    methods: {
        fetchPerson() {
            fetch(import.meta.env.VITE_TELEGRAM_API_URL + '/person/' + this.$route.params.id)
                .then(r => r.json())
                .then(
                    r => {
                        this.person = r.data;
                        console.log(this.person.id)
                    }
                )
        }
    }
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
