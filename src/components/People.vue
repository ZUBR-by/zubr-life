<template>
    <div class="section zbr-promo">
        <div class="column is-centered">
            <div class="content is-medium">
                <h3 class="pl-5 pb-5 has-text-weight-bold">
                    Люди
                </h3>
            </div>
            <table class="table is-fullwidth">
                <thead>
                <tr>
                    <td>Фото</td>
                    <th>ФИО</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="person of people">
                    <td>
                        <div class="grid-image">
                            <img :src="person.photo_url
                                        ? person.photo_url
                                        : 'https://zubr.in/assets/images/user.svg'">
                        </div>
                    </td>
                    <td style="vertical-align: middle">
                        <router-link :to="{name: 'person', params: {id: person.id}}">
                            {{ person.full_name }}
                        </router-link>
                    </td>
                    <td style="vertical-align: middle">
                        <template v-if="person.description">
                            {{ person.description }} в
                            <router-link :to="{name: 'organization', params: {id: person.org.id}}">
                                {{ person.org.name }}
                            </router-link>
                        </template>
                        <template v-else>
                            <router-link :to="{name: 'organization', params: {id: person.org.id}}">
                                {{ person.org.name }}
                            </router-link>
                        </template>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>

export default {
    created() {
        this.fetchPeople();
    },
    data() {
        return {
            people: [],
        }
    },
    methods: {
        fetchPeople() {
            fetch(import.meta.env.VITE_TELEGRAM_API_URL + '/person')
                .then(r => r.json())
                .then(
                    r => {
                        this.people = r.data;
                    }
                )
        }
    }
}
</script>

<style lang="scss">
.grid-image {
    width: 70px;
    height: 70px;
    border-radius: 70px;
    overflow: hidden;

    img {
        width: 70px;
        border-radius: 70px;
    }
}
</style>
