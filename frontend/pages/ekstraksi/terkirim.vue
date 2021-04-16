<template>
  <div class="wrapper wrapper-content">
    <portal to="title-name">Sampel yang Telah Dikirim</portal>
    <portal to="title-action"></portal>

    <div class="row">
      <div class="col-lg-12">
        <Ibox title="Data Sampel yang telah dikirim">
          <p class="sub-header">
            Berikut ini adalah daftar dari status registrasi yang sampelnya telah dipilih dan dikirim ke
            laboratorium tingkat 3
          </p>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Tanggal Pengiriman Ekstraksi</label>
                <date-picker placeholder="Pilih Tanggal" format="d MMMM yyyy" input-class="form-control"
                  :monday-first="true" v-model="params.waktu_extraction_sample_sent" />
              </div>
              <label>No Sampel</label>
              <div class="form-group row">
                <div class="col-md-12">
                  <div class="input-group">
                    <input class="form-control" type="text" placeholder="Nomor awal" v-model="params.no_sampel_start" />
                    &nbsp;
                    <input class="form-control" type="text" placeholder="Nomor akhir" v-model="params.no_sampel_end" />
                  </div>
                </div>
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group row">
                <div class="col-md-5 flex-text-center">
                  <div>Hasil Pemeriksaan</div>
                </div>
                <div class="col-md-7">
                  <dynamic-input :form="params" field="kesimpulan_pemeriksaan" :options="dataKesimpulanPemeriksaan"
                    :hasSemua="true">
                  </dynamic-input>
                </div>
              </div>
              <div class="form-group row">
                <div class="col-md-5 flex-text-center">
                  <div>Status Pemeriksaan</div>
                </div>
                <div class="col-md-7">
                  <dynamic-input :form="params" field="status_pemeriksaan" :options="[
										{id: 'extraction_sent',name: 'Semua'},
										{id: 'extraction_sample_sent',name: 'Telah Dikirim'},
										{id: 'pcr_sample_received',name: 'Telah Diterima'},
										{id: 'pcr_sample_analyzed',name: 'Telah Dianalisis'},
										{id: 'sample_verified',name: 'Telah Diverifikasi'},
										{id: 'sample_valid',name: 'Telah Divalidasi'}]" :hasSemua="false">
                  </dynamic-input>
                </div>
              </div>
              <div class="form-group row">
                <div class="col-md-5 flex-text-center">
                  <div>Status Pemusnahan</div>
                </div>
                <div class="col-md-7">
                  <dynamic-input :form="params" field="is_musnah_ekstraksi"
                    :options="[{id: 'true',name: 'Sudah Dimusnahkan'},{id: 'false',name: 'Belum Dimusnahkan'}]"
                    :hasSemua="true">
                  </dynamic-input>
                </div>
              </div>
            </div>
          </div>

          <div class="row mt-2 mb-2">
            <div class="col-md-12 flex-right">
              <apply-clear-filter-button :doFilter="doFilter" :clearFilter="clearFilter" />
            </div>
          </div>
          <hr>
          <ajax-table url="/v2/ekstraksi/get-data" :oid="'ekstraksi-kirim'"
            :disableSort="['lab_pcr_nama','jenis_sampel_nama','sampel_status','kesimpulan_pemeriksaan']"
            :params="params" :config="{
							autoload: true,
							has_number: true,
							has_entry_page: true,
							has_pagination: true,
							has_action: true,
							has_search_input: true,
							custom_header: '',
							default_sort: 'waktu_extraction_sample_sent',
							default_sort_dir: 'desc',
							custom_empty_page: true,
							class: {
								table: [],
								wrapper: ['table-responsive'],
							}
						}" :rowtemplate="'tr-ekstraksi-kirim'" :columns="{
							nomor_register: 'NOMOR REGISTER',
							nomor_sampel : 'NOMOR SAMPEL',
							jenis_sampel_nama : 'JENIS SAMPEL',
							lab_pcr_nama : 'LAB PCR',
							waktu_extraction_sample_sent: 'EKSTRAKSI DILAKUKAN PADA',
							sampel_status : 'STATUS',
							kesimpulan_pemeriksaan : 'HASIL PEMERIKSAAN',
						}" />
        </Ibox>
      </div>
    </div>
  </div>
</template>

<script>
  import {
    mapGetters
  } from "vuex";
  import {
    KesimpulanPemeriksaan
  } from './../../assets/js/constant/enum';
  var debounce = require('lodash/debounce')
  export default {
    middleware: "auth",
    computed: mapGetters({
      user: "auth/user",
      lab_pcr: "options/lab_pcr",
    }),
    data() {
      return {
        dataKesimpulanPemeriksaan: KesimpulanPemeriksaan.data,
        params: {
          lab_pcr_id: "",
          lab_pcr_nama: "",
          status_pemeriksaan: 'extraction_sent',
          sampel_status: 'extraction_sent',
          kesimpulan_pemeriksaan: "",
          is_musnah_ekstraksi: "",
          waktu_extraction_sample_sent: '',
          no_sampel_start: "",
          no_sampel_end: "",
        }
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
    methods: {
      clearFilter() {
        this.params.waktu_extraction_sample_sent = "";
        this.params.kesimpulan_pemeriksaan = "";
        this.params.sampel_status = "extraction_sent";
        this.params.status_pemeriksaan = "extraction_sent";
        this.params.is_musnah_ekstraksi = "";
        this.params.no_sampel_start = "";
        this.params.no_sampel_end = "";
        this.$bus.$emit("clear-dropdown");
        this.$bus.$emit("refresh-ajaxtable", "ekstraksi-kirim");
      },
      doFilter() {
        this.$bus.$emit("refresh-ajaxtable", "ekstraksi-kirim", this.params);
      },
      refreshDebounce: debounce(function () {
        this.$bus.$emit('refresh-ajaxtable', 'ekstraksi-kirim')
      }, 500),
    },
    head() {
      return {
        title: "Sampel yang Telah Dikirim"
      };
    }
  };
</script>