<template>
    <div class="section zbr-promo">
        <div class="column is-centered">
            <div class="content is-medium">
                <h1 class="pl-5 pb-5 has-text-weight-bold">
                    Объявления
                </h1>
            </div>
            <table class="table">
                <thead>
                <tr>
                    <th>Номер</th>
                    <th>Название</th>
                    <th>Описание</th>
                    <th>
                        <button class="button" @click="showModal = true">Добавить</button>
                    </th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="ad of ads">
                    <th>{{ ad.id }}</th>
                    <th>{{ ad.name }}</th>
                    <th>{{ ad.description }}</th>
                    <th></th>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="modal is-clipped" :class="{'is-active': showModal}">
        <div class="modal-background"></div>
        <div class="modal-content">
            <div class="box">
                <form @submit.prevent="save">
                    <h3 class="pb-5 has-text-weight-bold">Добавить объявление</h3>
                    <div class="field">
                        <label class="label">Название</label>
                        <div class="control">
                            <input class="input" type="text" v-model="ad.name">
                        </div>
                    </div>
                    <div class="field">
                        <label class="label">Описание</label>
                        <div class="control">
                            <textarea class="textarea" v-model="ad.description"></textarea>
                        </div>
                    </div>

                    <div class="field is-grouped">
                        <div class="control">
                            <button class="button is-link" type="submit">Сохранить</button>
                        </div>
                        <div class="control">
                            <button type="button"
                                    class="button is-link is-light" @click="showModal = false">Отмена
                            </button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
        <button class="modal-close is-large" aria-label="close" @click="showModal = false"></button>
    </div>
</template>

<script>

let emptyAd = {name: '', description: ''};

export default {
    created() {
        let that = this;

        document.addEventListener('keyup', function (evt) {
            if (evt.keyCode === 27) {
                that.showModal = false
            }
        });
        this.fetchAds();
    },
    data() {
        return {
            ads      : [{id: 1, name: 'Test'}],
            showModal: false,
            ad       : Object.assign({}, emptyAd)
        }
    },
    methods: {
        fetchAds() {
            fetch(import.meta.env.VITE_TELEGRAM_API_URL + '/ad')
                .then(r => r.json())
                .then(
                    r => {
                        this.ads = r.data;
                    }
                )
        },
        save() {
            fetch(import.meta.env.VITE_TELEGRAM_API_URL + '/ad', {
                method : 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },

                credentials: 'include',
                body       : JSON.stringify(this.ad)
            }).then(r => r.json()).then(result => {
                this.fetchAds();
                this.ad = Object.assign({}, emptyAd)
                this.showModal = false
            })
        }
    }
}
</script>

<style scoped>

</style>
