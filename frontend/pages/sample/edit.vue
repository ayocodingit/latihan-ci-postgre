<template>
  <div class="wrapper wrapper-content">
    <portal to="title-name">Edit Data Sampel</portal>
    <portal to="title-action">
      <div class="title-action">
        <a href="#" @click.prevent="$router.back()" class="btn btn-black">
          <em class="uil-arrow-left" /> Kembali
        </a>
      </div>
    </portal>

    <div class="row">
      <div class="col-lg-12">
        <h4 class="header-title mt-0 mb-1" v-if="selected_reg.reg_no!=null">
          No. Registrasi : #{{selected_reg.reg_no}}
        </h4>
        <h4 class="header-title mt-0 mb-1" v-if="selected_reg.reg_nik!=null">
          No. Induk Kependudukan : {{selected_reg.reg_nik}}
        </h4>
        <form @submit.prevent="submit" @keydown="form.onKeydown($event)">
          <input type="hidden" v-model="form.pen_noreg" />
          <input type="hidden" v-model="form.pen_nik" />

          <Ibox title="Penerimaan atau Pengambilan Sampel">
            <div class="col-md-12">
              <div class="form-group row mt-2">
                <label class="col-md-3">
                  Sumber Sampel<span style="color:red">*</span>
                </label>
                <div class="col-md-9">
                  <div v-if="this.form.id == 'take'">
                    <input type="hidden" v-model="form.pen_sampel_sumber" value="Mandiri">
                    <label> Mandiri </label>
                  </div>
                  <div v-if="this.form.id != 'take'">
                    <div class="form-check form-check-inline" v-if="this.form.pen_sampel_sumber == 'Mandiri'"
                      :class="{ 'is-invalid': form.errors.has('pen_sampel_sumber') }">
                      <input class="form-check-input" type="radio" v-model="form.pen_sampel_sumber" value="Mandiri">
                      <span>Mandiri</span>
                    </div>
                    <div class="form-check form-check-inline" v-if="this.form.pen_sampel_sumber != 'Mandiri'"
                      :class="{ 'is-invalid': form.errors.has('pen_sampel_sumber') }">
                      <input class="form-check-input" type="radio" v-model="form.pen_sampel_sumber" value="Rujukan Dinkes">
                      <span>Rujukan Dinkes</span>
                    </div>
                    <div class="form-check form-check-inline" v-if="this.form.pen_sampel_sumber != 'Mandiri'">
                      <input class="form-check-input" type="radio" v-model="form.pen_sampel_sumber" value="Rujukan RS">
                      <span>Rujukan RS</span>
                    </div>
                  </div>
                  <has-error :form="form" field="pen_sampel_sumber" />
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3">
                  Petugas Penerima Sampel
                </label>
                <div class="col-md-9">
                  <dynamic-input :form="form" field="pen_penerima_sampel" :options="this.optionsPenerimaSampel"
                    :hasLainnya="true" ref="pen_penerima_sampel" placeholder="Petugas Penerima Sampel">
                  </dynamic-input>
                  <has-error :form="form" field="pen_penerima_sampel" />
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 col-form-label" for="gejlain">
                  Catatan Lain
                </label>
                <div class="col-md-9">
                  <textarea class="form-control" rows="5" v-model="form.pen_catatan"
                    :class="{ 'is-invalid': form.errors.has('pen_catatan') }"
                    placeholder="Contoh: 'kualitas sampel kurang baik', 'jumlah material terlalu sedikit', 'pengepakan atau pengiriman sampel kurang layak', 'pengambilan serum akut/konvalesen tidak sesuai rentang waktu', dll."></textarea>
                  <has-error :form="form" field="pen_catatan" />
                </div>
              </div>
            </div>
          </Ibox>

          <Ibox title="Sampel">
            <div class="col-md-12">
              <div class="form-group row mt-2">
                <label class="col-md-3">
                  Nomor Sampel<span style="color:red">*</span>
                </label>
                <div class="col-md-9">
                  <label class="form-control form-disabled">
                    {{ form.nomorsampel }}
                  </label>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3">
                  Jenis Sampel<span style="color:red">*</span>
                </label>
                <div class="col-md-9">
                  <select class="form-control" v-model="form.sam_jenis_sampel"
                    :class="{ 'is-invalid': form.errors.has(`sam_jenis_sampel`) }">
                    <option :value="js.id" v-for="(js, $index2) in jenis_sampel" :key="$index2">
                      {{ js.text }}
                    </option>
                  </select>
                  <has-error :form="form" :field="`sam_jenis_sampel`" />
                  <div v-if="form.sam_jenis_sampel == 999999">
                    <small for="specify">Jenis Lainnya (isi apabila tidak tercantum diatas)</small>
                    <input type="text" class="form-control" v-model="form.sam_namadiluarjenis"
                      placeholder="isi apabila tidak tercantum"
                      :class="{ 'is-invalid': form.errors.has(`sam_namadiluarjenis`) }" preventForm />
                    <has-error :form="form" :field="`sam_namadiluarjenis`" />
                  </div>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3">
                  Kondisi Sampel<span style="color:red">*</span>
                </label>
                <div class="col-md-9">
                  <dynamic-input :form="form" field="petugas_pengambil"
                    :options="['Baik','Sampel Sedikit','Tabung Rusak']" :hasLainnya="true"
                    placeholder="Masukkan kondisi sampel" preventForm></dynamic-input>
                  <has-error :form="form" :field="`petugas_pengambil`" />
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3">
                  Tanggal Pengambilan<span style="color:red">*</span>
                </label>
                <div class="col-md-9">
                  <date-picker placeholder="Pilih Tanggal" format="d MMMM yyyy" input-class="form-control"
                    :monday-first="true" :wrapper-class="{ 'is-invalid': form.errors.has(`tanggalsampel`) }"
                    v-model="form.tanggalsampel" />
                  <has-error :form="form" :field="`tanggalsampel`" />
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3">
                  Pukul<span style="color:red">*</span>
                </label>
                <div class="col-md-9">
                  <masked-input class="form-control" v-model="form.pukulsampel" mask="11:11"
                    :class="{ 'is-invalid': form.errors.has('pukulsampel') }" />
                  <has-error :form="form" :field="`pukulsampel`" />
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3">
                  Jenis VTM<span style="color:red">*</span>
                </label>
                <div class="col-md-9">
                  <select class="form-control" v-model="form.vtm"
                    :class="{ 'is-invalid': form.errors.has(`vtm`) }">
                    <option :value="js.text" v-for="(js, $index) in jenis_vtm" :key="$index">
                      {{ js.text }}
                    </option>
                    <option> Tidak Diketahui </option>
                  </select>
                  <has-error :form="form" :field="`vtm`" />
                </div>
              </div>
            </div>
          </Ibox>

          <div class="col-md-12 mb-4">
            <v-button :loading="form.busy" class="btn btn-primary">
              <em class="fa fa-check" /> Simpan Sampel
            </v-button>
            <a href="#" @click.prevent="$router.back()" class="btn btn-clear">
              <em class="fa fa-close" /> Batal
            </a>
          </div>
        </form>

      </div>
    </div>
  </div>
