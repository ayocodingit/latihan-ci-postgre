<template>
  <div class="wrapper wrapper-content">
    <portal to="title-name">Hasil Pemeriksaan</portal>
    <portal to="title-action">
      <div class="title-action">
        <button
          v-show="!isRoleAdminRegistrasi"
          type="button"
          class="btn btn-primary"
          data-toggle="modal"
          data-target="#modalBulkValidate">
          <em class="fa fa-check" /> Validasi
        </button>
        <nuxt-link tag="button" to="/swab-antigen/tambah" class="btn btn-primary">
          <em class="fa fa-plus" /> Register Baru
        </nuxt-link>
        <button tag="button" class="btn btn-import-export" data-toggle="modal" data-target="#importHasil">
          <em class="fa fa-download" /> Import
        </button>
      </div>
    </portal>

    <div class="row">
      <div class="col-lg-12">
        <Ibox title="Filter">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group row">
                <div class="col-md-3 flex-text-center">
                  <div>Tanggal Hasil</div>
                </div>
                <div class="col-md-8 input-group p-0">
                  <div class="col-md-6">
                    <date-picker placeholder="Tanggal Awal" format="dd MMMM yyyy" ref="tanggal_periksa_start"
                      input-class="form-control" :monday-first="true" v-model="params.tanggal_periksa_start" />
                  </div>
                  <div class="col-md-6">
                    <date-picker placeholder="Tanggal Akhir" format="dd MMMM yyyy" ref="tanggal_periksa_end"
                      input-class="form-control ml-1" :monday-first="true" v-model="params.tanggal_periksa_end" />
                  </div>
                </div>
              </div>
              <div class="form-group row">
                <div class="col-md-3 flex-text-center">
                  <div>Nama Pasien</div>
                </div>
                <div class="col-md-8">
                  <input class="form-control" type="text" v-model="params.nama_pasien" />
                </div>
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group row">
                <div class="col-md-4 flex-text-center">
                  <div>Hasil Pemeriksaan</div>
                </div>
                <div class="col-md-7">
                  <dynamic-input :form="params" field="hasil_periksa" v-model="params.hasil_periksa"
                    :options="dataKesimpulanPemeriksaan" :hasSemua="true">
                  </dynamic-input>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-12 mt-2 flex-right">
              <apply-clear-filter-button :doFilter="doFilter" :clearFilter="clearFilter" />
            </div>
          </div>
        </Ibox>
        <Ibox title="Hasil Pemeriksaan Swab Antigen">
          <ajax-table ref="hasil-pemeriksaan" url="/v1/swab-antigen" :oid="'swab-antigen-hasil'"
            :disableSort="['checkbox_input']"
            :params="params"
            :config="{
              autoload: true,
              disabled_checkbox: isRoleAdminRegistrasi,
              has_number: true,
              has_entry_page: true,
              has_pagination: true,
              has_action: true,
              has_search_input: false,
              custom_empty_page: false,
              default_sort: 'tanggal_periksa',
              default_sort_dir: 'desc',
              class: {
                table: [],
                wrapper: ['table-responsive'],
              }
            }"
            :rowtemplate="'tr-swab-antigen-hasil'"
            :columns="listColumn" />
        </Ibox>
      </div>
    </div>

    <custom-modal modal_id="importHasil" title="Import Data">
      <div slot="body">
        <div class="col-lg-12">
          <div class="form-group text-blue alert alert-info rounded-lg mx-1 row">
            <div class="col-lg-8 form-inline small px-0">
              {{ this.$t("keterangan_template_hasil_pemeriksaan") }}
            </div>
            <div class="col-md-4 pr-0 justify-content-center">
              <button class="btn btn-xs btn-default my-1" type="button" @click="downloadFormat('FormatSwabAntigen')"
                :disabled="loading" :class="{'btn-loading': loading}">
                <em class="fa fa-file" />
                <span>Format Import</span>
              </button>
              <button class="btn btn-xs btn-default my-1" type="button" @click="downloadFormat('wilayah')"
                :disabled="loading" :class="{'btn-loading': loading}">
                <em class="fa fa-file" />
                <span>Data Wilayah</span>
              </button>
            </div>
          </div>
          <div class="form-group">
            <dropzone-import-excel :previewFile="previewFile" />
          </div>
          <div class="form-group">
            <button @click="doImport()" :disabled="loading" :class="{'btn-loading': loading}"
              class="btn btn-md btn-primary block m-b pull-right" type="button">
              <em class="fa fa-check" /> Import Excel
            </button>
          </div>
        </div>
      </div>
    </custom-modal>

    <custom-modal modal_id="modalErrorMessage" title="Error">
      <div slot="body">
        <div class="row">
          <div class="form-group">
            <div v-for="item in dataError" :key="item.id">
              {{ item.message }}
              <ul>
                <li v-for="err in item.messageArr" :key="err.id"> {{ err }}</li>
              </ul>
            </div>
          </div>
        </div>
        <div class="row pull-right">
          <button class="btn btn-md btn-danger" type="button" data-dismiss="modal">
            OK
          </button>
        </div>
      </div>
    </custom-modal>

    <custom-modal modal_id="modalBulkValidate" title="Validasi Sampel">
      <div slot="body">
        <div class="alert alert-danger" v-show="sampelIds.length < 1">
          <strong>Perhatian!</strong>
          Belum ada sampel yang dipilih. Silahkan pilih beberapa pada tabel.
        </div>
        <div class="alert alert-info" v-if="showSampelSum">
          <p>Jumlah Sampel: {{ sampelIds.length }}</p>
        </div>
        <form @submit.prevent="dummy">
          <div class="row">
            <div class="form-group">
              <label>
                Pejabat yang Menandatangani Hasil Lab yang tercetak
                <span style="color:red">*</span>
              </label>
              <div :class="{ 'is-invalid': form.errors.has('validator') }">
                <div v-for="item in listValidator" :key="item.id">
                  <label class="form-check-label">
                    <input type="radio" v-model="formValidasi.validator_id" :value="item.id" />
                    <strong>{{item.nama}}</strong> (NIP. {{ item.nip }})
                  </label>
                </div>
              </div>
              <has-error :form="formValidasi" field="validator" />
            </div>
          </div>
          <div class="row">
            <button @click="submit()" :disabled="isDisabledValidasi" :class="{'btn-loading': loading}"
              class="btn btn-md btn-primary block full-width m-b" type="button">
              <em class="fa fa-check" /> Validasi
            </button>
          </div>
        </form>
      </div>
    </custom-modal>

  </div>
