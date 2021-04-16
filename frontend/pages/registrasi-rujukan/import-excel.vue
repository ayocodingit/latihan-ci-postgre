<template>
  <div>
    <portal to="title-name">Import</portal>
    <div class="navbar white-bg row border-bottom p-0">
      <div class="w-100 h-100 flex py-0 px-4">
        <div
          class="d-inline-flex p-3 cursor-pointer"
          :class="option === 'manual' ? 'active' : 'text-muted'"
          @click="toggleOption('manual')"
        >
          Import Manual
        </div>
        <div
          class="d-inline-flex p-3 cursor-pointer"
          :class="option === 'pikobar' ? 'active' : 'text-muted'"
          @click="toggleOption('pikobar')"
        >
          Import Pikobar
        </div>
      </div>
    </div>

    <div class="d-flex justify-content-center">
      <div class="w-50">
        <step-progress
          :steps="importSteps"
          :current-step="currentStep"
          icon-class="fa fa-check"
          active-color="#1DB0E6"
        ></step-progress>
      </div>
    </div>

    <div class="wrapper wrapper-content">
      <Ibox title="Import Data Pasien Rujukan" v-if="currentStep === 0">
        <div class="col-lg-12">
          <div class="form-group">
            <dropzone-import-excel :previewFile="previewFile" :alertInfo="alertInfo" />
            <div class="row justify-content-center" v-show="form.register_file">
              <button
                class="btn btn-primary block mt-4"
                type="button"
                @click="reviewFile()"
              >
                Review File
              </button>
            </div>
          </div>
        </div>
      </Ibox>
      <div class="bg-white rounded-lg w-100 mb-4" v-if="currentStep === 1">
        <div class="col-lg-12 d-flex py-3 bg-light w-100">
          <div class="col-md-3">
            <button
              class="btn btn-black"
              type="button"
              @click="actionBack()"
            >
              Kembali
            </button>
          </div>
          <div class="col-md-6 text-muted flex-text-center">
            {{ fileName }}
          </div>
          <div class="col-md-3 flex-right">
            <button
              :class="errors_count === 0 ? 'btn btn-primary' : 'btn btn-black'"
              type="button"
              @click="importData()"
              :disabled="errors_count === 0 ? false : true"
              :title="errors_count === 0 ? 'Import Data' : 'Harap cek kembali data yang diimport'"
            >
              <em class="fa fa-download" />
              Import Data
            </button>
          </div>
        </div>
        <div class="row justify-content-center" v-if="loading">
          <img
            alt="loading"
            src="~/assets/css/plugins/blueimp/img/loading.gif"
            width="50"
            height="50"
          />
        </div>
        <div v-else class="col-lg-12 py-3 bg-white table-responsive">
          <div class="col-md-12 d-flex text-blue font-weight-bold py-2">
            <div class="col-md-3">PASIEN</div>
            <div class="col-md-2">DOMISILI</div>
            <div class="col-md-2">KATEGORI</div>
            <div class="col-md-2">INSTANSI PENGIRIM</div>
            <div class="col-md-2">
              {{ option === 'pikobar' ? 'SAMPEL' : 'SAMPEL LABKES'}}
            </div>
            <div class="col-md-2" v-if="option === 'pikobar'">SAMPEL FASYANKES</div>
          </div>
          <div
            v-for="(item, index) in data"
            :key="index"
            class="py-2 rounded-lg"
            :class="errors[index] ? 'data-error' : ''"
          >
            <div class="col-md-12 d-flex">
              <div class="col-md-3">
                <div v-if="item.nama_lengkap" class="text-capitalize font-weight-bold">
                  {{ item.nama_lengkap }}
                </div>
                <div v-if="item.nik">
                  {{ item.nik }}
                </div>
                <div class="text-muted">
                  <span v-if="item.usia_tahun">{{ `${item.usia_tahun} tahun ` }}</span>
                  <span v-if="item.usia_bulan">{{ `${item.usia_bulan} bulan ` }}</span>
                </div>
              </div>
              <div class="col-md-2">
                {{ item.kota_nama || '-'}}
              </div>
              <div class="col-md-2">
                {{ item.sumber_pasien || '-' }}
              </div>
              <div class="col-md-2">
                {{ item.fasyankes_nama || '-' }}
              </div>
              <div class="col-md-2">
                <span class="badge badge-white">
                  {{ item.nomor_sampel || '-' }}
                </span>
              </div>
              <div class="col-md-2" v-if="option === 'pikobar'">
                <span class="badge badge-white">
                  {{ item.nomor_sampel_fasyankes || '-' }}
                </span>
              </div>
            </div>
            <div v-if="errors[index]">
              <div
                class="bg-white mx-4 my-1 p-2 rounded-lg"
                v-for="(err, index) in errors[index]"
                :key="index"
              >
                <em class="fa fa-exclamation-circle fa-lg mr-2 text-red" />
                {{ err }}
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="bg-white rounded-lg w-100 mb-4 py-3" v-if="currentStep > 1">
        <div class="col-lg-12 py-4 mb-4" v-if="loading">
          <div class="row justify-content-center py-3">
            <div class="spinner-border text-blue"></div>
          </div>
          <div class="row justify-content-center">
            <h3>Import Sedang Berlangsung</h3>
          </div>
          <div class="row justify-content-center">
            Mohon tunggu beberapa saat
          </div>
        </div>
        <div v-else class="col-lg-12 py-3">
          <div class="row justify-content-center">
            <em class="fa fa-check-circle-o fa-5x text-blue" />
          </div>
          <div class="row justify-content-center">
            <h2 class="font-weight-bold">Berhasil</h2>
          </div>
          <div class="row justify-content-center" v-if="fileName">
            {{ `Import ${fileName}` }}
          </div>
          <div class="row justify-content-center">
            Sampel berhasil diimport
          </div>
          <div class="row justify-content-center mt-4">
            <button
              class="btn btn-primary"
              type="button"
              @click="actionBack()"
            >
              Kembali
            </button>
          </div>
        </div>
      </div>
    </div>

  </div>
