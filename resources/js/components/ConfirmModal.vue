<template>
    <vue-final-modal
        v-slot="{ params, close }"
        v-bind="$attrs"
        classes="flex justify-center items-center"
        content-class="relative flex flex-col max-h-full mx-4 p-8 border dark:border-gray-800 rounded bg-white dark:bg-gray-900"
    >
    <span class="mr-8 text-2xl font-bold ">
      <slot name="title"></slot>
    </span>
        <div class="flex-grow overflow-y-auto">
            <slot v-bind:params="params"></slot>
        </div>
        <div class="pt-4">
            <main-button @click="$emit('confirm', close)" class="mr-3" theme="danger">Подтвердить</main-button>
            <main-button @click="$emit('cancel', close)">Отмена</main-button>
        </div>
        <button class="absolute top-0 right-0 mt-2 mr-2" @click="close">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </vue-final-modal>
</template>

<script>
import MainButton from "./MainButton.vue";

export default {
    name: "ConfirmModal",
    components: {
        MainButton,
    },
}
</script>
