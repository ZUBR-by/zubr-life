import {createRouter, createWebHistory} from 'vue-router'
import Feed from './pages/Feed.vue'
import Place from './pages/Place.vue'
import Rating from "./pages/Rating.vue";
import Person from "./pages/Person.vue";
import Organization from "./pages/Organization.vue";
import Activity from "./pages/Activity.vue";
import page404 from "./404.vue";
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
        component: () => import('./pages/' + slug + '/Home.vue'),
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
        component: slug === 'bntu' ? () => import('./pages/' + slug + '/Rating.vue') : Rating,
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
        component: () => import('./pages/' + slug + '/About.vue'),
    },
]
const router = createRouter({
    history: createWebHistory(),
    routes,
})
router.afterEach(async (to, from) => {
    await nextTick()
    let name = 'Лошица';
    if (slug === 'bntu') {
        name = 'БНТУ'
    }
    document.title = to.meta.title
        ? to.meta.title + ` - ${name} ZUBR.life`
        : `Экран локального сообщества - ${name} ZUBR.life`;
})
export default router
export {routes}
