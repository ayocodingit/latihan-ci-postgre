<template>
  <div class="wrapper wrapper-content">
    <portal to="title-name">Validasi Hasil Pemeriksaan</portal>
    <portal to="title-action">
      <div class="title-action">
        <a href="#" @click.prevent="$router.back()" class="btn btn-black">
          <em class="uil-arrow-left" />Kembali
        </a>
      </div>
    </portal>
    <form @submit.prevent="dummy">
      <div class="row">
        <div class="col-md-6">
          <Ibox title="Informasi Sampel">
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
                Nomor Induk Kependudukan
              </div>
              <div class="col-md-7 flex-text-center">
                {{ data.pasien && data.pasien.nik ? data.pasien.nik : ''}}
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-5 text-blue flex-text-center">
                Nama Pasien
              </div>
              <div class="col-md-7 flex-text-center">
                {{ data.pasien && data.pasien.nama_lengkap ? data.pasien.nama_lengkap : ''}}
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-5 text-blue flex-text-center">
                Tanggal Lahir Pasien
              </div>
              <div class="col-md-7 flex-text-center">
                {{ data.pasien.tanggal_lahir | formatDate }}
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-5 text-blue flex-text-center">
                No. Telp/HP
              </div>
              <div class="col-md-7 flex-text-center">
                <span v-if="data.pasien.no_hp">{{data.pasien.no_hp }}</span>
                <span v-else-if="data.pasien.no_telp">{{data.pasien.no_telp }}</span>
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-5 text-blue flex-text-center">
                Alamat
              </div>
              <div class="col-md-7 flex-text-center">
                {{ data.pasien && data.pasien.alamat ? data.pasien.alamat : ''}}
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
                Jenis Sampel
              </div>
              <div class="col-md-7 flex-text-center">
                {{data.jenis_sampel_nama || ''}}
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-5 text-blue flex-text-center">
                Tanggal Penerimaan Sampel
              </div>
              <div class="col-md-7 flex-text-center">
                {{data.last_pemeriksaan_sampel.tanggal_penerimaan_sampel | formatDate}}
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-5 text-blue flex-text-center">
                Petugas Penerimaan Sampel RNA
              </div>
              <div class="col-md-7 flex-text-center">
                {{ data.last_pemeriksaan_sampel && data.last_pemeriksaan_sampel.petugas_penerima_sampel_rna ? data.last_pemeriksaan_sampel.petugas_penerima_sampel_rna : ''}}
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-5 text-blue flex-text-center">
                Lab Penerima
              </div>
              <div class="col-md-7 flex-text-center">
                {{data.lab_pcr_nama || ''}}
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-5 text-blue flex-text-center">
                Operator Realtime PCR
              </div>
              <div class="col-md-7 flex-text-center">
                {{ data.last_pemeriksaan_sampel && data.last_pemeriksaan_sampel.operator_real_time_pcr ? data.last_pemeriksaan_sampel.operator_real_time_pcr : ''}}
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-5 text-blue flex-text-center">
                Tanggal Mulai Pemeriksaan
              </div>
              <div class="col-md-7 flex-text-center">
                {{data.last_pemeriksaan_sampel.tanggal_mulai_pemeriksaan | formatDate}}
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-5 text-blue flex-text-center">
                Jam Mulai Pemeriksaan
              </div>
              <div class="col-md-7 flex-text-center">
                {{ data.last_pemeriksaan_sampel && data.last_pemeriksaan_sampel.jam_mulai_pcr ? data.last_pemeriksaan_sampel.jam_mulai_pcr : ''}}
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-5 text-blue flex-text-center">
                Jam Selesai Pemeriksaan
              </div>
              <div class="col-md-7 flex-text-center">
                {{ data.last_pemeriksaan_sampel && data.last_pemeriksaan_sampel.jam_selesai_pcr ? data.last_pemeriksaan_sampel.jam_selesai_pcr : ''}}
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-5 text-blue flex-text-center">
                Metode Pemeriksaan
              </div>
              <div class="col-md-7 flex-text-center">
                {{ data.last_pemeriksaan_sampel && data.last_pemeriksaan_sampel.metode_pemeriksaan ? data.last_pemeriksaan_sampel.metode_pemeriksaan : ''}}
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-5 text-blue flex-text-center">
                Nama Kit Pemeriksaan
              </div>
              <div class="col-md-7 flex-text-center">
                {{ data.last_pemeriksaan_sampel && data.last_pemeriksaan_sampel.nama_kit_pemeriksaan ? data.last_pemeriksaan_sampel.nama_kit_pemeriksaan : ''}}
              </div>
            </div>
          </Ibox>
        </div>

        <div class="col-md-6">
          <Ibox title="Hasil Analisa PCR">
            <table class="table dt-responsive table-bordered" style="width:100%">
              <caption v-show="false">Hasil Analisa PCR</caption>
              <thead>
                <tr>
                  <th scope="col">Target Gen</th>
                  <th scope="col">CT Value</th>
                </tr>
              </thead>
              <tbody class="field_wrapper">
                <tr v-for="(hasil, $index) in form.hasil_deteksi" :key="$index">
                  <td>
                    <p class="form-control control-disabled">
                      <strong>
                        {{ hasil.target_gen }}
                      </strong>
                    </p>
                  </td>
                  <td>
                    <p class="form-control control-disabled">
                      <strong v-if="!!hasil.ct_value">{{ hasil.ct_value }}</strong>
                      <strong v-if="hasil.ct_value == ''">{{ '-' }}</strong>
                    </p>
                  </td>
                </tr>
              </tbody>
            </table>

            <div class="form-group">
              <label class="text-blue">
                Kesimpulan Pemeriksaan
              </label>
              <div>
                <p class="form-control control-disabled">
                  <strong>{{ form.kesimpulan_pemeriksaan == 'swab_ulang' ? 'swab ulang' : form.kesimpulan_pemeriksaan }}</strong>
                </p>
              </div>
            </div>

            <div class="form-group" v-if="data.pemeriksaan_sampel.grafik">
              <label class="text-blue">
                Grafik
              </label>
              <div>
                <a :href="url" target="_blank" v-for="(url, $index) in data.pemeriksaan_sampel.grafik" :key="$index"
                  class="thumbnail-wrapper">
                  <img :src="url" alt="" />
                </a>
              </div>
            </div>

            <input type="hidden" v-model="form.last_pemeriksaan_id">

            <div class="form-group">
              <label class="text-blue">
                Pejabat yang Menandatangani Hasil Lab yang tercetak
                <span style="color:red">*</span>
              </label>
              <div :class="{ 'is-invalid': form.errors.has('validator') }">
                <div v-for="item in listValidator" :key="item.id">
                  <label>
                    <input type="radio" v-model="form.validator" :value="item.id">
                    <span><strong>{{item.nama}}</strong> (NIP. {{ item.nip }})</span>
                  </label>
                </div>
              </div>
              <has-error :form="form" field="validator" />
            </div>

            <div class="form-group">
              <label class="text-blue">Catatan Pemeriksaan</label>
              <textarea class="form-control" v-model="form.catatan_pemeriksaan"
                :class="{ 'is-invalid': form.errors.has(`catatan_pemeriksaan`) }"></textarea>
              <has-error :form="form" field="catatan_pemeriksaan" />
            </div>

            <div class="form-group">
              <button @click="submit()" :disabled="loading" :class="{'btn-loading': loading}"
                class="btn btn-md btn-primary block full-width m-b" type="button">
                <em class="fa fa-check" />
                {{ 'Validasi' }}
              </button>
            </div>
          </Ibox>
        </div>

      </div>

    </form>
  </div>
