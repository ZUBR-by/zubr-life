<template>
    <div class="section zbr-promo">
        <div class="column is-centered">
            {{ ad }}
        </div>
    </div>
</template>

<script>

export default {
    created() {
        this.fetchAd();
    },
    data() {
        return {
            ad: {},
        }
    },
    methods: {
        fetchAd() {
            fetch(import.meta.env.VITE_TELEGRAM_API_URL + '/ad/' + this.$route.params.id)
                .then(r => r.json())
                .then(
                    r => {
                        this.ad = r.data;
                    }
                )
        }
    }
}
</script>

<style scoped>

</style>