</template>

<script>
  import Form from "vform"
  export default {
    middleware: "auth",
    head() {
      return {
        title: "Import Data Pasien Rujukan"
      };
    },
    data() {
      const form = new Form({
        register_file: null
      });
      return {
        loading: false,
        data: [],
        errors: [],
        errors_count: null,
        form,
        option: 'manual',
        importSteps: ['Import', 'Review', 'Selesai'],
        currentStep: 0,
        fileName: '',
        alertInfo: this.$t("label.rules-import-registrasi-rujukan")
      }
    },
    methods: {
      toggleOption(option){
        this.option = option
        if (this.form.register_file) {
          this.$bus.$emit("reset-dropzone")
        }
        this.actionBack()
      },
      previewFile(file) {
        if (file?.name) {
          this.fileName = file.name
        }
        this.form.register_file = file
      },
      async reviewFile() {
        this.loading = true
        let formData = new FormData()
        formData.append("register_file", this.form.register_file)
        const urlScanFile = this.option === 'manual' ? '/v1/register/import-rujukan' : '/v1/register/import-rujukan-tes-masif'
        try {
          const resp = await this.$axios.post(urlScanFile, formData, {
            headers: {
              "Content-Type": "multipart/form-data"
            }
          })
          const { data, errors, errors_count } = resp.data || []
          if (data && data.length > 0) {
            this.currentStep = 1
            this.data = data
            this.errors = errors || []
            this.errors_count = errors_count || 0
          } else {
            this.actionBack()
            this.$swal.fire("Terjadi kesalahan", "File yang di import kosong atau data sudah ada pada aplikasi", "error")
          }
        } catch (err) {
          if (err?.response?.status === 422 && err?.response?.data) {
            this.$toast.error("Mohon cek kembali data yang anda import", {
              icon: "times",
              iconPack: "fontawesome",
              duration: 5000
            })
            this.currentStep = 1
            const { data, errors, errors_count } = err.response.data || {}
            this.data = data || []
            this.errors = errors || []
            this.errors_count = errors_count || 0
          } else {
            if (err?.response?.data?.errors) {
              this.actionBack()
              this.$swal.fire("Terjadi kesalahan", err.response.data.errors, "error")
            }
          }
        }
        this.loading = false
      },
      async importData() {
        this.loading = true
        const newData = {
          data: this.data,
          errors: this.errors,
          errors_count: this.errors_count
        }
        const urlImportData = this.option === 'manual' ? '/v1/register/import-data-rujukan' : '/v1/register/import-data-tes-masif'
        try {
          this.currentStep = 2
          const response = await this.$axios.post(urlImportData, newData)
          this.$toast.success(response.data.message, {
            icon: "check",
            iconPack: "fontawesome",
            duration: 5000
          })
          this.currentStep = 3
        } catch (err) {
          this.actionBack()
          this.$swal.fire(
            "Terjadi kesalahan",
            "Silakan hubungi Admin",
            "error"
          )
        }
        this.loading = false
      },
      actionBack() {
        this.currentStep = 0
        this.data = []
        this.errors = []
        this.errors_count = null
      }
    }
  };
</script>

<style scoped>
  .active {
    color: #1DB0E6;
    border-bottom: 2px solid #1DB0E6;
  }

  .info-bar {
    background: #1DB0E6;
    opacity: 0.2;
  }

  .data-error {
    background: #FDEDED;
  }
</style>
