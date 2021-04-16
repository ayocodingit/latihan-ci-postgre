<template>
  <div class="wrapper wrapper-content">
    <portal to="title-name">Ubah Penerimaan dan Ekstraksi RNA</portal>
    <portal to="title-action">
      <div class="title-action">
        <a href="#" @click.prevent="$router.back()" class="btn btn-black">
          <em class="uil-arrow-left" /> Kembali
        </a>
      </div>
    </portal>
    <div class="error-container" v-if="error">
      <div class="error">
        <svg xmlns="http://www.w3.org/2000/svg" width="90" height="90" fill="#DBE1EC" viewBox="0 0 48 48">
          <path
            d="M22 30h4v4h-4zm0-16h4v12h-4zm1.99-10C12.94 4 4 12.95 4 24s8.94 20 19.99 20S44 35.05 44 24 35.04 4 23.99 4zM24 40c-8.84 0-16-7.16-16-16S15.16 8 24 8s16 7.16 16 16-7.16 16-16 16z" />
        </svg>
        <div class="title">
          Sampel ini tidak bisa diubah karena masih pada status {{ data.status.deskripsi }}
        </div>
        <p class="description">
          <a @click.prevent="$router.back()" class="error-link">Kembali</a>
        </p>
      </div>
    </div>
    <form @submit.prevent="dummy" v-if="!error">
      <div class="row">
        <div class="col-md-6">
          <Ibox title="Penerimaan Ekstraksi">
            <div class="form-group">
              <label>
                Tanggal penerimaan sampel
                <span style="color:red">*</span>
              </label>
              <date-picker placeholder="Pilih Tanggal" format="d MMMM yyyy" input-class="form-control"
                :monday-first="true" :wrapper-class="{ 'is-invalid': form.errors.has(`tanggal_penerimaan_sampel`) }"
                v-model="form.tanggal_penerimaan_sampel" />
              <has-error :form="form" field="tanggal_penerimaan_sampel" />
            </div>

            <div class="form-group">
              <label>
                Jam penerimaan sampel
                <span style="color:red">*</span>
              </label>
              <div class="form-group" :class="{ 'is-invalid': form.errors.has(`pukulsampel`) }">
                <masked-input class="form-control" v-model="form.jam_penerimaan_sampel" mask="11:11"
                  :class="{ 'is-invalid': form.errors.has('jam_penerimaan_sampel') }" />
              </div>
              <has-error :form="form" field="jam_penerimaan_sampel" />
            </div>

            <div class="form-group">
              <label>
                Petugas penerima sampel
                <span style="color:red">*</span>
              </label>
              <input class="form-control" type="text" v-model="form.petugas_penerima_sampel"
                placeholder="Petugas penerima sampel"
                :class="{ 'is-invalid': form.errors.has(`petugas_penerima_sampel`) }" />
              <has-error :form="form" field="petugas_penerima_sampel" />
            </div>

            <div class="form-group">
              <label>Catatan Penerimaan</label>
              <textarea class="form-control" v-model="form.catatan_penerimaan"></textarea>
            </div>

            <div class="form-group">
              <label>
                Operator ekstraksi
                <span style="color:red">*</span>
              </label>
              <input class="form-control" type="text" v-model="form.operator_ekstraksi" placeholder="Operator ekstraksi"
                :class="{ 'is-invalid': form.errors.has(`operator_ekstraksi`) }" />
              <has-error :form="form" field="operator_ekstraksi" />
            </div>

            <div class="form-group">
              <label>
                Tanggal mulai ekstraksi
                <span style="color:red">*</span>
              </label>
              <date-picker placeholder="Pilih Tanggal" format="d MMMM yyyy" input-class="form-control"
                :monday-first="true" :wrapper-class="{ 'is-invalid': form.errors.has(`tanggal_mulai_ekstraksi`) }"
                v-model="form.tanggal_mulai_ekstraksi" />
              <has-error :form="form" field="tanggal_mulai_ekstraksi" />
            </div>

            <div class="form-group">
              <label>
                Jam mulai ekstraksi
                <span style="color:red">*</span>
              </label>
              <div class="form-group" :class="{ 'is-invalid': form.errors.has(`jam_mulai_ekstraksi`) }">
                <masked-input class="form-control" v-model="form.jam_mulai_ekstraksi" mask="11:11"
                  :class="{ 'is-invalid': form.errors.has('jam_mulai_ekstraksi') }" />
              </div>
              <has-error :form="form" field="jam_mulai_ekstraksi" />
            </div>

            <div class="form-group">
              <label>
                Metode ekstraksi
                <span style="color:red">*</span>
              </label>
              <select v-model="form.metode_ekstraksi" name="metode_ekstraksi" required class="form-control">
                <option v-for="(item, idx) in metode" :key="idx" :value="item">
                  {{ item }}
                </option>
              </select>
            </div>

            <div class="form-group" v-show="form.metode_ekstraksi === metode[0]">
              <label>
                Reagen Ekstraksi
                <span style="color:red">*</span>
              </label>
              <select v-model="form.nama_kit_ekstraksi" name="nama_kit_ekstraksi" required class="form-control">
                <option :value="form.nama_kit_ekstraksi">{{ form.nama_kit_ekstraksi }}</option>
                <option v-for="(item, idx) in reagenEkstraksi" :key="idx" :value="item.nama">
                  {{ item.nama }}
                </option>
              </select>
            </div>

            <div class="form-group" v-show="form.metode_ekstraksi === metode[1]">
              <label>
                Reagen Ekstraksi
                <span style="color:red">*</span>
              </label>
              <select v-model="form.alat_ekstraksi" name="alat_ekstraksi" required class="form-control">
                <option :value="form.alat_ekstraksi">{{ form.alat_ekstraksi }}</option>
                <option v-for="(item, idx) in reagenEkstraksi" :key="idx" :value="item.nama">
                  {{ item.nama }}
                </option>
              </select>
            </div>
          </Ibox>
        </div>
        <div class="col-md-6">
          <Ibox title="Pengiriman Ekstraksi">
            <div class="form-group">
              <label>Nomor Sampel</label>
              <p class="form-control">
                <strong>{{ data.nomor_sampel }}</strong>
              </p>
            </div>

            <template v-if="can_edit_pengiriman">
              <div class="form-group" v-if="can_edit_pengiriman_tujuan">
                <label>
                  Dikirim ke Lab
                  <span style="color:red">*</span>
                </label>
                <div :class="{ 'is-invalid': form.errors.has('lab_pcr_id') }">
                  <div v-for="item in lab_pcr.slice(0, 1)" :key="item.id">
                    <label class="fancy-radio custom-color-green m-0 w-100">
                      <input type="radio" v-model="form.lab_pcr_id" :value="item.id">
                      <span>{{item.text}}</span>
                    </label>
                  </div>
                </div>
                <has-error :form="form" field="lab_pcr_id" />
              </div>

              <div class="form-group">
                <label>
                  Tanggal pengiriman RNA
                  <span style="color:red">*</span>
                </label>
                <date-picker placeholder="Pilih Tanggal" format="d MMMM yyyy" input-class="form-control"
                  :monday-first="true" :wrapper-class="{ 'is-invalid': form.errors.has(`tanggal_pengiriman_rna`) }"
                  v-model="form.tanggal_pengiriman_rna" />
                <has-error :form="form" field="tanggal_pengiriman_rna" />
              </div>

              <div class="form-group">
                <label>
                  Jam pengiriman RNA
                  <span style="color:red">*</span>
                </label>
                <div class="form-group" :class="{ 'is-invalid': form.errors.has(`jam_pengiriman_rna`) }">
                  <masked-input class="form-control" v-model="form.jam_pengiriman_rna" mask="11:11"
                    :class="{ 'is-invalid': form.errors.has('jam_pengiriman_rna') }" />
                </div>
                <has-error :form="form" field="jam_pengiriman_rna" />
              </div>

              <div class="form-group">
                <label>
                  Nama pengirim RNA
                  <span style="color:red">*</span>
                </label>
                <input class="form-control" type="text" v-model="form.nama_pengirim_rna" placeholder="Nama pengirim RNA"
                  :class="{ 'is-invalid': form.errors.has(`nama_pengirim_rna`) }" />
                <has-error :form="form" field="nama_pengirim_rna" />
              </div>

              <div class="form-group">
                <label>Catatan Pengiriman</label>
                <textarea class="form-control" v-model="form.catatan_pengiriman" placeholder=""></textarea>
              </div>
            </template>

            <div class="form-group">
              <button @click="submit()" :disabled="loading" :class="{'btn-loading': loading}"
                class="btn btn-md btn-primary block full-width m-b" type="button">
                <em class="fa fa-check" /> Simpan Perubahan
              </button>
            </div>
          </Ibox>
        </div>
      </div>
    </form>
  </div>
