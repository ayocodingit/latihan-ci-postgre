<template>
  <div class="wrapper wrapper-content">
    <portal to="title-name">Detail Sampel</portal>
    <portal to="title-action">
      <div class="title-action">
        <nuxt-link to="/pelacakan-sampel" class="btn btn-black">
          <em class="uil-arrow-left" /> Kembali
        </nuxt-link>
      </div>
    </portal>

    <div class="col-lg-12 row">
      <div class="col-md-6">
        <Ibox title="Informasi Sampel">
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Nomor Sampel
            </div>
            <div class="col-md-7 flex-text-center">
              {{data.nomor_sampel || null}}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Petugas Penerima Sampel
            </div>
            <div class="col-md-7 flex-text-center">
              {{data.pengambilanSampel.penerima_sampel || null}}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Tipe Sampel
            </div>
            <div class="col-md-7 flex-text-center">
              {{data.jenis_sampel || null}}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Kondisi Sampel
            </div>
            <div class="col-md-7 flex-text-center">
              {{data.kondisi_sampel || null}}
            </div>
          </div>

          <hr>

          <div class="form-group row">
            <div class="col-md-12 flex-text-center">
              <h4>Pengambilan Sampel</h4>
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Waktu Pengambilan
            </div>
            <div class="col-md-7 flex-text-center">
              {{data.waktu_sample_taken | formatDateTime}}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Sumber Sampel
            </div>
            <div class="col-md-7 flex-text-center">
              {{data.pengambilanSampel.sumber_sampel || null}}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Catatan
            </div>
            <div class="col-md-7 flex-text-center">
              {{data.pengambilanSampel.catatan || '-'}}
            </div>
          </div>

          <hr>

          <div class="form-group row">
            <div class="col-md-12 flex-text-center">
              <h4>Ekstraksi</h4>
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Tanggal Penerimaan
            </div>
            <div class="col-md-7 flex-text-center">
              {{data.ekstraksi.tanggal_penerimaan_sampel | formatDate}}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Jam Penerimaan
            </div>
            <div class="col-md-7 flex-text-center">
              {{data.ekstraksi.jam_penerimaan_sampel || null}}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Petugas Penerimaan
            </div>
            <div class="col-md-7 flex-text-center">
              {{data.ekstraksi.petugas_penerima_sampel || null}}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Catatan Penerimaan
            </div>
            <div class="col-md-7 flex-text-center">
              {{data.ekstraksi.catatan_penerimaan || null}}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Operator Ekstraksi
            </div>
            <div class="col-md-7 flex-text-center">
              {{data.ekstraksi.operator_ekstraksi || null}}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Tanggal Mulai Ekstraksi
            </div>
            <div class="col-md-7 flex-text-center">
              {{data.ekstraksi.tanggal_mulai_ekstraksi | formatDate}}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Jam Mulai Ekstraksi
            </div>
            <div class="col-md-7 flex-text-center">
              {{data.ekstraksi.jam_mulai_ekstraksi || null}}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Metode Ekstraksi
            </div>
            <div class="col-md-7 flex-text-center">
              {{data.ekstraksi.metode_ekstraksi || null}}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Nama Kit Ekstraksi
            </div>
            <div class="col-md-7 flex-text-center">
              {{data.ekstraksi.nama_kit_ekstraksi || null}}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Lab Penerima
            </div>
            <div class="col-md-7 flex-text-center">
              {{data.lab_pcr_nama || null}}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Validator
            </div>
            <div class="col-md-7 flex-text-center">
              <span v-if="data.validator.nama">
                {{data.validator.nama}}
                (NIP. {{ data.validator.nip }})
              </span>
            </div>
          </div>
        </Ibox>
          <Ibox title="Pemeriksaan Sampel" v-if="hasil">
            <div class="form-group row">
              <div class="col-md-5 text-blue flex-text-center">
                Tanggal Penerimaan Sampel
              </div>
              <div class="col-md-7 flex-text-center">
                {{hasil.tanggal_penerimaan_sampel | formatDate}}
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-5 text-blue flex-text-center">
                Petugas Penerimaan Sampel RNA
              </div>
              <div class="col-md-7 flex-text-center">
                {{hasil.petugas_penerimaan_sampel_rna || null}}
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-5 text-blue flex-text-center">
                Operator Realtime PCR
              </div>
              <div class="col-md-7 flex-text-center">
                {{data.asdf || null}}
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-5 text-blue flex-text-center">
                Tanggal Mulai Pemeriksaan
              </div>
              <div class="col-md-7 flex-text-center">
                {{hasil.tanggal_mulai_pemeriksaan | formatDate}}
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-5 text-blue flex-text-center">
                Jam Mulai Pemeriksaan
              </div>
              <div class="col-md-7 flex-text-center">
                {{hasil.jam_mulai_pemeriksaan || null}}
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-5 text-blue flex-text-center">
                Jam Selesai Pemeriksaan
              </div>
              <div class="col-md-7 flex-text-center">
                {{hasil.jam_selesai_pemeriksaan || null}}
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-5 text-blue flex-text-center">
                Metode Pemeriksaan
              </div>
              <div class="col-md-7 flex-text-center">
                <span v-if="hasil.nama_kit_pemeriksaan">
                  PCR - {{ hasil.nama_kit_pemeriksaan }}
                </span>
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-5 text-blue flex-text-center">
                Tanggal Input Hasil
              </div>
              <div class="col-md-7 flex-text-center">
                {{hasil.tanggal_input_hasil | formatDate}}
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-5 text-blue flex-text-center">
                Jam Input Hasil
              </div>
              <div class="col-md-7 flex-text-center">
                {{hasil.jam_input_hasil || null}}
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-5 text-blue flex-text-center">
                Kesimpulan Pemeriksaan
              </div>
              <div class="col-md-7 flex-text-center">
                {{hasil.kesimpulan_pemeriksaan || null}}
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-12">
                <table class="table dt-responsive table-bordered" style="width:100%">
              <caption v-show="false">Kesimpulan Pemeriksaan</caption>
                  <thead>
                    <tr>
                      <th scope="col">Target Gen</th>
                      <th scope="col">CT Value</th>
                    </tr>
                  </thead>
                  <tbody class="field_wrapper">
                    <tr v-for="(hasil_deteksi, key) in hasil.hasil_deteksi" :key="key">
                      <td>
                        <p class="form-control">
                          <strong>{{ hasil_deteksi.target_gen }}</strong>
                        </p>
                      </td>
                      <td>
                        <p class="form-control">
                          <strong>{{ hasil_deteksi.ct_value }}</strong>
                        </p>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </Ibox>
      </div>

      <div class="col-md-6">
        <Ibox title="Informasi Pasien">
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Nomor Registrasi
            </div>
            <div class="col-md-7 flex-text-center">
              {{data.nomor_register || null}}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Jenis Registrasi
            </div>
            <div class="col-md-7 flex-text-center">
              <span v-if="data.register">{{data.register.jenis_registrasi}}</span>
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Kategori
            </div>
            <div class="col-md-7 flex-text-center">
              <span v-if="data.register">{{data.register.sumber_pasien}}</span>
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Fasyankes
            </div>
            <div class="col-md-7 flex-text-center">
              <span v-if="data.fasyankes">{{data.fasyankes.nama}}</span>
              <span v-else>-</span>
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Nama Pasien
            </div>
            <div class="col-md-7 flex-text-center">
              {{data.pasien.nama_lengkap || null}}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Nomor Induk Kependudukan
            </div>
            <div class="col-md-7 flex-text-center">
              {{data.pasien.nik || null}}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Tempat &amp; Tanggal Lahir
            </div>
            <div class="col-md-7 flex-text-center">
              <span>
                {{ data.pasien.tempat_lahir || null }}
                &nbsp;
                {{data.pasien.tanggal_lahir | formatDate }}
              </span>
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Usia Pasien
            </div>
            <div class="col-md-7 flex-text-center">
              <span>{{ umurPasien }}</span>
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Jenis Kelamin
            </div>
            <div class="col-md-7 flex-text-center">
              <span v-if="data.pasien.jenis_kelamin === 'L'">Laki - laki</span>
              <span v-if="data.pasien.jenis_kelamin === 'P'">Perempuan</span>
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Provinsi
            </div>
            <div class="col-md-7 flex-text-center">
              <span v-if="data.pasien.provinsi">{{ data.pasien.provinsi.nama }}</span>
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Kabupaten/Kota
            </div>
            <div class="col-md-7 flex-text-center">
              <span v-if="data.pasien.kota">{{ data.pasien.kota.nama }}</span>
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Kecamatan
            </div>
            <div class="col-md-7 flex-text-center">
              <span v-if="data.pasien.kecamatan">{{ data.pasien.kecamatan.nama }}</span>
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Kelurahan
            </div>
            <div class="col-md-7 flex-text-center">
              <span v-if="data.pasien.kelurahan">{{ data.pasien.kelurahan.nama }}</span>
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              RT/RW
            </div>
            <div class="col-md-7 flex-text-center">
              <span>{{ data.pasien.no_rt }}</span>
              <span v-if="data.pasien.no_rw">/</span>
              <span>{{ data.pasien.no_rw }}</span>
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Alamat
            </div>
            <div class="col-md-7 flex-text-center">
              {{data.pasien.alamat_lengkap || null}}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              No. Telp/HP
            </div>
            <div class="col-md-7 flex-text-center">
              <span v-if="data.pasien.no_hp">{{data.pasien.no_hp }}</span>
              <span v-else-if="data.pasien.no_telp">{{data.pasien.no_telp }}</span>
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Tanggal Registrasi
            </div>
            <div class="col-md-7 flex-text-center">
              <span v-if="data.register">{{data.register.created_at | formatDate}}</span>
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Tanggal Periksa / Lapor
            </div>
            <div class="col-md-7 flex-text-center">
              <span v-if="data.register">{{data.register.tanggal_kunjungan | formatDate}}</span>
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Fasyankes
            </div>
            <div class="col-md-7 flex-text-center">
              <span v-if="data.fasyankes">{{ data.fasyankes.nama }}</span>
              <span v-if="data.register">{{ data.register.rs_kunjungan }}</span>
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Suhu
            </div>
            <div class="col-md-7 flex-text-center">
              <span v-if="data.pasien.suhu && data.pasien.suhu !== '0'">{{ data.pasien.suhu }} (°C)</span>
            </div>
          </div>
        </Ibox>

        <Ibox title="Log Sampel">
          <table class="table table-hover">
            <caption v-show="false">Log</caption>
            <thead>
              <tr>
                <th scope="col">Tanggal</th>
                <th scope="col">Perubahan Status</th>
                <th scope="col">Deskripsi</th>
              </tr>

            </thead>
            <tbody>
              <tr v-for="log in data.logs" :key="log.id">
                <td>{{ log.created_at | formatDateTime }}
                </td>
                <td>
                  {{ log.sampel_status_before }} ==> {{ log.sampel_status }}
                </td>
                <td>
                  {{ log.description }}
                </td>
              </tr>
            </tbody>
          </table>
        </Ibox>
      </div>
    </div>

  </div>
