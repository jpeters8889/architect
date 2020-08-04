<template>
    <div class="flex overflow-hidden border border-gray-500 rounded" :class="classes">
        <div class="bg-white p-2 flex-1">
            <input v-model="currentValue" :type="type" :name="name" :placeholder="placeholder" @blur="validate()" @keyup="onKeyup()" @keyup.enter="onEnter()"
                   class="w-full border-0 p-0 m-0 text-gray-900 leading-none" />
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
            },
            match: {
                type: String,
                default: null,
            }
        },

        mounted() {
            this.currentValue = this.value;

            if(this.required) {
                this.hasError = true;
            }

            if(this.match && this.value !== this.match) {
                this.hasError = true;
            }

            Architect.$on(this.name + '-get-value', () => {
                this.validate();
                Architect.$emit(this.name + '-value', this.currentValue);
            });

            Architect.$on(this.name + '-set-value', (value) => {
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

                if(this.match && this.currentValue !== this.match) {
                    this.hasError = true;
                    this.errorText = 'Field does not match';
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
                Architect.$emit(this.name + '-enter-press');
            },

            onKeyup() {
                Architect.$emit(this.name + '-keyup', this.currentValue);
            },

            checkEmail() {
                const emailRegex = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                return emailRegex.test(this.currentValue);
            },
        },

        watch: {
            hasError: function (error) {
                if (error) {
                    Architect.$emit(this.name + '-error', this.errorText);
                    return;
                }

                Architect.$emit(this.name + '-valid');
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