</template>

<script>
  import Form from "vform";
  import axios from "axios";
  import Dropzone from "nuxt-dropzone";
  import "nuxt-dropzone/dropzone.css";

  export default {
    middleware: "auth",
    components: {
      Dropzone
    },
    data() {
      return {
        loading: false
      };
    },
    async asyncData({
      route,
      store
    }) {
      let resp = await axios.get(`/v1/validasi/detail/${route.params.id}`);
      let data = resp.data.data;

      let respListValidator = await axios.get("/v1/validasi/list-validator");
      let listValidator = respListValidator.data.data;

      if (!data.pasien) {
        data.pasien = {};
      }

      let form = new Form({
        validator: data.validator_id,
        tanggal_input_hasil: new Date(),
        jam_input_hasil: ("" + new Date().getHours()).padStart(2, "0") + ":" + ("" + new Date()
          .getMinutes()).padStart(2, "0"),
        last_pemeriksaan_id: data.last_pemeriksaan_sampel.id,
        catatan_pemeriksaan: data.last_pemeriksaan_sampel.catatan_pemeriksaan,
        kesimpulan_pemeriksaan: data.last_pemeriksaan_sampel.kesimpulan_pemeriksaan,
        hasil_deteksi: data.last_pemeriksaan_sampel.hasil_deteksi ? data.last_pemeriksaan_sampel
          .hasil_deteksi : [{}],
        grafik: data.last_pemeriksaan_sampel.grafik ? data.last_pemeriksaan_sampel.grafik : [],
      });

      return {
        data,
        form,
        listValidator
      };
    },
    head() {
      return {
        title: "Validasi hasil pemeriksaan"
      };
    },
    methods: {
      dummy() {
        return false;
      },
      async submit() {
        // Submit the form.
        try {
          this.loading = true;
          const response = await this.form.post(`/v1/validasi/edit-status-sampel/${this.$route.params.id}`);
          this.$toast.success(response.data.message, {
            icon: "check",
            iconPack: "fontawesome",
            duration: 5000
          });
          this.$router.back()
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
              err.response.data.error,
              "error"
            )
          }
        }
        this.loading = false;
      }
    }
  };
</script>


<style scoped>
  .control-disabled {
    background-color: #eaeaea;
  }
</style>