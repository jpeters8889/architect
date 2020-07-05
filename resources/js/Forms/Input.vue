<template>
    <div class="flex overflow-hidden border border-gray-500 rounded" :class="classes">
        <div class="bg-white p-1 flex-1">
            <input v-model="currentValue" :type="type" :name="name" :placeholder="placeholder" @blur="validate()" @keyup="onKeyup()" @keyup.enter="onEnter()"
                   class="w-full text-sm border-0 p-0 m-0 text-gray-900" />
        </div>

        <div class="bg-red-500 flex justify-center items-center p-2 text-white" v-if="hasError"
             v-tooltip="errorText">
            <font-awesome-icon :icon="['fas', 'exclamation-circle']"></font-awesome-icon>
        </div>
    </div>
</template>

<script>
    export default {
        data: () => ({
            hasError: false,
            errorText: '',
            currentValue: '',
        }),

        props: {
            classes: {
                type: String,
                default: '',
            },
            type: {
                type: String,
                default: 'text',
            },
            name: {
                type: String,
                required: true,
            },
            value: {
                type: String,
                default: '',
            },
            placeholder: {
                type: String,
                default: '',
            },
            required: {
                type: Boolean,
                default: false,
            }
        },

        mounted() {
            this.currentValue = this.value;

            if(this.required) {
                this.hasError = true;
            }

            this.$root.$on(this.name + '-get-value', () => {
                this.validate();
                this.$root.$emit(this.name + '-value', this.currentValue);
            });

            this.$root.$on(this.name + '-set-value', (value) => {
                this.currentValue = value;
                this.validate();
            });
        },

        methods: {
            validate() {

                if (this.required && this.currentValue === '') {
                    this.hasError = true;
                    this.errorText = 'Field is required';
                    return;
                }

                switch (this.type) {
                    case 'email':
                        if (!this.checkEmail()) {
                            this.hasError = true;
                            this.errorText = 'Must be a valid email address';
                            return;
                        }
                }

                this.hasError = false;
                this.errorText = '';
            },

            onEnter() {
                this.$root.$emit(this.name + '-enter-press');
            },

            onKeyup() {
                this.$root.$emit(this.name + '-keyup', this.currentValue);
            },

            checkEmail() {
                const emailRegex = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                return emailRegex.test(this.currentValue);
            },
        },

        watch: {
            hasError: function (error) {
                if (error) {
                    this.$root.$emit(this.name + '-error', this.errorText);
                    return;
                }

                this.$root.$emit(this.name + '-valid');
            }
        }
    }
</script>

<style>
    input:focus {
        outline: none;
    }

    input:-webkit-autofill {
        background: none;
    }
</style>
