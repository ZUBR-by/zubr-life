<template>
    <div class="section zbr-promo">
        <div class="column is-centered">
            <div class="content is-medium">
                <h1 class="pl-5 pb-5 has-text-weight-bold">
                    Организации
                </h1>
            </div>
            <table class="table">
                <thead>
                <tr>
                    <th>Номер</th>
                    <th>Название</th>
                    <th>Описание</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="org of organizations">
                    <th><router-link :to="'/org/' + org.id">{{ org.id }}</router-link></th>
                    <th>{{ org.name }}</th>
                    <th>{{ org.description }}</th>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>

export default {
    created() {
        // this.fetchOrganizations();
    },
    data() {
        return {
            organizations: [{id: 1, name: 'Школа', description: 'test'}],
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
