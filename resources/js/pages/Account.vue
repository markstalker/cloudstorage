<template>
    <h2 class="mb-6 text-2xl tracking-tight font-bold text-gray-900">Аккаунт</h2>
    <div class="space-y-2">
        <div>
            <div class="mb-1 font-bold">Имя</div>
            <div class="">{{ user.name }}</div>
        </div>
        <div>
            <div class="mb-1 font-bold">Почта</div>
            <div class="">{{ user.email }}</div>
        </div>
        <div>
            <div class="mb-1 font-bold">Зарегистрирован</div>
            <div class="">{{$dayjs(user.created_at).format('D MMMM YYYY в H:mm') }}</div>
        </div>
        <div>
            <div class="mb-1 font-bold">Занято</div>
            <div class="">{{ formatSize(user.storage_size)+'/'+formatSize(user.storage_quota) }}</div>
        </div>
    </div>
    <main-button @click.prevent="logout" class="mt-5">Выйти</main-button>
</template>

<script>
import MainButton from "../components/MainButton.vue";

export default {
    name: "Account",
    components: {
        MainButton,
    },
    data() {
        return {
            user: this.$user,
        }
    },
    methods: {
        logout() {
            axios.post('/api/v1/auth/logout')
                .then(response => {
                    localStorage.removeItem('token')
                    localStorage.removeItem('user')
                    location.href = '/'
                })
        },
    }
}
</script>
