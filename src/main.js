import {createApp} from 'vue'
import App         from './App.vue'
import router      from './router';
import 'element-plus/lib/theme-chalk/el-icon.css'
import * as Sentry from '@sentry/browser'

const app = createApp(App)

app.use(router)
app.mount('#app')

Sentry.init({
    dsn: import.meta.env.VITE_SENTRY_DSN
})
app.config.errorHandler = (err) => {
    Sentry.captureException(err)
}
window.addEventListener('error', (event) => {
    Sentry.captureException(event)
})
window.addEventListener('unhandledrejection', (event) => {
    Sentry.captureException(event)
})
