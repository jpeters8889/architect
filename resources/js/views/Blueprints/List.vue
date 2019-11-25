<template>
    <div>
        <header-component can-add="true" :blueprint="blueprint">
            {{ this.title }}
        </header-component>

        <!-- List -->
        <div class="bg-white w-full p-2">
            <table-component
                    :blueprint="blueprint"
                    :headers="headers"
                    :rows="rows"
                    :components="components"
                    :hide-on-mobile="hideOnMobile"
            >
            </table-component>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['blueprint'],

        data: () => ({
            title: '',
            headers: {},
            rows: {},
            components: {},
            hideOnMobile: {},
        }),

        mounted() {
            this.initComponent();
        },

        methods: {
            async initComponent() {
                this.getBlueprint();
            },

            getBlueprint() {
                Architect.request().get(`/blueprints/${this.blueprint}/list`)
                    .then((response) => {
                        this.title = response.data.meta.title;
                        this.hideOnMobile = response.data.hiddenOnMobile,
                            this.headers = response.data.labels;
                        this.rows = response.data.data.data;
                        this.components = response.data.vuePrefixes;
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
            }
        },
    }
</script>