</template>

<script>
  import {
    KesimpulanPemeriksaan
  } from '~/assets/js/constant/enum'
  import {
    momentFormatDateYmd
  } from '~/utils'
  import Form from "vform"
  import $ from "jquery"
  import CustomModal from "~/components/CustomModal"
  const JQuery = $

  export default {
    middleware: ['auth', 'checkrole'],
    meta: {
      allow_role_id: [1, 2, 7]
    },
    head() {
      return {
        title: "Hasil Pemeriksaan Swab Antigen"
      }
    },
    components: {
      CustomModal,
    },
    async asyncData({
      store
    }) {
      const sampelIds = store.state.swab_antigen_hasil.selectedSampels
      const formValidasi = new Form({
        validator_id: null,
        id: sampelIds
      })
      const form = new Form({
        register_file: null
      })
      return {
        loading: false,
        dataError: [],
        dataKesimpulanPemeriksaan: KesimpulanPemeriksaan.data,
        form,
        params: {
          nama_pasien: "",
          tanggal_periksa_start: "",
          tanggal_periksa_end: "",
          hasil_periksa: "",
          status: "registrasi"
        },
        formValidasi,
        listValidator: [],
        sampelIds,
        showSampelSum: false,
        isDisabledValidasi: true,
        isRoleAdminRegistrasi : false,
        listColumn: {
          checkbox_input: '#',
          nomor_registrasi: 'NO REGISTRASI',
          nama_pasien : 'PASIEN',
          tanggal_lahir : 'TANGGAL LAHIR',
          kategori: 'KATEGORI',
          no_hasil: 'NO HASIL',
          tanggal_periksa: 'TANGGAL HASIL',
          hasil_periksa : 'HASIL PEMERIKSAAN',
        }
      }
    },
    created() {
      this.checkRole()
    },
    methods: {
      async checkRole() {
        const resp = await this.$axios.get("/user")
        const { role_id } = resp.data || null
        const isRoleAdminRegistrasi = role_id && role_id === 2 ? true : false
        this.isRoleAdminRegistrasi = isRoleAdminRegistrasi
        if (!isRoleAdminRegistrasi) {
          this.getListValidator()
        }
        if (isRoleAdminRegistrasi) {
          this.listColumn = {
            nomor_registrasi: 'NO REGISTRASI',
            nama_pasien : 'PASIEN',
            tanggal_lahir : 'TANGGAL LAHIR',
            kategori: 'KATEGORI',
            no_hasil: 'NO HASIL',
            tanggal_periksa: 'TANGGAL HASIL',
            hasil_periksa : 'HASIL PEMERIKSAAN',
          }
        }
      },
      async getListValidator() {
        const resp = await this.$axios.get("/v1/validasi/list-validator")
        const { data } = resp.data || []
        this.listValidator = data
      },
      doFilter() {
        if (this.params.tanggal_periksa_start) {
          this.params.tanggal_periksa_start = momentFormatDateYmd(this.params.tanggal_periksa_start)
        }
        if (this.params.tanggal_periksa_end) {
          this.params.tanggal_periksa_end = momentFormatDateYmd(this.params.tanggal_periksa_end)
        }
        this.$bus.$emit('refresh-ajaxtable2', 'swab-antigen-hasil', this.params)
      },
      clearFilter() {
        this.params.nama_pasien = ""
        this.params.tanggal_periksa_start = ""
        this.params.tanggal_periksa_end = ""
        this.params.hasil_periksa = ""
        this.$bus.$emit("clear-dropdown")
        this.$bus.$emit("refresh-ajaxtable2", "swab-antigen-hasil", this.params)
      },
      async doImport() {
        const formData = new FormData()
        formData.append('register_file', this.form.register_file)
        this.loading = true
        JQuery('#importHasil').modal('hide')
        try {
          await this.$axios.post('/v1/swab-antigen/import', formData, {
            headers: {
              'Content-Type': 'multipart/form-data'
            }
          })
          this.$toast.success('Sukses import data', {
            icon: "check",
            iconPack: "fontawesome",
            duration: 5000
          })
        } catch (err) {
          if (err.response && err.response.status === 422) {
            let errMessageArr = []
            for (const property in err.response.data.errors) {
              errMessageArr.push({
                id: property,
                message: `Baris ke ${err.response.data.errors[property].row}`,
                messageArr: err.response.data.errors[property].errors
              })
            }
            this.dataError = errMessageArr
            JQuery('#modalErrorMessage').modal('show')
          }
          if (err.response && err.response.status === 403) {
            this.$toast.error(err.response.data.errors, {
              icon: "times",
              iconPack: "fontawesome",
              duration: 5000
            })
          }
          if (err.response && err.response.status === 500) {
            this.$swal.fire(
              "Terjadi kesalahan",
              "Silakan hubungi Admin",
              "error"
            )
          }
        }
        this.$bus.$emit('refresh-ajaxtable', 'swab-antigen-hasil')
        $('#register_file').val('')
        this.form.reset()
        this.loading = false
      },
      previewFile(file) {
        this.form.register_file = file
      },
      async submit() {
        JQuery('#modalBulkValidate').modal('hide')
        try {
          this.loading = true
          const response = await this.formValidasi.post('/v1/swab-antigen/bulk')
          this.$store.commit('swab_antigen_hasil/clear')
          this.$bus.$emit('refresh-ajaxtable', 'swab-antigen-hasil')
          if (response?.data?.message === 'success') {
            const {
              value: isConfirm
            } = await this.$swal.fire({
              type: "success",
              title: "Berhasil validasi sampel"
            })
            if (isConfirm) {
              location.reload()
            }
          }
        } catch (err) {
          if (err.response && err.response.status === 422) {
            let errMessageArr = []
            for (const property in err.response.data.errors) {
              errMessageArr.push({
                id: property,
                messageArr: err.response.data.errors[property]
              })
            }
            this.dataError = errMessageArr
            JQuery('#modalErrorMessage').modal('show')
          } else {
            this.$swal.fire("Terjadi kesalahan", err.response.data.error, "error")
          }
        }
        this.loading = false
      },
      downloadFormat(namaFile) {
        this.$axios.get(`v1/download?namaFile=${namaFile}`, {
            responseType: 'blob'
          })
          .then(response => {
            let blob = new Blob([response.data], {
              type: response.headers['content-type']
            })
            let link = document.createElement('a')
            link.href = window.URL.createObjectURL(blob)
            link.download = namaFile + '.xlsx'
            link.setAttribute('download', link.download)
            document.body.appendChild(link)
            link.click()
            window.URL.revokeObjectURL(link.href)
            link.remove()
          })
      }
    },
    watch: {
      'sampelIds': function () {
        if (this.sampelIds.length > 0) {
          this.showSampelSum = true
          this.isDisabledValidasi = false
        } else {
          this.showSampelSum = false
          this.isDisabledValidasi = true
        }
      }
    }
  }
</script>
