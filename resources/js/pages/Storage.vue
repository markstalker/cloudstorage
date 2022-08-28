<template>
    <h2 class="mb-6 text-2xl tracking-tight font-bold text-gray-900">Файлы</h2>
    <loading-spinner class="mt-10" v-if="loading" />
    <div v-else>
        <input type="file" @change="uploadFile" ref="file" hidden>
        <button @click="$refs.file.click()" class="py-2 px-4 border border-transparent text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            Загрузить
        </button>
        <div class="flex flex-col">
            <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="py-4 inline-block min-w-min sm:px-6 lg:px-8">
                    <div class="overflow-hidden">
                        <table class="min-w-full text-center">
                            <thead class="border-b bg-gray-50">
                            <tr>
                                <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4">
                                </th>
                                <th scope="col" class="text-left text-sm font-medium text-gray-900 px-6 py-4">
                                    Имя
                                </th>
                                <th scope="col" class="text-right text-sm font-medium text-gray-900 px-6 py-4">
                                    Размер
                                </th>
                                <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4">
                                    Дата создания
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="file in files" class="bg-white border-b">
                                <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                    <div class="flex item-center justify-center space-x-3">
                                        <a target="_blank" :href="'api/v1/downloads/'+file.uuid" class="w-5 cursor-pointer transform hover:text-indigo-600 hover:scale-110">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                            </svg>
                                        </a>
                                        <button class="w-5 transform hover:text-indigo-600 hover:scale-110" @click="renameFile(file.id)">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                                            </svg>
                                        </button>
                                        <button class="w-5 transform hover:text-indigo-600 hover:scale-110" @click="deleteFile(file.id)">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-left text-sm font-medium text-gray-900">
                                    {{ file.full_name }}
                                </td>
                                <td class="text-right text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                    {{ formatBytes(file.size) }}
                                </td>
                                <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                    {{$dayjs(file.created_at).format('D MMMM YYYY в H:mm') }}
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import LoadingSpinner from "../components/LoadingSpinner.vue";

export default {
    name: "Storage",
    components: {
        LoadingSpinner,
    },
    data() {
        return {
            loading: true,
            user: this.$user,
            folders: [],
            files: [],
            uploadedFile: {},
        }
    },
    mounted() {
        axios.all([
            axios.get('api/v1/folders'),
            axios.get('api/v1/files'),
        ])
            .then(axios.spread((folders, files) => {
                this.folders = folders.data.data
                this.files = files.data.data
            }))
            .finally(() => {
                this.loading = false
            })
    },
    methods: {
        uploadFile() {
            const formData = new FormData();
            formData.append('file', this.$refs.file.files[0]);
            const headers = { 'Content-Type': 'multipart/form-data' };

            return axios.post('api/v1/files', formData, {headers})
                .then(response => {
                    let createdFile = response.data.data
                    this.files.push(createdFile)
                    return createdFile
                })
                .catch(error => {
                    this.handleErrors(error.response.data.errors)
                })
        },
        renameFile(id) {
        },
        deleteFile(id) {
        },
        formatBytes(a, b) {
            if (0 == a) return "0 Байт";
            var c = 1000,
                d = b || 2,
                e = ["Б", "КБ", "МБ", "ГБ", "ТБ"],
                f = Math.floor(Math.log(a) / Math.log(c));
            return parseFloat((a / Math.pow(c, f)).toFixed(d)) + " " + e[f]
        },
        handleErrors(errors) {
            errors = errors[Object.keys(errors)[0]]

            errors.forEach(error => {
                this.$notify({
                    type: 'error',
                    text: error,
                })
            })
        },
    },
}
</script>

<style scoped>

</style>
