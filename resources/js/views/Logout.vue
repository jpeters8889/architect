<template>
    <div class="bg-blue-900 h-full text-black h-full flex justify-center items-center w-full absolute">
        <div class="bg-blue-100 max-w-xs mx-auto rounded-lg shadow mx-auto w-full border-green-500 border-t-4">
            <div class="mx-auto text-center text-highlight p-4">
                <img src="/vendor/architect/images/architect-logo-dark.png" alt="" />
            </div>

            <div class="p-8">
                <h2 class="font-normal mb-6 text-2xl text-center text-2">
                    You're now logged out.
                </h2>

                <a class="text-center font-normal hover:underline text-sm block text-gray-500" :href="link">
                    Login again
                </a>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            window.Architect.request().post('/logout');
        },

        computed: {
            link() {
                return window.config.prefix;
            }
        }
    }
</script>
