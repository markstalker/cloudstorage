<template>
    <div>
        <notifications
            position="bottom center"
        />
        <sidebar v-if="loggedIn"></sidebar>
        <main
            class='h-screen'
            :class="{'ml-64 h-screen p-16' : loggedIn}">
            <router-view />
        </main>
    </div>
</template>

<script>
import Sidebar from "./components/Sidebar.vue";

export default {
    name: "App",
    components: {
        Sidebar,
    },
    data() {
        return {
            loggedIn: this.$user !== null,
        }
    },
}
</script>
