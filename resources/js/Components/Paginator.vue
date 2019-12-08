<template>
    <div class="flex justify-between items-center">
        <div v-if="last_page > 2" class="flex border-2 border-primary rounded-lg overflow-hidden">
            <div v-if="current_page > 1" class="paginator-page paginator-page-border" @click="changePage(1)">
                First
            </div>

            <div v-if="current_page > 2" class="paginator-page paginator-page-border" @click="changePage(current_page - 1)">
                {{ current_page - 1}}
            </div>

            <div class="paginator-page-active">
                {{ current_page }}
            </div>

            <div v-if="current_page < (last_page - 1)" class="paginator-page" v-bind:class="current_page < (last_page - 1) ? 'paginator-page-border' : ''" @click="changePage(current_page + 1)">
                {{ current_page + 1}}
            </div>

            <div v-if="current_page < last_page" class="paginator-page" @click="changePage(last_page)">
                Last
            </div>
        </div>
        <div>
            {{ from }} - {{ to }} of {{ total }}
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            current_page: Number,
            last_page: Number,
            per_page: Number,
            from: Number,
            to: Number,
            total: Number,
        },

        methods: {
            changePage(page) {
                window.Architect.$emit('paginator-change', page);
            }
        }
    }
</script>