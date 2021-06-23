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
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="org of organizations">
                        <td>
                            <router-link :to="{name: 'organization', params: {id: org.id}}">
                                {{ org.name }}
                            </router-link>
                        </td>
                        <td style="vertical-align: middle;text-align: center"
                            :class="{
                                'has-text-success': org.rating > 0,
                                'has-text-danger': org.rating < 0
                            }"
                        >
                            {{ org.rating }}
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>

export default {
    created() {
        this.fetchOrganizations();
    },
    data() {
        return {
            organizations: [],
        }
    },
    methods: {
        fetchOrganizations() {
            fetch(import.meta.env.VITE_TELEGRAM_API_URL + '/organization')
                .then(r => r.json())
                .then(
                    r => {
                        this.organizations = r.data;
                    }
                )
        }
    }
}
</script>

<style scoped>

</style>
