<template>
  <div class="wrapper wrapper-content">
    <portal to="title-name">Verifikasi Hasil Pemeriksaan</portal>
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
                Nomor Sampel
              </div>
              <div class="col-md-7 flex-text-center">
                {{data.nomor_sampel || '-'}}
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-5 text-blue flex-text-center">
                Nomor Registrasi
              </div>
              <div class="col-md-7 flex-text-center">
                {{data.nomor_register || '-'}}
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-5 text-blue flex-text-center">
                Tanggal Input Hasil
              </div>
              <div class="col-md-7 flex-text-center">
                {{data.last_pemeriksaan_sampel.tanggal_input_hasil || '-'}}
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-5 text-blue flex-text-center">
                Jam Input Hasil
              </div>
              <div class="col-md-7 flex-text-center">
                {{data.last_pemeriksaan_sampel.jam_input_hasil || '-'}}
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-5 text-blue flex-text-center">
                Nama Pasien
              </div>
              <div class="col-md-7 flex-text-center">
                {{data.pasien.nama_lengkap || '-'}}
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-5 text-blue flex-text-center">
                NIK
              </div>
              <div class="col-md-7 flex-text-center">
                {{data.pasien.nik || '-'}}
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-5 text-blue flex-text-center">
                Tanggal Lahir
              </div>
              <div class="col-md-7 flex-text-center">
                {{data.pasien.tanggal_lahir ? momentFormatDate(data.pasien.tanggal_lahir) : '-'}}
              </div>
            </div>
          </Ibox>
        </div>

        <div class="col-md-6">
          <Ibox title="Hasil Analisa PCR">
            <table class="table table-striped dt-responsive table-bordered" style="width:100%">
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
                    <input class="form-control" type="text" v-model="hasil.target_gen"
                      :class="{ 'is-invalid': form.errors.has(`hasil_deteksi.${$index}.target_gen`) }" />
                    <has-error :form="form" :field="`hasil_deteksi.${$index}.target_gen`" />
                  </td>
                  <td>
                    <input class="form-control" type="text" v-model="hasil.ct_value"
                      :class="{ 'is-invalid': form.errors.has(`hasil_deteksi.${$index}.ct_value`) }" />
                    <has-error :form="form" :field="`hasil_deteksi.${$index}.ct_value`" />
                  </td>
                  <td>
                    <button class="btn btn-sm btn-danger" type="button" @click.prevent="removeHasilDeteksi($index)">
                      <em class="uil-trash" />
                    </button>
                  </td>
                </tr>
                <tr>
                  <td></td>
                  <td colspan="2">
                    <button class="btn btn-sm btn-secondary" type="button" @click.prevent="addHasilDeteksi()">
                      <em class="fa fa-plus" /> Tambah Hasil CT
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>

            <div class="form-group">
              <label class="text-blue">
                Kesimpulan Pemeriksaan
                <span style="color:red">*</span>
              </label>
              <div :class="{ 'is-invalid': form.errors.has('kesimpulan_pemeriksaan') }">
                <div>
                  <label>
                    <input type="radio" v-model="form.kesimpulan_pemeriksaan" value="positif">
                    <span>>POSITIF</span>
                  </label>
                </div>
                <div>
                  <label>
                    <input type="radio" v-model="form.kesimpulan_pemeriksaan" value="negatif">
                    <span>NEGATIF</span>
                  </label>
                </div>
                <div>
                  <label>
                    <input type="radio" v-model="form.kesimpulan_pemeriksaan" value="inkonklusif">
                    <span>INKONKLUSIF</span>
                  </label>
                </div>
              </div>
              <has-error :form="form" field="kesimpulan_pemeriksaan" />
            </div>

            <input type="hidden" v-model="form.last_pemeriksaan_id">

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
                {{ 'Verifikasi' }}
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
  import {
    momentFormatDate
  } from '~/utils'

  export default {
    middleware: "auth",
    components: {
      Dropzone
    },
    data() {
      return {
        loading: false,
        momentFormatDate
      };
    },
    async asyncData({
      route,
      store
    }) {
      let resp = await axios.get("/v1/verifikasi/detail/" + route.params.id);
      let data = resp.data.data;

      if (!data.pasien) {
        data.pasien = {};
      }

      let form = new Form({
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
      };
    },
    head() {
      return {
        title: "Verifikasi hasil pemeriksaan"
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
          const response = await this.form.post("/v1/verifikasi/edit-status-sampel/" + this.$route.params
            .id);
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
      },
      addHasilDeteksi() {
        this.form.hasil_deteksi.push({
          tanggalsampel: new Date(),
          pukulsampel: new Date().getHours() * 100 + new Date().getMinutes()
        });
      },
      removeHasilDeteksi(index) {
        if (this.form.hasil_deteksi.length <= 1) {
          this.$toast.error("Hasil deteksi minimal satu", {
            duration: 5000
          });
          return;
        }
        this.form.hasil_deteksi.splice(index, 1);
      },
      onFileSuccess(file, resp) {
        this.form.grafik.push(resp.url);
      }
    }
  };
</script>