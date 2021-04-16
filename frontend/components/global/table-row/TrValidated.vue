<template>
  <tr>
    <td v-text="(pagination.page - 1) * pagination.perpage + 1 + index"></td>
    <td>{{item.nomor_register}}</td>
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
    <td style="text-transform: uppercase;" :class="item.kesimpulan_pemeriksaan == 'positif' ? 'text-danger' : ''">
      {{item.kesimpulan_pemeriksaan == 'swab_ulang' ? 'swab ulang' : item.kesimpulan_pemeriksaan}}</td>
    <td>{{item.status | statusPasien }}</td>
    <td nowrap>
      <div>
        <strong>{{ item.waktu_sample_valid ? momentFormatDate(item.waktu_sample_valid) : null }}</strong>
      </div>
      <div class="text-muted">
        {{ item.waktu_sample_valid ? 'pukul ' + momentFormatTime(item.waktu_sample_valid) : null }}</div>
    </td>
    <td width="20%">
      <nuxt-link tag="a" class="mb-1 text-nowrap btn btn-yellow btn-sm" :to="`/validasi/detail-validated/${item.id}`"
        title="Klik untuk melihat detail">
        <em class="uil-info-circle" />
      </nuxt-link>
      <button @click="downloadPDF()" class="mb-1 btn btn-sm btn-green" type="button" :disabled="loading == true">
        <span v-if="loading == true">
          <em class="fa fa-spinner" />
        </span>
        <span v-if="loading == false">
          <em class="fa fa-file-pdf-o" />&nbsp;
          {{'Make PDF'}}
        </span>
      </button>
      <button @click="regeneratePDF()" class="mb-1 text-nowrap btn btn-sm btn-default"
        type="button">{{ 'Regenerate Print' }}</button>
      <small v-if="item.waktu_sample_print" class="counter-print">
        (Terkahir print : {{ item.waktu_sample_print }})
      </small>
    </td>
  </tr>
</template>

<script>
  import Form from "vform";
  import {
    getHumanAge,
    momentFormatDate,
    momentFormatTime
  } from '~/utils';

  export default {
    props: ["item", "pagination", "rowparams", "index"],
    data() {
      let form = new Form({
        sampel_id: this.item.id
      });

      return {
        loading: false,
        form,
        momentFormatDate,
        momentFormatTime
      };
    },
    methods: {
      async downloadPDF() {
        let _this = this;
        try {
          this.loading = true;
          this.$axios({
            url: process.env.apiUrl + "/v1/validasi/export-pdf/" + this.item.id,
            method: "GET",
            responseType: "blob"
          }).then(response => {
            const blob = new Blob([response.data], {
              type: response.data.type
            });
            const url = window.URL.createObjectURL(blob);

            const link = document.createElement("a");
            link.href = url;

            setTimeout(function () {
              window.open(url);
            }, 500);

            this.$bus.$emit("refresh-ajaxtable", "validated");

          }).catch(function (error) {
            if (error.response && error.response.status == 404) {
              _this.$swal.fire(
                "Terjadi kesalahan",
                "Silahkan Klik Generate Print Terlebih Dahulu",
                "error"
              );
            }
          });
          this.loading = false;
        } catch (err) {
          if (err.response && err.response.data.code == 422) {
            this.$nextTick(() => {
              this.form.errors.set(err.response.data.error);
            });
            this.$toast.error("Mohon cek kembali formulir Anda", {
              icon: "times",
              iconPack: "fontawesome",
              duration: 5000
            });
          } else {
            console.log(err);

            this.$swal.fire(
              "Terjadi kesalahan",
              "Silakan hubungi Admin",
              "error"
            );
          }

          this.loading = false;
        }
      },

      regeneratePDF() {
        const swalWithBootstrapButtons = this.$swal.mixin({
          customClass: {
            confirmButton: "mb-1 text-nowrap btn btn-success",
            cancelButton: "mb-1 text-nowrap btn btn-danger"
          },
          buttonsStyling: false
        });

        swalWithBootstrapButtons
          .fire({
            title: "Apakah Anda Yakin untuk memuat ulang PDF hasil pemeriksaan ini?",
            text: "Setelah dimuat ulang, file PDF sebelumnya akan diganti.",
            type: "warning",
            // input: 'text',
            showCancelButton: true,
            confirmButtonText: "Ya, Muat Ulang Hasil Print PDF",
            cancelButtonText: "Batalkan",
            reverseButtons: true
          })
          .then(async result => {
            console.log(result);
            if (result.value == "") {
              swalWithBootstrapButtons.fire(
                "Gagal",
                "Terjadi kesalahan. Hubungi Admin!",
                "error"
              );
            } else if (result.value) {
              try {
                this.loading = true;
                let resp = await this.form.post(
                  "/v1/validasi/regenerate-pdf/" + this.item.id
                );
                swalWithBootstrapButtons.fire(
                  "Selesai!",
                  "Hasil Print PDF berhasil dimuat ulang",
                  "success"
                );
              } catch (err) {
                if (err.response && err.response.data.code == 422) {
                  swalWithBootstrapButtons.fire(
                    "Gagal",
                    err.response.data.message,
                    "error"
                  );
                } else {
                  swalWithBootstrapButtons.fire(
                    "Gagal",
                    "Gagal memuat ulang hasil print",
                    "error"
                  );
                }
              }

              this.loading = false;
            } else if (
              /* Read more about handling dismissals below */
              result.dismiss === this.$swal.DismissReason.cancel
            ) {}
          });
      }
    },
    computed: {
      usiaPasien() {
        if (this.item.usia_tahun) {
          return `${this.item.usia_tahun} tahun`;
        }
        if (this.item.tanggal_lahir) {
          return getHumanAge(this.item.tanggal_lahir);
        }
        return "-";
      }
    }
  };
</script>

<style scoped>
  .nik,
  .counter-print {
    display: block;
    color: rgb(140, 143, 135);
  }
</style>