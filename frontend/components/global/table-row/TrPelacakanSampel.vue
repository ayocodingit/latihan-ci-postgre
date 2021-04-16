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
      <span>{{item.sumber_pasien || null}}</span>
    </td>
    <td>{{ item.fasyankes_nama || '-' }}</td>
    <td>
      <span>{{item.nama_kota || null}}</span>
    </td>
    <td>
      <div class="badge badge-white" style="text-align:left; padding:5px">
        <span v-if="item.nomor_sampel"><strong>{{item.nomor_sampel}}</strong></span>
        <span v-if="item.kondisi_sampel" style="display: block;">Kondisi: {{ item.kondisi_sampel }}</span>
      </div>
    </td>
    <td>
      <span>{{item.kesimpulan_pemeriksaan == 'swab_ulang' ? 'swab ulang' : item.kesimpulan_pemeriksaan}}</span>
    </td>
    <td>{{ item.deskripsi }}</td>
    <td>
      <span>{{ item.nama_validator || null }}</span>
    </td>
    <td nowrap>
      <span v-if="item && item.kunjungan_ke">
        Kunjungan ke-{{ item.kunjungan_ke }}
      </span>
    </td>
    <td>
      <span>{{ momentFormatDate(item.waktu_sample_taken) || null }}</span>
    </td>
    <td width="20%">
      <nuxt-link tag="a" class="btn btn-yellow btn-sm" :to="`/pelacakan-sampel/detail/${item.id}`"
        title="Klik untuk melihat detail">
        <em class="uil-info-circle" />
      </nuxt-link>
    </td>
  </tr>
</template>

<script>
  import Form from "vform";
  import {
    getHumanAge,
    momentFormatDate
  } from '~/utils';

  export default {
    props: ["item", "pagination", "rowparams", "index"],
    data() {
      let form = new Form({
        sampel_id: this.item.id
      });

      return {
        momentFormatDate,
        loading: false,
        form
      };
    },
    methods: {
      async downloadPDF() {
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
            const contentDisposition = response.headers["content-disposition"];

            const fileNameHeader = "x-suggested-filename";
            const suggestedFileName = response.headers[fileNameHeader];
            const effectiveFileName =
              suggestedFileName === undefined ?
              "surat-hasil-pemeriksaan-" + this.item.nomor_sampel :
              suggestedFileName;

            let fileName = effectiveFileName + ".pdf";

            if (contentDisposition) {
              const fileNameMatch = contentDisposition.match(/filename=(.+)/);
              if (fileNameMatch.length === 2) fileName = fileNameMatch[1];
            }
            link.setAttribute("download", fileName);
            document.body.appendChild(link);
            link.click();
            link.remove();
            window.URL.revokeObjectURL(url);

            this.$bus.$emit("refresh-ajaxtable", "validated");

            this.loading = false;
          });
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
        }
        this.loading = false;
      },

      regeneratePDF() {
        const swalWithBootstrapButtons = this.$swal.mixin({
          customClass: {
            confirmButton: "btn btn-success",
            cancelButton: "btn btn-danger"
          },
          buttonsStyling: false
        });

        swalWithBootstrapButtons
          .fire({
            title: "Apakah Anda Yakin untuk memuat ulang PDF hasil pemeriksaan ini?",
            text: "Setelah dimuat ulang, file PDF sebelumnya akan diganti.",
            type: "warning",
            showCancelButton: true,
            confirmButtonText: "Ya, Muat Ulang Hasil Print PDF",
            cancelButtonText: "Batalkan",
            reverseButtons: true
          })
          .then(async result => {
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
        if (this.item.tanggal_lahir) {
          return getHumanAge(this.item.tanggal_lahir);
        }
        if (this.item.usia_tahun) {
          return `${this.item.usia_tahun} tahun`;
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