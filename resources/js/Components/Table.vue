<template>
    <table class="w-full">
        <tr class="font-semibold text-l w-full bg-primary-10">
            <th class="p-2 text-left whitespace-no-wrap"
                v-for="(header, key) in headers"
                :class="hideOnMobile.includes(key) ? 'hidden sm:table-cell' : ''"
            >
                {{ header }}
            </th>

            <!-- Buttons -->
            <th>&nbsp;</th>
        </tr>

        <tr class="border-b border-primary-10" v-for="row in rows" :data-id="row.id">
            <td class="p-2 align-top"
                v-for="(header, key) in headers"
                :class="hideOnMobile.includes(key) ? 'hidden sm:table-cell' : ''"
            >
                <blueprint-field
                        :component="components[key]+'-list'"
                        :value="row[key]"
                        :name="key"
                ></blueprint-field>
            </td>

            <td class="p-2 align-top">
                <a class="button button-default button-primary cursor-pointer hover:bg-highlight flex items-center transition-bg">
                    <div class="mr-1">
                        <font-awesome-icon :icon="['fas', 'edit']"></font-awesome-icon>
                    </div>
                    <div class="flex-1">Edit</div>
                </a>
            </td>
        </tr>
    </table>
</template>

<script>
    export default {
        props: {
            headers: Object | Array,
            rows: Object | Array,
            components: Object | Array,
            hideOnMobile: Object | Array,
        }
    }
</script>