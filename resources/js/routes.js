import Login from "./pages/Login.vue";
import Register from "./pages/Register.vue";
import Account from "./pages/Account.vue";
import Storage from "./pages/Storage.vue";

export const routes = [
    {
        path: '/login',
        name: 'login',
        component: Login,
        meta: {
            title: 'Вход',
        }
    },
    {
        path: '/register',
        name: 'register',
        component: Register,
        meta: {
            title: 'Регистрация',
        }
    },
    {
        path: '/account',
        name: 'account',
        component: Account,
        meta: {
            title: 'Аккаунт',
        }
    },
    {
        path: '/',
        name: 'storage',
        component: Storage,
        meta: {
            title: 'Ваше хранилище',
        }
    }
];
