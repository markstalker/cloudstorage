import './bootstrap';
import '../css/app.css';
import router from './router'
import {createApp} from 'vue'
import App from './App.vue'
import { plugin, defaultConfig } from '@formkit/vue'
import {locales, ru} from '@formkit/i18n'
import dayjs from "dayjs"
import Notifications from '@kyvg/vue3-notification'
import vfmPlugin from 'vue-final-modal'

import updateLocale from 'dayjs/plugin/updateLocale'
dayjs.extend(updateLocale)
import ruLocale from 'dayjs/locale/ru'
dayjs.updateLocale('ru', {
    weekdays: "Воскресенье_Понедельник_Вторник_Среда_Четверг_Пятница_Суббота".split("_")
})
dayjs.locale('ru')

async function checkAuth() {
    if (localStorage.getItem('token')) {
        await axios.get('api/v1/user')
            .then(response => {
                localStorage.setItem('user', JSON.stringify(response.data.data))
            })
    }
}


checkAuth().then(() => {
    const app = createApp(App)
    app.config.globalProperties.$user = JSON.parse(localStorage.getItem('user'))

    app.config.globalProperties.clone = (obj) => {
        if (null == obj || 'object' != typeof obj) return obj;
        let copy = obj.constructor();
        for (let attr in obj) {
            if (obj.hasOwnProperty(attr)) copy[attr] = obj[attr];
        }
        return copy;
    }

    app.config.globalProperties.formatSize = (a, b) => {
        if (0 == a) return "";
        let c = 1000,
            d = b || 2,
            e = ["Б", "КБ", "МБ", "ГБ", "ТБ"],
            f = Math.floor(Math.log(a) / Math.log(c));
        return parseFloat((a / Math.pow(c, f)).toFixed(d)) + " " + e[f]
    }

    app.config.globalProperties.getId = (array, id) => {
        return array.map(data => data.id).indexOf(id)
    }

    app.config.globalProperties.$dayjs = dayjs
    app.use(router)
        .use(plugin, defaultConfig({
            locales: { ru },
            locale: 'ru',
        }))
        .use(Notifications)
        .use(vfmPlugin)
        .mount("#app")

})
