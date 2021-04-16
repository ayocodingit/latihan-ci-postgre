<template>
  <div class="wrapper wrapper-content">
    <portal to="title-name">Penerimaan RNA Baru</portal>
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
          <sample-picker v-model="form.samples" :disable-input="staticInput" ref="sample_picker"
            sampel-status="extraction_sample_sent" title="Sampel yang Diterima"
            :selectedNomorSampels="selectedNomorSampels"></sample-picker>
        </div>
        <div class="col-md-10">
          <Ibox title="Informasi PCR">
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
                <input class="form-control" type="text" v-model="form.petugas_penerima_sampel_rna"
                  placeholder="Petugas penerima sampel"
                  :class="{ 'is-invalid': form.errors.has(`petugas_penerima_sampel_rna`) }" />
                <has-error :form="form" field="petugas_penerima_sampel_rna" />
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-3 flex-text-center">
                Catatan Penerimaan
              </div>
              <div class="col-md-8">
                <textarea class="form-control" v-model="form.catatan_penerimaan"
                  :class="{ 'is-invalid': form.errors.has(`catatan_penerimaan`) }"></textarea>
                <has-error :form="form" field="catatan_penerimaan" />
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-3 flex-text-center">
                Operator PCR
                <span style="color:red">*</span>
              </div>
              <div class="col-md-8">
                <input class="form-control" type="text" v-model="form.operator_real_time_pcr" placeholder="Operator PCR"
                  :class="{ 'is-invalid': form.errors.has(`operator_real_time_pcr`) }" />
                <has-error :form="form" field="operator_real_time_pcr" />
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-3 flex-text-center">
                Tanggal mulai PCR
                <span style="color:red">*</span>
              </div>
              <div class="col-md-8">
                <date-picker placeholder="Pilih Tanggal" format="d MMMM yyyy" input-class="form-control"
                  :monday-first="true" :wrapper-class="{ 'is-invalid': form.errors.has(`tanggal_mulai_pemeriksaan`) }"
                  v-model="form.tanggal_mulai_pemeriksaan" />
                <has-error :form="form" field="tanggal_mulai_pemeriksaan" />
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-3 flex-text-center">
                Jam mulai PCR
                <span style="color:red">*</span>
              </div>
              <div class="col-md-8">
                <masked-input class="form-control" v-model="form.jam_mulai_pcr" mask="11:11"
                  :class="{ 'is-invalid': form.errors.has('jam_mulai_pcr') }" />
                <has-error :form="form" field="jam_mulai_pcr" />
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-3 flex-text-center">
                Jam selesai PCR
                <span style="color:red">*</span>
              </div>
              <div class="col-md-8">
                <masked-input class="form-control" v-model="form.jam_selesai_pcr" mask="11:11"
                  :class="{ 'is-invalid': form.errors.has('jam_selesai_pcr') }" />
                <has-error :form="form" field="jam_selesai_pcr" />
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-3 flex-text-center">
                Reagen PCR
                <span style="color:red">*</span>
              </div>
              <div class="col-md-8">
                <select v-model="form.nama_kit_pemeriksaan" name="nama_kit_pemeriksaan" required class="form-control">
                  <option v-for="(item, idx) in reagenPcr" :key="idx" :value="item.nama">
                    {{ item.nama }}
                  </option>
                </select>
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-3 flex-text-center">
                Nilai CT Normal
                <span style="color:red">*</span>
              </div>
              <div class="col-md-8">
                <input class="form-control" type="text" v-model="form.ct_normal" placeholder="Nilai CT Normal"
                  :class="{ 'is-invalid': form.errors.has(`ct_normal`) }" />
                <has-error :form="form" field="ct_normal" />
              </div>
            </div>

            <div class="form-group row">
              <div class="col-md-3 flex-text-center" />
              <div class="col-md-8">
                <button @click="submit()" :disabled="loading" :class="{'btn-loading': loading}"
                  class="btn btn-md btn-primary block full-width m-b" type="button">
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
      let selectedNomorSampels = this.$store.state.pcr_penerimaan.selectedSampels;
      return {
        staticInput: false,
        form: new Form({
          tanggal_penerimaan_sampel: new Date(),
          jam_penerimaan_sampel: this.getTimeNow(),
          petugas_penerima_sampel_rna: "",
          catatan_penerimaan: "",
          operator_real_time_pcr: "",
          tanggal_mulai_pemeriksaan: new Date(),
          jam_mulai_pcr: this.getTimeNow(),
          jam_selesai_pcr: this.getTimeNow(),
          nama_kit_pemeriksaan: "",
          ct_normal: "",
          samples: [],
        }),
        reagenPcr: [],
        loading: false,
        input_nomor_sampel: "",
        selectedNomorSampels
      };
    },
    head() {
      return {
        title: "Penerimaan Sampel RNA"
      };
    },
    watch: {
      'form.nama_kit_pemeriksaan': function () {
        const findCT = this.reagenPcr.find((el) => el.nama === this.form.nama_kit_pemeriksaan)
        if (findCT) {
          this.form.ct_normal = findCT.ct_normal
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
          petugas_penerima_sampel_rna: "",
          catatan_penerimaan: "",
          operator_real_time_pcr: "",
          tanggal_mulai_pemeriksaan: new Date(),
          jam_mulai_pcr: this.getTimeNow(),
          jam_selesai_pcr: this.getTimeNow(),
          nama_kit_pemeriksaan: "",
          ct_normal: "",
          samples: []
        })
        this.input_nomor_sampel = ""
        this.reagenPcr = []
      },
      async submit() {
        const isJamPenerimaanSampel = this.form.jam_penerimaan_sampel ? isFormatTimeValid(this.form
          .jam_penerimaan_sampel) : true;
        const isJamMulaiPcr = this.form.jam_mulai_pcr ? isFormatTimeValid(this.form.jam_mulai_pcr) : true;
        const isJamSelesaiPcr = this.form.jam_selesai_pcr ? isFormatTimeValid(this.form.jam_selesai_pcr) : true;

        // Submit the form.
        if (!isJamPenerimaanSampel) {
          this.$swal.fire(getAlertTimeMessage('Jam Penerimaan Sampel'));
        } else if (!isJamMulaiPcr) {
          this.$swal.fire(getAlertTimeMessage('Jam Jam Mulai PCR dengan'));
        } else if (!isJamSelesaiPcr) {
          this.$swal.fire(getAlertTimeMessage('Jam Selesai PCR'));
        } else {
          try {
            if (!this.$refs.sample_picker.isFormValid()) {
              return
            }
            this.loading = true
            const response = await this.form.post("/v1/pcr/terima");

            // Clear selected sampels
            this.$store.commit('pcr_penerimaan/clear');

            this.$toast.success(response.data.message, {
              icon: "check",
              iconPack: "fontawesome",
              duration: 5000
            });
            if (this.staticInput) {
              this.$router.back();
            } else {
              this.initForm();
            }
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
      async getReagenPcr() {
        const resp = await this.$axios.get('/v1/list/reagen-pcr/')
        const { data } = resp || []
        this.reagenPcr = data || []
      }
    },
    created() {
      this.getReagenPcr()
      if (this.$route.params && this.$route.params.nomor_sampels) {
        this.form.samples = this.$route.params.nomor_sampels.split(',').map((nomor_sampel) => {
          return {
            nomor_sampel: nomor_sampel,
            valid: true,
            error: ""
          }
        })
        this.staticInput = true
      }
    }
  };
</script>