<template>
  <div class="wrapper wrapper-content">
    <portal to="title-name">Penerimaan dan Ekstraksi RNA Baru</portal>
    <portal to="title-action">
      <div class="title-action">
        <a href="#" @click.prevent="$router.back()" class="btn btn-black">
          <em class="uil-arrow-left" /> Kembali
        </a>
      </div>
    </portal>
    <form @submit.prevent="dummy">
      <div class="row">
        <div class="col-md-10">
          <sample-picker v-model="form.samples" ref="sample_picker" sampel-status="sample_taken"
            title="Daftar Sampel yang Diterima" :selectedNomorSampels="selectedNomorSampels">
          </sample-picker>
        </div>
        <div class="col-md-10">
          <Ibox title="Informasi Ekstraksi">
            <div class="form-group row">
              <div class="col-md-3 flex-text-center">
                Tanggal penerimaan sampel
                <span style="color:red">*</span>
              </div>
              <div class="col-md-8">
                <date-picker placeholder="Pilih Tanggal" format="d MMMM yyyy" input-class="form-control"
                  :monday-first="true" :wrapper-class="{ 'is-invalid': form.errors.has(`tanggal_penerimaan_sampel`) }"
                  v-model="form.tanggal_penerimaan_sampel" />
                <has-error :form="form" field="tanggal_penerimaan_sampel" />
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-3 flex-text-center">
                Jam penerimaan sampel
                <span style="color:red">*</span>
              </div>
              <div class="col-md-8">
                <masked-input class="form-control" v-model="form.jam_penerimaan_sampel" mask="11:11"
                  :class="{ 'is-invalid': form.errors.has('jam_penerimaan_sampel') }" />
                <has-error :form="form" field="jam_penerimaan_sampel" />
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-3 flex-text-center">
                Petugas penerima sampel
                <span style="color:red">*</span>
              </div>
              <div class="col-md-8">
                <input class="form-control" type="text" v-model="form.petugas_penerima_sampel"
                  placeholder="Petugas penerima sampel"
                  :class="{ 'is-invalid': form.errors.has(`petugas_penerima_sampel`) }" />
                <has-error :form="form" field="petugas_penerima_sampel" />
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-3 flex-text-center">
                Catatan penerimaan
              </div>
              <div class="col-md-8">
                <textarea class="form-control" v-model="form.catatan_penerimaan"
                  :class="{ 'is-invalid': form.errors.has(`catatan_penerimaan`) }"></textarea>
                <has-error :form="form" field="catatan_penerimaan" />
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-3 flex-text-center">
                Operator ekstraksi
                <span style="color:red">*</span>
              </div>
              <div class="col-md-8">
                <input class="form-control" type="text" v-model="form.operator_ekstraksi" placeholder="Operator PCR"
                  :class="{ 'is-invalid': form.errors.has(`operator_ekstraksi`) }" />
                <has-error :form="form" field="operator_ekstraksi" />
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-3 flex-text-center">
                Tanggal mulai Ekstraksi
                <span style="color:red">*</span>
              </div>
              <div class="col-md-8">
                <date-picker placeholder="Pilih Tanggal" format="d MMMM yyyy" input-class="form-control"
                  :monday-first="true" :wrapper-class="{ 'is-invalid': form.errors.has(`tanggal_mulai_ekstraksi`) }"
                  v-model="form.tanggal_mulai_ekstraksi" />
                <has-error :form="form" field="tanggal_mulai_ekstraksi" />
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-3 flex-text-center">
                Jam mulai Ekstraksi
                <span style="color:red">*</span>
              </div>
              <div class="col-md-8">
                <masked-input class="form-control" v-model="form.jam_mulai_ekstraksi" mask="11:11"
                  :class="{ 'is-invalid': form.errors.has('jam_mulai_ekstraksi') }" />
                <has-error :form="form" field="jam_mulai_ekstraksi" />
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-3 flex-text-center">
                Metode ekstraksi
                <span style="color:red">*</span>
              </div>
              <div class="col-md-8">
                <select v-model="form.metode_ekstraksi" name="metode_ekstraksi" required class="form-control">
                  <option v-for="(item, idx) in metode" :key="idx" :value="item">
                    {{ item }}
                  </option>
                </select>
              </div>
            </div>
            <div class="form-group row" v-show="form.metode_ekstraksi === metode[0]">
              <div class="col-md-3 flex-text-center">
                Reagen ekstraksi
                <span style="color:red">*</span>
              </div>
              <div class="col-md-8">
                <select v-model="form.nama_kit_ekstraksi" name="nama_kit_ekstraksi" required class="form-control">
                  <option v-for="(item, idx) in reagenEkstraksi" :key="idx" :value="item.nama">
                    {{ item.nama }}
                  </option>
                </select>
              </div>
            </div>
            <div class="form-group row" v-show="form.metode_ekstraksi === metode[1]">
              <div class="col-md-3 flex-text-center">
                Reagen Ekstraksi
                <span style="color:red">*</span>
              </div>
              <div class="col-md-8">
                <select v-model="form.alat_ekstraksi" name="alat_ekstraksi" required class="form-control">
                  <option v-for="(item, idx) in reagenEkstraksi" :key="idx" :value="item.nama">
                    {{ item.nama }}
                  </option>
                </select>
              </div>
            </div>

            <div class="form-group row">
              <div class="col-md-3 flex-text-center" />
              <div class="col-md-8">
                <button @click="submit()" :disabled="loading" :class="{'btn-loading': loading}"
                  class="btn btn-md btn-primary block pull-right mt-4 m-b" type="button">
                  <em class="fa fa-check" /> Terima Sampel
                </button>
              </div>
            </div>
          </Ibox>
        </div>
      </div>
    </form>
  </div>
