<template>
    <div>
        <header-component can-view-list="true" :blueprint="blueprint">
            {{ this.pageTitle }}
        </header-component>

        <div class="bg-white w-full p-2">
            <form autocomplete="off" @submit.prevent="submitForm">
                <div class="w-full py-3" v-for="field in fields">
                    <form-field :field="field"></form-field>
                </div>

                <div class="w-full py-3 flex justify-end">
                    <button class="button button-primary button-default" type="submit">
                        {{ buttonLabel }}
                    </button>
                </div>
            </form>
        </div>

        <portal to="modal" v-if="showModal">
            <modal>
                <div class="text-2xl">
                    {{ this.blueprint }} saved.
                </div>
                <div class="flex justify-end">
                    <router-link
                            class="button button-primary rounded px-4 py-1"
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
                            class="button button-primary rounded px-4 py-1"
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

                    <a class="button button-primary rounded px-4 py-1"
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
            state: {
                type: String,
                default: 'add',
            },
        },

        data: () => ({
            title: '',
            fields: [],
            values: {},
            showModal: false,
            savedBlueprintUrl: '',
        }),

        mounted() {
            this.initComponent();

            window.Architect.$on('form-field-change', (field) => {
                this.$set(this.values, field.name, field.value);
            });
        },

        computed: {
            pageTitle() {
                if (this.state === 'add') {
                    return this.title + ' - Add New';
                }

                return this.title + ' - Update';
            },

            buttonLabel() {
                if (this.state === 'add') {
                    return 'Add Record';
                }

                return 'Update Record';
            }
        },

        methods: {
            async initComponent() {
                this.getBlueprint();
            },

            getBlueprint() {
                Architect.request().get(`/blueprints/${this.blueprint}/add`)
                    .then((response) => {
                        this.title = response.data.meta.title;
                        this.fields = response.data.fields;

                        this.fields.forEach((field) => {
                            this.$set(this.values, field.name, field.value);
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
            },

            submitForm() {
                let url = `/blueprints/submit`;

                window.Architect.request().post(url, this.collectData())
                    .then((response) => {
                        this.savedBlueprintUrl = response.response.url;
                        this.showModal = true;
                    })
                    .catch((error) => {
                        window.Architect.$emit('error', 'An error has occurred, ' + error.message + ' - ' + error.response.data.message);
                    });
            },

            collectData() {
                window.Architect.$emit('prepare-form-data');

                let formData = new FormData();

                Object.keys(this.values).forEach((name) => {
                    formData.append(name, this.values[name]);
                });

                formData.append('_blueprint', this.blueprint);
                formData.append('_state', this.state);

                return formData;
            },
        },
    }
</script>