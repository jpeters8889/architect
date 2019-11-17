<template>
    <div class="flex">
        <div class="flex items-center justify-center text-xs mr-2" :class="parseInt(value) === 1 ? 'text-positive' : 'text-negative'">
            <font-awesome-icon :icon="['fas', 'circle']"></font-awesome-icon>
        </div>
        <div class="flex-1">{{ parseInt(value) === 1 ? 'Yes' : 'No' }}</div>
    </div>
</template>

<script>
    export default {
        props: ['name', 'label', 'value']
    }
</script>