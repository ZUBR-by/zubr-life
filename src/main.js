import {createApp} from 'vue'
import App         from './App.vue'
import router      from './router';
import 'element-plus/lib/theme-chalk/el-icon.css'

createApp(App).use(router).mount('#app')
