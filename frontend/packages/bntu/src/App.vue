<template>
    <navbar></navbar>
    <router-view/>
</template>

<script>
import {defineComponent} from 'vue'
import Navbar from "./Navbar.vue";

export default defineComponent({
    components: {
        navbar: Navbar
    }
})
</script>

<style>

</style>
