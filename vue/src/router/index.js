import { createRouter, createWebHistory } from "vue-router";
import Layout from '../components/Layout.vue'
import Index from '../views/Index.vue'
import SingleAd from '../views/SingleAd.vue'

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
      }
    ]
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

export default router