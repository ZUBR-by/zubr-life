<template>
    <navbar></navbar>
    <router-view/>
</template>

<script>
import {defineAsyncComponent, defineComponent} from 'vue'

export default defineComponent({
    components: {
        navbar: defineAsyncComponent(() =>
            import('./pages/' + slug + '/Navbar.vue')
        )
    }
})
</script>

<style>

</style>
