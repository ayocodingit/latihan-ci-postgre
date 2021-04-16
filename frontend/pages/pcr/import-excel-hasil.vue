<template>
  <div class="wrapper wrapper-content">
    <portal to="title-name">Import Hasil PCR Laboratorium</portal>
    <portal to="title-action">
      <div class="title-action">
        <a href="#" @click.prevent="$router.back()" class="btn btn-secondary">
          <em class="uil-arrow-left" />Kembali
        </a>
      </div>
    </portal>
    <form @submit.prevent="dummy">
      <div class="row">
        <div class="col-md-4">
          <Ibox title="Loading Data">
            <div class="form-group">
              <label for="register_file">File Hasil Pemeriksaan</label>
              <input class="form-control" type="file" id="fileHasilPeriksa" ref="myFile" @change="previewFile"
                accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" />
            </div>

            <div class="form-group">
              <button @click="submit()" :disabled="loading" :class="{'btn-loading': loading}"
                class="btn btn-md btn-primary block full-width m-b" type="button">
                <em class="fa fa-search" />
                Baca Excel
              </button>
            </div>
          </Ibox>
          <Ibox title="Import Data">
            <div class="form-group" v-if="errors_count == 0 && data.length > 0">
              Terdapat {{ data.length }} data
            </div>
            <div class="form-group" v-if="errors_count > 0">
              Terdapat {{ errors_count }} error. Mohon periksa file Anda
            </div>

            <div class="form-group">
              <button @click="submitData()" :disabled="loading || data.length == 0 || errors_count > 0"
                :class="{'btn-loading': loading}" class="btn btn-md btn-success block full-width m-b" type="button">
                <em class="fa fa-check" />
                Import Data
              </button>
            </div>
          </Ibox>
        </div>
        <div class="col-md-8" v-if="data.length > 0">
          <Ibox title="Pratijau Import Data">
            <table class="table table-bordered table-striped">
              <caption v-show="false">Import Data</caption>
              <thead>
                <tr>
                  <th scope="col">No</th>
                  <th scope="col">Nomor Sampel</th>
                  <th scope="col">Target Gen</th>
                  <th scope="col">Kesimpulan Pemeriksaan</th>
                  <th scope="col">Tanggal Periksa</th>
                </tr>
              </thead>
              <tbody>
                <template v-for="(row, $index) in data">
                  <tr :key="row.nomor_sampel">
                    <td>{{ row.no }}</td>
                    <td>{{ row.nomor_sampel }}</td>
                    <td>
                      <div v-for="(value) in row.target_gen" :key="value.target_gen">- {{ value.target_gen }}:
                        {{ value.ct_value }}</div>
                    </td>
                    <td>{{ row.kesimpulan_pemeriksaan }}</td>
                    <td>{{ row.tanggal_input_hasil }}</td>
                  </tr>
                  <tr :key="$index" class="bg-warning" v-if="errors[$index]">
                    <td>Error</td>
                    <td colspan="4">
                      <div v-for="(error, $index2) in errors[$index]" :key="$index2">- {{ error }}</div>
                    </td>
                  </tr>
                </template>
              </tbody>
            </table>
          </Ibox>
        </div>
      </div>
    </form>
  </div>
</template>

<script>
  import Form from "vform";
  import axios from "axios";

  export default {
    middleware: "auth",

    data() {
      return {
        loading: false,
        data: [],
        errors: {},
        errors_count: 0,
      };
    },

    async asyncData({
      route,
      store
    }) {
      let form = new Form({
        register_file: null
      });

      return {
        form
      };
    },

    head() {
      return {
        title: "Import Hasil PCR Laboratorium"
      };
    },
    methods: {
      dummy() {
        return false;
      },

      async submit() {
        let formData = new FormData();
        formData.append("register_file", this.form.register_file);
        this.loading = true;
        try {
          let resp = await axios.post("/v1/pcr/import-hasil-pemeriksaan", formData, {
            headers: {
              "Content-Type": "multipart/form-data"
            }
          });
          this.$toast.success("Mohon cek data ini terlebih dahulu", {
            icon: "check",
            iconPack: "fontawesome",
            duration: 5000
          });
          this.data = resp.data.data
          this.errors = resp.data.errors
          this.errors_count = resp.data.errors_count
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
            this.$swal.fire(
              "Terjadi kesalahan",
              "Format Excel Salah",
              "error"
            );
          }
          this.$sentry.captureException(err)
        }
        this.loading = false;
        $('#fileHasilPeriksa').val('');
        formData.delete('register_file');
      },
      async submitData() {
        // Submit the form.
        try {
          this.loading = true;
          const response = await axios.post("/v1/pcr/import-data-hasil-pemeriksaan", {
            data: this.data
          });
          this.$toast.success(response.data.message, {
            icon: "check",
            iconPack: "fontawesome",
            duration: 5000
          });
          this.$router.back()
        } catch (err) {
          this.$swal.fire(
            "Terjadi kesalahan",
            "Silakan hubungi Admin",
            "error"
          );
        }
      },
      previewFile() {
        this.form.register_file = this.$refs.myFile.files[0];
        this.$nextTick(() => {
          this.submit()
        })
      }
    }
  };
</script>