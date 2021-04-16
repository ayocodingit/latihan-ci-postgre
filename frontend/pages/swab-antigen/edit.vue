<template>
  <div class="wrapper wrapper-content">
    <portal to="title-name">
      Edit Data Registrasi
    </portal>
    <portal to="title-action">
      <div class="title-action">
        <button class="btn btn-md btn-primary" @click="submit" :disabled="isDisabledSubmit">
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
            <div class="col-lg-3 flex-text-center required">
              Nomor Registrasi
            </div>
            <div class="col-lg-9">
              <input class="form-control" type="text" name="reg_no" placeholder="Nomor Registrasi" required
                v-model="form.reg_no" disabled :class="{ 'is-invalid': form.errors.has('reg_no') }" />
              <has-error :form="form" field="reg_no" />
            </div>
          </div>
          <div class="form-group row px-4">
            <div class="col-lg-3 flex-text-center required">
              Kewarganegaraan
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
            <div class="col-lg-3 flex-text-center required">
              Negara Asal
            </div>
            <div class="col-lg-9">
              <vue-bootstrap-typeahead ref="ref_negara" placeholder="Pilih Negara Asal" :serializer="s => s.text" :class="{ 'is-invalid': form.errors.has('negara_asal') }"
                :minMatchingChars="1" :data="dataNegara" backgroundVariant="white" textVariant="dark" v-model="form.negara_asal" />
              <has-error :form="form" field="negara_asal" />
            </div>
          </div>
          <div class="form-group row px-4">
            <div class="col-lg-3 flex-text-center">
              Kategori
            </div>
            <div class="col-lg-9">
              <div class="form-check form-check-inline" v-for="(item, idx) in optionKategori" :key="idx" id="kategori">
                <input class="form-check-input" type="radio" name="kategori" :id="item.key" :value="item.key"
                  v-model="kategoriSampel">
                <label class="form-check-label">{{ item.value }}</label>
              </div>
            </div>
          </div>
          <div class="form-group row px-4" v-if="kategoriSampel !== 'Umum'">
            <div class="col-lg-3">Kategori Lainnya</div>
            <div class="col-lg-9">
              <input type="text" name="kategori" placeholder="Ketikkan Kategori" class="form-control"
                v-model="kategoriLainnya">
            </div>
          </div>
        </Ibox>

        <Ibox title="Identitas Pasien">
          <div class="form-group row px-4">
            <div class="col-lg-3 flex-text-center required">
              Nama Lengkap
            </div>
            <div class="col-lg-9">
              <input class="form-control" type="text" name="nama_pasien" placeholder="Nama Lengkap Pasien" required
                v-model="form.nama_pasien" :class="{ 'is-invalid': form.errors.has('nama_pasien') }" />
              <has-error :form="form" field="nama_pasien" />
            </div>
          </div>
          <div class="form-group row px-4">
            <div class="col-lg-3 flex-text-center required">
              Jenis Identitas
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
            <div class="col-lg-3 flex-text-center required">
              No. Identitas
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
                input-class="form-control" :monday-first="true" v-model="form.tanggal_lahir" :language="id"
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
            <div class="col-lg-3 flex-text-center required">
              Nomor Telepon/HP
            </div>
            <div class="col-lg-9">
              <input class="form-control" type="number" name="no_telp" placeholder="Nomor Telepon/HP" required
                v-model="form.no_telp" :class="{ 'is-invalid': form.errors.has('no_telp') }" />
              <has-error :form="form" field="no_telp" />
            </div>
          </div>
          <div class="form-group row px-4 mt-4">
            <div class="col-lg-3 flex-text-center required">
              Provinsi
            </div>
            <div class="col-lg-9">
              <vue-bootstrap-typeahead ref="ref_provinsi" placeholder="Pilih Provinsi" @hit="onSelectedProvinsi($event)"
                :serializer="s => s.nama" :minMatchingChars="1" :data="dataProvinsi" backgroundVariant="white"
                textVariant="dark" :class="{ 'is-invalid': form.errors.has('kode_provinsi') }" />
              <has-error :form="form" field="kode_provinsi" />
            </div>
          </div>
          <div class="form-group row px-4 mt-4">
            <div class="col-lg-3 pt-2 required">
              Kota / Kabupaten
            </div>
            <div class="col-lg-9">
              <vue-bootstrap-typeahead ref="ref_kota" placeholder="Pilih Kota / Kabupaten" @hit="onSelectedKota($event)"
                :serializer="s => s.nama" :minMatchingChars="1" :data="dataKota" backgroundVariant="white"
                textVariant="dark" :class="{ 'is-invalid': form.errors.has('kode_kota_kabupaten') }" />
              <has-error :form="form" field="kode_kota_kabupaten" />
            </div>
          </div>
          <div class="form-group row px-4">
            <div class="col-lg-3 flex-text-center required">
              Kecamatan
            </div>
            <div class="col-lg-9">
              <vue-bootstrap-typeahead ref="ref_kecamatan" placeholder="Pilih Kecamatan" backgroundVariant="white" textVariant="dark"
                @hit="onSelectedKecamatan($event)" :serializer="s => s.nama" :minMatchingChars="1" :data="dataKecamatan"
                :class="{ 'is-invalid': form.errors.has('kode_kecamatan') }" />
              <has-error :form="form" field="kode_kecamatan" />
            </div>
          </div>
          <div class="form-group row px-4 mt-4">
            <div class="col-lg-3 flex-text-center required">
              Kelurahan / Desa
            </div>
            <div class="col-lg-9">
              <vue-bootstrap-typeahead ref="ref_kelurahan" placeholder="Pilih Kelurahan" backgroundVariant="white" textVariant="dark"
                @hit="onSelectedKelurahan($event)" :serializer="s => s.nama" :minMatchingChars="1" :data="dataKelurahan"
                :class="{ 'is-invalid': form.errors.has('kode_kelurahan') }" />
              <has-error :form="form" field="kode_kelurahan" />
            </div>
          </div>
          <div class="form-group row px-4">
            <div class="col-lg-3 flex-text-center required">
              Alamat
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
            <div class="col-lg-3 flex-text-center required">
              Kondisi Pasien
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
                :monday-first="true" v-model="form.tanggal_gejala" :language="id"
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
                :monday-first="true" v-model="form.tanggal_periksa" :language="id"
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
            <div class="col-lg-3 flex-text-center required">
              No. Hasil
            </div>
            <div class="col-lg-9">
              <input class="form-control" type="number" name="no_hasil" placeholder="Masukan No. Hasil" disabled
                v-model="form.no_hasil" required :class="{ 'is-invalid': form.errors.has('no_hasil') }" />
              <has-error :form="form" field="no_hasil" />
            </div>
          </div>
          <div class="form-group row px-4">
            <div class="col-lg-3 flex-text-center required">
              Hasil Pemeriksaan
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
  import axios from 'axios'
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
    jenisGejala
  } from '~/assets/js/constant/enum'
  import { id } from 'vuejs-datepicker/dist/locale'

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
    async asyncData({
      route
    }) {
      const resp = await axios.get(`/v1/swab-antigen/${route.params.id}`)
      const { data } = resp || {}
      const findTujuanPeriksa = data?.tujuan_pemeriksaan ? tujuanPemeriksaan.find((el) => el.value === data.tujuan_pemeriksaan) : null
      return {
        humanize,
        isDisabledSubmit: true,
        form: new Form({
          reg_no: data.nomor_registrasi,
          warganegara: data.warganegara,
          negara_asal: data.negara_asal,
          kategori: data.kategori,
          nama_pasien: data.nama_pasien,
          jenis_identitas: data.jenis_identitas,
          no_identitas: data.no_identitas,
          tanggal_lahir: data.tanggal_lahir,
          usia_tahun: data.usia_tahun,
          usia_bulan: data.usia_bulan,
          jenis_kelamin: data.jenis_kelamin,
          no_telp: data.no_telp,
          kode_provinsi: data.kode_provinsi,
          kode_kota_kabupaten: data.kode_kota_kabupaten,
          kode_kecamatan: data.kode_kecamatan,
          kode_kelurahan: data.kode_kelurahan,
          alamat: data.alamat,
          rt: data.rt,
          rw: data.rw,
          kondisi_pasien: data.kondisi_pasien ? data.kondisi_pasien.toLowerCase() : null,
          tanggal_gejala: data.tanggal_gejala,
          tujuan_pemeriksaan: findTujuanPeriksa ? findTujuanPeriksa.id : null,
          jenis_gejala: data.jenis_gejala ? data.jenis_gejala.toLowerCase() : null,
          tujuan_pemeriksaan_lainnya: data.tujuan_pemeriksaan_lainnya,
          jenis_antigen: data.jenis_antigen ? data.jenis_antigen.toLowerCase() : null,
          no_hasil: data.no_hasil,
          tanggal_periksa: data.tanggal_periksa,
          hasil_periksa: data.hasil_periksa ? data.hasil_periksa.toLowerCase() : null,
        }),
        kategoriSampel: data?.kategori !== 'Umum' ? 'Lainnya' : 'Umum',
        kategoriLainnya: data?.kategori !== 'Umum' ? data.kategori : 'Umum',
        dataNegara: [],
        dataProvinsi: [],
        dataKota: [],
        dataKecamatan: [],
        dataKelurahan: [],
        optionIdentitas,
        optionJenisKelamin,
        optionKewarganegaraan,
        optionKategori,
        optionKondisiPasien,
        optionJenisAntigen,
        optionHasilSwabAntigen,
        jenisGejala,
        tujuanPemeriksaan,
        id
      }
    },
    created() {
      this.getProvinsi()
      this.getKota()
      this.getKecamatan()
      this.getKelurahan()
      this.getNegara()
    },
    methods: {
      findTujuanPeriksa(value) {
        const findKey = tujuanPemeriksaan.find((el) => el.value === value) || null
        if (findKey) {
          return findKey.id
        }
      },
      async getNegara() {
        const resp = await this.$axios.get('/negara-option')
        const { data } = resp || []
        this.dataNegara = data
        if (this.form.negara_asal) {
          this.$refs.ref_negara.inputValue = this.form.negara_asal
        }
      },
      async getProvinsi() {
        const resp = await this.$axios.get('/provinsi')
        const { data } = resp || []
        this.dataProvinsi = data
        const findProvinsi = this.form.kode_provinsi && Array.isArray(data) ? data.find(el => el.id === this.form.kode_provinsi) : null
        this.$refs.ref_provinsi.inputValue = findProvinsi ? findProvinsi.nama : ''
      },
      async getKota(provinsi_id) {
        const resp = await this.$axios.get('/v1/list-kota-jabar', {params: { provinsi_id }})
        const { data } = resp || []
        this.dataKota = data
        const findKota = this.form.kode_kota_kabupaten && Array.isArray(data) ? data.find(el => el.id === this.form.kode_kota_kabupaten) : null
        this.$refs.ref_kota.inputValue = findKota ? findKota.nama : ''
      },
      async getKecamatan(kota_id) {
        const resp = await this.$axios.get('/kecamatan', {params: { kota_id }})
        const { data } = resp || []
        this.dataKecamatan = data
        const findKecamatan = this.form.kode_kecamatan && Array.isArray(data) ? data.find(el => el.id === this.form.kode_kecamatan) : null
        this.$refs.ref_kecamatan.inputValue = findKecamatan ? findKecamatan.nama : ''
      },
      async getKelurahan(kecamatan_id) {
        const resp = await this.$axios.get('/kelurahan', {params: { kecamatan_id }})
        const { data } = resp || []
        this.dataKelurahan = data
        const findKelurahan = this.form.kode_kelurahan && Array.isArray(data) ? data.find(el => el.id === this.form.kode_kelurahan) : null
        this.$refs.ref_kelurahan.inputValue = findKelurahan ? findKelurahan.nama : ''
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
        this.$refs.ref_kota.inputValue = ""
        this.$refs.ref_kecamatan.inputValue = ""
        this.$refs.ref_kelurahan.inputValue = ""
        this.form.kode_kota_kabupaten = null
        this.form.kode_kecamatan = null
        this.form.kode_kelurahan = null
        this.getKota(item.id)
      },
      onSelectedKota(item) {
        this.form.kode_kota_kabupaten = item.id
        this.$refs.ref_kecamatan.inputValue = ""
        this.$refs.ref_kelurahan.inputValue = ""
        this.form.kode_kecamatan = null
        this.form.kode_kelurahan = null
        this.getKecamatan(item.id)
      },
      onSelectedKecamatan(item) {
        this.form.kode_kecamatan = item.id
        this.$refs.ref_kelurahan.inputValue = ""
        this.form.kode_kelurahan = null
        this.getKelurahan(item.id)
      },
      onSelectedKelurahan(item) {
        this.form.kode_kelurahan = item.id
      },
      async submit() {
        this.form.tanggal_lahir = this.form.tanggal_lahir ? momentFormatDateYmd(this.form.tanggal_lahir) : null
        this.form.tanggal_gejala = this.form.tanggal_gejala ? momentFormatDateYmd(this.form.tanggal_gejala) : null
        this.form.tanggal_periksa = this.form.tanggal_periksa ? momentFormatDateYmd(this.form.tanggal_periksa) : null
        if (this.kategoriSampel === 'Umum') {
          this.form.kategori = 'Umum'
        } else {
          this.form.kategori = this.kategoriLainnya
        }
        try {
          await axios.put(`/v1/swab-antigen/${this.$route.params.id}`, this.form)
          this.$swal.fire('Success', 'Perubahan Data Berhasil', 'success')
          this.$router.push('/swab-antigen/hasil-pemeriksaan')
        } catch (err) {
          if (err?.response?.status === 422) {
            this.$nextTick(() => {
              this.form.errors.set(err.response.data.errors)
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
      'form': {
        deep: true,
        handler() {
          this.isDisabledSubmit = false
        }
      },
      "kategoriSampel"(){
        this.isDisabledSubmit = false
      }
    }
  }
</script>
