<template>
    <header class="w-full p-4 flex">
        <div class="flex-1">
            <h1 class="text-2xl font-semibold text-blue-700">
                <slot></slot>
            </h1>
        </div>

        <div class="flex justify-end">
            <blueprint-search v-if="searchable && !hasCard" class="mr-2"></blueprint-search>

            <blueprint-filter :filters="filters" v-if="!hasCard && Object.keys(filters).length > 0"
                              class="mr-2"></blueprint-filter>

            <div class="flex space-x-2">
              <router-link
                  v-if="button"
                  class="button button-primary px-4 py-2 rounded cursor-pointer hover:bg-highlight transition-bg"
                  :to="button.link"
                  v-text="button.label"
              />

                <router-link
                        v-if="canAdd"
                        class="button button-primary px-4 py-2 rounded cursor-pointer hover:bg-highlight transition-bg"
                        :to="{
                        name: 'blueprintForm',
                        params: {
                            blueprint: blueprint,
                            state: 'add',
                        }
                    }"
                >
                    Add
                </router-link>
            </div>

            <div>
                <router-link
                        v-if="canViewList"
                        class="button button-primary px-4 py-2 rounded cursor-pointer hover:bg-highlight transition-bg"
                        :to="{
                        name: 'blueprintList',
                        params: {
                            blueprint: blueprint,
                        }
                    }"
                >
                    Back to List
                </router-link>
            </div>
        </div>

    </header>
</template>

<script>
    export default {
        props: {
            canAdd: {
                type: Boolean,
                required: false,
                default: false,
            },
            canViewList: {
                type: Boolean,
                required: false,
                default: false,
            },
            blueprint: {
                type: String,
                required: true,
            },
            hasCard: {
                type: Boolean,
                default: false,
            },
            filters: {
                type: Object,
                default: () => {
                    return {}
                },
            },
            searchable: {
                type: Boolean,
                default: false,
            },
            button: {
              type: Array | null,
              default: () => null,
            },
        },
    }
</script>
