<template>
    <div class="section zbr-promo">
        <div class="columns">
            <div style="padding-right: 40px">
                <div class="person-photo">
                    <img :src="person.photo_url
                    ? person.photo_url
                    : 'https://zubr.in/assets/images/user.svg'">
                </div>
            </div>

            <div class="column is-four-fifths">
                <h3 class="is-size-3">{{ person.full_name }}</h3>
                <p v-if="person.org && person.description">
                    {{ person.description }} в
                    <router-link :to="{name: 'organization', params: {id: person.org.id}}">
                        {{ person.org.name }}
                    </router-link>
                </p>
                <p v-else-if="person.org">
                    <router-link :to="{name: 'organization', params: {id: person.org.id}}">
                        {{ person.org.name }}
                    </router-link>
                </p>
                <p v-else>{{ person.description }}</p>
            </div>
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

<style scoped lang="scss">
.person-photo {
    width: 150px;
    height: 150px;
    border-radius: 150px;
    overflow: hidden;

    img {
        width: 150px;
        border-radius: 150px;
    }
}
</style>
