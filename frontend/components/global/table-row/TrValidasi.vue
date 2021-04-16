<template>
  <tr>
    <td v-text="(pagination.page - 1) * pagination.perpage + 1 + index" />
    <td>
      <input type="checkbox" name="list-sampel" v-bind:value="item.id" v-bind:id="'selected-sampel-'+item.id"
        v-model="selected" @click="sampelOnChangeSelect">
    </td>
    <td>
      {{item.nomor_register}}
    </td>
    <td nowrap>
      <div v-if="item.nama_lengkap" style="text-transform: capitalize;"><strong>{{ item.nama_lengkap }}</strong></div>
      <div v-if="item.nik" class="text-muted">{{ item.nik }}</div>
      <div class="text-muted">{{ usiaPasien || null }}</div>
    </td>
    <td>
      <span>{{item.jenis_kelamin}}</span>
    </td>
    <td>
      <span>{{ item.sumber_pasien }}</span>
    </td>
    <td>{{ item.fasyankes_nama || '-' }}</td>
    <td>
      <span>{{item.nama_kota}}</span>
    </td>
    <td>
      <div class="badge badge-white" style="text-align:left; padding:5px">
        {{item.nomor_sampel}}
      </div>
    </td>
    <td>
      <div v-for="item in item.hasil_deteksi" :key="item.target_gen">
        - {{ item.target_gen }} :
        <span v-if="!!item.ct_value">{{ parseFloat(item.ct_value).toFixed(2).replace('.', ',') }}</span>
        <span v-if="item.ct_value == null">{{ '-' }}</span>
      </div>
    </td>
    <td>
      {{item.petugas_pengambilan_sampel}}
    </td>
    <td style="text-transform: uppercase;" :class="item.kesimpulan_pemeriksaan == 'positif' ? 'text-danger' : ''">
      {{item.kesimpulan_pemeriksaan == 'swab_ulang' ? 'swab ulang' : item.kesimpulan_pemeriksaan }}
    </td>
    <td width="20%">
      <nuxt-link tag="a" class="mb-1 text-nowrap btn btn-yellow btn-sm" :to="`/validasi/detail/${item.id}`"
        title="Klik untuk melihat detail">
        <em class="uil-info-circle" />
      </nuxt-link>
      <nuxt-link :to="`/validasi/edit/${item.id}`" class="mb-1 text-nowrap btn btn-primary btn-sm" tag="a"
        title="Klik untuk mengubah data">
        <em class="fa fa-edit" />
      </nuxt-link>
      <button type="button" class="mb-1 text-nowrap btn btn-clear btn-sm" @click="reject(item.id)" :disabled="loading"
        :class="{'btn-loading': loading}">
        <em class="fa fa-times" /> Tolak
      </button>
    </td>
  </tr>
</template>

<script>
  import axios from 'axios';
  import {
    getHumanAge
  } from '~/utils';

  export default {
    props: ['item', 'pagination', 'rowparams', 'index'],
    data() {
      let datas = this.$store.state.validasi.selectedSampels;
      return {
        loading: false,
        checked: false,
        selected: [],
        dataArr: datas,
      }
    },
    methods: {
      sampelOnChangeSelect(e) {
        const newDomchecked = e.target.checked;
        const el = e ? e.currentTarget : null;
        const nomorSampel = el ? el.getAttribute('value') : null;
        this.checked = newDomchecked;
        if (this.checked) {
          this.$store.commit('validasi/add', nomorSampel)
        }
        if (!this.checked) {
          this.$store.commit('validasi/remove', nomorSampel)
        }
      },
      reject(id) {
        let swal = this.$swal;
        let toast = this.$toast;
        let bus = this.$bus;
        swal.fire({
          type: "warning",
          title: "Tolak validasi",
          text: "Anda yakin akan melakukan penolakan validasi?",
          showCancelButton: true,
          cancelButtonColor: '#d33',
          confirmButtonText: 'Ya, Tolak',
          cancelButtonText: 'Batal',
        }).then(function (input) {
          if (input.value != undefined) {
            (async (id) => {
              await axios.post(`v1/validasi/reject-sample/${id}`).then(function (response) {
                toast.success('Sample telah ditolak', {
                  icon: 'check',
                  iconPack: 'fontawesome',
                  duration: 5000
                })
                bus.$emit('refresh-ajaxtable', 'validasi');
                location.reload()
              }).catch(function (error) {
                swal.fire("Terjadi kesalahan", "Silakan hubungi Admin",
                  "error");
              });

            })(id)
          }
        })
      }
    },

    computed: {
      selectedSampels: {
        set(val) {
          this.$store.state.selectedSampels = val
        },
        get() {
          return this.$store.state.selectedSampels
        }
      },

      usiaPasien() {
        if (this.item.tanggal_lahir) {
          return getHumanAge(this.item.tanggal_lahir);
        }
        if (this.item.usia_tahun) {
          return `${this.item.usia_tahun} tahun`;
        }
        return "-";
      }
    },

    watch: {
      'selected': function (newVal, oldVal) {
        const sampel = document.getElementById('selected-sampel-' + this.item.id).value;
        const findinArr = this.dataArr.length > 0 ? this.dataArr.find(el => el === sampel) : null;
        if (findinArr) {
          document.getElementById('selected-sampel-' + this.item.id).checked = true;
        } else {
          document.getElementById('selected-sampel-' + this.item.id).checked = false;
        }
      }
    },
  }
</script>

<style scoped>
  .nik {
    display: block;
    color: rgb(140, 143, 135);
  }

  .checkbox-unvalidate {
    transform: scale(1.7);
  }
</style>