</template>

<script>
  import Form from "vform";
  import axios from 'axios';
  import {
    mapGetters
  } from "vuex";
  import MaskedInput from 'vue-masked-input';
  import {
    isFormatTimeValid,
    getAlertTimeMessage
  } from '~/utils';
  import {
    optionsPenerimaSampel
  } from '~/assets/js/constant/enum';

  export default {
    middleware: "auth",
    computed: mapGetters({
      jenis_sampel: "options/jenis_sampel",
      jenis_vtm: "options/jenis_vtm",
    }),
    components: {
      MaskedInput
    },
    data() {
      return {
        optionsPenerimaSampel
      }
    },
    async asyncData({
      route,
      store
    }) {
      let error = false;
      let resp = await axios.get("/sample/edit/" + route.params.id);
      if (!store.getters['options/jenis_sampel'].length) {
        await store.dispatch('options/fetchJenisSampel')
      }
      if (!store.getters['options/jenis_vtm'].length) {
        await store.dispatch('options/fetchJenisVTM')
      }
      let data = resp.data.result;
      let takeAction = data.sampel_status == "waiting_sample";
      let currentTime = ("0" + (new Date).getHours()).slice(-2) + ':' + ("0" + (new Date).getMinutes()).slice(-
        2);
      let sampelSumber = data.jenis_registrasi === 'mandiri' ? 'Mandiri' : data.sumber_sampel
      return {
        // data:null,
        form: new Form({
          id: data.pengambilan_sampel_id,
          pen_noreg: null,
          pen_nik: null,
          pen_sampel_sumber: takeAction ? "Mandiri" : sampelSumber,
          pen_penerima_sampel: data.penerima_sampel,
          pen_catatan: data.catatan,
          sampel_id: data.sampel_id,
          nomorsampel: data.nomorsampel,
          pukulsampel: takeAction ? currentTime : data.pukulsampel,
          tanggalsampel: takeAction ? new Date : data.tanggalsampel,
          sam_jenis_sampel: takeAction ? "1" : data.sam_jenis_sampel,
          petugas_pengambil: takeAction ? "Baik" : data.petugas_pengambil,
          vtm: data.jenis_vtm,
        }),
        selected_reg: {}
      };
    },
    methods: {
      initForm() {
        this.form = new Form({
          pen_noreg: null,
          pen_nik: null,
          pen_sampel_sumber: null,
          pen_penerima_sampel: null,
          pen_catatan: null,
          tanggalsampel: null,
          pukulsampel: null,
          sam_jenis_sampel: '1',
          petugas_pengambil: 'Baik',
          vtm: null,

        })
      },
      async submit() {
        const isPukulSampel = this.form.pukulsampel ? isFormatTimeValid(this.form.pukulsampel) : true;

        // Submit the form.
        if (!isPukulSampel) {
          this.$swal.fire(getAlertTimeMessage('waktu'));
        } else {
          try {
            const response = await this.form.post("/sample/update/" + this.form.id);
            this.$swal.fire("Success", "Berhasil update data", "success");
            if (this.$route.name == 'sample.take') {
              this.$router.push('/sample')
            }else{
              this.$router.push('/sample/daftar')
            }
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
      }
    },
    head() {
      return {
        title: "Pengambilan / Penerimaan Sampel Baru"
      };
    },
  };
</script>