import { createRouter, createWebHistory } from 'vue-router';
import Feed from './pages/Feed.vue';
import Place from '@zubr-life/main/src/pages/Place.vue';
import Rating from './pages/Rating.vue';
import Issues from './pages/Issues.vue';
import Issue from './pages/Issue.vue';
import Person from './pages/Person.vue';
import Organization from '@zubr-life/main/src/pages/Organization.vue';
import Activity from './pages/Activity.vue';
import About from './pages/About.vue';
import page404 from '../404.vue';
import Home from './pages/Home.vue';
import { nextTick } from 'vue';

const routes = [
  {
    path: '/:pathMatch(.*)*',
    name: 'not-found',
    component: page404,
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
    label: 'Новости',
    meta: {
      title: 'Новости',
    },
  },
  {
    path: '/issues',
    name: 'issues',
    label: 'Проблемы',
    component: Issues,
    meta: {
      title: 'Проблемы',
    },
  },
  {
    path: '/issue/:id',
    name: 'issue',
    component: Issue,
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
    label: 'Люди',
    meta: {
      title: 'Люди',
    },
    component: Rating,
    alias: ['/people', '/org'],
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
    label: 'О нас',
    meta: {
      title: 'О нас',
    },
    component: About,
  },
];
const router = createRouter({
  history: createWebHistory(),
  routes,
});
router.beforeEach(function (to, from, next) {
  window.scrollTo(0, 0);
  next();
});
router.afterEach(async (to, from) => {
  await nextTick();
  let name = 'БНТУ 97%';
  document.title = to.meta.title ? to.meta.title + ` - ${name}` : name;
});
export default router;
export { routes };
