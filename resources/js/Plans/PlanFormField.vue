<template>
    <div>
        <div class="text-lg font-semibold text-highlight mb-1">
            <label>
                {{ plan.label }}
            </label>
        </div>

        <div>
            <component
                    :is="plan.component"
                    :name="plan.name"
                    :value="plan.value"
                    :metas="plan.metas"
            ></component>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['plan'],
    }
</script>