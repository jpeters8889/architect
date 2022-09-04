<template>
  <div class="bg-blue-900 h-full text-black h-full flex justify-center items-center w-full absolute">
    <div class="bg-blue-100 max-w-xs mx-auto rounded-lg shadow mx-auto w-full border-red-500 border-t-4">
      <div class="p-8">
        <h2 class="font-semibold mb-6 text-xl leading-tight text-center text-red-500">
          Sorry, you don't have the permission to complete this action.
        </h2>

        <a
          class="text-center font-semibold hover:underline text-sm block"
          :href="url"
        >
          Back to {{ siteName }}
        </a>

        <router-link
          class="text-center font-normal hover:underline text-sm block text-gray-500"
          :to="{name: 'logout'}"
        >
          Logout
        </router-link>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  computed: {
    siteName() {
      return window.config.siteName;
    },

    url() {
      return window.config.url;
    },
  },
};
</script>
