import {createRouter, createWebHistory} from 'vue-router'
import Feed from '@zubr-life/main/src/pages/Feed.vue'
import Place from '@zubr-life/main/src/pages/Place.vue'
import Rating from "@zubr-life/main/src/pages/Rating.vue";
import Person from "@zubr-life/main/src/pages/Person.vue";
import Organization from "@zubr-life/main/src/pages/Organization.vue";
import Activity from "@zubr-life/main/src/pages/Activity.vue";
import About from "./pages/About.vue";
import page404 from "@zubr-life/main/src/404.vue";
import Home from "./pages/Home.vue";
import {nextTick} from "vue";

const routes = [
    {
        path: '/:pathMatch(.*)*',
        name: 'not-found',
        component: page404
    },
    {
        path: '/',
        name: 'home',
        label: 'Главная',
        component: Home,
    },
    {
        path: '/feed',
        name: 'feed',
        component: Feed,
        label: 'Лента новостей',
        meta: {
            title: 'Лента новостей'
        },
    },
    {
        path: '/place/:id',
        name: 'place',
        component: Place,
    },
    {
        path: '/person/:id',
        name: 'person',
        component: Person,
    },
    {
        path: '/rating',
        name: 'rating',
        label: 'Рейтинг',
        meta: {
            title: 'Рейтинг'
        },
        component: Rating,
        alias: ['/people', '/org']
    },
    {
        path: '/org/:id',
        name: 'organization',
        component: Organization,
    },
    {
        path: '/activity/:id',
        name: 'activity',
        component: Activity,
    },
    {
        path: '/about',
        name: 'about',
        label: 'О проекте',
        meta: {
            title: 'О проекте'
        },
        component: About,
    },
]
const router = createRouter({
    history: createWebHistory(),
    routes,
})
router.afterEach(async (to, from) => {
    await nextTick()
    let name = 'БНТУ';
    document.title = to.meta.title
        ? to.meta.title + ` - ${name} ZUBR.life`
        : `Экран локального сообщества - ${name} ZUBR.life`;
})
export default router
export {routes}
