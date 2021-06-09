<template>
    <div class="section zbr-promo">
        <div class="column is-centered">
            <div class="content is-medium">
                <h1 class="pl-5 pb-5 has-text-weight-bold">
                    События
                </h1>
            </div>
            <table class="table">
                <thead>
                <tr>
                    <th>Дата</th>
                    <th>Название</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="event of events">
                    <th>
                        {{ event.created_at }}
                    </th>
                    <th>
                        <router-link :to="{name: 'event', params: {id: event.id}}">
                            {{ event.name }}
                        </router-link>
                    </th>
                    <th>{{ event.description }}</th>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>

export default {
    created() {
        this.fetchEvents();
    },
    data() {
        return {
            events: [],
        }
    },
    methods: {
        fetchEvents() {
            fetch(import.meta.env.VITE_TELEGRAM_API_URL + '/event')
                .then(r => r.json())
                .then(
                    r => {
                        this.events = r.data;
                    }
                )
        }
    }
}
</script>

<style scoped>

</style>
