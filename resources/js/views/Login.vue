<template>
  <div class="bg-blue-900 h-full text-black h-full flex justify-center items-center w-full absolute">
    <div class="bg-blue-100 max-w-xs mx-auto rounded-lg shadow mx-auto w-full border-green-500 border-t-4">
      <div class="mx-auto text-center text-highlight p-4">
        <img
          src="/vendor/architect/images/architect-logo-dark.png"
          alt=""
        >
      </div>

      <div class="p-8 pt-0">
        <h2 class="font-normal mb-6 text-2xl text-center text-2">
          Please login
        </h2>

        <div>
          <div class="mb-2">
            <form-input
              name="email"
              type="email"
              placeholder="Email Address"
              required
              :value="fields.email.value"
            />
          </div>

          <div class="mb-6">
            <form-input
              name="password"
              type="password"
              placeholder="Password"
              required
            />
          </div>

          <form-button
            class="w-full"
            theme="positive"
            error-event-listener="loginError"
            :click="processLogin"
          >
            Login
          </form-button>
        </div>
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
      },
    },
  }),

  mounted() {
    this.forEachFields((field) => {
      Architect.$on(`${field}-value`, (value) => {
        this.fields[field].value = value;
      });

      Architect.$on(`${field}-keyup`, (value) => {
        this.fields[field].value = value;
      });

      Architect.$on(`${field}-valid`, () => {
        this.fields[field].valid = true;
      });

      Architect.$on(`${field}-error`, () => {
        this.fields[field].valid = false;
      });

      Architect.$on(`${field}-enter-press`, () => {
        this.processLogin();
      });
    });
  },

  methods: {
    processLogin() {
      this.forEachFields((field) => {
        Architect.$emit(`${field}-get-value`);
      });

      setTimeout(() => {
        if (this.fields.email.valid && this.fields.password.valid) {
          Architect.request().post('/auth', {
            email: this.fields.email.value,
            password: this.fields.password.value,
          }).then((request) => {
            if (request.status === 200) {
              window.location = request.data.redirect;
              return;
            }

            this.loginError();
          }).catch(() => {
            this.loginError();
          });

          return;
        }

        Architect.$emit('loginError');
        Architect.error('Please enter your details...');
      }, 100);
    },

    loginError() {
      Architect.$emit('loginError');
      Architect.error('There was an error logging you in...');
      this.fields.password.value = '';
      this.fields.password.valid = false;

      Architect.$emit('password-set-value', '');
    },

    forEachFields(closure) {
      Object.keys(this.fields).forEach((field) => {
        closure(field);
      });
    },
  },
};
</script>
