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
    </div>
    <button @click.prevent="logout"
            class="mt-5 py-2 px-4 border border-transparent text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
        Выйти
    </button>
</template>

<script>
export default {
    name: "Account",
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
