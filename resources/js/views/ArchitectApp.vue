<template>
    <div>
        <loader :show="showLoader"></loader>

        <div v-cloak class="flex min-h-screen">
            <!-- navigation -->
            <div class="navigation absolute z-30 hidden bg-highlight flex-none min-h-screen pt-15 w-56 max-w-225 sm:block sm:relative">
                <a :href="homeLink">
                    <div class="absolute bg-blue-500 flex h-15 items-center left-0 pxx-6 right-0 text-center top-0">
                        Logo
                    </div>
                </a>

                <architect-nav></architect-nav>
            </div>

            <!-- main content -->
            <div class="architect-container">
                <div class="bg-primary flex h-15 items-center px-2 relative shadow z-20">
                    <div class="flex-1">
                        <a class="font-bold mr-6 no-underline text-grey-800" :href="homeLink">

                        </a>
                    </div>
                    <div class="hamburger md:hidden text-4xl cursor-pointer float-right">
                        <font-awesome-icon :icon="['fas', 'bars']"></font-awesome-icon>
                    </div>
                </div>

                <div class="p-2 mx-auto">
                    <router-view></router-view>
                </div>
            </div>
        </div>

        <portal-target name="modal"></portal-target>
    </div>
</template>

<script>
    export default {
        data: () => ({
            showLoader: true,
        }),

        computed: {
            homeLink() {
                return window.config.prefix;
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