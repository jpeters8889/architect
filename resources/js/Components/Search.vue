<template>
  <form-input
    type="search"
    placeholder="Search..."
    name="search"
    :value="searchText"
    classes="w-255"
  />
</template>

<script>
export default {
  data: () => ({
    searchText: '',
  }),
};
</script>
