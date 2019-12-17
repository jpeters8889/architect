<template>
    <div>
        <input type="text"
               class="form-control form-control-input w-full"
               v-model="actualValue"
               :name="name"
               @keyup="lookup()" />

        <div :class="searchResultsDisplay" class="relative">
            <ul class="absolute bg-7 w-auto b-1 border-8 shadow">
                <li class="flex justify-between p-2 border-b-1 border-black-50 cursor-pointer hover:bg-black-10"
                    v-for="result in lookupResults"
                    @click="select(result)"
                >
                    {{ result.search_name || result[metas.lookupVariable] }}
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
    import {IsAFormField} from 'architect-js-helpers';

    export default {
        mixins: [IsAFormField],

        data: () => ({
            lookupResults: [],
            searchResultsDisplay: 'hidden',
            setEmitterValue: false,
        }),

        methods: {
            lookup() {
                this.lookupResults = [];
                Architect.request().post('/lookup', {
                    blueprint: this.$route.params.blueprint,
                    column: this.name,
                    value: this.actualValue
                }).then((response) => {
                    this.lookupResults = response.data;
                    this.searchResultsDisplay = 'block';
                }).catch(error => {
                    Architect.$emit(error.response.data.message);
                });
            },

            select(option) {
                this.searchResultsDisplay = 'hidden';
                this.lookupResults = [];
                this.actualValue = option[this.metas.lookupVariable];
                this.emitterValue = option;
            }
        }
    }
</script>