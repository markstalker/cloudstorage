import './bootstrap';
import '../css/app.css';
import router from './router'
import {createApp} from 'vue'
import App from './App.vue'
import { plugin, defaultConfig } from '@formkit/vue'
import {locales, ru} from '@formkit/i18n'
import dayjs from "dayjs"
import Notifications from '@kyvg/vue3-notification'

import updateLocale from 'dayjs/plugin/updateLocale'
dayjs.extend(updateLocale)
import ruLocale from 'dayjs/locale/ru'
dayjs.updateLocale('ru', {
    weekdays: "Воскресенье_Понедельник_Вторник_Среда_Четверг_Пятница_Суббота".split("_")
})
dayjs.locale('ru')


async function checkAuth() {
    const user = localStorage.getItem('user')
    if (localStorage.getItem('token') && !user) {
        await axios.get('api/v1/user')
            .then(response => {
                localStorage.setItem('user', JSON.stringify(response.data.data))
            })
    }
}

checkAuth().then(() => {
    const app = createApp(App)
    app.config.globalProperties.$user = JSON.parse(localStorage.getItem('user'))
    app.config.globalProperties.$dayjs = dayjs
    app.use(router)
        .use(plugin, defaultConfig({
            locales: { ru },
            locale: 'ru',
        }))
        .use(Notifications)
        .mount("#app")

})
