<template>
    <button v-bind="$attrs"
        class="py-2 px-4
        border border-transparent
        text-sm font-medium text-white
        bg-indigo-600 hover:bg-indigo-700
        focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            :class="style"
    >
        <slot></slot>
    </button>
</template>

<script>
export default {
    props: ['theme'],
    name: "MainButton",
    computed: {
        style() {
            let themes = {
                'default': 'bg-indigo-600 hover:bg-indigo-700 focus:ring-indigo-500',
                'danger': 'bg-red-600 hover:bg-red-700 focus:ring-red-500',
            }
            return this.theme ? themes[this.theme] : themes['default']
        },
    },
}
</script>
