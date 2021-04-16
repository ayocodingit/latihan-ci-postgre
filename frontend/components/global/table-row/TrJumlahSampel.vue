<template>
  <tr>
    <td v-text="(pagination.page - 1) * pagination.perpage + 1 + index"></td>
    <td>
      <span>{{ item.domisili || null }}</span>
    </td>
    <td>
      <span>{{ item.jumlah || null }}</span>
    </td>
  </tr>
</template>
<script>
  import Form from "vform";

  export default {
    props: ["item", "pagination", "rowparams", "index"],
    components: {},
    data() {

      let loading = false

      let form = new Form({
        sampel_id: this.item.id
      });

      return {
        loading,
        form
      };
    },
  };
</script>

<style scoped>
  thead {
    color: #1DB0E6;
  }
</style>