<template>
    <div class="section zbr-promo">
        <div class="column is-centered">
            <div class="content is-medium">
                <h1 class="pl-5 pb-5 has-text-weight-bold">
                    Лента новостей
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
                <tr v-for="item of feed">
                    <th>
                        <router-link :to="{name: item.type, params: {id: item.id}}">{{ item.name }}</router-link>
                    </th>
                    <th>{{ item.type }}</th>
                    <th>{{ item.description }}</th>
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
                    <article class="message is-danger" v-show="error">
                        <div class="message-header">
                            <p>Ошибка</p>
                        </div>
                        <div class="message-body">
                            {{ error }}
                        </div>
                    </article>
                    <h3 class="pb-5 has-text-weight-bold">Добавить объявление</h3>
                    <div class="field">
                        <label class="label">Название</label>
                        <div class="control">
                            <input class="input" type="text" v-model="ad.name" required>
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

import {ElMessage} from 'element-plus';

let emptyAd = {name: '', description: ''};

export default {
    created() {
        let that = this;

        document.addEventListener('keyup', function (evt) {
            if (evt.keyCode === 27) {
                that.showModal = false
            }
        });
        this.fetchFeed();
    },
    data() {
        return {
            feed      : [],
            error    : null,
            showModal: false,
            ad       : Object.assign({}, emptyAd)
        }
    },
    methods: {
        fetchFeed() {
            fetch(import.meta.env.VITE_TELEGRAM_API_URL + '/feed')
                .then(r => r.json())
                .then(
                    r => {
                        this.feed = r.data;
                    }
                )
        },
        save() {
            this.error = null;
            fetch(import.meta.env.VITE_TELEGRAM_API_URL + '/ad', {
                method : 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },

                credentials: 'include',
                body       : JSON.stringify(this.ad)
            }).then(r => r.json())
                .then(result => {
                        if (result.error) {
                            this.error = result.error;
                            return;
                        }
                        this.fetchFeed();
                        this.ad        = Object.assign({}, emptyAd)
                        this.showModal = false
                        ElMessage.success({'message': 'Добавлено'})
                    }
                )
                .catch(e => {
                    ElMessage.error({'message': 'Произошла ошибка'})
                    throw e;
                })
        }
    }
}
</script>

<style scoped>

</style>
