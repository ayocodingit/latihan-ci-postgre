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
      {{item.re_pcr ? '' : '-'}}
      <p class="badge badge-info" v-if="item.re_pcr">{{item.re_pcr}}</p>
    </td>
    <td>
      {{item.jenis_sampel_nama}}
    </td>
    <td nowrap>
      <div>
        <strong>{{ item.waktu_pcr_sample_received ? momentFormatDate(item.waktu_pcr_sample_received) : null }}</strong>
      </div>
      <div class="text-muted">
        {{ item.waktu_pcr_sample_received ? 'pukul ' + momentFormatTime(item.waktu_pcr_sample_received) : null }}
      </div>
    </td>
    <td>
      {{item.catatan_pengiriman}}
    </td>
    <td width="20%">
      <nuxt-link tag="a" class="btn btn-yellow btn-sm mb-1" :to="`/pcr/detail/${item.id}`"
        title="Klik untuk melihat detail">
        <em class="uil-info-circle" />
      </nuxt-link>
      <nuxt-link tag="a" class="btn btn-primary btn-sm mb-1" :to="`/pcr/input/${item.id}`"
        title="Klik untuk mengisi hasil analisis">
        <em class="uil-flask" /> Proses
      </nuxt-link>
      <nuxt-link tag="a" class="btn btn-clear btn-sm mb-1" :to="`/pcr/invalid/${item.id}`"
        title="Klik untuk menandai sampel invalid">
        <em class="uil-flask-potion" /> Invalid
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