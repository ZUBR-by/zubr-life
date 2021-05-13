<template>
    <div class="box is-radiusless zbr-hdr">
        <div class="columns is-align-items-center	">
            <div class="column pl-6">
                <img class="zbr-h-logo" src="/imgs/icons/svg/zubr-h-logo.svg">
                <div class="has-text-weight-medium pr-6 is-hidden-desktop">
                    <input id="brgrbtn" class="is-hidden-desktop" type="checkbox">
                    <label for="brgrbtn" class="burger-button is-hidden-desktop mr-5">
                        <div class="burger-button-line"></div>
                        <div class="burger-button-line"></div>
                        <div class="burger-button-line"></div>
                    </label>

                    <div class="box is-radiusless pt-6 brgr-nav">
                        <aside class="menu pt-6">
                            <p class="menu-label">
                                Разделы сайта
                            </p>
                            <ul class="menu-list">

                                <li><a href="/help">Нужна помощь</a></li>
                                <li><a href="">Готовы помочь</a></li>
                                <li><a href="">О проекте</a></li>
                            </ul>
                        </aside>
                    </div>


                </div>
            </div>
            <div class="column has-text-right has-text-weight-medium pr-6 is-hidden-mobile">
                <div id="widget"></div>
                <a href="/help">Нужна помощь</a>
                <a class="ml-5" href="#">Готовы помочь</a>
                <a class="ml-5" href="#">О проекте</a>
            </div>
        </div>
    </div>
</template>

<script setup>

</script>

<style scoped>

</style>
