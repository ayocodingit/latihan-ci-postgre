<template>
  <div class="wrapper wrapper-content">
    <portal to="title-name">
      Registrasi Baru
    </portal>
    <portal to="title-action">
      <div class="title-action">
        <button class="btn btn-md btn-primary" @click="submit">
          <em class="fa fa-check" /> Simpan Data
        </button>
        <a href="#" @click.prevent="$router.back()" class="btn btn-clear">
          <em class="fa fa-close" />
          Batal
        </a>
      </div>
    </portal>

    <div class="row justify-content-center">
      <div class="col-lg-10">

        <Ibox title="Penerimaan atau Pengambilan Sampel">
          <div class="form-group row px-4">
            <div class="col-lg-3 flex-text-center">
              Nomor Registrasi
              <span class="text-red ml-1">*</span>
            </div>
            <div class="col-lg-9">
              <input class="form-control" type="text" name="reg_no" placeholder="Nomor Registrasi" required
                v-model="form.reg_no" disabled :class="{ 'is-invalid': form.errors.has('reg_no') }" />
              <has-error :form="form" field="reg_no" />
            </div>
          </div>
          <div class="form-group row px-4">
            <div class="col-lg-3 flex-text-center">
              Kewarganegaraan
              <span class="text-red ml-1">*</span>
            </div>
            <div class="col-lg-9">
              <div class="form-check form-check-inline" v-for="(item, idx) in optionKewarganegaraan" :key="idx"
                id="warganegara" :class="{ 'is-invalid': form.errors.has('warganegara') }">
                <input class="form-check-input" type="radio" name="warganegara" :id="item.key" :value="item.key"
                  v-model="form.warganegara" @change="onChangeKewarganegaraan">
                <label class="form-check-label">{{ item.value }}</label>
              </div>
              <has-error :form="form" field="warganegara" />
            </div>
          </div>
          <div class="form-group row px-4" v-if="form.warganegara === 'WNA'">
            <div class="col-lg-3 flex-text-center">
              Negara Asal
            </div>
            <div class="col-lg-9">
              <vue-bootstrap-typeahead ref="negara_asal" placeholder="Pilih Negara Asal" :serializer="s => s.text"
                :minMatchingChars="1" :data="dataNegara" backgroundVariant="white" textVariant="dark" v-model="form.negara_asal" />
            </div>
          </div>
          <div class="form-group row px-4">
            <div class="col-lg-3 flex-text-center">
              Kategori
            </div>
            <div class="col-lg-9">
              <div class="form-check form-check-inline" v-for="(item, idx) in optionKategori" :key="idx" id="kategori">
                <input class="form-check-input" type="radio" name="kategori" :id="item.key" :value="item.key"
                  v-model="form.kategori">
                <label class="form-check-label">{{ item.value }}</label>
              </div>
            </div>
          </div>
          <div class="form-group row px-4" v-if="form.kategori === 'Other'">
            <div class="col-lg-3">Kategori Lainnya</div>
            <div class="col-lg-9">
              <input type="text" name="kategori" placeholder="Ketikkan Kategori" class="form-control"
                v-model="kategoriLain">
            </div>
          </div>
        </Ibox>

        <Ibox title="Identitas Pasien">
          <div class="form-group row px-4">
            <div class="col-lg-3 flex-text-center">
              Nama Lengkap
              <span class="text-red ml-1">*</span>
            </div>
            <div class="col-lg-9">
              <input class="form-control" type="text" name="nama_pasien" placeholder="Nama Lengkap Pasien" required
                v-model="form.nama_pasien" :class="{ 'is-invalid': form.errors.has('nama_pasien') }" />
              <has-error :form="form" field="nama_pasien" />
            </div>
          </div>
          <div class="form-group row px-4">
            <div class="col-lg-3 flex-text-center">
              Jenis Identitas
              <span class="text-red ml-1">*</span>
            </div>
            <div class="col-lg-9">
              <div class="form-check form-check-inline" v-for="(item, idx) in optionIdentitas" :key="idx"
                id="jenis_identitas" :class="{ 'is-invalid': form.errors.has('jenis_identitas') }">
                <input class="form-check-input" type="radio" name="jenis_identitas" :id="item" :value="item"
                  v-model="form.jenis_identitas" required>
                <label class="form-check-label text-uppercase">{{ item }}</label>
              </div>
              <has-error :form="form" field="jenis_identitas" />
            </div>
          </div>
          <div class="form-group row px-4">
            <div class="col-lg-3 flex-text-center">
              No. Identitas
              <span class="text-red ml-1">*</span>
            </div>
            <div class="col-lg-9">
              <input class="form-control" type="text" name="no_identitas" placeholder="Masukan No. Identitas"
                v-model="form.no_identitas" maxlength="16" required :class="{ 'is-invalid': form.errors.has('no_identitas') }" />
              <has-error :form="form" field="no_identitas" />
            </div>
          </div>
          <div class="form-group row px-4">
            <div class="col-lg-3 flex-text-center">
              Tanggal Lahir
            </div>
            <div class="col-lg-9">
              <date-picker placeholder="Pilih Tanggal Lahir" format="dd MMMM yyyy" ref="tanggal_lahir"
                input-class="form-control" :monday-first="true" v-model="form.tanggal_lahir"
                :class="{ 'is-invalid': form.errors.has('tanggal_lahir') }" />
              <has-error :form="form" field="tanggal_lahir" />
            </div>
          </div>
          <div class="form-group row px-4">
            <div class="col-lg-3 flex-text-center">
              Usia
            </div>
            <div class="col-lg-3 flex-text-center">
              <input class="form-control" type="number" name="usia_tahun" placeholder="00 Tahun"
                v-model="form.usia_tahun" min="0" step="1" max="999"
                :class="{ 'is-invalid': form.errors.has('usia_tahun') }" />
              <has-error :form="form" field="usia_tahun" />
            </div>
            <div class="col-lg-3 flex-text-center">
              <input class="form-control" type="number" name="usia_bulan" placeholder="00 Bulan"
                v-model="form.usia_bulan" min="0" step="1" max="12"
                :class="{ 'is-invalid': form.errors.has('usia_bulan') }" />
              <has-error :form="form" field="usia_bulan" />
            </div>
          </div>
          <div class="form-group row px-4">
            <div class="col-lg-3 flex-text-center">
              Jenis Kelamin
            </div>
            <div class="col-lg-9">
              <div class="form-check form-check-inline" v-for="(item, idx) in optionJenisKelamin" :key="idx"
                id="jenis_kelamin">
                <input class="form-check-input" type="radio" name="jenis_kelamin" :id="item.key" :value="item.key"
                  v-model="form.jenis_kelamin">
                <label class="form-check-label">{{ item.value }}</label>
              </div>
            </div>
          </div>
        </Ibox>

        <Ibox title="Alamat Pasien">
          <div class="form-group row px-4">
            <div class="col-lg-3 flex-text-center">
              Nomor Telepon/HP
              <span class="text-red ml-1">*</span>
            </div>
            <div class="col-lg-9">
              <input class="form-control" type="number" name="no_telp" placeholder="Nomor Telepon/HP" required
                v-model="form.no_telp" :class="{ 'is-invalid': form.errors.has('no_telp') }" />
              <has-error :form="form" field="no_telp" />
            </div>
          </div>
          <div class="form-group row px-4 mt-4">
            <div class="col-lg-3 flex-text-center">
              Provinsi
              <span class="text-red ml-1">*</span>
            </div>
            <div class="col-lg-9">
              <vue-bootstrap-typeahead ref="ref_provinsi" placeholder="Pilih Provinsi" @hit="onSelectedProvinsi($event)"
                :serializer="s => s.nama" :minMatchingChars="1" :data="dataProvinsi" backgroundVariant="white"
                textVariant="dark" v-model="provinsi_text" :class="{ 'is-invalid': form.errors.has('kode_provinsi') }" />
              <has-error :form="form" field="kode_provinsi" />
            </div>
          </div>
          <div class="form-group row px-4 mt-4">
            <div class="col-lg-3 pt-2">
              Kota / Kabupaten
              <span class="text-red ml-1">*</span>
            </div>
            <div class="col-lg-9">
              <vue-bootstrap-typeahead ref="ref_kota" placeholder="Pilih Kota / Kabupaten" @hit="onSelectedKota($event)"
                :serializer="s => s.nama" :minMatchingChars="1" :data="dataKota" backgroundVariant="white"
                textVariant="dark" v-model="kota_text" :class="{ 'is-invalid': form.errors.has('kode_kota_kabupaten') }" />
              <has-error :form="form" field="kode_kota_kabupaten" />
            </div>
          </div>
          <div class="form-group row px-4">
            <div class="col-lg-3 flex-text-center">
              Kecamatan
              <span class="text-red ml-1">*</span>
            </div>
            <div class="col-lg-9">
              <vue-bootstrap-typeahead disabled ref="ref_kecamatan" placeholder="Pilih Kecamatan"
                @hit="onSelectedKecamatan($event)" :serializer="s => s.nama" :minMatchingChars="1" :data="dataKecamatan"
                backgroundVariant="white" textVariant="dark" v-model="kecamatan_text"
                :class="{ 'is-invalid': form.errors.has('kode_kecamatan') }" />
              <has-error :form="form" field="kode_kecamatan" />
            </div>
          </div>
          <div class="form-group row px-4 mt-4">
            <div class="col-lg-3 flex-text-center">
              Kelurahan / Desa
              <span class="text-red ml-1">*</span>
            </div>
            <div class="col-lg-9">
              <vue-bootstrap-typeahead ref="ref_kelurahan" placeholder="Pilih Kelurahan"
                @hit="onSelectedKelurahan($event)" :serializer="s => s.nama" :minMatchingChars="1" :data="dataKelurahan"
                backgroundVariant="white" textVariant="dark" v-model="kelurahan_text"
                :class="{ 'is-invalid': form.errors.has('kode_kelurahan') }" />
              <has-error :form="form" field="kode_kelurahan" />
            </div>
          </div>
          <div class="form-group row px-4">
            <div class="col-lg-3 flex-text-center">
              Alamat
              <span class="text-red ml-1">*</span>
            </div>
            <div class="col-lg-9">
              <textarea class="multisteps-form__input form-control" type="text" name="alamat" required
                v-model="form.alamat" placeholder="Alamat" :class="{ 'is-invalid': form.errors.has('alamat') }" />
              <has-error :form="form" field="alamat" />
            </div>
          </div>
          <div class="form-group row px-4">
            <div class="col-lg-3 flex-text-center">
              RT / RW
            </div>
            <div class="input-group col-lg-3 flex-text-center">
              <input class="form-control col-md-4" placeholder="RT" readonly="readonly" />
              <input class="form-control" type="number" name="rt" v-model="form.rt" placeholder="000"
              :class="{ 'is-invalid':form.errors.has('rt') }" maxlength="3">
            </div>
            <div class="input-group col-lg-3 flex-text-center">
              <input class="form-control col-md-4" placeholder="RW" readonly="readonly" />
              <input class="form-control" type="number" name="reg_domisilirw" v-model="form.rw" placeholder="000"
              :class="{ 'is-invalid':form.errors.has('rw') }" maxlength="3">
            </div>
            <has-error :form="form" field="rt" />
            <has-error :form="form" field="rw" />
          </div>
        </Ibox>

        <Ibox title="Data Sampel">
          <div class="form-group row px-4">
            <div class="col-lg-3 flex-text-center">
              Kondisi Pasien
              <span class="text-red ml-1">*</span>
            </div>
            <div class="col-lg-9">
              <div class="form-check form-check-inline" v-for="(item, idx) in optionKondisiPasien" :key="idx"
                id="kondisi_pasien" :class="{ 'is-invalid': form.errors.has('kondisi_pasien') }">
                <input class="form-check-input" type="radio" name="kondisi_pasien" :id="item" :value="item"
                  v-model="form.kondisi_pasien">
                <label class="form-check-label">{{ humanize(item) }}</label>
              </div>
              <has-error :form="form" field="kondisi_pasien" />
            </div>
          </div>
          <div class="form-group row px-4">
            <div class="col-lg-3 flex-text-center">
              Jenis Gejala
            </div>
            <div class="col-lg-9">
              <select class="form-control" v-model="form.jenis_gejala">
                <option v-for="(item, idx) in jenisGejala" :value="item" :key="idx">
                  {{ humanize(item) }}
                </option>
              </select>
            </div>
          </div>
          <div class="form-group row px-4">
            <div class="col-lg-3 flex-text-center">
              Tanggal Gejala
            </div>
            <div class="col-lg-9">
              <date-picker placeholder="Pilih Tanggal Gejala" format="dd MMMM yyyy" ref="tanggal_gejala" input-class="form-control"
                :monday-first="true" v-model="form.tanggal_gejala"
                :class="{ 'is-invalid': form.errors.has('tanggal_gejala') }" />
              <has-error :form="form" field="tanggal_gejala" />
            </div>
          </div>
          <div class="form-group row px-4">
            <div class="col-lg-3 flex-text-center">
              Tanggal Periksa
            </div>
            <div class="col-lg-9">
              <date-picker placeholder="Pilih Tanggal Periksa" format="dd MMMM yyyy" ref="tanggal_periksa" input-class="form-control"
                :monday-first="true" v-model="form.tanggal_periksa"
                :class="{ 'is-invalid': form.errors.has('tanggal_periksa') }" />
              <has-error :form="form" field="tanggal_periksa" />
            </div>
          </div>
          <div class="form-group row px-4">
            <div class="col-lg-3 flex-text-center">
              Tujuan Pemeriksaan
            </div>
            <div class="col-lg-9">
              <select class="form-control text-capitalize" v-model="form.tujuan_pemeriksaan" @change="onChangeTujuanPemeriksaan">
                <option v-for="item in tujuanPemeriksaan" :value="item.id" :key="item.id">
                  {{ item.value }}
                </option>
              </select>
              <has-error :form="form" field="tujuan_pemeriksaan" />
            </div>
          </div>
          <div class="form-group row px-4" v-show="form.tujuan_pemeriksaan === 5">
            <div class="col-lg-3 flex-text-center">
              Alasan Lain
            </div>
            <div class="col-lg-9">
              <input class="form-control" type="text" name="tujuan_pemeriksaan_lainnya" placeholder="Masukan Alasan Lain"
                v-model="form.tujuan_pemeriksaan_lainnya" />
              <has-error :form="form" field="tujuan_pemeriksaan_lainnya" />
            </div>
          </div>
          <div class="form-group row px-4">
            <div class="col-lg-3 flex-text-center">
              Jenis Antigen
            </div>
            <div class="col-lg-9">
              <div class="form-check form-check-inline" v-for="(item, idx) in optionJenisAntigen" :key="idx"
                id="jenis_antigen">
                <input class="form-check-input" type="radio" name="jenis_antigen" :id="item" :value="item"
                  v-model="form.jenis_antigen">
                <label class="form-check-label">{{ humanize(item) }}</label>
              </div>
            </div>
          </div>
          <div class="form-group row px-4">
            <div class="col-lg-3 flex-text-center">
              No. Hasil
              <span class="text-red ml-1">*</span>
            </div>
            <div class="col-lg-9">
              <input class="form-control" type="number" name="no_hasil" placeholder="Masukan No. Hasil"
                v-model="form.no_hasil" required :class="{ 'is-invalid': form.errors.has('no_hasil') }" />
              <has-error :form="form" field="no_hasil" />
            </div>
          </div>
          <div class="form-group row px-4">
            <div class="col-lg-3 flex-text-center">
              Hasil Pemeriksaan
              <span class="text-red ml-1">*</span>
            </div>
            <div class="col-lg-9">
              <div class="form-check form-check-inline" v-for="(item, idx) in optionHasilSwabAntigen" :key="idx"
                id="hasil_periksa" :class="{ 'is-invalid': form.errors.has('hasil_periksa') }">
                <input class="form-check-input" type="radio" name="hasil_periksa" :id="item" :value="item"
                  v-model="form.hasil_periksa">
                <label class="form-check-label">{{ humanize(item) }}</label>
              </div>
              <has-error :form="form" field="hasil_periksa" />
            </div>
          </div>
        </Ibox>

      </div>
    </div>
  </div>
