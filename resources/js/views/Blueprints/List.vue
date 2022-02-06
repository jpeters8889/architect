<template>
    <div>
        <div class="flex flex-col bg-white rounded-xl shadow mb-2">
            <header-component :blueprint="blueprint" :can-add="canEdit" :has-card="!!card" :filters="filters" :searchable="searchable">
                {{ this.title }}
            </header-component>
        </div>

        <!-- List -->
        <div>
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
                        v-if="paginate"
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
            filters: {},
            appliedFilters: {},
            paginate: true,

            searchTimeout: null,
        }),

        mounted() {
            this.initComponent();

            Architect.$on('paginator-change', (page) => {
                this.page = page;
                this.initComponent();
                window.scrollTo(0, 0);
            });

            Architect.$on('reload-page', () => {
                this.initComponent();
            })

            Architect.$on('search-keyup', (value) => {
              clearTimeout(this.searchTimeout);

              this.searchTimeout = setTimeout(() => {
                this.searchText = value;
                this.getBlueprint();
              }, 250);
            });

            Architect.$on('filter-change', (filter) => {
                this.appliedFilters = filter.filters;
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
                Architect.$emit('search-set-value', '');
                Architect.$emit('load-start');
                this.getBlueprint();
            },

            blueprintUrl() {
                let url = `/blueprints/${this.blueprint}/list?page=${this.page}`

                if (this.searchText) {
                    url = `/blueprints/${this.blueprint}/list?page=1&search=${this.searchText}`;
                }

                if (this.appliedFilters !== {}) {
                    let filters = [];

                    Object.keys(this.appliedFilters).forEach((key) => {
                        filters.push(`filter[${key}]=${this.appliedFilters[key]}`)
                    });

                    filters = filters.join('&')

                    url = url + `&${filters}`;
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
                        this.filters = Array.isArray(response.data.filters) ? {} : response.data.filters;
                        this.paginate = response.data.paginate;
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
            }
        },
    }
</script>
