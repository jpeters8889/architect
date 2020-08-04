<template>
    <div class="flex">
        <div class="flex items-center justify-center py-1 leading-none px-3 rounded-full text-sm font-semibold"
             :class="parseInt(value) === 1 ? 'bg-green-200 text-green-700' : 'bg-red-200 text-red-700'">
            {{ parseInt(value) === 1 ? 'Yes' : 'No' }}
        </div>
    </div>
</template>

<script>
    export default {
        props: ['name', 'label', 'value']
    }
</script>