</template>

<script>
  import axios from "axios";
  import Form from "vform";
  import {
    mapGetters
  } from "vuex";
  import MaskedInput from 'vue-masked-input';
  import {
    metodeEkstraksi
  } from '~/assets/js/constant/enum';
  import {
    isFormatTimeValid,
    getAlertTimeMessage
  } from '~/utils';

  export default {
    middleware: "auth",
    computed: mapGetters({
      user: "auth/user",
      lab_pcr: "options/lab_pcr",
    }),
    components: {
      MaskedInput
    },
    async asyncData({
      route,
      store
    }) {
      let error = false;
      let can_edit_pengiriman = true;
      let can_edit_pengiriman_tujuan = true;
      let resp = await axios.get(`/v1/ekstraksi/detail/${route.params.id}`);
      let data = resp.data.data;
      if (!data.ekstraksi) {
        data.ekstraksi = {};
      }
      if (["waiting_sample", "sample_taken", "sample_verified", "sample_valid"].indexOf(data.sampel_status) > -
        1) {
        error = true;
      }
      if (["waiting_sample", "sample_taken", "extraction_sample_extracted", "sample_verified", "sample_valid"]
        .indexOf(data.sampel_status) > -1) {
        can_edit_pengiriman = false;
      }
      if (!store.getters['options/lab_pcr'].length) {
        await store.dispatch('options/fetchLabPCR')
      }
      return {
        error: error,
        data: data,
        can_edit_pengiriman: can_edit_pengiriman,
        can_edit_pengiriman_tujuan: can_edit_pengiriman_tujuan,
        form: new Form({
          tanggal_penerimaan_sampel: data.ekstraksi.tanggal_penerimaan_sampel,
          jam_penerimaan_sampel: data.ekstraksi.jam_penerimaan_sampel,
          petugas_penerima_sampel: data.ekstraksi.petugas_penerima_sampel,
          operator_ekstraksi: data.ekstraksi.operator_ekstraksi,
          tanggal_mulai_ekstraksi: data.ekstraksi.tanggal_mulai_ekstraksi,
          jam_mulai_ekstraksi: data.ekstraksi.jam_mulai_ekstraksi,
          metode_ekstraksi: data.ekstraksi.metode_ekstraksi,
          nama_kit_ekstraksi: data.ekstraksi.nama_kit_ekstraksi,
          alat_ekstraksi: data.ekstraksi.alat_ekstraksi,
          lab_pcr_id: data.lab_pcr_id,
          lab_pcr_nama: data.lab_pcr_nama,
          tanggal_pengiriman_rna: data.ekstraksi.tanggal_pengiriman_rna,
          jam_pengiriman_rna: data.ekstraksi.jam_pengiriman_rna,
          nama_pengirim_rna: data.ekstraksi.nama_pengirim_rna,
          catatan_penerimaan: data.ekstraksi.catatan_penerimaan,
          catatan_pengiriman: data.ekstraksi.catatan_pengiriman,
        }),
        metode: metodeEkstraksi,
        reagenEkstraksi: [],
        loading: false
      };
    },
    watch: {
      'form.metode_ekstraksi': function () {
        this.getReagenEkstraksi(this.form.metode_ekstraksi)
        if (this.form.metode_ekstraksi === this.metode[0]) {
          this.form.nama_kit_ekstraksi = "";
        } else if (this.form.metode_ekstraksi === this.metode[1]) {
          this.form.alat_ekstraksi = "";
        }
      },
    },
    head() {
      return {
        title: "Ubah Data Sampel"
      };
    },
    created() {
      this.getReagenEkstraksi(this.form.metode_ekstraksi)
    },
    methods: {
      dummy() {
        return false
      },
      async submit() {
        if (this.form.metode_ekstraksi === this.metode[0]) {
          this.form.alat_ekstraksi = "";
        } else if (this.form.metode_ekstraksi === this.metode[1]) {
          this.form.nama_kit_ekstraksi = "";
        }
        const isJamPenerimaanSampel = this.form.jam_penerimaan_sampel ? isFormatTimeValid(this.form
          .jam_penerimaan_sampel) : true;
        const isJamMulaiEkstraksi = this.form.jam_mulai_ekstraksi ? isFormatTimeValid(this.form
          .jam_mulai_ekstraksi) : true;
        const isJamPengirimanRna = this.form.jam_pengiriman_rna ? isFormatTimeValid(this.form
          .jam_pengiriman_rna) : true;

        // Submit the form.
        if (!isJamPenerimaanSampel) {
          this.$swal.fire(getAlertTimeMessage('Jam Penerimaan Sampel'));
        } else if (!isJamMulaiEkstraksi) {
          this.$swal.fire(getAlertTimeMessage('Jam Mulai Ekstraksi'));
        } else if (!isJamPengirimanRna) {
          this.$swal.fire(getAlertTimeMessage('Jam Pengiriman RNA'));
        } else {
          try {
            this.loading = true;
            const response = await this.form.post("/v1/ekstraksi/edit/" + this.$route.params.id);
            this.$toast.success(response.data.message, {
              icon: "check",
              iconPack: "fontawesome",
              duration: 5000
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
              this.$swal.fire(
                "Terjadi kesalahan",
                "Silakan hubungi Admin",
                "error"
              );
            }
          }
          this.loading = false;
        }
      },
      async getReagenEkstraksi(metode) {
        const resp = await this.$axios.get('/v1/list/reagen-ekstraksi/')
        const { data } = resp || []
        this.reagenEkstraksi = data.filter(el => el.metode_ekstraksi === metode)
      }
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
    max-width: 450px;
  }

  .title {
    font-size: 1.5rem;
    margin-top: 15px;
    color: #47494e;
    margin-bottom: 8px;
  }

  .description {
    color: #7f828b;
    line-height: 21px;
    margin-bottom: 10px;
  }

  .error-container a {
    color: #7f828b !important;
    text-decoration: none;
  }

  .logo {
    position: fixed;
    left: 12px;
    bottom: 12px;
  }
</style>