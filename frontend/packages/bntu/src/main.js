import {createApp} from 'vue'
import App from './App.vue'
import router from './router';
import 'element-plus/lib/theme-chalk/el-icon.css'
import urql from '@urql/vue';
import 'primevue/resources/themes/saga-orange/theme.css'
import 'element-plus/packages/theme-chalk/src/index.scss'
import ToastService from 'primevue/toastservice';
import PrimeVue from 'primevue/config';

const app = createApp(App)
app.use(PrimeVue);
app.use(ToastService);
app.use(
    urql,
    ({
        url: import.meta.env.VITE_GRAPH_URL,
        fetchOptions: {
            credentials: 'include'
        },
    }),
);
app.use(router)
app.mount('#app')

if (import.meta.env.MODE !== 'development') {
    import('@zubr-life/main/src/sentry').then(
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


