<template>
  <div class="wrapper wrapper-content">
    <portal to="title-name">
      Detail
    </portal>
    <portal to="title-action">
      <div class="title-action">
        <a href="#" @click.prevent="$router.back()" class="btn btn-black">
          <em class="uil-arrow-left" />Kembali
        </a>
      </div>
    </portal>

    <div class="col-lg-10">
      <Ibox title="Informasi Pasien">
        <div class="form-group row">
          <div class="col-md-4 text-blue flex-text-center">
            Nomor Registrasi
          </div>
          <div class="col-md-8 flex-text-center">
            {{nomor_registrasi || '-'}}
          </div>
        </div>
        <div class="form-group row">
          <div class="col-md-4 text-blue flex-text-center">
            Kategori
          </div>
          <div class="col-md-8 flex-text-center">
            {{kategori ? kategori.toLowerCase() === 'umum' ? kategori : 'Lainnya' : '-'}}
          </div>
        </div>
        <div class="form-group row" v-if="kategori && kategori.toLowerCase() !== 'umum'">
          <div class="col-md-4 text-blue flex-text-center">
            Kategori Lainnya
          </div>
          <div class="col-md-8 flex-text-center">
            {{kategori || '-'}}
          </div>
        </div>
        <div class="form-group row">
          <div class="col-md-4 text-blue flex-text-center">
            Nama Pasien
          </div>
          <div class="col-md-8 flex-text-center">
            {{nama_pasien || '-'}}
          </div>
        </div>
        <div class="form-group row">
          <div class="col-md-4 text-blue flex-text-center">
            Kewarganegaraan
          </div>
          <div class="col-md-8 flex-text-center">
            {{warganegara || '-'}}
          </div>
        </div>
        <div class="form-group row" v-if="negara_asal">
          <div class="col-md-4 text-blue flex-text-center">
            Negara Asal
          </div>
          <div class="col-md-8 flex-text-center">
            {{negara_asal || '-'}}
          </div>
        </div>
        <div class="form-group row" v-if="jenis_identitas">
          <div class="col-md-4 text-blue flex-text-center">
            Jenis Identitas
          </div>
          <div class="col-md-8 flex-text-center text-uppercase">
            {{jenis_identitas || '-'}}
          </div>
        </div>
        <div class="form-group row">
          <div class="col-md-4 text-blue flex-text-center">
            No. Identitas
          </div>
          <div class="col-md-8 flex-text-center">
            {{no_identitas || '-'}}
          </div>
        </div>
        <div class="form-group row">
          <div class="col-md-4 text-blue flex-text-center">
            Tanggal Lahir
          </div>
          <div class="col-md-8 flex-text-center">
            {{tanggal_lahir ? momentFormatDate(tanggal_lahir) : '-'}}
          </div>
        </div>
        <div class="form-group row">
          <div class="col-md-4 text-blue flex-text-center">
            Usia
          </div>
          <div class="col-md-8 flex-text-center">
            <span v-if="usia_tahun">
              {{usia_tahun ? `${usia_tahun} tahun` : ''}}
            </span>
            <span v-if="usia_bulan" class="ml-2">
              {{usia_bulan ? `${usia_bulan} bulan` : ''}}
            </span>
          </div>
        </div>
        <div class="form-group row">
          <div class="col-md-4 text-blue flex-text-center">
            Jenis Kelamin
          </div>
          <div class="col-md-8 flex-text-center">
            {{jenis_kelamin ? getJenisKelaminValue() : '-'}}
          </div>
        </div>
        <div class="form-group row">
          <div class="col-md-4 text-blue flex-text-center">
            Provinsi
          </div>
          <div class="col-md-8 flex-text-center">
            {{provinsi && provinsi.nama ? provinsi.nama : '-'}}
          </div>
        </div>
        <div class="form-group row">
          <div class="col-md-4 text-blue flex-text-center">
            Kota / Kabupaten
          </div>
          <div class="col-md-8 flex-text-center">
            {{kota && kota.nama ? kota.nama : '-'}}
          </div>
        </div>
        <div class="form-group row">
          <div class="col-md-4 text-blue flex-text-center">
            Kecamatan
          </div>
          <div class="col-md-8 flex-text-center">
            {{kecamatan && kecamatan.nama ? kecamatan.nama : '-'}}
          </div>
        </div>
        <div class="form-group row">
          <div class="col-md-4 text-blue flex-text-center">
            Kelurahan
          </div>
          <div class="col-md-8 flex-text-center">
            {{kelurahan && kelurahan.nama ? kelurahan.nama : '-'}}
          </div>
        </div>
        <div class="form-group row">
          <div class="col-md-4 text-blue flex-text-center">
            Alamat
          </div>
          <div class="col-md-8 flex-text-center">
            {{alamat || '-'}}
          </div>
        </div>
        <div class="form-group row">
          <div class="col-md-4 text-blue flex-text-center">
            RT / RW
          </div>
          <div class="col-md-8 flex-text-center">
            <span>{{rt || '-'}}</span>
            <span v-if="rw" class="mx-1">/</span>
            <span>{{rw || '-'}}</span>
          </div>
        </div>
        <div class="form-group row">
          <div class="col-md-4 text-blue flex-text-center">
            No. HP / Telepon
          </div>
          <div class="col-md-8 flex-text-center">
            {{no_telp || '-'}}
          </div>
        </div>
      </Ibox>
      <Ibox title="Informasi Sampel">
        <div class="form-group row">
          <div class="col-md-4 text-blue flex-text-center">
            Nomor Hasil
          </div>
          <div class="col-md-8 flex-text-center">
            {{no_hasil || '-'}}
          </div>
        </div>
        <div class="form-group row">
          <div class="col-md-4 text-blue flex-text-center">
            Tanggal Pemeriksaan
          </div>
          <div class="col-md-8 flex-text-center">
            {{tanggal_periksa ? momentFormatDate(tanggal_periksa) : '-'}}
          </div>
        </div>
        <div class="form-group row">
          <div class="col-md-4 text-blue flex-text-center">
            Kondisi Pasien
          </div>
          <div class="col-md-8 flex-text-center">
            {{kondisi_pasien ? humanize(kondisi_pasien) : '-'}}
          </div>
        </div>
        <div class="form-group row">
          <div class="col-md-4 text-blue flex-text-center">
            Tanggal Gejala
          </div>
          <div class="col-md-8 flex-text-center">
            {{tanggal_gejala ? momentFormatDate(tanggal_gejala) : '-'}}
          </div>
        </div>
        <div class="form-group row">
          <div class="col-md-4 text-blue flex-text-center">
            Jenis Gejala
          </div>
          <div class="col-md-8 flex-text-center">
            {{jenis_gejala ? humanize(jenis_gejala) : '-'}}
          </div>
        </div>
        <div class="form-group row">
          <div class="col-md-4 text-blue flex-text-center">
            Tujuan Pemeriksaan
          </div>
          <div class="col-md-8 flex-text-center">
            {{tujuan_pemeriksaan ? humanize(tujuan_pemeriksaan) : '-'}}
          </div>
        </div>
        <div class="form-group row" v-if="tujuan_pemeriksaan_lainnya">
          <div class="col-md-4 text-blue flex-text-center">
            Tujuan Pemeriksaan Lainnya
          </div>
          <div class="col-md-8 flex-text-center">
            {{tujuan_pemeriksaan_lainnya ? humanize(tujuan_pemeriksaan_lainnya) : '-'}}
          </div>
        </div>
        <div class="form-group row">
          <div class="col-md-4 text-blue flex-text-center">
            Jenis Antigen
          </div>
          <div class="col-md-8 flex-text-center">
            {{jenis_antigen || '-'}}
          </div>
        </div>
        <div class="form-group row">
          <div class="col-md-4 text-blue flex-text-center">
            Hasil Pemeriksaan
          </div>
          <div class="col-md-8 flex-text-center">
            {{hasil_periksa || '-'}}
          </div>
        </div>
        <div class="form-group row" v-if="validator">
          <div class="col-md-4 text-blue flex-text-center">
            Status
          </div>
          <div class="col-md-8 flex-text-center">
            {{status || '-'}}
          </div>
        </div>
        <div class="form-group row" v-if="validator">
          <div class="col-md-4 text-blue flex-text-center">
            Tanggal Validasi
          </div>
          <div class="col-md-8 flex-text-center">
            {{tanggal_validasi ? momentFormatDate(tanggal_validasi) : '-'}}
          </div>
        </div>
        <div class="form-group row" v-if="validator">
          <div class="col-md-4 text-blue flex-text-center">
            Validator
          </div>
          <div class="col-md-8 flex-text-center">
            {{validator && validator.nama ? validator.nama : '-'}}
          </div>
        </div>
      </Ibox>
      <Ibox title="Riwayat Perubahan">
        <div v-if="logs && logs.length > 0">
          <LogInfo :data="logs" />
        </div>
        <div v-else>
          {{ $t('title.no_data_changes') }}
        </div>
      </Ibox>
    </div>
  </div>
