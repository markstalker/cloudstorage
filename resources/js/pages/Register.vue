<template>
    <div class="min-h-full flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div>
                <h1 class="mt-6 text-center text-3xl tracking-tight font-bold text-gray-900">Регистрация</h1>
            </div>
            <FormKit
                type="form"
                id="registration"
                form-class="mt-8 space-y-6"
                @submit="register"
                v-model="formData"
                :actions="false"
                #default="{ value }"
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
                        type="text"
                        name="name"
                        placeholder="Имя"
                        validation-visibility="submit"
                        validation="required|string|length:2,255"
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
                    <FormKit
                        type="password"
                        name="password_confirm"
                        validation-visibility="submit"
                        placeholder="Повторите пароль"
                        validation="required|string|confirm"
                        :validation-messages="{
                            confirm: 'Пароли не совпадают.',
                        }"
                        input-class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                    />
                </div>
                <div class="flex items-center justify-between">
                    <div class="text-sm">
                        Уже зарегистрированы? <router-link class="font-medium text-indigo-600 hover:text-indigo-500" :to="{name: 'login'}">Войти</router-link>
                    </div>
                </div>
                <div>
                    <button type="submit" class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Зарегистрироваться
                    </button>
                </div>
            </FormKit>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            formData: {},
        }
    },
    methods: {
        register() {
            axios.get('/sanctum/csrf-cookie').then(response => {
                axios.post('api/v1/auth/register', this.formData)
                    .then(response => {
                        if (response.status === 201) {
                            this.$router.push({name: 'login'})
                        }
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
        },
    },
}
</script>
