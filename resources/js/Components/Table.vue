<template>
  <div class="pb-2 bg-gray-200">
    <table class="w-full rounded-xl overflow-hidden">
      <tr class="font-semibold text-l w-full bg-gray-100 rounded-t-xl border-b border-gray-300 shadow">
        <th
          v-for="(header, key) in headers"
          :key="key"
          class="p-4 text-left whitespace-no-wrap text-gray-700 font-semibold leading-none"
          :class="hideOnMobile.includes(key) ? 'hidden sm:table-cell' : ''"
        >
          {{ header }}
        </th>

        <!-- Buttons -->
        <th v-if="canEdit">
          &nbsp;
        </th>
      </tr>

      <tr
        v-for="(row, index) in rows"
        :key="`${row.id}-${index}`"
        class="border-b bg-white border-gray-200"
        :data-id="row.id"
      >
        <td
          v-for="(header, key) in headers"
          :key="`${key}-${row.id}`"
          class="p-4 align-top"
          :class="hideOnMobile.includes(key) ? 'hidden sm:table-cell' : ''"
        >
          <blueprint-field
            :id="row.id"
            :component="components[key]+'-list'"
            :value="row[key]"
            :label="header"
            :name="key"
          />
        </td>

        <td
          v-if="canEdit"
          class="p-4 align-top"
        >
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
              <font-awesome-icon :icon="['fas', 'edit']" />
            </div>
            <div class="flex-1">
              Edit
            </div>
          </router-link>
        </td>
      </tr>
    </table>
  </div>
</template>

<script>
export default {
  props: {
    blueprint: { type: String, required: true },
    headers: { type: [Object, Array], required: true },
    rows: { type: [Object, Array], required: true },
    components: { type: [Object, Array], required: true },
    hideOnMobile: { type: [Object, Array], required: true },
    canEdit: { type: Boolean, required: true },
  },
};
</script>
