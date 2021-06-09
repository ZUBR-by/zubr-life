<template>
    <div class="section zbr-promo">
        <div class="column is-centered">
            {{ event }}
        </div>
    </div>
</template>

<script>

export default {
    created() {
        this.fetchEvent();
    },
    data() {
        return {
            event: {},
        }
    },
    methods: {
        fetchEvent() {
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
