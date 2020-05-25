<template>
    <div>
        <header-component :blueprint="blueprint" :can-add="canEdit">
            {{ this.title }}
        </header-component>

        <!-- Search -->
        <blueprint-search v-if="searchable && !card"></blueprint-search>

        <!-- List -->
        <div class="bg-white w-full p-2">
            <div v-if="Object.keys(headers).length > 0">
                <table-component
                        :blueprint="blueprint"
                        :headers="headers"
                        :rows="data.data"
                        :components="components"
                        :hide-on-mobile="hideOnMobile"
                        :can-edit="canEdit"
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
                        :current="data.current_page"
                        :lastPage="data.last_page"
                        :can-go-back="!! data.prev_page_url"
                        :can-go-forward="!! data.next_page_url"
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
            canEdit: true,
            card: {
                component: null,
                labels: [],
            },
            page: 1,
            searchable: true,
            searchText: '',
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

            this.$root.$on('search-keyup', (value) => {
                this.searchText = value;
                this.getBlueprint();
            });
        },

        watch: {
            $route() {
                this.initComponent();
            }
        },

        methods: {
            initComponent() {
                this.searchText = '';
                this.$root.$emit('search-set-value', '');
                window.Architect.$emit('load-start');
                this.getBlueprint();
            },

            blueprintUrl() {
                let url = `/blueprints/${this.blueprint}/list?page=${this.page}`

                if (this.searchText) {
                    url = `/blueprints/${this.blueprint}/list?page=1&search=${this.searchText}`;
                }

                return url;
            },

            getBlueprint() {
                Architect.request().get(this.blueprintUrl())
                    .then((response) => {
                        this.title = response.data.meta.title;
                        this.hideOnMobile = response.data.hiddenOnMobile;
                        this.headers = response.data.labels;
                        this.card = response.data.card;
                        this.data = response.data.data;
                        this.components = response.data.vuePrefixes;
                        this.canEdit = response.data.canEdit;
                        this.searchable = response.data.searchable;
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

                window.Architect.$emit('load-end');
            }
        },
    }
</script>
