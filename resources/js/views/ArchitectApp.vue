<template>
    <div>
        <loader :show="showLoader"></loader>

        <template v-if="!isLoggedIn">
            <router-view></router-view>
        </template>

        <template v-else>
            <div v-cloak class="flex flex-col min-h-screen">
                <!-- Header bar -->
                <div class="flex justify-between">
                    <div class="w-full bg-blue-900 p-2 h-15 flex sm:w-255">
                        <div class="mr-1 p-1 flex justify-center items-center text-blue-100 font-bold text-3xl cursor-pointer sm:hidden"
                             @click="showMobileNav = true">
                            <font-awesome-icon :icon="['fas', 'bars']"></font-awesome-icon>
                        </div>
                        <router-link :to="{name: 'home'}">
                            Logo
                        </router-link>
                    </div>
                    <div class="hidden sm:block bg-gray-100 flex-1 p-2 h-15 flex shadow">
                        Title?
                    </div>
                </div>

                <div class="flex flex-1">
                    <div class="hidden navigation w-255 max-w-255 sm:block sm:relative p-2 flex-1 bg-blue-700 shadow" style="flex: 0 0 255px">
                        <architect-nav class="flex-1"></architect-nav>
                    </div>

                    <div class="p-2 mx-auto flex-1">
                        <div class="bg-gray-100 rounded p-4 shadow">
                            <router-view></router-view>
                        </div>
                    </div>
                </div>

            </div>

            <div v-if="showMobileNav" class="fixed top-0 left-0 w-full h-full bg-blue-700 overflow-auto">
                <div class="h-full p-4">
                    <architect-nav class="pb-15 overflow-y-scroll"></architect-nav>
                </div>

                <div class="w-full fixed bottom-0 leading-tight cursor-pointer">
                    <a class="no-underline text-center text-7 text-xl p-6 text-center text-gray-100 block"
                       @click="showMobileNav = false">
                        Close
                    </a>
                </div>
            </div>

            <portal-target name="modal"></portal-target>
        </template>
    </div>
</template>

<script>
    export default {
        data: () => ({
            showLoader: true,
            showMobileNav: false,
        }),

        computed: {
            isLoggedIn() {
                const routes = ['login', 'logout', 'error'];

                if (routes.includes(this.$route.name)) {
                    return false;
                }

                return !!document.querySelector('meta[name="api-token"]');
            }
        },

        // created() {
        //   if(this.isLoggedIn) {
        //       window.Architect.request().get('/health').then((response) => {
        //           if(response.status !== 200) {
        //
        //           }
        //       })
        //   }
        // },

        mounted() {
            window.Architect.$on('load-start', () => {
                this.showLoader = true;
            });

            window.Architect.$on('load-end', () => {
                this.showLoader = false;
            });

            window.Architect.$emit('load-end');
        }
    }
</script>
