<template>
    <div class="bg-black-50 fixed w-full h-full top-0 left-0 flex justify-center items-center z-50"
         @click="closeModal()">

        <div class="flex flex-col bg-white rounded-lg shadow-xl w-11/12 max-w-2xl max-h-modal overflow-hidden overflow-y-scroll">
            <div class="flex justify-between items-center bg-gray-100 p-3 shadow-outline-dark">
                <h1 class="text-2xl leading-none flex-1">
                    {{ title }}
                </h1>
                <div v-if="closable" @click="closeModal()"
                     class="text-gray-500 text-xl hover:text-gray-700 cursor-pointer transition-colour leading-none flex justify-center items-center">
                    <font-awesome-icon :icon="['fas', 'times']"></font-awesome-icon>
                </div>
            </div>

            <div class="mt-2 p-3">
                <slot></slot>
            </div>
        </div>

    </div>
</template>

<script>
    export default {
        props: {
            title: {
                type: String,
                required: true,
            },
            id: {
                type: String,
            },
            closable: {
                type: Boolean,
                default: true,
            }
        },

        methods: {
            closeModal() {
                if (!this.closable) {
                    return;
                }

                window.Architect.$emit('modal-close', {
                    id: this.id
                });
            }
        }
    }
</script>
