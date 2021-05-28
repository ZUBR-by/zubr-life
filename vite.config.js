import {defineConfig} from 'vite'
import vue            from '@vitejs/plugin-vue'
import styleImport    from 'vite-plugin-style-import'
import viteSentry     from 'vite-plugin-sentry'

const sentryConfig = {
    url       : 'https://my.ondemand.sentry.com',
    authToken : process.env.VITE_SENTRY_DSN,
    release   : '1.0',
    deploy    : {
        env: 'production'
    },
    sourceMaps: {
        include  : ['./dist/assets'],
        ignore   : ['node_modules'],
        urlPrefix: '~/assets'
    }
}
// https://vitejs.dev/config/
export default defineConfig({
    plugins: [
        vue(),
        viteSentry(sentryConfig),
        styleImport({
            libs: [{
                libraryName     : 'element-plus',
                esModule        : true,
                ensureStyleFile : true,
                resolveStyle    : (name) => {
                    name = name.slice(3)
                    return `element-plus/packages/theme-chalk/src/${name}.scss`;
                },
                resolveComponent: (name) => {
                    return `element-plus/lib/${name}`;
                },
            }]
        })
    ]
})
