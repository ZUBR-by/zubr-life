<template>
    <div class="section zbr-promo">
        <div class="column is-centered">
            {{ ad }}
            <place v-if="ad.longitude" :latitude="ad.latitude" :longitude="ad.longitude"></place>

        </div>
    </div>
</template>

<script>
import place from "../components/place.vue";

export default {
    components: {
        place
    },
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
