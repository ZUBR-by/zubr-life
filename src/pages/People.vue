<template>
    <div class="section zbr-promo">
        <div class="columns is-centered is-fullwidth">
            <div class="column is-four-fifths-desktop is-full-mobile">
                <h3 class="content has-text-weight-bold is-medium pl-3">
                    Люди
                </h3>
                <table class="table is-striped">
                    <thead>
                    <tr>
                        <td></td>
                        <th>ФИО</th>
                        <!--                        <th>Рейтинг</th>-->
                        <th>Описание</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="person of data.people" v-if="data">
                        <td style="vertical-align: middle">
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
                        <!--                        <th style="vertical-align: middle;text-align: center"-->
                        <!--                            :class="{-->
                        <!--                                'has-text-success': person.rating > 0,-->
                        <!--                                'has-text-danger': person.rating < 0-->
                        <!--                            }"-->
                        <!--                        >-->
                        <!--                            {{ person.rating }}-->
                        <!--                        </th>-->
                        <td style="vertical-align: middle">
                            <template v-for="item of person.organizations">
                                {{ item.position }} в
                                <router-link :to="{name: 'organization', params: {id: item.organization.id}}">
                                    {{ item.organization.name }}
                                </router-link>
                            </template>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</template>

<script>

import {defineComponent} from "vue";
import {useQuery}        from "@urql/vue";

export default defineComponent({
    setup() {
        const result = useQuery({
                // language=GraphQL
                query    : `
query ($community: String!) {
    people: person(
        where: {
            communities: {community_id: {_eq: $community}}
        }
    ) {
        attachments
        id
        photo_url
        full_name
        description
        organizations(where: {organization: {communities: {community_id: {_eq: $community}}}}) {
            position
            organization {
                id
                name
            }
        }
        extra
    }
}
      `,
                variables: {
                    community: slug
                }
            }
        )
        return {
            fetching: result.fetching,
            data    : result.data,
            error   : result.error
        }
    },
})
</script>

<style lang="scss">
.grid-image {
    width: 70px;
    height: 70px;
    border-radius: 70px;
    overflow: hidden;

    img {
        width: 70px;
        border-radius: 70px;
    }
}
</style>
