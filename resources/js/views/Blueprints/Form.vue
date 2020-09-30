<template>
    <div>
        <div class="flex flex-col bg-white rounded-xl shadow mb-2">
            <header-component :can-view-list="true" :blueprint="blueprint">
                {{ this.pageTitle }}
            </header-component>
        </div>

        <div class="bg-white w-full p-4 rounded-xl shadow">
            <form autocomplete="off" @submit.prevent="submitForm">
                <div class="w-full py-3" v-for="plan in plans">
                    <plan-form-field :plan="plan"></plan-form-field>
                </div>

                <div class="w-full py-3 flex justify-end">
                    <button class="button button-primary button-default" type="submit">
                        {{ buttonLabel }}
                    </button>
                </div>
            </form>
        </div>

        <portal to="modal" v-if="showModal">
            <modal title="Done!">
                <div class="text-2xl">
                    {{ this.title }} saved.
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

                    <router-link
                            class="button button-primary rounded px-4 py-1 m-1"
                            v-if="state==='add'"
                            :to="{
                                name: 'blueprintForm',
                                params: {
                                    blueprint: blueprint,
                                    state: 'add',
                                }
                            }"
                    >
                        Add Another
                    </router-link>

                    <a class="button button-primary rounded px-4 py-1 m-1"
                       v-if="savedBlueprintUrl"
                       :href="savedBlueprintUrl"
                       target="_blank"
                       @click="showModal = false"
                    >
                        View {{ this.blueprint }}
                    </a>
                </div>
            </modal>
        </portal>
    </div>
</template>

<script>
    export default {
        props: {
            blueprint: String,
            state: String,
        },

        data: () => ({
            title: '',
            plans: [],
            values: {},
            showModal: false,
            savedBlueprintUrl: '',
        }),

        mounted() {
            this.initComponent();

            Architect.$on('form-field-change', (field) => {
                this.$set(this.values, field.name, field.value);
            });
        },

        computed: {
            currentState() {
                if (this.$route.params.id !== undefined) {
                    return 'update';
                }

                return 'add';
            },

            pageTitle() {
                if (this.currentState === 'add') {
                    return this.title + ' - Add New';
                }

                return this.title + ' - Update';
            },

            buttonLabel() {
                if (this.currentState === 'add') {
                    return 'Add Record';
                }

                return 'Update Record';
            },

            blueprintUrl() {
                if (this.currentState === 'update') {
                    return `/blueprints/${this.blueprint}/${this.$route.params.id}`
                }

                return `/blueprints/${this.blueprint}/add`;
            }
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
                    .catch(error => {
                        if (error.response.status >= 500) {
                            Architect.$emit(error.response.data.message);
                            return;
                        }

                        if (error.response.status === 404) {

                        }

                        Architect.error("Can't find Blueprint");
                    });

                Architect.$emit('load-end');
            },

            submitForm() {
                let url = `/blueprints/submit`;

                Architect.request().post(url, this.collectData())
                    .then((response) => {
                        this.savedBlueprintUrl = response.data.url;
                        this.showModal = true;
                    })
                    .catch((error) => {
                        Architect.$emit('error', 'An error has occurred, ' + error.message + ' - ' + error.response.data.message);
                    });
            },

            collectData() {
                Architect.$emit('prepare-form-data');

                let formData = new FormData();

                Object.keys(this.values).forEach((name) => {
                    formData.append(name, this.values[name]);
                });

                formData.append('_blueprint', this.blueprint);
                formData.append('_state', this.currentState);
                formData.append('_id', this.$route.params.id);

                return formData;
            },
        },
    }
</script>
