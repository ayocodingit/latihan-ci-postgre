<template>
  <div class="wrapper wrapper-content">
    <portal to="title-name">Detail Sampel PCR &amp; Ekstraksi</portal>
    <portal to="title-action">
      <div class="title-action">
        <router-link :to="'/pcr/input/' + this.data.id" class="btn btn-import-export" v-show="data.re_pcr !== null && data.sampel_status == 'sample_valid' && data.sampel_status == 'sample_verified'">
          <em class="fa fa-edit" /> Perbarui Data
        </router-link>
        <a href="#" @click.prevent="$router.back()" class="btn btn-black">
          <em class="uil-arrow-left" />Kembali
        </a>
      </div>
    </portal>

    <div class="row">
      <div v-show="oid !== 'pcr-penerimaan'" class="col-md-12" style="padding: 0 20px 0 20px">
        <Ibox title="Informasi Detail RT-PCR">
          <div class="col-md-12">
            <div class="form-group row">
              <div class="col-md-4 text-blue flex-text-center">
                Tanggal penerimaan sampel
              </div>
              <div class="col-md-7 flex-text-center">
                {{data.pcr.tanggal_penerimaan_sampel | formatDate}} {{data.pcr.jam_penerimaan_sampel}}
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-4 text-blue flex-text-center">
                Petugas Penerima Sampel
              </div>
              <div class="col-md-7 flex-text-center">
                {{data.pcr.petugas_penerima_sampel_rna || null}}
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-4 text-blue flex-text-center">
                Catatan Penerimaan
              </div>
              <div class="col-md-7 flex-text-center">
                {{data.pcr.catatan_penerimaan || null}}
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-4 text-blue flex-text-center">
                Operator Realtime PCR
              </div>
              <div class="col-md-7 flex-text-center">
                {{data.pcr.operator_real_time_pcr || null}}
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-4 text-blue flex-text-center">
                Tanggal PCR
              </div>
              <div class="col-md-7 flex-text-center">
                {{data.pcr.tanggal_mulai_pemeriksaan | formatDate }}&nbsp;
                {{data.pcr.jam_mulai_pcr}} -{{data.pcr.jam_selesai_pcr}}
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-4 text-blue flex-text-center">
                Reagen PCR
              </div>
              <div class="col-md-7 flex-text-center">
                {{data.pcr.nama_kit_pemeriksaan || null}}
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-4 text-blue flex-text-center">
                Target Gen
              </div>
              <div class="col-md-7 flex-text-center">
                {{data.pcr.target_gen || null}}
              </div>
            </div>
            <div v-show="oid !== 'pcr-analisis'">
              <div class="form-group row">
                <div class="col-md-4 text-blue">
                  Hasil Deteksi
                </div>
                <div class="col-md-7 flex-text-center">
                  <table class="table dt-responsive table-bordered" style="width:100%">
                    <caption v-show="false">Hasil Deteksi</caption>
                    <thead>
                      <tr>
                        <th scope="col">Target Gen</th>
                        <th scope="col">CT Value</th>
                      </tr>
                    </thead>
                    <tbody class="field_wrapper" v-if="data.pcr.hasil_deteksi">
                      <tr v-for="(hasil, $index) in data.pcr.hasil_deteksi" :key="$index">
                        <td>
                          {{ hasil.target_gen || null }}
                        </td>
                        <td>
                          {{ hasil.ct_value || null }}
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <div class="form-group row">
                <div class="col-md-4 text-blue flex-text-center">
                  Grafik
                </div>
                <div class="col-md-7 flex-text-center" v-if="data.pcr.grafik">
                  <a :href="url" target="_blank" v-for="(url, $index) in data.pcr.grafik" :key="$index"
                    class="thumbnail-wrapper">
                    <img :src="url" alt="" />
                  </a>
                </div>
              </div>
              <div class="form-group row">
                <div class="col-md-4 text-blue flex-text-center">
                  Kesimpulan Pemeriksaan
                </div>
                <div class="col-md-7 flex-text-center">
                  {{data.pcr.kesimpulan_pemeriksaan || null}}
                </div>
              </div>
              <div class="form-group row">
                <div class="col-md-4 text-blue flex-text-center">
                  Catatan Pemeriksaan
                </div>
                <div class="col-md-7 flex-text-center">
                  {{data.pcr.catatan_pemeriksaan || null}}
                </div>
              </div>
            </div>
          </div>
        </Ibox>
      </div>

      <div class="col-md-12" style="padding: 0 20px 0 20px">
        <Ibox title="Informasi Detail Ekstraksi">
          <div class="col-md-12">
            <div class="form-group row">
              <div class="col-md-4 text-blue flex-text-center">
                Nomor Registrasi
              </div>
              <div class="col-md-7 flex-text-center">
                {{data.nomor_register || null}}
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-4 text-blue flex-text-center">
                Nomor Sampel
              </div>
              <div class="col-md-7 flex-text-center">
                {{data.nomor_sampel || null}}
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-4 text-blue flex-text-center">
                Tanggal penerimaan sampel
              </div>
              <div class="col-md-7 flex-text-center">
                {{data.ekstraksi.tanggal_penerimaan_sampel | formatDate}}
                {{data.ekstraksi.jam_penerimaan_sampel}}
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-4 text-blue flex-text-center">
                Petugas Penerima Sampel
              </div>
              <div class="col-md-7 flex-text-center">
                {{data.ekstraksi.petugas_penerima_sampel || null}}
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-4 text-blue flex-text-center">
                Catatan Penerimaan
              </div>
              <div class="col-md-7 flex-text-center">
                {{data.ekstraksi.catatan_penerimaan || null}}
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-4 text-blue flex-text-center">
                Operator Ektraksi
              </div>
              <div class="col-md-7 flex-text-center">
                {{data.ekstraksi.operator_ekstraksi || null}}
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-4 text-blue flex-text-center">
                Tanggal Ekstraksi
              </div>
              <div class="col-md-7 flex-text-center">
                {{data.ekstraksi.tanggal_mulai_ekstraksi | formatDate }}
                {{data.ekstraksi.jam_mulai_ekstraksi}}
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-4 text-blue flex-text-center">
                Metode Ekstraksi
              </div>
              <div class="col-md-7 flex-text-center">
                {{data.ekstraksi.metode_ekstraksi || null}}
              </div>
            </div>
            <div class="form-group row" v-if="data.ekstraksi.metode_ekstraksi">
              <div class="col-md-4 text-blue flex-text-center">
                Reagen Ekstraksi
              </div>
              <div class="col-md-7 flex-text-center">
                {{data.ekstraksi.nama_kit_ekstraksi || data.ekstraksi.alat_ekstraksi || null}}
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-4 text-blue flex-text-center">
                Dikirim ke Lab
              </div>
              <div class="col-md-7 flex-text-center">
                {{data.lab_pcr_nama || null}}
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-4 text-blue flex-text-center">
                Nama Pengirim RNA
              </div>
              <div class="col-md-7 flex-text-center">
                {{data.ekstraksi.nama_pengirim_rna || null}}
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-4 text-blue flex-text-center">
                Tanggal Pengiriman RNA
              </div>
              <div class="col-md-7 flex-text-center">
                {{data.ekstraksi.tanggal_pengiriman_rna | formatDate }}
                {{data.ekstraksi.jam_pengiriman_rna }}
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-4 text-blue flex-text-center">
                Catatan Pengiriman
              </div>
              <div class="col-md-7 flex-text-center">
                {{data.ekstraksi.catatan_pengiriman || null}}
              </div>
            </div>
          </div>
        </Ibox>
      </div>

      <div class="col-md-12" style="padding: 0 20px 0 20px">
        <Ibox title="Riwayat Perubahan atau Pengiriman Kembali">
          <table class="table table-bordered">
            <caption v-show="false">Log</caption>
            <tr class="text-blue">
              <th scope="col" width="20%">WAKTU PERUBAHAN</th>
              <th scope="col">KETERANGAN</th>
            </tr>
            <tr v-if="data.log_pcr.length == 0">
              <td colspan="2">
                <em>Belum ada data terkait ekstraksi</em>
              </td>
            </tr>
            <tr v-for="(item,idx) in data.log_pcr" :key="idx">
              <td>{{item.created_at | formatDateTime}}</td>
              <td>{{item.metadata.catatan_penerimaan}} | {{item.metadata.catatan_pemeriksaan}}.
                {{item.description}}</td>
            </tr>
          </table>
        </Ibox>
      </div>
    </div>
  </div>
</template>

<script>
  import axios from "axios";
  export default {
    middleware: "auth",
    async asyncData({
      route
    }) {
      let resp = await axios.get(`/v1/pcr/detail/${route.params.id}`);
      let data = resp.data.data;
      if (!data.log_pcr) {
        data.log_pcr = [];
      }
      if (!data.ekstraksi) {
        data.ekstraksi = {};
      }
      if (!data.pcr) {
        data.pcr = {};
      }
      return {
        data,
        oid: '',
      };
    },
    head() {
      return {
        title: "Detail Sampel - PCR dan Ekstraksi"
      };
    },
    created() {
      this.oid = this.$store.state.detail.oid
    },
  };
</script>