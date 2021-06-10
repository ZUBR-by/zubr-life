<template>
    <div class="section zbr-promo">
        <div class="column is-centered">
            {{ place }}
            <place v-if="place.longitude" :latitude="place.latitude" :longitude="place.longitude"></place>
        </div>
    </div>
</template>

<script>
import place from "../components/place.vue";

export default {
    components: {place},
    created() {
        this.fetchPlace();
    },
    data() {
        return {
            place: {},
        }
    },
    methods: {
        fetchPlace() {
            fetch(import.meta.env.VITE_TELEGRAM_API_URL + '/place/' + this.$route.params.id)
                .then(r => r.json())
                .then(
                    r => {
                        this.place = r.data;
                    }
                )
        }
    }
}
</script>

<style scoped>

</style>
