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
                    <th>Номер</th>
                    <th>Название</th>
                    <th>Описание</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="event of events">
                    <th>
                        <router-link :to="{name: 'event', params: {id: event.id}}">
                            {{ event.id }}
                        </router-link>
                    </th>
                    <th>{{ event.name }}</th>
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
            events: [{id: 1, name: 'Событие', description: 'test'}],
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
