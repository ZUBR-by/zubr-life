<template>
    <div class="section zbr-promo">
        <div class="column is-centered">
            {{organization}}
        </div>
    </div>
</template>

<script>

export default {
    created() {
        this.fetchOrganization();
    },
    data() {
        return {
            organization: {},
            error        : null,
        }
    },
    methods: {
        fetchOrganization() {
            fetch(import.meta.env.VITE_TELEGRAM_API_URL + '/organization/' + this.$route.params.id)
                .then(r => r.json())
                .then(
                    r => {
                        this.organization = r.data;
                    }
                )
        }
    }
}
</script>

<style scoped>

</style>
