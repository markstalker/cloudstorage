<template>
    <div class="min-h-full flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div>
                <h1 class="mt-6 text-center text-3xl tracking-tight font-bold text-gray-900">Вход в систему</h1>
            </div>
            <FormKit
                type="form"
                id="login"
                form-class="mt-8 space-y-6"
                @submit="login"
                v-model="formData"
                :actions="false"
            >
                <div class="rounded-md shadow-sm space-y-2">
                    <FormKit
                        type="text"
                        name="email"
                        placeholder="Почтовый адрес"
                        validation-visibility="submit"
                        validation="required|email"
                        input-class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                    />
                    <FormKit
                        type="password"
                        name="password"
                        validation-visibility="submit"
                        validation="required|string"
                        input-class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                        placeholder="Пароль"
                    />
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input v-model="rememberMe" id="remember-me" name="remember-me" type="checkbox" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                        <label for="remember-me" class="ml-2 block text-sm text-gray-900">Запомнить меня</label>
                    </div>
                    <div class="text-sm">
                        Нет аккаунта? <router-link class="font-medium text-indigo-600 hover:text-indigo-500" :to="{name: 'register'}">Зарегистрироваться</router-link>
                    </div>
                </div>
                <div>
                    <button type="submit" class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        </span>
                        Войти
                    </button>
                </div>
            </FormKit>
        </div>
    </div>
</template>

<script>
export default {
    name: "Login",
    data() {
        return {
            rememberMe: false,
            formData: {},
        }
    },
    methods:{
        login() {
            axios.get('/sanctum/csrf-cookie').then(response => {
                axios.post('api/v1/auth/login',{
                    'email': this.formData.email,
                    'password': this.formData.password,
                    'remember': this.rememberMe,
                })
                    .then(response => {
                        localStorage.setItem('token', response.data.data.token)
                        location.href = '/'
                    })
                    .catch(error => {
                        let errors = error.response.data.errors
                        errors = errors[Object.keys(errors)[0]]

                        errors.forEach(error => {
                            this.$notify({
                                type: 'error',
                                text: error,
                            })
                        })
                    })
            })
        }
    }
}
</script>
