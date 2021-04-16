<template>
  <tr>
    <td v-text="(pagination.page - 1) * pagination.perpage + 1 + index"></td>
    <td>
      <input type="checkbox" name="list-sampel" v-bind:value="item.id" v-bind:id="'selected-sampel-' + item.id"
        v-bind:nomor_sampel="item.nomor_sampel" v-model="selected" @click="sampelOnChangeSelect" />
    </td>
    <td>{{item.nomor_register}}</td>
    <td nowrap>
      <div v-if="item.nama_lengkap" style="text-transform: capitalize;"><strong>{{ item.nama_lengkap }}</strong></div>
      <div v-if="item.nik" class="text-muted">{{ item.nik }}</div>
      <div class="text-muted">{{ usiaPasien || null }}</div>
    </td>
    <td>
      <div>{{ item.sumber_pasien }}</div>
    </td>
    <td>
      <div>{{item.nama_kota}}</div>
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
    <td width="20%">
      <nuxt-link tag="a" class="text-nowrap btn btn-yellow btn-sm mb-1" :to="`/verifikasi/detail/${item.id}`"
        title="Klik untuk melihat detail">
        <em class="uil-info-circle" />
      </nuxt-link>
      <nuxt-link :to="`/verifikasi/edit/${item.id}`" class="text-nowrap btn btn-primary btn-sm mb-1" tag="a"
        v-if="item.sampel_status != 'sample_valid' && item.sampel_status != 'sample_verified'">
        <em class="fa fa-edit" />
      </nuxt-link>

      <button v-if="config.has_action" type="button" class="text-nowrap btn btn-default btn-sm mb-1"
        @click="verifikasiSampel()" :disabled="loading" :class="{'btn-loading': loading}">
        Verifikasi
      </button>
    </td>
  </tr>
</template>

<script>
  import Form from "vform"
  import {
    getHumanAge
  } from '~/utils';

  export default {
    props: ["item", "pagination", "rowparams", "index", "config"],
    components: {},
    data() {
      let loading = false
      let form = new Form({
        sampel_id: this.item.id
      });
      const datas = this.$store.state.verifikasi_sampel.selectedSampels
      return {
        loading,
        form,
        checked: false,
        selected: [],
        dataArr: datas,
      }
    },
    methods: {
      invalidExists(arg) {
        return arg[1] != null && arg[0] === 'sample_invalid';
      },
      verifikasiSampel() {
        const swalWithBootstrapButtons = this.$swal.mixin({
          customClass: {
            confirmButton: 'text-nowrap btn btn-success',
            cancelButton: 'text-nowrap btn btn-danger'
          },
          buttonsStyling: false
        })
        let swal = this.$swal;
        swal.fire({
          title: 'Apakah Anda Yakin untuk Verifikasi Sampel ini?',
          text: "Setelah sampel menjadi verifikasi, data tidak dapat dikembalikan.",
          type: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Ya, Tandai Sampel Terverifikasi',
          cancelButtonText: 'Batalkan',
          cancelButtonColor: '#d33',
          reverseButtons: true
        }).then(async (result) => {
          if (result.value == '') {
            swalWithBootstrapButtons.fire(
              'Gagal',
              'Terjadi kesalahan. Hubungi Admin!',
              'error'
            )
          } else if (result.value) {
            try {
              this.loading = true
              let resp = await this.form.post("/v1/verifikasi/verifikasi-single-sampel/" + this
                .item.id);
              swalWithBootstrapButtons.fire(
                'Selesai!',
                'Data Berhasil terverifikasi',
                'success'
              )
              this.$bus.$emit('refresh-ajaxtable', 'verifikasi')
            } catch (error) {
              this.$swal.fire(
                "Terjadi kesalahan",
                error.response.data.error,
                "error"
              )
            }

            this.loading = false

          } else if (
            /* Read more about handling dismissals below */
            result.dismiss === this.$swal.DismissReason.cancel
          ) {}
        })

      },
      sampelOnChangeSelect(e) {
        const newDomchecked = e.target.checked
        const el = e ? e.currentTarget : null
        const nomorSampel = el ? el.getAttribute("value") : null
        this.checked = newDomchecked
        if (this.checked) {
          this.$store.commit("verifikasi_sampel/add", nomorSampel)
        }
        if (!this.checked) {
          this.$store.commit("verifikasi_sampel/remove", nomorSampel)
        }
      }
    },
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
    },

    watch: {
      'selected': function () {
        const sampel = document.getElementById("selected-sampel-" + this.item.id).value
        const findinArr = this.dataArr.length > 0 ? this.dataArr.find((el) => el === sampel) : null
        if (findinArr) {
          document.getElementById("selected-sampel-" + this.item.id).checked = true
        } else {
          document.getElementById("selected-sampel-" + this.item.id).checked = false
        }
      },
    },
  };
</script>

<style scoped>
  .nik {
    display: block;
    color: rgb(140, 143, 135);
  }
</style>