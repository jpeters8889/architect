<template>
    <header class="bg-white w-full p-2 flex mb-2">
        <div class="flex-1 text-2xl font-bold text-highlight">
            <slot></slot>
        </div>
        <div class="flex justify-end">
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
    </header>
</template>

<script>
    export default {
        props: [
            'canAdd',
            'canViewList',
            'blueprint'
        ],
    }
</script>