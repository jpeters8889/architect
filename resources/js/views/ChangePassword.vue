<template>
    <div>
        <p class="mb-6">
            To change your password please enter your current password below, then your new password, and finally confirm
            your new password.
        </p>

        <div class="mb-6">
            <form-input name="currentPassword" type="password" placeholder="Current Password"
                        required></form-input>
        </div>

        <div class="mb-6">
            <form-input name="newPassword" type="password" placeholder="New Password"
                        required></form-input>
        </div>

        <div class="mb-6">
            <form-input name="passwordConfirmation" type="password" placeholder="Confirm New Password"
                        :match="fields.newPassword.value" required></form-input>
        </div>

        <form-button class="w-full" theme="positive" error-event-listener="loginError"
                     :click.prevent="processForm">Change Password
        </form-button>
    </div>
</template>

<script>
    export default {
        data: () => ({
            fields: {
                currentPassword: {
                    value: '',
                    valid: false,
                },
                newPassword: {
                    value: '',
                    valid: false,
                },
                passwordConfirmation: {
                    value: '',
                    valid: false,
                },
            },
        }),

        mounted() {
            this.forEachFields((field) => {
                this.$root.$on(field + '-value', (value) => {
                    this.fields[field].value = value;
                });

                this.$root.$on(field + '-keyup', (value) => {
                    this.fields[field].value = value;
                });

                this.$root.$on(field + '-valid', () => {
                    this.fields[field].valid = true;

                });

                this.$root.$on(field + '-error', () => {
                    this.fields[field].valid = false;
                });

                this.$root.$on(field + '-enter-press', () => {
                    this.processForm();
                });
            });
        },

        methods: {
            processForm() {
                this.forEachFields((field) => {
                    this.$root.$emit(field + '-get-value');
                });

                if (!this.fields.currentPassword.valid || !this.fields.newPassword.valid || !this.fields.passwordConfirmation.valid) {
                    window.Architect.error('Please enter your details...');
                    return;
                }

                window.Architect.request().post('/change-password', {
                    current_password: this.fields.currentPassword.value,
                    new_password: this.fields.newPassword.value,
                    new_password_confirmation: this.fields.passwordConfirmation.value,
                }).then((request) => {
                    if (request.status === 200) {
                        window.Architect.success('Password updated...');
                        this.$router.push('home');

                        return;
                    }

                    this.error();
                }).catch(() => {
                    this.error();
                });
            },

            error() {
                window.Architect.error('There was an error changing your password...');
                this.forEachFields((field) => {
                    this.fields[field].value = '';
                    this.$root.$emit(`${field}-set-value`, '');
                });
            },

            forEachFields(closure) {
                Object.keys(this.fields).forEach((field) => {
                    closure(field);
                });
            }
        }
    }
</script>
