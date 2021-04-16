<template>
  <tr>
    <td v-text="(pagination.page - 1) * pagination.perpage + 1 + index"></td>
    <td>
      {{ item.nama }}
    </td>
    <td>
      {{ item.provinsi.nama }}
    </td>
    <td>
      <nuxt-link :to="`kota/detail/${item.id}`" class="mb-1 btn btn-yellow btn-sm">
        <em class="fa fa-eye" />
      </nuxt-link>
    </td>
  </tr>
</template>

<script>
  export default {
    props: ['item', 'pagination', 'rowparams', 'index'],
    data() {
      return {}
    },
    methods: {

    }
  }
</script>