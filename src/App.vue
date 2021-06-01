<template>
    <navbar></navbar>
    <router-view/>
</template>

<script>
import Navbar from "./components/Navbar.vue";

export default {
    components: {
        Navbar
    },
    watch     : {
        $route: {
            immediate: true,
            handler(to) {
                document.title = to.meta.title
                    ? 'ZUBR.life - ' + to.meta.title
                    : 'ZUBR.life - Экран местного самоуправления';
            }
        },
    }
}
</script>

<style>

</style>
