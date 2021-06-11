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
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="org of organizations">
                        <th>{{ org.name }}</th>
                        <th>
                            <router-link :to="{name: 'organization', params: {id: org.id}}">
                                Комментировать
                            </router-link>
                        </th>
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
