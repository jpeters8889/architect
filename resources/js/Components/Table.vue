<template>
    <div class="pb-2 bg-gray-200">

        <table class="w-full rounded-xl overflow-hidden">
            <tr class="font-semibold text-l w-full bg-gray-100 rounded-t-xl border-b border-gray-300 shadow">
                <th class="p-4 text-left whitespace-no-wrap text-gray-700 font-semibold leading-none"
                    v-for="(header, key) in headers"
                    :class="hideOnMobile.includes(key) ? 'hidden sm:table-cell' : ''"
                >
                    {{ header }}
                </th>

                <!-- Buttons -->
                <th v-if="canEdit">&nbsp;</th>
            </tr>

            <tr class="border-b bg-white border-gray-200" v-for="row in rows" :data-id="row.id">
                <td class="p-4 align-top"
                    v-for="(header, key) in headers"
                    :class="hideOnMobile.includes(key) ? 'hidden sm:table-cell' : ''"
                >
                    <blueprint-field
                            :component="components[key]+'-list'"
                            :value="row[key]"
                            :label="header"
                            :name="key"
                            :id="row.id"
                    ></blueprint-field>
                </td>

                <td class="p-4 align-top" v-if="canEdit">
                    <router-link
                            class="button button-default button-primary cursor-pointer hover:bg-highlight flex items-center transition-bg"
                            :to="{
                        name: 'blueprintEdit',
                        params: {
                            blueprint: blueprint,
                            id: row.id,
                            state: 'update',
                        }
                    }"
                    >
                        <div class="mr-1">
                            <font-awesome-icon :icon="['fas', 'edit']"></font-awesome-icon>
                        </div>
                        <div class="flex-1">Edit</div>
                    </router-link>
                </td>
            </tr>
        </table>

    </div>
</template>

<script>
    export default {
        props: {
            blueprint: String,
            headers: Object | Array,
            rows: Object | Array,
            components: Object | Array,
            hideOnMobile: Object | Array,
            canEdit: Boolean,
        }
    }
</script>
