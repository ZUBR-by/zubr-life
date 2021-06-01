<template>
    <div class="section zbr-promo">
        <div class="column is-centered">
            {{ $route.params.id }}
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
            event: {id: 1, name: 'Школа', description: 'test'},
        }
    },
    methods: {
        fetchOrganizations() {
            fetch(import.meta.env.VITE_TELEGRAM_API_URL + '/event/' + this.$route.params.id)
                .then(r => r.json())
                .then(
                    r => {
                        this.event = r.data;
                    }
                )
        }
    }
}
</script>

<style scoped>

</style>
