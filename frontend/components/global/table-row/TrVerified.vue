<template>
  <tr>
    <td v-text="(pagination.page - 1) * pagination.perpage + 1 + index"></td>
    <td>
      {{item.nomor_register}}
    </td>
    <td nowrap>
      <div v-if="item.nama_lengkap" style="text-transform: capitalize;"><strong>{{ item.nama_lengkap }}</strong></div>
      <div v-if="item.nik" class="text-muted">{{ item.nik }}</div>
      <div class="text-muted">{{ usiaPasien || null }}</div>
    </td>
    <td>
      <span>{{ item.sumber_pasien }}</span>
    </td>
    <td>
      <span>{{item.nama_kota}}</span>
    </td>
    <td>
     {{ item.fasyankes_nama || '-' }}
    </td>
    <td>
      <div class="badge badge-white" style="text-align:left; padding:5px">
        {{item.nomor_sampel}}
      </div>
    </td>
    <td nowrap>
      <div v-for="item in item.hasil_deteksi" :key="item.target_gen">
        - {{ item.target_gen }} :
        <span v-if="!!item.ct_value">{{ parseFloat(item.ct_value).toFixed(2).replace('.', ',') }}</span>
        <span v-if="item.ct_value == null">{{ '-' }}</span>
      </div>
    </td>
    <td>{{item.petugas_pengambilan_sampel}}</td>
    <td style="text-transform: uppercase;">
      {{item.kesimpulan_pemeriksaan == 'swab_ulang' ? 'swab ulang' : item.kesimpulan_pemeriksaan}}
    </td>
    <td>
      {{item.status | statusPasien}}
    </td>
    <td nowrap>
      <div>
        <strong>{{ item.waktu_sample_verified ? momentFormatDate(item.waktu_sample_verified) : null }}</strong>
      </div>
      <div class="text-muted">
        {{ item.waktu_sample_verified ? 'pukul ' + momentFormatTime(item.waktu_sample_verified) : null }}</div>
    </td>
    <td width="20%">
      <nuxt-link tag="a" class="btn btn-yellow btn-sm" :to="`/verifikasi/detail-verified/${item.id}`"
        title="Klik untuk melihat detail">
        <em class="uil-info-circle" />
      </nuxt-link>
    </td>
  </tr>
</template>

<script>
  import {
    getHumanAge,
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
    methods: {},
    computed: {
      usiaPasien() {
        if (this.item.tanggal_lahir) {
          return getHumanAge(this.item.tanggal_lahir);
        }
        if (this.item.usia_tahun) {
          return `${this.item.usia_tahun} tahun`;
        }
        return "-";
      }
    }
  }
</script>

<style scoped>
  .nik {
    display: block;
    color: rgb(140, 143, 135);
  }
</style>