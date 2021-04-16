<template>
  <div class="wrapper wrapper-content">
    <portal to="title-name">Tandai Sampel {{data.nomor_sampel}} Invalid</portal>
    <portal to="title-action">
      <div class="title-action">
        <a href="#" @click.prevent="$router.back()" class="btn btn-default">
          <em class="uil-arrow-left" />Kembali
        </a>
      </div>
    </portal>

    <div class="error-container">
      <div class="error">
        <svg xmlns="http://www.w3.org/2000/svg" width="90" height="90" fill="#DBE1EC" viewBox="0 0 48 48">
          <path
            d="M22 30h4v4h-4zm0-16h4v12h-4zm1.99-10C12.94 4 4 12.95 4 24s8.94 20 19.99 20S44 35.05 44 24 35.04 4 23.99 4zM24 40c-8.84 0-16-7.16-16-16S15.16 8 24 8s16 7.16 16 16-7.16 16-16 16z" />
        </svg>
        <div class="title">
          Apakah Anda yakin hendak menandai sampel ini sebagai
          <strong>Invalid</strong> sehingga perlu dilakukan re-ekstraksi ulang?
        </div>
        <form @submit.prevent="submit">
          <div>
            <textarea class="form-control" v-model="form.catatan_pemeriksaan"
              placeholder="Masukkan Alasan di sini"></textarea>
            <has-error :form="form" field="catatan_pemeriksaan" />
          </div>
          <p class="description m-t">
            <v-button :loading="form.busy" class="btn btn-md btn-danger">
              <em class="fa fa-times" /> Ya, Tandai Invalid
            </v-button>
            <a @click.prevent="$router.back()" href="#" class="btn btn-md btn-default">Batal</a>
          </p>
        </form>
      </div>
    </div>

    <div class="row">
      <div class="col-md-6">
        <Ibox title="Informasi Detil RT-PCR">
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Tanggal penerimaan sampel
            </div>
            <div class="col-md-7 flex-text-center">
              {{data.pcr.tanggal_penerimaan_sampel | formatDate}} pada {{data.pcr.jam_penerimaan_sampel}}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Petugas Penerima Sampel
            </div>
            <div class="col-md-7 flex-text-center">
              {{data.pcr.petugas_penerima_sampel_rna || ''}}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Catatan Penerimaan
            </div>
            <div class="col-md-7 flex-text-center">
              {{data.pcr.catatan_penerimaan || ''}}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Operator Realtime PCR
            </div>
            <div class="col-md-7 flex-text-center">
              {{data.pcr.operator_real_time_pcr || ''}}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Tanggal Mulai PCR
            </div>
            <div class="col-md-7 flex-text-center">
              {{data.pcr.tanggal_mulai_pemeriksaan | formatDate }} pada {{data.pcr.jam_mulai_pcr}}
              sampai dengan {{data.pcr.jam_selesai_pcr}}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Nama Kit PCR
            </div>
            <div class="col-md-7 flex-text-center">
              {{data.pcr.nama_kit_pemeriksaan || ''}}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Target Gen
            </div>
            <div class="col-md-7 flex-text-center">
              {{data.pcr.target_gen || ''}}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Hasil Deteksi
            </div>
            <div class="col-md-7 flex-text-center">
              {{data.pcr.hasil_deteksi || ''}}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Grafik
            </div>
            <div class="col-md-7 flex-text-center">
              {{data.pcr.grafik || ''}}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Kesimpulan Pemeriksaan
            </div>
            <div class="col-md-7 flex-text-center">
              {{data.pcr.kesimpulan_pemeriksaan || ''}}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Catatan Pemeriksaan
            </div>
            <div class="col-md-7 flex-text-center">
              {{data.pcr.catatan_pemeriksaan || ''}}
            </div>
          </div>
        </Ibox>
      </div>

      <div class="col-md-6">
        <Ibox title="Informasi Detil Ekstraksi">
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Nomor Registrasi
            </div>
            <div class="col-md-7 flex-text-center">
              {{data.nomor_register || ''}}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Nomor Sampel
            </div>
            <div class="col-md-7 flex-text-center">
              {{data.nomor_sampel || ''}}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Tanggal penerimaan sampel
            </div>
            <div class="col-md-7 flex-text-center">
              {{data.ekstraksi.tanggal_penerimaan_sampel | formatDate}} pada
              {{data.ekstraksi.jam_penerimaan_sampel}}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Petugas Penerima Sampel
            </div>
            <div class="col-md-7 flex-text-center">
              {{data.ekstraksi.petugas_penerima_sampel || ''}}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Catatan Penerimaan
            </div>
            <div class="col-md-7 flex-text-center">
              {{data.ekstraksi.catatan_penerimaan || ''}}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Operator Ektraksi
            </div>
            <div class="col-md-7 flex-text-center">
              {{data.ekstraksi.operator_ekstraksi || ''}}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Tanggal Mulai Ekstraksi
            </div>
            <div class="col-md-7 flex-text-center">
              {{data.ekstraksi.tanggal_mulai_ekstraksi | formatDate }} pada
              {{data.ekstraksi.jam_mulai_ekstraksi}}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Metode Ekstraksi
            </div>
            <div class="col-md-7 flex-text-center">
              {{data.ekstraksi.metode_ekstraksi || ''}}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Nama Kit Ekstraksi
            </div>
            <div class="col-md-7 flex-text-center">
              {{data.ekstraksi.nama_kit_ekstraksi || ''}}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Dikirim ke Lab
            </div>
            <div class="col-md-7 flex-text-center">
              {{data.lab_pcr_nama || ''}}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Nama Pengirim RNA
            </div>
            <div class="col-md-7 flex-text-center">
              {{data.ekstraksi.nama_pengirim_rna || ''}}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Tanggal Pengiriman RNA
            </div>
            <div class="col-md-7 flex-text-center">
              {{data.ekstraksi.tanggal_pengiriman_rna | formatDate }} pada
                  {{data.ekstraksi.jam_pengiriman_rna }}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Catatan Pengiriman
            </div>
            <div class="col-md-7 flex-text-center">
              {{data.ekstraksi.catatan_penerimaan || ''}}
            </div>
          </div>
        </Ibox>
      </div>

      <div class="col-md-12">
        <Ibox title="Riwayat Perubahan atau Pengiriman Kembali">
          <table class="table table-bordered">
            <caption v-show="false">Log</caption>
            <tr>
              <th scope="col" width="20%">Tanggal Perubahan</th>
              <th scope="col">Keterangan</th>
            </tr>
            <tr v-if="data.log_pcr.length == 0">
              <td colspan="2">
                <em>Belum ada data terkait ekstraksi</em>
              </td>
            </tr>
            <tr v-for="(item,idx) in data.log_pcr" :key="idx">
              <td>{{item.created_at | formatDateTime}}</td>
              <td>{{item.metadata.catatan_penerimaan}} | {{item.metadata.catatan_pemeriksaan}}. {{item.description}}
              </td>
            </tr>
          </table>
        </Ibox>
      </div>
    </div>
  </div>
