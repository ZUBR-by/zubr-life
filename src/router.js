import {createRouter, createWebHistory} from 'vue-router'
import Home                             from './pages/Home.vue'
import Feed                             from './pages/Feed.vue'
import Ad                               from './pages/Ad.vue'
import Place                            from './pages/Place.vue'
import People                           from './pages/People.vue'
import Person                           from './pages/Person.vue'
import Organizations                    from "./pages/Organizations.vue";
import Organization                     from "./pages/Organization.vue";

import Activity   from "./pages/Activity.vue";
import {nextTick} from "vue";

const routes = [
    {
        path     : '/:pathMatch(.*)*',
        name     : 'not-found',
        component: Home
    },
    {
        path     : '/',
        name     : 'home',
        label    : 'Главная',
        component: Home,
    },
    {
        path     : '/feed',
        name     : 'feed',
        component: Feed,
        label    : 'Лента новостей',
        meta     : {
            title: 'Лента новостей'
        },
    },
    {
        path     : '/ad/:id',
        name     : 'ad',
        component: Ad,
    },
    {
        path     : '/place/:id',
        name     : 'place',
        component: Place,
    },
    {
        path     : '/people',
        name     : 'people',
        label    : 'Люди',
        meta     : {
            title: 'Люди'
        },
        component: People,
    },
    {
        path     : '/person/:id',
        name     : 'person',
        component: Person,
    },
    {
        path     : '/org',
        name     : 'organizations',
        label    : 'Организации',
        meta     : {
            title: 'Организации'
        },
        component: Organizations,
    },
    {
        path     : '/org/:id',
        name     : 'organization',
        component: Organization,
    },
    {
        path     : '/activity/:id',
        name     : 'activity',
        component: Activity,
    },
    {
        path     : '/about',
        name     : 'about',
        label    : 'О проекте',
        meta     : {
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
    document.title = to.meta.title
        ? to.meta.title + ' - Лошица ZUBR.life'
        : 'Экран локального сообщества - Лошица ZUBR.life';
})
export default router
export {routes}
