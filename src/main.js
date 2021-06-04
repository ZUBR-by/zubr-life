import {createApp} from 'vue'
import App         from './App.vue'
import router      from './router';
import 'element-plus/lib/theme-chalk/el-icon.css'

const app = createApp(App)

app.use(router)
app.mount('#app')

if (import.meta.env.MODE !== 'development') {
    import('./sentry').then(
        ({Sentry}) => {
            app.config.errorHandler = (err) => {
                Sentry.captureException(err)
            }
            window.addEventListener('error', (event) => {
                Sentry.captureException(event)
            })
            window.addEventListener('unhandledrejection', (event) => {
                Sentry.captureException(event)
            })
        }
    )
}


