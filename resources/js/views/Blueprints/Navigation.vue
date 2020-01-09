<template>
    <div>
        <div v-for="building in buildings">
            <h3 class="flex items-center text-1 p-2 text-xl bg-white-05 border-b border-white-20">
                <div class="mr-1 text-center w-8">
                    <font-awesome-icon :icon="['far', 'building']"></font-awesome-icon>
                </div>
                <span>{{ building }}</span>
            </h3>

            <ul class="list-none mb-8">
                <li v-for="blueprint in blueprints[building]"
                    class="leading-tight border-b border-highlight transition-bg"
                    :class="isActiveRoute(blueprint.route) ? 'bg-primary hover:bg-primary-50' : 'bg-white-10 hover:bg-white-20'">
                    <router-link :to="{
                        name: 'blueprintList',
                        params: {
                            blueprint: blueprint.route,
                        }
                    }" class="no-underline text-justify text-7 text-xl flex items-center p-2">
                        <div class="mr-1 text-center w-8">
                            <font-awesome-icon :icon="['fas', 'cogs']"></font-awesome-icon>
                        </div>

                        <div class="flex-1">{{ blueprint.label }}</div>

                        <div v-if="blueprint.count > 0"
                             class="bg-negative rounded-full p-2 text-sm text-white font-semibold">
                            {{ blueprint.count }}
                        </div>
                    </router-link>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
    export default {
        computed: {
            buildings() {
                return window.config.navigation.buildings;
            },

            blueprints() {
                return window.config.navigation.blueprints;
            }
        },

        methods: {
            isActiveRoute(route) {
                return route === this.$route.params.blueprint;
            }
        }
    }
</script>