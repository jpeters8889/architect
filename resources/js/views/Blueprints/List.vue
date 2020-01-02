<template>
    <div>
        <header-component :blueprint="blueprint">
            {{ this.title }}
        </header-component>

        <!-- List -->
        <div class="bg-white w-full p-2">
            <div v-if="headers.length > 0">
                <table-component
                        :blueprint="blueprint"
                        :headers="headers"
                        :rows="data.data"
                        :components="components"
                        :hide-on-mobile="hideOnMobile"
                >
                </table-component>
            </div>

            <div v-if="card && card.component" class="flex flex-wrap">
                <card-component
                        v-for="row in data.data"
                        :key="row.id"
                        :component="card.component"
                        :labels="headers"
                        :data="row"
                >
                </card-component>
            </div>

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
            card: {
                component: null,
                labels: [],
            },
            page: 1,
        }),

        mounted() {
            this.initComponent();

            window.Architect.$on('paginator-change', (page) => {
                this.page = page;
                this.initComponent();
                window.scrollTo(0, 0);
            });

            window.Architect.$on('reload-page', () => {
                this.initComponent();
            })
        },

        watch: {
            $route() {
                this.initComponent();
            }
        },

        methods: {
            initComponent() {
                this.getBlueprint();
            },

            getBlueprint() {
                Architect.request().get(`/blueprints/${this.blueprint}/list?page=${this.page}`)
                    .then((response) => {
                        this.title = response.data.meta.title;
                        this.hideOnMobile = response.data.hiddenOnMobile;
                        this.headers = response.data.labels;
                        this.card = response.data.card;
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