import { createRouter, createWebHistory } from "vue-router";
import Layout from '../components/Layout.vue'
import Index from '../views/Index.vue'
import SingleAd from '../views/SingleAd.vue'
import CreateAd from '../views/CreateAd.vue'
import EditProfile from '../views/EditProfile.vue'
import store from "../store";

const routes = [
  {
    path: '/',
    component: Layout,
    redirect: '/index',
    children: [
      {
        name: 'index',
        path: '/index',
        component: Index
      },
      {
        name: 'single',
        path: '/ad/:id',
        component: SingleAd
      },
      {
        name: 'create',
        path: '/create',
        meta: {requiresAuth: true},
        component: CreateAd
      },
      {
        name: 'editProfile',
        path: '/profile/edit',
        meta: {requiresAuth: true},
        component: EditProfile
      },
    ]
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

router.beforeEach((to, from, next) => {
  if (to.meta.requiresAuth && !store.state.user.token) {
    router.push(from)
    alert('You need to be logged in to access this page')
  } else {
    next();
  }
});

export default router