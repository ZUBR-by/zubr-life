<template>
    <div class="box is-radiusless zbr-hdr">
        <div class="columns is-align-items-center">
            <div class="column is-two-fifth pl-1">
                <div>
                    <a href="/" style="float: left">
                        <img class="zbr-h-logo" :src="flag">
                    </a>
                    <a id="widget" style="float: left;padding-top:20px;padding-left: 5px"></a>
                </div>

                <div class="has-text-weight-medium pr-6">
                    <input id="brgrbtn" class="is-hidden-desktop" type="checkbox" v-model="hidden">
                    <label for="brgrbtn" class="burger-button is-hidden-desktop mr-5">
                        <div class="burger-button-line"></div>
                        <div class="burger-button-line"></div>
                        <div class="burger-button-line"></div>
                    </label>
                    <div class="box is-radiusless pt-6 brgr-nav is-hidden-desktop">
                        <aside class="menu pt-6">
                            <p class="menu-label">
                                Разделы сайта
                            </p>
                            <ul class="menu-list">
                                <li v-for="(route, i) of routes" :key="route.name">
                                    <a href="https://drive.google.com/drive/folders/1J9NNrlqAKeDWA2a0bLqsvDk7nBmTEX-D"
                                       v-if="i === (routes.length - 2)">
                                      Репозиторий
                                    </a>
                                    <router-link :to="{name: route.name}" @click="hidden = false">
                                        {{ route.label }}
                                    </router-link>
                                </li>
                            </ul>
                        </aside>
                    </div>
                </div>
            </div>
            <div class="column is-three-fifths has-text-right has-text-weight-medium is-hidden-mobile">
                <template v-for="(route, i) of routes" :key="route.name">
                    <a class="ml-5"
                       target="_blank"
                       href="https://drive.google.com/drive/folders/1J9NNrlqAKeDWA2a0bLqsvDk7nBmTEX-D"
                       v-if="i === (routes.length - 2)">
                      Репозиторий
                    </a>
                    <router-link :to="{name: route.name}" class="ml-5">
                        {{ route.label }}
                    </router-link>
                </template>
            </div>
        </div>
    </div>
</template>

<script>
import {routes} from "../../router";

export default {
    data() {
        return {
            hidden: false,
            routes: routes.filter(i => i.label)
        }
    },
    computed: {
        flag() {
            const map = {
                'loshitsa': [
                    '0.png',
                    '1.png',
                    '2.png'
                ],
                'vitebsk' : [
                    '1.jpg'
                ],
                'bntu' : [
                    '0.jpg'
                ]
            }

            return '/imgs/' + slug + '/' + map[slug][Math.floor(Math.random() * map[slug].length)];
        }
    },
    mounted() {
        let telegramScript = document.createElement('script')
        telegramScript.setAttribute('src', 'https://telegram.org/js/telegram-widget.js?14')
        telegramScript.setAttribute('async', '')
        telegramScript.setAttribute('data-telegram-login', 'zubr_life_' + slug + '_bot')
        telegramScript.setAttribute('data-userpic', 'false')
        telegramScript.setAttribute('data-size', 'medium')
        telegramScript.setAttribute('data-auth-url', import.meta.env.VITE_TELEGRAM_AUTH_URL)
        // document.getElementById("widget").appendChild(telegramScript)
    }
}
</script>

<style scoped>

</style>
