import { createRouter, createWebHistory } from "vue-router";
import Layout from '../components/Layout.vue'
import Index from '../views/Index.vue'
import SingleAd from '../views/SingleAd.vue'
import CreateAd from '../views/CreateAd.vue'

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
        path: '/single',
        component: SingleAd
      },
      {
        name: 'create',
        path: '/create',
        component: CreateAd
      },
    ]
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

export default router