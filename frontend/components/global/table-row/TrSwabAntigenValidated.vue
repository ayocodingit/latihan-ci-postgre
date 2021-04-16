<template>
  <tr>
    <td v-text="(pagination.page - 1) * pagination.perpage + 1 + index" />
    <td>
      {{ item.nomor_registrasi || '' }}
    </td>
    <td nowrap>
      <div v-if="item.nama_pasien">
        <strong>{{ item.nama_pasien || '' }}</strong>
      </div>
      <div v-if="item.no_identitas" class="text-muted">
        {{ item.no_identitas }}
      </div>
      <div class="text-muted">
        {{ item.usia_tahun ? `${item.usia_tahun} tahun` : '' }}
      </div>
    </td>
    <td>
      {{ item.tanggal_lahir || '' }}
    </td>
    <td>
      {{ item.kota && item.kota.nama ? item.kota.nama : '' }}
    </td>
    <td>
      {{ item.kategori || '' }}
    </td>
    <td>
      {{ item.tanggal_periksa || '' }}
    </td>
    <td>
      {{ item.no_hasil || '' }}
    </td>
    <td>
      {{ item.hasil_periksa ? humanize(item.hasil_periksa) : '' }}
    </td>
    <td>
      {{ item.tanggal_validasi || '' }}
    </td>
    <td>
      <nuxt-link tag="a" class="mb-1 text-nowrap btn btn-yellow btn-sm" title="Klik untuk melihat detail"
        :to="`/swab-antigen/detail/${item.id}`">
        <em class="uil-info-circle" />
      </nuxt-link>
      <button @click="downloadPDF()" class="mb-1 btn btn-sm btn-green" type="button" :disabled="loading === true">
        <span v-if="loading === true">
          <em class="fa fa-spinner" />
        </span>
        <span v-if="loading === false">
          <em class="fa fa-file-pdf-o" />&nbsp;
          {{'Make PDF'}}
        </span>
      </button>
      <button @click="regeneratePDF()" class="mb-1 text-nowrap btn btn-sm btn-default" type="button">
        {{ 'Regenerate Print' }}
      </button>
    </td>
  </tr>
</template>

<script>
  import Form from 'vform'
  import {
    humanize
  } from '~/utils'
  export default {
    props: ['item', 'pagination', 'rowparams', 'index'],
    data() {
      const form = new Form({
        swab_antigen: this.item.id
      })
      return {
        loading: false,
        checked: false,
        humanize,
        form
      }
    },
    methods: {
      async downloadPDF() {
        let _this = this;
        try {
          this.loading = true
          this.$axios({
            url: `/v1/swab-antigen/export-pdf/${this.item.id}`,
            method: "GET",
            responseType: "blob"
          }).then(response => {
            const blob = new Blob([response.data], {
              type: response.data.type
            })
            const url = window.URL.createObjectURL(blob)
            const link = document.createElement("a")
            link.href = url
            setTimeout(function () {
              window.open(url)
            }, 500)
            this.$bus.$emit("refresh-ajaxtable", "swab-antigen-validasi")

          }).catch(function (error) {
            if (error.response && error.response.status === 404) {
              _this.$swal.fire(
                "Terjadi kesalahan",
                "Silahkan Klik Generate Print Terlebih Dahulu",
                "error"
              )
            }
          })
          this.loading = false
        } catch (err) {
          if (err.response && err.response.data.code === 422) {
            this.$nextTick(() => {
              this.form.errors.set(err.response.data.error)
            })
            this.$toast.error("Mohon cek kembali formulir Anda", {
              icon: "times",
              iconPack: "fontawesome",
              duration: 5000
            })
          } else {
            this.$swal.fire(
              "Terjadi kesalahan",
              "Silakan hubungi Admin",
              "error"
            )
          }
          this.loading = false
        }
      },
      regeneratePDF() {
        const swalWithBootstrapButtons = this.$swal.mixin({
          customClass: {
            confirmButton: "mb-1 ml-2 text-nowrap btn btn-success",
            cancelButton: "mb-1 text-nowrap btn btn-danger"
          },
          buttonsStyling: false
        })
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
              )
            } else if (result.value) {
              try {
                this.loading = true
                await this.form.post(`/v1/swab-antigen/regenerate-pdf/${this.item.id}`)
                swalWithBootstrapButtons.fire(
                  "Selesai!",
                  "Hasil Print PDF berhasil dimuat ulang",
                  "success"
                )
              } catch (err) {
                if (err.response && err.response.data.code == 422) {
                  swalWithBootstrapButtons.fire(
                    "Gagal",
                    err.response.data.message,
                    "error"
                  )
                } else {
                  swalWithBootstrapButtons.fire(
                    "Gagal",
                    "Gagal memuat ulang hasil print",
                    "error"
                  )
                }
              }
              this.loading = false
            } else if (
              result.dismiss === this.$swal.DismissReason.cancel
            ) {}
          })
      }
    }
  }
</script>