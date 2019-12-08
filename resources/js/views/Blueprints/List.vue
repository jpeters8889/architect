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
                    :rows="data.data"
                    :components="components"
                    :hide-on-mobile="hideOnMobile"
            >
            </table-component>
            <div v-if="data.last_page > 1" class="bg-primary-10 p-2">
                <pagination
                        :current_page="data.current_page"
                        :from="data.from"
                        :last_page="data.last_page"
                        :per_page="data.per_page"
                        :to="data.to"
                        :total="data.total"
                ></pagination>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['blueprint'],

        data: () => ({
            title: '',
            headers: {},
            data: {},
            components: {},
            hideOnMobile: {},
            page: 1,
        }),

        mounted() {
            this.initComponent();

            window.Architect.$on('paginator-change', (page) => {
                this.page = page;
                this.initComponent();
                window.scrollTo(0,0);
            });
        },

        methods: {
            async initComponent() {
                this.getBlueprint();
            },

            getBlueprint() {
                Architect.request().get(`/blueprints/${this.blueprint}/list?page=${this.page}`)
                    .then((response) => {
                        this.title = response.data.meta.title;
                        this.hideOnMobile = response.data.hiddenOnMobile;
                        this.headers = response.data.labels;
                        this.data = response.data.data;
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