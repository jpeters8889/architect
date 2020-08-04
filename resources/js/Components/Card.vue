<template>
    <div class="w-full bg-white rounded-lg mb-2 p-4 shadow overflow-hidden">
        <component
                :is="component"
                :labels="labels"
                :data="data"
        ></component>
    </div>
</template>

<script>
    export default {
        props: {
            component: String,
            labels: Object | Array,
            data: Object | Array,
        }
    }
</script>
