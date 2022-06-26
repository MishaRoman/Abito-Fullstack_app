import { createApp } from 'vue'
import store from './store'
import router from './router'
import App from './App.vue'
import VIntersection from './directives/VIntersection'
import './assets/css/normalize.css'
import './assets/css/style.css'

const app = createApp(App)
  

app.directive('intersection', VIntersection)

app
  .use(store)
  .use(router)
  .mount('#app')