</template>

<script>
  import axios from 'axios'
  import {
    humanize,
    momentFormatDate
  } from '~/utils'
  import {
    optionJenisKelamin
  } from '~/assets/js/constant/enum'
  export default {
    middleware: 'auth',
    head() {
      return {
        title: this.$t('home')
      }
    },
    async asyncData({
      route
    }) {
      const resp = await axios.get(`/v1/swab-antigen/${route.params.id}`)
      const { data } = resp || []
      const {
        id,
        nomor_registrasi,
        nama_pasien,
        warganegara,
        negara_asal,
        jenis_identitas,
        no_identitas,
        usia_tahun,
        usia_bulan,
        tanggal_lahir,
        jenis_kelamin,
        kategori,
        tanggal_periksa,
        tanggal_validasi,
        status,
        no_hasil,
        hasil_periksa,
        validator,
        provinsi,
        kota,
        kecamatan,
        kelurahan,
        alamat,
        rt,
        rw,
        no_telp,
        kondisi_pasien,
        tanggal_gejala,
        jenis_gejala,
        tujuan_pemeriksaan,
        tujuan_pemeriksaan_lainnya,
        jenis_antigen,
        logs
      } = data || {}
      return {
        id,
        nomor_registrasi,
        nama_pasien,
        warganegara,
        negara_asal,
        jenis_identitas,
        no_identitas,
        usia_tahun,
        usia_bulan,
        tanggal_lahir,
        jenis_kelamin,
        kategori,
        tanggal_periksa,
        tanggal_validasi,
        status,
        no_hasil,
        hasil_periksa,
        validator,
        provinsi,
        kota,
        kecamatan,
        kelurahan,
        alamat,
        rt,
        rw,
        no_telp,
        kondisi_pasien,
        tanggal_gejala,
        jenis_gejala,
        tujuan_pemeriksaan,
        tujuan_pemeriksaan_lainnya,
        jenis_antigen,
        humanize,
        momentFormatDate,
        logs
      }
    },
    methods: {
      getJenisKelaminValue() {
        const findJenisKelamin = Array.isArray(optionJenisKelamin) && this.jenis_kelamin ? optionJenisKelamin.find((el) => el.key === this.jenis_kelamin) : null
        if (findJenisKelamin?.value) {
          return findJenisKelamin.value
        }
        return
      }
    }
  }
</script>
