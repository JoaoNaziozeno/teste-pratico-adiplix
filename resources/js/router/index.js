import { createRouter, createWebHistory } from 'vue-router';

import People from '../views/People.vue';
import Tasks from '../views/Tasks.vue';
import Profile from '../views/Profile.vue';

const routes = [
  {
    path: '/',
    component: People
  },
  { path: '/people', component: People },
  { path: '/tasks', component: Tasks },
  { path: '/people/:id', component: Profile }
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

export default router;