</template>

<script>
  import Form from "vform";
  import SamplePicker from '~/components/SamplePicker';
  import MaskedInput from 'vue-masked-input';
  import {
    metodeEkstraksi,
  } from '~/assets/js/constant/enum';
  import {
    isFormatTimeValid,
    getAlertTimeMessage
  } from '~/utils';

  export default {
    middleware: "auth",
    components: {
      SamplePicker,
      MaskedInput,
    },
    data() {
      let selectedNomorSampels = this.$store.state.ekstraksi_penerimaan.selectedSampels;

      return {
        form: new Form({
          tanggal_penerimaan_sampel: new Date(),
          jam_penerimaan_sampel: this.getTimeNow(),
          petugas_penerima_sampel: "",
          catatan_penerimaan: "",
          operator_ekstraksi: "",
          tanggal_mulai_ekstraksi: new Date(),
          jam_mulai_ekstraksi: this.getTimeNow(),
          metode_ekstraksi: "",
          nama_kit_ekstraksi: "",
          alat_ekstraksi: "",
          samples: []
        }),
        selectedNomorSampels,
        metode: metodeEkstraksi,
        reagenEkstraksi: [],
        loading: false,
      };
    },
    head() {
      return {
        title: "Penerimaan Sampel"
      };
    },
    watch: {
      'form.metode_ekstraksi': function () {
        this.getReagenEkstraksi(this.form.metode_ekstraksi)
        if (this.form.metode_ekstraksi === this.metode[0]) {
          this.form.nama_kit_ekstraksi = ''
        } else if (this.form.metode_ekstraksi === this.metode[1]) {
          this.form.alat_ekstraksi = ''
        } else {
          this.form.nama_kit_ekstraksi = ''
          this.form.alat_ekstraksi = ''
        }
      },
    },
    methods: {
      getTimeNow() {
        let h = ('' + new Date().getHours()).padStart(2, '0')
        return h + ':' + ('' + new Date().getMinutes()).padStart(2, '0')
      },
      dummy() {
        return false;
      },
      initForm() {
        this.form = new Form({
          tanggal_penerimaan_sampel: new Date(),
          jam_penerimaan_sampel: this.getTimeNow(),
          petugas_penerima_sampel: "",
          catatan_penerimaan: "",
          operator_ekstraksi: "",
          tanggal_mulai_ekstraksi: new Date(),
          jam_mulai_ekstraksi: this.getTimeNow(),
          metode_ekstraksi: "",
          nama_kit_ekstraksi: "",
          alat_ekstraksi: "",
          samples: []
        })
      },
      async submit() {
        if (this.form.metode_ekstraksi === this.metode[0]) {
          this.form.alat_ekstraksi = ""
        } else if (this.form.metode_ekstraksi === this.metode[1]) {
          this.form.nama_kit_ekstraksi = ""
        }
        const isJamPenerimaanSampel = this.form.jam_penerimaan_sampel ? isFormatTimeValid(this.form
          .jam_penerimaan_sampel) : true;
        const isJamMulaiEkstraksi = this.form.jam_mulai_ekstraksi ? isFormatTimeValid(this.form
          .jam_mulai_ekstraksi) : true;

        // Submit the form.
        if (!isJamPenerimaanSampel) {
          this.$swal.fire(getAlertTimeMessage('Jam Penerimaan Sampel'));
        } else if (!isJamMulaiEkstraksi) {
          this.$swal.fire(getAlertTimeMessage('Jam Mulai Ekstraksi'));
        } else {
          try {
            if (!this.$refs.sample_picker.isFormValid()) {
              return
            }
            this.loading = true
            const response = await this.form.post("/v1/ekstraksi/terima");

            // Clear selected sampels
            this.$store.commit('ekstraksi_penerimaan/clear');

            this.$toast.success(response.data.message, {
              icon: "check",
              iconPack: "fontawesome",
              duration: 5000
            });
            this.initForm();
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
          this.loading = false
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