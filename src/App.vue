<template>
    <navbar></navbar>
    <router-view/>
</template>

<script>
import Navbar from "./pages/Navbar.vue";

export default {
    components: {
        Navbar
    }
}
</script>

<style>

</style>
