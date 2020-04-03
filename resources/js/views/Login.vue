<template>
    <div class="bg-blue-900 h-full text-black h-full flex justify-center items-center w-full absolute">
        <div class="bg-blue-100 max-w-xs mx-auto rounded-lg shadow mx-auto w-full border-green-500 border-t-4">
            <div class="mx-auto text-center text-highlight">
                Logo
            </div>

            <div class="p-8">
                <h2 class="font-normal mb-6 text-2xl text-center text-2">Please login</h2>

                <form>
                    <!-- csrf -->

                    <div class="mb-2">
                        <form-input name="email" type="email" placeholder="Email Address"
                                          required :value="fields.email.value"></form-input>
                    </div>

                    <div class="mb-6">
                        <form-input name="password" type="password" placeholder="Password"
                                          required></form-input>
                    </div>

                    <form-button class="w-full" theme="positive" error-event-listener="loginError"
                                       :click="processLogin">Login
                    </form-button>
                </form>

            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data: () => ({
            fields: {
                email: {
                    value: '',
                    valid: false,
                },
                password: {
                    value: '',
                    valid: false,
                }
            },
        }),

        mounted() {
            this.forEachFields((field) => {
                this.$root.$on(field + '-value', (value) => {
                    this.fields[field].value = value;
                });

                this.$root.$on(field + '-valid', () => {
                    this.fields[field].valid = true;

                });

                this.$root.$on(field + '-error', () => {
                    this.fields[field].valid = false;
                });
            });
        },

        methods: {
            processLogin() {
                this.forEachFields((field) => {
                    this.$root.$emit(field + '-get-value');
                });

                this.$root.$on('jp-form-error', () => {
                    this.$root.$emit('loginError');
                    window.Architect.error('Please enter your details...');
                });

                if (this.fields.email.valid && this.fields.password.valid) {
                    window.Architect.request().post('/auth', {
                        email: this.fields.email.value,
                        password: this.fields.password.value,
                    }).then(() => {
                        window.location = window.config.prefix;
                    }).catch(error => {
                        console.log(error);
                        window.Architect.error('There was an error logging you in...');
                        this.fields.password.value = '';
                        this.fields.password.valid = false;

                        this.$root.$emit('password-set-value', '');
                    });
                }
            },

            forEachFields(closure) {
                Object.keys(this.fields).forEach((field) => {
                    closure(field);
                });
            }
        }
    }
</script>
