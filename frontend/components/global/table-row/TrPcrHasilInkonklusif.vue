<template>
  <tr>
    <td v-text="(pagination.page - 1) * pagination.perpage + 1 + index"></td>
    <td>
      {{item.nomor_register}}
    </td>
    <td>
      <div class="badge badge-white" style="text-align:left; padding:5px">
        {{item.nomor_sampel}}
      </div>
    </td>
    <td>
      {{item.jenis_sampel_nama}}
    </td>
    <td>
      {{item.pcr ? item.pcr.catatan_pemeriksaan : '-'}}
    </td>
    <td nowrap>
      <div>
        <strong>{{ item.waktu_pcr_sample_analyzed ? momentFormatDate(item.waktu_pcr_sample_analyzed) : null }}</strong>
      </div>
      <div class="text-muted">
        {{ item.waktu_pcr_sample_analyzed ? 'pukul ' + momentFormatTime(item.waktu_pcr_sample_analyzed) : null }}
      </div>
    </td>
    <td width="20%">
      <nuxt-link tag="a" class="text-nowrap btn btn-yellow btn-sm mb-1" :to="`/pcr/detail/${item.id}`"
        title="Klik untuk melihat detail">
        <em class="uil-info-circle" />
      </nuxt-link>
      <nuxt-link tag="a" class="text-nowrap btn btn-primary btn-sm mb-1" :to="`/pcr/terima/${item.nomor_sampel}`"
        title="Klik untuk mengisi hasil analisis" v-if="item.sampel_status != 'sample_valid'">
        <em class="uil-flask" /> Proses
      </nuxt-link>
    </td>
  </tr>
</template>

<script>
  import {
    momentFormatDate,
    momentFormatTime
  } from '~/utils';
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