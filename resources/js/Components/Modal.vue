<template>
    <div class="bg-black-90 fixed w-full h-full top-0 left-0 flex justify-center items-center z-50">
        <div class="rounded-lg shadow-2xl p-3 bg-gray-200 w-11/12 max-w-2xl">
            <div class="relative">
                <slot></slot>
            </div>
            <div class="mt-2 flex items-center justify-center" v-if="buttons">
                <div class="button button-primary"
                     v-for="button in buttons"
                >
                    {{ button.label }}
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            buttons: Array
        },
    }
</script>