</template>

<script>
  import axios from "axios";
  import Form from "vform";

  export default {
    middleware: "auth",
    data() {
      return {
        form: new Form({
          catatan_pemeriksaan: "",
        }),
      }
    },
    async asyncData({
      route
    }) {
      let resp = await axios.get("/v1/pcr/detail/" + route.params.id);
      let data = resp.data.data;
      if (!data.log_pcr) {
        data.log_pcr = [];
      }
      if (!data.ekstraksi) {
        data.ekstraksi = {};
      }
      if (!data.pcr) {
        data.pcr = {};
      }
      return {
        data
      };
    },
    methods: {
      async submit() {
        // Submit the form.
        try {
          const response = await this.form.post("/v1/pcr/invalid/" + this.$route.params.id);
          this.$toast.success(response.data.message, {
            icon: 'check',
            iconPack: 'fontawesome',
            duration: 5000
          })
          this.$router.back();
        } catch (err) {
          if (err.response && err.response.data.code == 422) {
            this.$nextTick(() => {
              this.form.errors.set(err.response.data.error)
            })
            this.$toast.error('Mohon cek kembali formulir Anda', {
              icon: 'times',
              iconPack: 'fontawesome',
              duration: 5000
            })
          } else {
            this.$swal.fire("Terjadi kesalahan", "Silakan hubungi Admin", "error");
          }
          return;
        }
      }
    },
    head() {
      return {
        title: "Tandai Sampel " + this.data.nomor_sampel + " Invalid"
      };
    }
  };
</script>

<style scoped>
  .error-container {
    padding: 1rem;
    background: #f7f8fb;
    color: #47494e;
    text-align: center;
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
    font-family: sans-serif;
    font-weight: 100 !important;
    -ms-text-size-adjust: 100%;
    -webkit-text-size-adjust: 100%;
    -webkit-font-smoothing: antialiased;
  }

  .error {
    max-width: 500px;
  }

  .title {
    font-size: 1.2rem;
    margin-top: 15px;
    color: #47494e;
    margin-bottom: 8px;
  }

  .description {
    color: #7f828b;
    line-height: 21px;
    margin-bottom: 10px;
  }

  .logo {
    position: fixed;
    left: 12px;
    bottom: 12px;
  }
</style>