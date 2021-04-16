<template>
  <div class="wrapper wrapper-content">
    <portal to="title-name">Pengiriman Sampel Ekstraksi RNA ke Lab</portal>
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
          <sample-picker v-model="form.samples" ref="sample_picker" sampel-status="extraction_sample_extracted"
            title="Daftar Sampel yang Akan Dikirim" :selectedNomorSampels="selectedNomorSampels"></sample-picker>
        </div>
        <div class="col-md-10">
          <Ibox title="Informasi Ekstraksi">
            <div class="form-group row">
              <div class="col-md-3 flex-text-center">
                Dikirim ke Lab
                <span style="color:red">*</span>
              </div>
              <div class="col-md-8" :class="{ 'is-invalid': form.errors.has('lab_pcr_id') }">
                <label>
                  <input type="radio" v-model="form.lab_pcr_id" :value="lab_pcr[0].id">
                  <span>{{lab_pcr[0].text}}</span>
                </label>
              </div>
              <has-error :form="form" field="lab_pcr_id" />
            </div>
            <div class="form-group row" v-if="form.lab_pcr_id =='999999'">
              <div class="col-md-3 flex-text-center">
                Nama Lab
                <span style="color:red">*</span>
              </div>
              <div class="col-md-8">
                <input class="form-control" type="text" v-model="form.lab_pcr_nama"
                  :class="{ 'is-invalid': form.errors.has(`lab_pcr_nama`) }" />
                <has-error :form="form" field="lab_pcr_nama" />
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-3 flex-text-center">
                Tanggal pengiriman RNA
                <span style="color:red">*</span>
              </div>
              <div class="col-md-8">
                <date-picker placeholder="Pilih Tanggal" format="d MMMM yyyy" input-class="form-control"
                  :monday-first="true" :wrapper-class="{ 'is-invalid': form.errors.has(`tanggal_pengiriman_rna`) }"
                  v-model="form.tanggal_pengiriman_rna" />
                <has-error :form="form" field="tanggal_pengiriman_rna" />
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-3 flex-text-center">
                Jam pengiriman RNA
                <span style="color:red">*</span>
              </div>
              <div class="col-md-8">
                <masked-input class="form-control" v-model="form.jam_pengiriman_rna" mask="11:11"
                  :class="{ 'is-invalid': form.errors.has('jam_pengiriman_rna') }" />
                <has-error :form="form" field="jam_pengiriman_rna" />
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-3 flex-text-center">
                Nama pengirim RNA
                <span style="color:red">*</span>
              </div>
              <div class="col-md-8">
                <input class="form-control" type="text" v-model="form.nama_pengirim_rna" placeholder="Nama pengirim RNA"
                  :class="{ 'is-invalid': form.errors.has(`nama_pengirim_rna`) }" />
                <has-error :form="form" field="nama_pengirim_rna" />
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-3 flex-text-center">
                Catatan Pengiriman
              </div>
              <div class="col-md-8">
                <textarea class="form-control" v-model="form.catatan_pengiriman"
                  :class="{ 'is-invalid': form.errors.has(`catatan_pengiriman`) }"></textarea>
                <has-error :form="form" field="catatan_pengiriman" />
              </div>
            </div>

            <div class="form-group row">
              <div class="col-md-3 flex-text-center" />
              <div class="col-md-8">
                <button @click="submit()" :disabled="loading" :class="{'btn-loading': loading}"
                  class="btn btn-md btn-primary block pull-right mt-4 m-b" type="button">
                  <em class="fa fa-check" /> Kirim Sampel
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
  import axios from "axios";
  import SamplePicker from '~/components/SamplePicker'
  import {
    mapGetters
  } from "vuex";
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
    computed: mapGetters({
      user: "auth/user",
      lab_pcr: "options/lab_pcr",
    }),
    data() {

      let selectedNomorSampels = this.$store.state.ekstraksi_dilakukan.selectedSampels;

      return {
        form: new Form({
          tanggal_pengiriman_rna: new Date(),
          jam_pengiriman_rna: this.getTimeNow(),
          nama_pengirim_rna: "",
          catatan_pengiriman: "",
          lab_pcr_id: "1",
          lab_pcr_nama: "",
          samples: []
        }),
        selectedNomorSampels,
        loading: false,
      };
    },
    async asyncData({
      store
    }) {
      if (!store.getters['options/lab_pcr'].length) {
        await store.dispatch('options/fetchLabPCR')
      }
      return {}
    },
    head() {
      return {
        title: "Pengiriman Sampel Ekstraksi"
      };
    },
    methods: {
      getTimeNow() {
        let h = ("" + new Date().getHours()).padStart(2, "0");
        return h + ":" + ("" + new Date().getMinutes()).padStart(2, "0");
      },
      dummy() {
        return false;
      },
      initForm() {
        this.form = new Form({
          tanggal_pengiriman_rna: new Date(),
          jam_pengiriman_rna: this.getTimeNow(),
          nama_pengirim_rna: "",
          catatan_pengiriman: "",
          tanggal_mulai_ekstraksi: new Date(),
          jam_mulai_ekstraksi: this.getTimeNow(),
          lab_pcr_id: this.form.lab_pcr_id,
          samples: []
        });
      },
      async submit() {
        const isJamPengirimanRna = this.form.jam_pengiriman_rna ? isFormatTimeValid(this.form
          .jam_pengiriman_rna) : true;

        // Submit the form.
        if (!isJamPengirimanRna) {
          this.$swal.fire(getAlertTimeMessage('Jam Pengiriman RNA'));
        } else {
          try {
            if (!this.$refs.sample_picker.isFormValid()) {
              return
            }
            this.loading = true;
            const response = await this.form.post("/v1/ekstraksi/kirim");

            // Clear selected sampels
            this.$store.commit('ekstraksi_dilakukan/clear');

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
          this.loading = false;
        }
      },
    },
  };
</script>