</template>

<script>
  import axios from "axios";
  import {
    getHumanAge
  } from '~/utils';

  export default {
    middleware: "auth",
    async asyncData({
      route
    }) {
      let resp = await axios.get(`/v1/pelacakan-sampel/detail/${route.params.id}`);
      let data = resp.data.data;

      if (!data.pasien) {
        data.pasien = {};
      }

      if (!data.validator) {
        data.validator = {};
      }

      if (!data.pengambilanSampel) {
        data.pengambilanSampel = {};
      }

      if (!data.ekstraksi) {
        data.ekstraksi = {};
      }

      let hasil = data.pemeriksaan_sampel

      return {
        data,
        hasil
      };
    },
    data() {
      return {};
    },
    computed: {
      umurPasien() {
        if (this.data.pasien && this.data.pasien.usia_tahun) {
          if (this.data.pasien && this.data.pasien.usia_bulan) {
            return `${this.data.pasien.usia_tahun} tahun ${this.data.pasien.usia_bulan} bulan`;
          }
          return `${this.data.pasien.usia_tahun} tahun`;
        }
        if (this.data.pasien && this.data.pasien.tanggal_lahir) {
          return getHumanAge(this.data.pasien.tanggal_lahir);
        }
        return "-";
      }
    },
    head() {
      return {
        title: "Detil Sampel Hasil Pemeriksaan"
      };
    }
  };
</script>

<style scoped>
  .table-sub-head {
    padding-top: 25px;
  }
</style>