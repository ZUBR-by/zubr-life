import {createRouter, createWebHistory} from 'vue-router'
import Home                             from '/src/components/Home.vue'
import Help                             from '/src/components/Help.vue'
import Ad                             from '/src/components/Ad.vue'

const routes = [
    {
        path     : '/',
        name     : 'Home',
        component: Home,
    },
    {
        path     : '/help',
        name     : 'Help',
        component: Help,
    },
    {
        path     : '/ad',
        name     : 'ad',
        component: Ad,
    },
]
const router = createRouter({
    history: createWebHistory(),
    routes,
})
export default router
