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
        }),

        mounted() {
            this.initComponent();
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
                let url = `/architect-api/blueprint/${this.state}`;

                window.Architect.request().post(url, this.collectData())
            },

            collectData() {
                return _.tap(new FormData(), formData => {
                    _.each(this.fields, field => {
                        console.log(field);
                        field.fillFormData(formData);
                    });

                    formData.append('blueprint', this.blueprint);

                    if (this.state === 'update') {
                        formData.append('id', 1)//todo;
                    }
                });
            }
        },
    }
</script>