<template>
  <div class="wrapper wrapper-content">
    <portal to="title-name">
      Update Registrasi Pasien Mandiri
    </portal>
    <portal to="title-action">
      <div class="title-action">
        <nuxt-link to="/registrasi/mandiri" class="btn btn-black">
          <em class="uil-arrow-left" /> Kembali
        </nuxt-link>
      </div>
    </portal>

    <div class="row">
      <div class="col-lg-12">
        <form @submit.prevent="submit" @keydown="form.onKeydown($event)">
          <Ibox title="Penerimaan atau Pengambilan Sampel">
            <div class="form-group row">
              <div class="col-md-3 col-lg-2 flex-text-center">
                Nomor Registrasi
                <span style="color:red">*</span>
              </div>
              <div class="col-md-8 col-lg-6" :class="{ 'is-invalid': form.errors.has('reg_no') }">
                <input class="form-control" type="text" name="reg_no" placeholder="Nomor Registrasi" required
                  v-model="form.reg_no" disabled />
                <has-error :form="form" field="reg_no" />
              </div>
            </div>

            <div class="form-group row">
              <div class="col-md-3 col-lg-2 flex-text-center">Kewarganegaraan </div>
              <div class="col-md-8 col-lg-6">
                <select v-model="form.reg_kewarganegaraan" class="multisteps-form__input form-control"
                  name="reg_kewarganegaraan">
                  <option value="WNI">WNI</option>
                  <option value="WNA">WNA</option>
                </select>
              </div>
            </div>

            <div class="form-group row">
              <div class="col-md-3 col-lg-2 flex-text-center">
                Kategori
              </div>
              <div class="col-md-8 col-lg-6">
                <div class="form-check form-check-inline">
                  <input class="form-check-input" v-model="form.reg_sumberpasien" value="Umum" type="radio">
                  <span>Umum</span>
                </div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" v-model="form.reg_sumberpasien" value="Other" type="radio">
                  <span>Isian</span>
                </div>
              </div>
            </div>
            <div class="form-group row" v-if="form.reg_sumberpasien=='Other'">
              <label for="" class="col-md-3 col-lg-2 flex-text-center"></label>
              <div class="col-md-8 col-lg-6">
                <input type="text" name="reg_sumberpasien_isian" placeholder="Ketikkan Kategori" id=""
                  class="form-control" v-model="form.reg_sumberpasien_isian">
              </div>
            </div>
          </Ibox>

          <Ibox title="Identitas Pasien">
            <div class="form-group row">
              <div class="col-lg-2 col-md-3 flex-text-center">
                {{ $t('label.pasien.status') }}
              </div>
              <div class="col-md-8 col-lg-6">
                <select class="form-control" :class="{ 'is-invalid':form.errors.has('status') }" v-model="form.status">
                  <option v-for="index in pasien_status_option" v-bind:key="index.value" :value="index.value">
                    {{index.text}}</option>
                </select>
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-3 col-lg-2 flex-text-center">
                Nama Pasien
                <span style="color:red">*</span>
              </div>
              <div class="col-md-8 col-lg-6" :class="{ 'is-invalid': form.errors.has('reg_nama_pasien') }">
                <input class="form-control" type="text" name="reg_nama_pasien" placeholder="Nama Pasien" required
                  v-model="form.reg_nama_pasien" />
                <has-error :form="form" field="reg_nama_pasien" />
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-3 col-lg-2 flex-text-center">
                NIK
              </div>
              <div class="col-md-8 col-lg-6" :class="{ 'is-invalid': form.errors.has('reg_nik') }">
                <input class="form-control" type="text" name="reg_nik" placeholder="NIK" v-model="form.reg_nik"
                  maxlength="16" />
                <has-error :form="form" field="reg_nik" />
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-3 col-lg-2 flex-text-center">
                Tempat Lahir
              </div>
              <div class="col-md-8 col-lg-6" :class="{ 'is-invalid': form.errors.has('reg_tempatlahir') }">
                <input class="form-control" type="text" name="reg_tempatlahir" placeholder="Tempat Lahir"
                  v-model="form.reg_tempatlahir" />
                <has-error :form="form" field="reg_tempatlahir" />
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-3 col-lg-2 flex-text-center">
                Tanggal Lahir
              </div>
              <div class="col-md-8 col-lg-6" :class="{ 'is-invalid': form.errors.has('reg_tgllahir') }">
                <date-picker placeholder="Tanggal Lahir" format="dd MMMM yyyy" ref="tgl_lahir"
                  input-class="form-control" :monday-first="true" v-model="form.reg_tgllahir" />
                <has-error :form="form" field="reg_tgllahir" />
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-3 col-lg-2 flex-text-center">
                Usia
              </div>
              <div class="col-md-3 col-lg-2 flex-text-center"
                :class="{ 'is-invalid': form.errors.has('reg_usia_tahun') }">
                <input class="form-control" type="number" name="reg_usia_tahun" placeholder="Tahun"
                  v-model="form.reg_usia_tahun" min="0" step="1" max="999" />
                <has-error :form="form" field="reg_usia_tahun" />
              </div>
              <div class="col-md-3 col-lg-2 flex-text-center"
                :class="{ 'is-invalid': form.errors.has('reg_usia_bulan') }">
                <input class="form-control" type="number" name="reg_usia_bulan" placeholder="Bulan"
                  v-model="form.reg_usia_bulan" min="0" step="1" max="12" />
                <has-error :form="form" field="reg_usia_bulan" />
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-3 col-lg-2 flex-text-center">
                Jenis Kelamin
              </div>
              <div class="col-md-8 col-lg-6">
                <div class="form-check form-check-inline">
                  <input class="form-check-input" v-model="form.reg_jk" value="L" type="radio">
                  <span>Laki-laki</span>
                </div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" v-model="form.reg_jk" value="P" type="radio">
                  <span>Perempuan</span>
                </div>
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-3 col-lg-2 flex-text-center">
                Suhu (°C)
              </div>
              <div class="col-md-8 col-lg-6">
                <masked-input class="form-control" v-model="form.reg_suhu" mask="11.1" placeholder="Suhu"
                  :class="{ 'is-invalid': form.errors.has('reg_suhu') }" />
                <has-error :form="form" field="reg_suhu" />
              </div>
            </div>
          </Ibox>

          <Ibox title="Alamat Pasien">
            <div class="form-group row">
              <div class="col-md-3 col-lg-2 flex-text-center">
                No. Telp / HP
              </div>
              <div class="col-md-8 col-lg-6" :class="{ 'is-invalid': form.errors.has('reg_nohp') }">
                <input class="form-control" type="number" name="reg_nohp" placeholder="Nomor Telepon / HP"
                  v-model="form.reg_nohp" />
                <has-error :form="form" field="reg_nohp" />
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-3 col-lg-2 flex-text-center">
                Alamat
                <span style="color:red">*</span>
              </div>
              <div class="col-md-8 col-lg-6" :class="{ 'is-invalid': form.errors.has('reg_alamat') }">
                <textarea class="multisteps-form__input form-control" type="text" name="reg_alamat" required
                  v-model="form.reg_alamat" placeholder="Alamat" />
                <has-error :form="form" field="reg_alamat" />
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-3 col-lg-2 flex-text-center">
                RT / RW
              </div>
            <div class="input-group col-md-3 col-lg-2 flex-text-center" :class="{ 'is-invalid':form.errors.has('reg_rt') }">
              <input class="form-control" placeholder="RT" readonly="readonly" />
              <input class="form-control" type="text" name="reg_rt" v-model="form.reg_rt"  />
            </div>
            <div class="input-group col-md-3 col-lg-2 flex-text-center" :class="{ 'is-invalid':form.errors.has('reg_rw') }">
              <input class="form-control" placeholder="RW" readonly="readonly" />
              <input class="form-control" type="text" name="reg_domisilirw" v-model="form.reg_rw" />
            </div>
            <has-error :form="form" field="reg_rt" />
            <has-error :form="form" field="reg_rw" />
          </div>
          <div class="form-group row">
            <label class="col-md-3 col-lg-2">
              Provinsi
            </label>
          <div class="col-md-8 col-lg-6" :class="{ 'is-invalid': form.errors.has('provinsi_id') }">
            <vue-bootstrap-typeahead
              ref="ref_provinsi"
              placeholder="Pilih Provinsi"
              @hit="onSelectedProvinsi($event)"
              :serializer="s => s.nama"
              :minMatchingChars="1"
              :data="dataProvinsi"
              backgroundVariant="white"
              textVariant="dark"
              v-model="provinsi_text"
            />
            <has-error :form="form" field="provinsi_id" />
          </div>
        </div>

        <div class="form-group row">
          <label class="col-md-3 col-lg-2">
            Kota / Kabupaten
          </label>
          <div class="col-md-8 col-lg-6" :class="{ 'is-invalid': form.errors.has('kota_id') }">
            <vue-bootstrap-typeahead
              ref="ref_kota"
              placeholder="Pilih Kota / Kabupaten"
              @hit="onSelectedKota($event)"
              :serializer="s => s.nama"
              :minMatchingChars="1"
              :data="dataKota"
              backgroundVariant="white"
              textVariant="dark"
              v-model="kota_text"
            />
            <has-error :form="form" field="kota_id" />
          </div>
        </div>

        <div class="form-group row">
          <label class="col-md-3 col-lg-2">
            Kecamatan
          </label>
          <div class="col-md-8 col-lg-6" :class="{ 'is-invalid': form.errors.has('kecamatan_id') }">
            <vue-bootstrap-typeahead
              ref="ref_kecamatan"
              placeholder="Pilih Kecamatan"
              @hit="onSelectedKecamatan($event)"
              :serializer="s => s.nama"
              :minMatchingChars="1"
              :data="dataKecamatan"
              backgroundVariant="white"
              textVariant="dark"
            />
            <has-error :form="form" field="kecamatan_id" />
          </div>
        </div>

        <div class="form-group row">
          <label class="col-md-3 col-lg-2">
            Kelurahan / Desa
          </label>
          <div class="col-md-8 col-lg-6" :class="{ 'is-invalid': form.errors.has('kelurahan_id') }">
            <vue-bootstrap-typeahead
              ref="ref_kelurahan"
              placeholder="Pilih Kelurahan"
              @hit="onSelectedKelurahan($event)"
              :serializer="s => s.nama"
              :minMatchingChars="1"
              :data="dataKelurahan"
              backgroundVariant="white"
              textVariant="dark"
            />
            <has-error :form="form" field="kelurahan_id" />
          </div>
        </div>
      </Ibox>

      <Ibox title="Riwayat Kunjungan">
        <div class="form-group row">
          <div class="col-md-3 col-lg-2 flex-text-center">
            Kunjungan Ke
          </div>
          <div class="col-md-8 col-lg-6"
            :class="{ 'is-invalid': form.errors.has('reg_kunjungan_ke') }">
            <div class="form-check-inline" v-for="index of 10" v-bind:key="index">
              <input class="form-check-input" name="reg_kunjungan_ke" :id="`reg_kunjungan_ke-${index}`" :value="index" v-model="form.reg_kunjungan_ke" type="radio">
              <span>Ke-{{index}}</span>
            </div>
          </div>
        </div>
        <div class="form-group row">
          <div class="col-md-3 col-lg-2 flex-text-center">
            Tanggal Kunjungan
          </div>
          <div class="col-md-8 col-lg-6"
            :class="{ 'is-invalid': form.errors.has('reg_tanggalkunjungan') }">
            <date-picker placeholder="Pilih Tanggal Kunjungan" format="d MMMM yyyy"
              input-class="form-control" :monday-first="true"
              v-model="form.reg_tanggalkunjungan" />
            <has-error :form="form" field="reg_tanggalkunjungan" />
          </div>
        </div>
        <div class="form-group row">
          <div class="col-md-3 col-lg-2 flex-text-center">
            Rumah Sakit / Fasyankes
          </div>
          <div class="col-md-8 col-lg-6" :class="{ 'is-invalid':form.errors.has('reg_rsfasyankes') }">
            <input class="form-control" type="text" name="reg_rsfasyankes"
              placeholder="Nama Rumah Sakit / Fasyankes" v-model="form.reg_rsfasyankes" />
            <has-error :form="form" field="reg_rsfasyankes" />
          </div>
        </div>
      </Ibox>

      <Ibox title="Identitas Sampel">
        <div class="form-group row">
          <div class="col-md-3 col-lg-2 flex-text-center col-form-div" for="sampel">Nomor Sampel <span
              style="color:red">*</span></div>
          <div class="col-md-8 col-lg-6">
            <input class="form-control" type="text" name="pen_sampel" v-model="form.reg_sampel"
              placeholder="Nomor Sampel" :class="{ 'is-invalid':form.errors.has(`reg_sampel`) }"
              required />
            <has-error :form="form" field="reg_sampel" />
          </div>
        </div>
        <div class="form-group row">
          <div class="col-md-3 col-lg-2 flex-text-center">
            Keterangan Lainnya
          </div>
          <div class="col-md-8 col-lg-6" :class="{ 'is-invalid': form.errors.has('reg_keterangan') }">
            <textarea class="form-control" type="text" name="reg_keterangan"
              placeholder="Keterangan Lainnya" v-model="form.reg_keterangan" rows="6"></textarea>
                <has-error :form="form" field="reg_keterangan" />
              </div>
            </div>
          </Ibox>

          <div class="col-md-12 mb-4">
            <button :loading="form.busy" class="btn btn-md btn-primary">
              <em class="fa fa-check" /> Simpan Data Register
            </button>
            <nuxt-link to="/registrasi/mandiri" class="btn btn-clear">
              <em class="fa fa-close" /> Batal
            </nuxt-link>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
  import axios from "axios";
  import Form from "vform";
  import {
    id
  } from 'vuejs-datepicker/dist/locale';
  import {
    pasienStatus
  } from '~/assets/js/constant/enum';
  import VueBootstrapTypeahead from 'vue-bootstrap-typeahead';
  import MaskedInput from 'vue-masked-input';
  import {
    checkDataFalsy
  } from '~/utils';
  import moment from 'moment';

  export default {
    components: {
      VueBootstrapTypeahead,
      MaskedInput
    },
    data() {
      return {
        id: id,
        pasien_status_option: pasienStatus
      }
    },
    middleware: "auth",
    async asyncData({
      route,
      store
    }) {
      let error = false;
      let resp = await axios.get(
        `/v1/register/mandiri/${route.params.register_id}/${route.params.pasien_id}`);
      let data = resp.data.result;
      return {
        form: new Form({
          reg_no: data.nomor_register,
          reg_kewarganegaraan: data.kewarganegaraan,
          reg_sumberpasien: data.sumber_pasien == 'Umum' ? 'Umum' : 'Other',
          reg_nama_pasien: data.nama_lengkap,
          reg_nik: data.nik,
          reg_tempatlahir: data.tempat_lahir,
          reg_tgllahir_formatted: data.tanggal_lahir,
          reg_tgllahir: data.tanggal_lahir,
          reg_nohp: data.no_hp,
          reg_provinsi_id: data.provinsi_id,
          reg_kota_id: data.kota_id,
          reg_kecamatan_id: data.kecamatan_id,
          reg_kelurahan_id: data.kelurahan_id,
          reg_kecamatan: data.kecamatan,
          reg_kelurahan: data.kelurahan,
          reg_alamat: data.alamat_lengkap,
          reg_rt: data.no_rt,
          reg_rw: data.no_rw,
          reg_suhu: checkDataFalsy(data.suhu),
          reg_sampel: data.nomor_sampel,
          sampel_id: data.sampel_id,
          reg_keterangan: data.keterangan_lain,
          reg_jk: data.jenis_kelamin,
          reg_rsfasyankes: data.rs_kunjungan,
          reg_tanggalkunjungan: data.tanggal_kunjungan,
          reg_kunjungan_ke: data.kunjungan_ke,
          reg_usia_tahun: data.usia_tahun,
          reg_usia_bulan: data.usia_bulan,
          reg_sumberpasien_isian: data.sumber_pasien == "Umum" ? null : data.sumber_pasien,
          status: data.status,

        }),
        selected_reg: {},
        dataKota: [],
        dataKecamatan: [],
        dataKelurahan: [],
        dataProvinsi: [],
        nik_tgl: null,
        kotaArray: [],
        provinsi_text: null,
        kota_text: null
      };
    },
    methods: {
      initForm() {
        this.form = new Form({
          reg_no: null,
          reg_kewarganegaraan: null,
          reg_sumberpasien: null,
          reg_nama_pasien: null,
          reg_nik: null,
          reg_tempatlahir: null,
          reg_tgllahir: null,
          reg_nohp: null,
          reg_kota: null,
          reg_kecamatan: null,
          reg_kelurahan: null,
          reg_alamat: null,
          reg_rt: null,
          reg_rw: null,
          reg_suhu: null,
          reg_kunjungan_ke: null,
          reg_sampel: null,
          sampel_id: null,
          reg_keterangan: null,
          status: null,
        })
      },
      onSelectedProvinsi(item) {
        this.form.reg_provinsi_id = item.id
        this.getKota(item.id)
      },
      onSelectedKota(item) {
        this.form.reg_kota_id = item.id
        this.getKecamatan(item.id)
      },
      onSelectedKecamatan(item) {
        this.form.reg_kecamatan_id = item.id
        this.getKelurahan(item.id)
      },
      onSelectedKelurahan(item) {
        this.form.reg_kelurahan_id = item.id
      },
      async getProvinsi() {
        let resp = await this.$axios.get('/provinsi');
        this.dataProvinsi = resp.data;
        let item = this.form.reg_provinsi_id && Array.isArray(this.dataProvinsi) ? this.dataProvinsi.find(el => el.id === this.form.reg_provinsi_id) : null
        this.$refs.ref_provinsi.inputValue = item ? item.nama : ''
      },
      async getKota(provinsi_id) {
        let resp = await this.$axios.get('/v1/list-kota-jabar', {
          params: {
            provinsi_id
          }
        });
        this.dataKota = resp.data;
        let item = this.form.reg_kota_id && this.dataKota ? this.dataKota.find(el => el.id === this.form
          .reg_kota_id) : null
        this.$refs.ref_kota.inputValue = item ? item.nama : ''
      },
      async getKecamatan(kota_id) {
        let response = await this.$axios.get('/kecamatan', {
          params: {
            kota_id
          }
        })
        this.dataKecamatan = response.data
        let item = this.form.reg_kecamatan_id && this.dataKecamatan ? this.dataKecamatan.find(el => el
          .id === this.form.reg_kecamatan_id) : null
        this.$refs.ref_kecamatan.inputValue = item ? item.nama : ''
      },
      async getKelurahan(kecamatan_id) {
        let response = await this.$axios.get('/kelurahan', {
          params: {
            kecamatan_id
          }
        })
        this.dataKelurahan = response.data
        let item = this.form.reg_kelurahan_id && this.dataKelurahan ? this.dataKelurahan.find(el => el
          .id === this.form.reg_kelurahan_id) : null
        this.$refs.ref_kelurahan.inputValue = item ? item.nama : ''
      },
      otherCategoryHandler() {
        this.form.reg_sumberpasien_isian = null;
      },
      async submit() {
        try {
          const response = await this.form.post(
            `/v1/register/mandiri/update/${this.$route.params.register_id}/${this.$route.params.pasien_id}`
          );
          this.$swal.fire("Berhasil Ubah Data", "Data Pasien Register Berhasil Diubah", "success");
          this.$router.push('/registrasi/mandiri');
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
      },
      toggleDisable(enabled = false) {
        let ref_kecamatan = this.$refs.ref_kecamatan.$el.querySelector('.form-control')
        let ref_kelurahan = this.$refs.ref_kelurahan.$el.querySelector('.form-control')

        if (enabled) {
          ref_kecamatan.removeAttribute('disabled')
          ref_kelurahan.removeAttribute('disabled')
        } else {
          ref_kecamatan.setAttribute('disabled', '')
          ref_kelurahan.setAttribute('disabled', '')
        }
      }
    },
    head() {
      return {
        title: "Pengambilan / Penerimaan Sampel Baru"
      };
    },
    created() {
      this.getProvinsi();
      this.getKota(this.form.reg_provinsi_id);
      this.getKecamatan(this.form.reg_kota_id);
      this.getKelurahan(this.form.reg_kecamatan_id);
    },
    mounted() {
      let enabled = this.form.provinsi_id && this.form.kota_id ? true : false
      this.toggleDisable(enabled)
    },
    watch: {
      "form.reg_nik": function (newVal, oldVal) {
        if (newVal && newVal.length >= 12) {
          let dd = parseInt(newVal.substr(6, 2))
          if (dd >= 40) {
            this.form.reg_jk = 'P'
            dd -= 40
          } else {
            this.form.reg_jk = 'L'
          }
          let mm = parseInt(newVal.substr(8, 2))
          let yy = parseInt(newVal.substr(10, 2))
          if (yy <= 30) {
            let str = '' + (2000 + yy) + '-' + ('' + mm).padStart(2, '0') + '-' + ('' + dd).padStart(2,
              '0')
            this.form.reg_tgllahir = str
            this.nik_tgl = str
          } else {
            let str = '' + (1900 + yy) + '-' + ('' + mm).padStart(2, '0') + '-' + ('' + dd).padStart(2,
              '0')
            this.form.reg_tgllahir = str
            this.nik_tgl = str
          }
          this.$nextTick(() => {
            this.$refs.tgl_lahir.init();
          })
        }
      },
      "form.reg_tgllahir": function (newVal, oldVal) {
        var birthday = new Date(this.form.reg_tgllahir);
        this.form.reg_usia_tahun = moment().diff(birthday, 'years');
        this.form.reg_usia_bulan = moment().diff(birthday, 'months') % 12;
      },
      "provinsi_text": function (newVal, oldVal) {
        if(!newVal) {
          this.form.reg_provinsi_id = null
        } 
      },
      "kota_text": function (newVal, oldVal) {
        if(!newVal) {
          this.form.reg_kota_id = null
        } 
      },
      "form.reg_provinsi_id": function(newVal, oldVal) {
        if(this.form.reg_kota_id && newVal) {
          this.toggleDisable(true)
        } else {
          this.toggleDisable()
        }
      },
      "form.reg_kota_id": function(newVal, oldVal) {
        if(this.form.reg_provinsi_id && newVal) {
          this.toggleDisable(true)
        } else {
          this.toggleDisable()
        }
      }
    },
    computed: {
      registerId() {
        return this.$route.params.register_id
      },
      pasienId() {
        return this.$route.params.pasien_id
      }
    }
  };
</script>