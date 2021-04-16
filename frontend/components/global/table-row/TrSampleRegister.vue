<template>
  <tr>
    <td v-text="(pagination.page - 1) * pagination.perpage + 1 + index"></td>
    <td>
      <div class="badge badge-white" style="text-align:left; padding:5px">
        Sampel #{{item.nomor_sampel}}
      </div>
    </td>
    <td>
      {{item.nomor_register}}
    </td>
    <td nowrap>
      <div class="font-weight-bold">
        {{ item.tgl_input_sampel ? momentFormatDate(item.tgl_input_sampel) : null }}
      </div>
      <div class="text-muted">
        {{ item.tgl_input_sampel ? `pukul ${momentFormatTime(item.tgl_input_sampel)}` : null }}
      </div>
    </td>
    <td>
      <nuxt-link :to="`sample/get-samples/${item.nomor_sampel}`" class="btn btn-primary btn-sm">
        <em class="fa fa-edit" />
        Ambil Sampel
      </nuxt-link>
    </td>
  </tr>
</template>

<script>
  import {
    momentFormatDate,
    momentFormatTime
  } from '~/utils'
  export default {
    props: ['item', 'pagination', 'rowparams', 'index'],
    data() {
      return {
        momentFormatDate,
        momentFormatTime
      }
    },
    methods: {}
  }
</script>