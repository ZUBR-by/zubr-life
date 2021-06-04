import {createRouter, createWebHistory} from 'vue-router'
import Home                             from './components/Home.vue'
import Ad                               from './components/Ad.vue'
import People                           from './components/People.vue'
import Person                           from './components/Person.vue'
import Organizations                    from "./components/Organizations.vue";
import Organization                     from "./components/Organization.vue";
import About                            from "./components/About.vue";
import Events                           from "./components/Events.vue";
import Event                            from "./components/Event.vue";

const routes = [
    {
        path     : '/',
        name     : 'home',
        label    : 'Главная',
        component: Home,
    },
    {
        path     : '/ad',
        name     : 'ad',
        label    : 'Объявления',
        component: Ad,
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
        path     : '/people/:id',
        name     : 'person',
        meta     : {
            title: 'Человек'
        },
        component: Person,
    },
    {
        path     : '/org',
        name     : 'organizations',
        label    : 'Организации',
        component: Organizations,
    },
    {
        path     : '/org/:id',
        name     : 'organization',
        component: Organization,
    },
    {
        path     : '/event',
        name     : 'events',
        label    : 'События',
        component: Events,
    },
    {
        path     : '/event/:id',
        name     : 'event',
        component: Event,
    },
    {
        path     : '/about',
        name     : 'about',
        label    : 'О проекте',
        component: About,
    },
]
const router = createRouter({
    history: createWebHistory(),
    routes,
})
export default router
export {routes}
