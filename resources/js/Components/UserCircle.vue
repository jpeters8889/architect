<template>
  <div>
    <div class="relative">
      <button
        v-click-outside="closeLinks"
        class="text-white p-2 font-thin cursor-pointer transition-bg outline-none focus:outline-none"
        :class="showLinks ? 'rounded-t-full bg-blue-500' : 'rounded-full bg-blue-700 hover:bg-blue-500'"
        @click.prevent="showLinks = !showLinks"
      >
        {{ userInitials }}
      </button>

      <ul
        v-if="showLinks"
        class="absolute bg-blue-500 right-0 rounded-b-lg rounded-tl-lg text-right text-white -mt-2 overflow-hidden"
        style="width: 200px"
      >
        <li
          v-if="canChangePassword"
          class="block"
        >
          <a
            class="cursor-pointer w-full block py-2 pr-2 transition-bg hover:bg-blue-600 border-b border-gray-200"
            @click="showChangePassword = true"
          >
            Change Password
          </a>
        </li>

        <li class="block">
          <router-link
            class="w-full block py-2 pr-2 transition-bg hover:bg-blue-600"
            :to="{name: 'logout'}"
          >
            Logout
          </router-link>
        </li>
      </ul>
    </div>

    <portal
      v-if="showChangePassword"
      to="modal"
    >
      <modal title="Change Password">
        <change-password />
      </modal>
    </portal>
  </div>
</template>

<script>
import ClickOutside from 'vue-click-outside';

export default {

  directives: {
    ClickOutside,
  },
  data: () => ({
    showLinks: false,
    showChangePassword: false,
  }),

  computed: {
    canChangePassword() {
      return window.config.canChangePassword;
    },

    userInitials() {
      const initials = window.config.user.name.split(' ').map((user) => user[0]);

      if (initials.length === 1) {
        return initials[0];
      }

      return `${initials[0]}${initials[initials.length - 1]}`;
    },
  },

  mounted() {
    window.Architect.$on('modal-close', () => {
      this.showChangePassword = false;
    });
  },

  methods: {
    closeLinks() {
      this.showLinks = false;
    },
  },
};
</script>
