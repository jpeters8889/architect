<template>
    <div>
        <loader :show="showLoader"></loader>

        <template v-if="!isLoggedIn">
            <router-view></router-view>
        </template>

        <template v-else>
            <div v-cloak class="flex flex-col min-h-screen">
                <!-- Header bar -->
                <div class="bg-highlight p-2 h-15 flex justify-between">
                    <div class="flex">
                        <div class="mr-1 p-1 flex justify-center items-center text-white font-bold text-3xl cursor-pointer sm:hidden"
                             @click="showMobileNav = true">
                            <font-awesome-icon :icon="['fas', 'bars']"></font-awesome-icon>
                        </div>
                        <router-link :to="{name: 'home'}">
                            Logo
                        </router-link>
                    </div>
                </div>

                <div class="flex">
                    <div class="navigation absolute z-30 hidden bg-highlight flex-none min-h-screen w-56 max-w-225 sm:block sm:relative">
                        <architect-nav></architect-nav>
                    </div>

                    <div class="p-2 mx-auto">
                        <router-view></router-view>
                    </div>
                </div>

            </div>

            <div v-if="showMobileNav" class="fixed top-0 left-0 w-full h-full bg-highlight overflow-auto">
                <div class="mb-15">
                    <architect-nav></architect-nav>
                </div>

                <div class="w-full fixed bottom-0 leading-tight border-b border-highlight transition-bg bg-primary cursor-pointer">
                    <a class="no-underline text-center text-7 text-xl flex items-center p-2"
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
                if (this.$route.name === 'logout') {
                    return false;
                }

                return !!document.querySelector('meta[name="api-token"]');
            }
        },

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