<template>
    <div class="section zbr-promo">
        <div class="columns is-centered">
            <div class="column is-half">
                <h3 class="content has-text-weight-bold is-medium pl-3">
                    Организации
                </h3>
                <table class="table">
                    <thead>
                    <tr>
                        <th>Название</th>
                        <th>Рейтинг</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="item of data.organization" v-if="data">
                        <th>
                            <router-link :to="{name: 'organization', params: {id: item.id}}">
                                {{ item.name }}
                            </router-link>
                        </th>
                        <td style="vertical-align: middle;text-align: center"
                            :class="{
                                'has-text-success': false,
                                'has-text-danger': false
                            }"
                        >
                            0
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
    organization(
        where: {

            communities: {community_id: {_eq: $community}}
        }
    ) {
        attachments
        id
        name
        description
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

<style scoped>

</style>
