<template>
  <div>
    <div class="flex flex-col bg-white rounded-xl shadow mb-2">
      <header-component
        :can-view-list="true"
        :blueprint="blueprint"
      >
        {{ pageTitle }}
      </header-component>
    </div>

    <div class="bg-white w-full p-4 rounded-xl shadow">
      <form
        autocomplete="off"
        @submit.prevent="submitForm"
      >
        <div
          v-for="plan in plans"
          :key="plan.label"
          class="w-full py-3"
        >
          <plan-form-field :plan="plan" />
        </div>

        <div class="w-full py-3 flex justify-end">
          <button
            class="button button-primary button-default"
            type="submit"
          >
            {{ buttonLabel }}
          </button>
        </div>
      </form>
    </div>

    <portal
      v-if="showModal"
      to="modal"
    >
      <modal title="Done!">
        <div class="text-2xl">
          {{ title }} saved.
        </div>
        <div class="flex justify-end">
          <router-link
            class="button button-primary rounded px-4 py-1 m-1"
            :to="{
              name: 'blueprintList',
              params: {
                blueprint: blueprint,
              }
            }"
          >
            Back to list
          </router-link>

          <a
            v-if="currentState==='add'"
            class="button button-primary rounded px-4 py-1 m-1"
            @click="refresh()"
          >
            Add Another
          </a>

          <a
            v-if="savedBlueprintUrl"
            class="button button-primary rounded px-4 py-1 m-1"
            :href="savedBlueprintUrl"
            target="_blank"
            @click="showModal = false"
          >
            View {{ blueprint }}
          </a>
        </div>
      </modal>
    </portal>
  </div>
</template>

<script>
export default {
  props: {
    blueprint: {
      type: String,
      required: true,
    },
    state: {
      type: String,
      required: true,
    },
  },

  data: () => ({
    title: '',
    plans: [],
    values: {},
    showModal: false,
    savedBlueprintUrl: '',
  }),

  computed: {
    currentState() {
      if (this.$route.params.id !== undefined) {
        return 'update';
      }

      return 'add';
    },

    pageTitle() {
      if (this.currentState === 'add') {
        return `${this.title} - Add New`;
      }

      return `${this.title} - Update`;
    },

    buttonLabel() {
      if (this.currentState === 'add') {
        return 'Add Record';
      }

      return 'Update Record';
    },

    blueprintUrl() {
      if (this.currentState === 'update') {
        return `/blueprints/${this.blueprint}/${this.$route.params.id}`;
      }

      return `/blueprints/${this.blueprint}/add`;
    },
  },

  mounted() {
    this.initComponent();

    Architect.$on('form-field-change', (field) => {
      this.$set(this.values, field.name, field.value);
    });
  },

  methods: {
    async initComponent() {
      Architect.$emit('load-start');
      this.getBlueprint();
    },

    getBlueprint() {
      Architect.request().get(this.blueprintUrl)
        .then((response) => {
          this.title = response.data.meta.title;
          this.plans = response.data.plans;

          this.plans.forEach((plan) => {
            this.$set(this.values, plan.name, plan.value);
          });
        })
        .catch((error) => {
          if (error.response.status >= 500) {
            Architect.$emit(error.response.data.message);
            return;
          }

          Architect.error('Can\'t find Blueprint');
        });

      Architect.$emit('load-end');
    },

    submitForm() {
      const url = '/blueprints/submit';

      Architect.request().post(url, this.collectData())
        .then((response) => {
          this.savedBlueprintUrl = response.data.url;
          this.showModal = true;
        })
        .catch((error) => {
          Architect.$emit('error', `An error has occurred, ${error.message} - ${error.response.data.message}`);
        });
    },

    collectData() {
      Architect.$emit('prepare-form-data');

      const formData = new FormData();

      Object.keys(this.values).forEach((name) => {
        formData.append(name, this.values[name]);
      });

      formData.append('_blueprint', this.blueprint);
      formData.append('_state', this.currentState);
      formData.append('_id', this.$route.params.id);

      return formData;
    },

    refresh() {
      window.refresh();
    },
  },
};
</script>
