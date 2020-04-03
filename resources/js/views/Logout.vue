<template>
    <div class="bg-highlight h-full text-black h-full flex justify-center items-center w-full absolute">
        <div class="bg-8 max-w-xs mx-auto rounded-lg shadow mx-auto w-full border-primary border-t-4 p-8 text-center">
            <h2 class="text-lg">You're now logged out.</h2>
            <h3 class="text-primary hover:text-highlight font-bold">
                <a :href="logoutLink">Login again</a>
            </h3>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            window.Architect.request().post('/logout');
        },

        computed: {
            logoutLink() {
                return window.config.prefix + '/logout'
            }
        }
    }
</script>