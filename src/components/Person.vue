<template>
    <div class="section zbr-promo">
        <div class="column is-centered">
            {{ $route.params.id }}
            {{ person }}
            <button @click="click"> click</button>
        </div>
    </div>
</template>

<script>

export default {
    created() {
        this.fetchPerson();
    },

    data() {
        return {
            person: {},
            error : null,
        }
    },
    methods: {
        click() {
            throw 'error';
        },
        fetchPerson() {
            fetch(import.meta.env.VITE_TELEGRAM_API_URL + '/person/' + this.$route.params.id)
                .then(r => r.json())
                .then(
                    r => {
                        this.person = r.data;
                    }
                )
        }
    }
}
</script>

<style scoped>

</style>
