<template>
    <div>
        <input type="hidden" :name="name" v-model="actualValue" />

        <div v-bind:class="[actualValue==='1' ? 'border-blue-500 justify-end' : '', 'border-gray-400 flex w-15 bg-gray-300 rounded-full items-center p-1']"
             @click="toggleValue()"
        >
            <div class="w-6 h-6 rounded-full bg-gray-700" v-bind:class="[actualValue==='1' ? 'hidden' : '', 'w-6 h-6 rounded-full bg-gray-700']"></div>
            <div class="w-6 h-6 rounded-full bg-blue-500" v-bind:class="[actualValue==='0' ? 'hidden' : '', 'w-6 h-6 rounded-full bg-gray-700']"></div>
        </div>
    </div>
</template>

<script>
    import {IsAFormField} from 'architect-js-helpers';

    export default {
        mixins: [IsAFormField],

        mounted() {
            if (this.actualValue === '') {
                this.actualValue = '0';
            }
        },

        methods: {
            toggleValue() {
                this.actualValue = this.actualValue === '1' ? '0' : '1';
            }
        }
    }
</script>