</template>

<script>
  import Form from 'vform'
  import moment from 'moment'
  import VueBootstrapTypeahead from 'vue-bootstrap-typeahead'
  import {
    humanize, momentFormatDateYmd
  } from '~/utils'
  import {
    optionIdentitas,
    optionJenisKelamin,
    optionKewarganegaraan,
    optionKategori,
    optionKondisiPasien,
    optionJenisAntigen,
    optionHasilSwabAntigen,
    tujuanPemeriksaan,
    jenisGejala,
    kodeJawaBarat
  } from '~/assets/js/constant/enum'

  export default {
    middleware: "auth",
    head() {
      return {
        title: "Hasil Swab Antigen"
      }
    },
    components: {
      VueBootstrapTypeahead
    },
    data() {
      return {
        humanize,
        form: new Form({
          reg_no: null,
          warganegara: 'WNI',
          negara_asal: null,
          kategori: null,
          nama_pasien: null,
          jenis_identitas: 'ktp',
          no_identitas: null,
          tanggal_lahir: null,
          usia_tahun: null,
          usia_bulan: null,
          jenis_kelamin: null,
          no_telp: null,
          kode_provinsi: null,
          kode_kota_kabupaten: null,
          kode_kecamatan: null,
          kode_kelurahan: null,
          alamat: null,
          rt: null,
          rw: null,
          kondisi_pasien: null,
          tanggal_gejala: null,
          tujuan_pemeriksaan: 0,
          jenis_gejala: null,
          tujuan_pemeriksaan_lainnya: null,
          jenis_antigen: null,
          no_hasil: null,
          tanggal_periksa: null,
          hasil_periksa: null
        }),
        kategoriLain : null,
        dataNegara: [],
        dataProvinsi: [],
        dataKota: [],
        dataKecamatan: [],
        dataKelurahan: [],
        provinsi_text: null,
        kota_text: null,
        kecamatan_text: null,
        kelurahan_text: null,
        optionIdentitas,
        optionJenisKelamin,
        optionKewarganegaraan,
        optionKategori,
        optionKondisiPasien,
        optionJenisAntigen,
        optionHasilSwabAntigen,
        jenisGejala,
        tujuanPemeriksaan
      }
    },
    created() {
      this.getNoreg()
      this.getProvinsi()
      this.getNegara()
    },
    methods: {
      initForm() {
        this.form = new Form({
          reg_no: null,
          warganegara: 'WNI',
          negara_asal: null,
          kategori: null,
          nama_pasien: null,
          jenis_identitas: 'ktp',
          no_identitas: null,
          tanggal_lahir: null,
          usia_tahun: null,
          usia_bulan: null,
          jenis_kelamin: null,
          no_telp: null,
          kode_provinsi: null,
          kode_kota_kabupaten: null,
          kode_kecamatan: null,
          kode_kelurahan: null,
          alamat: null,
          rt: null,
          rw: null,
          kondisi_pasien: null,
          tanggal_gejala: null,
          tujuan_pemeriksaan: 0,
          jenis_gejala: null,
          tujuan_pemeriksaan_lainnya: null,
          jenis_antigen: null,
          no_hasil: null,
          tanggal_periksa: null,
          hasil_periksa: null
        })
        this.kategoriLain = null
        this.$refs.ref_provinsi.inputValue = ""
        this.$refs.ref_kota.inputValue = ""
        this.$refs.ref_kecamatan.inputValue = ""
        this.$refs.ref_kelurahan.inputValue = ""
        this.toggleDisable()
      },
      async getNoreg() {
        const resp = await this.$axios.get('/v1/swab-antigen/nomor-registrasi')
        const { nomor_registrasi } = resp.data || ''
        this.form.reg_no = nomor_registrasi
      },
      async getNegara() {
        const resp = await this.$axios.get('/negara-option')
        const { data } = resp || []
        this.dataNegara = data
      },
      async getProvinsi() {
        const resp = await this.$axios.get('/provinsi')
        const { data } = resp || []
        this.dataProvinsi = data
        this.form.kode_provinsi = kodeJawaBarat.value
        this.$refs.ref_provinsi.inputValue = kodeJawaBarat.text
        await this.getKota(kodeJawaBarat.value)
      },
      async getKota(provinsi_id) {
        const resp = await this.$axios.get('/v1/list-kota-jabar', {params: { provinsi_id }})
        const { data } = resp || []
        this.dataKota = data
      },
      async getKecamatan(kota_id) {
        const resp = await this.$axios.get('/kecamatan', {params: { kota_id }})
        const { data } = resp || []
        this.dataKecamatan = data
      },
      async getKelurahan(kecamatan_id) {
        const resp = await this.$axios.get('/kelurahan', {params: { kecamatan_id }})
        const { data } = resp || []
        this.dataKelurahan = data
      },
      onChangeKewarganegaraan() {
        if (this.form.warganegara === 'WNI') {
          this.form.negara_asal = null
        }
      },
      onChangeTujuanPemeriksaan() {
        if (this.form.tujuan_pemeriksaan !== 5) {
          this.form.tujuan_pemeriksaan_lainnya = null
        }
      },
      onSelectedProvinsi(item) {
        this.form.kode_provinsi = item.id
        this.getKota(item.id)
      },
      onSelectedKota(item) {          
        this.form.kode_kota_kabupaten = item.id
        this.getKecamatan(item.id)
      },
      onSelectedKecamatan(item) {
        this.form.kode_kecamatan = item.id
        this.getKelurahan(item.id)
      },
      onSelectedKelurahan(item) {
        this.form.kode_kelurahan = item.id
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
      },
      async submit() {
        if (this.kategoriLain) {
          this.form.kategori = this.kategoriLain
        }
        this.form.tanggal_lahir = this.form.tanggal_lahir ? momentFormatDateYmd(this.form.tanggal_lahir) : null
        this.form.tanggal_gejala = this.form.tanggal_gejala ? momentFormatDateYmd(this.form.tanggal_gejala) : null
        this.form.tanggal_periksa = this.form.tanggal_periksa ? momentFormatDateYmd(this.form.tanggal_periksa) : null
        try {
          await this.form.post('/v1/swab-antigen')
          this.$swal.fire('Success', 'Registrasi berhasil', 'success')
          this.initForm()
          this.getNoreg()
        } catch (err) {
          if (err?.response?.status && err.response.status === 422) {
          this.$nextTick(() => {
            this.form.errors.set(err.response.data.error)
          })
          this.$swal.fire("Terjadi kesalahan", "Mohon cek kembali formulir Anda", "error")
          } else {
            this.$swal.fire("Terjadi kesalahan", "Silakan hubungi Admin!", "error")
          }
          return
        }
      }
    },
    watch: {
      "form.tanggal_lahir"() {
        const birthday = new Date(this.form.tanggal_lahir);
        this.form.usia_tahun = this.form.usia_tahun || moment().diff(birthday, 'years')
        this.form.usia_bulan = this.form.usia_bulan || moment().diff(birthday, 'months') % 12
      },
      "form.kode_provinsi"(newVal) {
        if(this.form.kode_kota_kabupaten && newVal) {
          this.toggleDisable(true)
        } else {
          this.toggleDisable()
        }
      },
      "form.kode_kota_kabupaten"(newVal) {
        if(this.form.kode_provinsi && newVal) {
          this.toggleDisable(true)
        } else {
          this.toggleDisable()
        }
      }
    }
  }
</script>
