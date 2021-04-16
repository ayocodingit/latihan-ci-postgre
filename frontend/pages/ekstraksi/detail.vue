<template>
  <div class="wrapper wrapper-content">
    <portal to="title-name">Detail Sampel</portal>
    <portal to="title-action">
      <div class="title-action">
        <router-link :to="'/ekstraksi/edit/' + this.data.id" class="btn btn-import-export"
          v-show="data.sampel_status !== 'sample_invalid' && data.sampel_status != 'sample_valid' && data.sampel_status != 'sample_verified' && data.sampel_status !== 'sample_taken'">
          <em class="fa fa-edit" /> Perbarui Data
        </router-link>
        <a href="#" @click.prevent="$router.back()" class="btn btn-black">
          <em class="uil-arrow-left" /> Kembali
        </a>
      </div>
    </portal>

    <div class="col-lg-12">
      <Ibox title="Informasi Ekstraksi">
        <div class="form-group row">
          <div class="col-md-4 text-blue flex-text-center">
            Nomor Registrasi
          </div>
          <div class="col-md-8 flex-text-center">
            {{data.nomor_register || null}}
          </div>
        </div>
        <div class="form-group row">
          <div class="col-md-4 text-blue flex-text-center">
            Nomor Sampel
          </div>
          <div class="col-md-8 flex-text-center">
            {{data.nomor_sampel || null}}
          </div>
        </div>
        <div class="form-group row">
          <div class="col-md-4 text-blue flex-text-center">
            Tanggal penerimaan sampel
          </div>
          <div class="col-md-8 flex-text-center">
            <span v-if="data.tanggal_pengambilan_sampel">
              {{data.tanggal_pengambilan_sampel | formatDate }}
            </span>
            <span v-if="data.jam_pengambilan_sampel">
              &nbsp;
              {{data.jam_pengambilan_sampel}}
            </span>
          </div>
        </div>
        <div class="form-group row">
          <div class="col-md-4 text-blue flex-text-center">
            Petugas Penerima Sampel
          </div>
          <div class="col-md-8 flex-text-center">
            {{data.petugas_pengambilan_sampel || null}}
          </div>
        </div>
        <div class="form-group row">
          <div class="col-md-4 text-blue flex-text-center">
            Catatan Penerimaan
          </div>
          <div class="col-md-8 flex-text-center">
            {{data.ekstraksi.catatan_penerimaan || null}}
          </div>
        </div>
        <div class="form-group row">
          <div class="col-md-4 text-blue flex-text-center">
            Operator Ektraksi
          </div>
          <div class="col-md-8 flex-text-center">
            {{data.ekstraksi.operator_ekstraksi || null}}
          </div>
        </div>
        <div class="form-group row">
          <div class="col-md-4 text-blue flex-text-center">
            Waktu Ekstraksi
          </div>
          <div class="col-md-8 flex-text-center">
            {{data.waktu_extraction_sample_extracted | formatDateTime}}
          </div>
        </div>
        <div class="form-group row">
          <div class="col-md-4 text-blue flex-text-center">
            Metode Ekstraksi
          </div>
          <div class="col-md-8 flex-text-center">
            {{data.ekstraksi.metode_ekstraksi || null}}
          </div>
        </div>
        <div class="form-group row">
          <div class="col-md-4 text-blue flex-text-center">
            Reagen Ekstraksi
          </div>
          <div class="col-md-8 flex-text-center" v-if="data.ekstraksi && data.ekstraksi.nama_kit_ekstraksi || data.ekstraksi.alat_ekstraksi">
            {{data.ekstraksi.nama_kit_ekstraksi || data.ekstraksi.alat_ekstraksi || null}}
          </div>
        </div>
        <div class="form-group row">
          <div class="col-md-4 text-blue flex-text-center">
            Dikirim ke Lab
          </div>
          <div class="col-md-8 flex-text-center">
            {{data.lab_pcr_nama || null}}
          </div>
        </div>
        <div class="form-group row">
          <div class="col-md-4 text-blue flex-text-center">
            Nama Pengirim RNA
          </div>
          <div class="col-md-8 flex-text-center">
            {{data.ekstraksi.nama_pengirim_rna || null}}
          </div>
        </div>
        <div class="form-group row">
          <div class="col-md-4 text-blue flex-text-center">
            Tanggal Pengiriman RNA
          </div>
          <div class="col-md-8 flex-text-center">
            <span v-if="data.ekstraksi.tanggal_pengiriman_rna">
              {{data.ekstraksi.tanggal_pengiriman_rna | formatDate }}
            </span>
            <span v-if="data.ekstraksi.jam_pengiriman_rna">
              &nbsp;
              {{data.ekstraksi.jam_pengiriman_rna}}
            </span>
          </div>
        </div>
        <div class="form-group row">
          <div class="col-md-4 text-blue flex-text-center">
            Catatan Pengiriman
          </div>
          <div class="col-md-8 flex-text-center">
            {{data.ekstraksi.catatan_pengiriman || null}}
          </div>
        </div>
      </Ibox>

      <Ibox title="Riwayat Perubahan atau Pengiriman Kembali">
        <div class="form-group row col-md-12">
          <table class="table table-bordered">
            <caption v-show="false">Riwayat Perubahan atau Pengiriman Kembali</caption>
            <tr>
              <th scope="col" width="20%">Tanggal Perubahan</th>
              <th scope="col">Keterangan</th>
            </tr>
            <tr v-if="data.log_ekstraksi.length == 0">
              <td colspan="2"><em>Belum ada data terkait ekstraksi</em></td>
            </tr>
            <tr v-for="(item,idx) in data.log_ekstraksi" :key="idx">
              <td>{{item.created_at | formatDateTime}}</td>
              <td>{{item.metadata.catatan_penerimaan}} | {{item.metadata.catatan_pengiriman}}.
                {{item.description}}</td>
            </tr>
          </table>
        </div>
      </Ibox>
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
      let resp = await axios.get(`/v1/ekstraksi/detail/${route.params.id}`);
      let data = resp.data.data;
      if (!data.ekstraksi) {
        data.ekstraksi = {}
      }
      return {
        data
      };
    },
    head() {
      return {
        title: "Detil Ekstraksi dan Pengiriman Sampel"
      };
    }
  };
</script>