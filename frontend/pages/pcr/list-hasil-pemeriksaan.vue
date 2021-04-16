<template>
  <div class="wrapper wrapper-content">
    <portal to="title-name">List Hasil Pemeriksaan PCR</portal>

    <div class="row">
      <div class="col-lg-12">
        <Ibox title="List Hasil Pemeriksaan PCR">
          <p class="sub-header">Berikut ini adalah daftar analisa RT-PCR terhadap sampel</p>
          <div class="row">
            <div class="col-md-6" v-if="!user.lab_pcr_id">

              <div class="row">
                <div class="col-md-12">
                  <label>No Sampel</label>
                  <div class="form-group row">
                    <div class="col-md-12">
                      <div class="input-group">
                        <input class="form-control" type="text" placeholder="Nomor awal"
                          v-model="params.no_sampel_start" />
                        &nbsp;
                        <input class="form-control" type="text" placeholder="Nomor akhir"
                          v-model="params.no_sampel_end" />
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-12">
                  <label>Tanggal Analisis PCR</label>
                  <date-picker placeholder="Pilih Tanggal" format="d MMMM yyyy" input-class="form-control"
                    :monday-first="true" v-model="params.tanggal_mulai_pemeriksaan" />
                </div>
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group row">
                <div class="col-md-5 flex-text-center">
                  <label>Hasil Pemeriksaan</label>
                </div>
                <div class="col-md-7">
                  <dynamic-input :form="params" field="kesimpulan_pemeriksaan" :options="dataKesimpulanPemeriksaan"
                    :hasSemua="true">
                  </dynamic-input>
                </div>
              </div>
              <div class="form-group row">
                <div class="col-md-5 flex-text-center">
                  <label>Status Pemeriksaan</label>
                </div>
                <div class="col-md-7">
                  <dynamic-input :form="params" field="status_pemeriksaan" :options="[
                    {id: 'semua',name: 'Semua'},
                    {id: 'pcr_sample_analyzed',name: 'Telah Dianalisis'},
                    {id: 'sample_verified',name: 'Telah Diverifikasi'},
                    {id: 'sample_valid',name: 'Telah Divalidasi'}]" :hasSemua="false">
                  </dynamic-input>
                </div>
              </div>
              <div class="form-group row">
                <div class="col-md-5 flex-text-center">
                  <label>Status Pemusnahan</label>
                </div>
                <div class="col-md-7">
                  <dynamic-input :form="params" field="is_musnah_pcr"
                    :options="[{id: 'true',name: 'Sudah Dimusnahkan'},{id: 'false',name: 'Belum Dimusnahkan'}]"
                    :hasSemua="true">
                  </dynamic-input>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-12 mt-2 flex-right">
              <apply-clear-filter-button :doFilter="doFilter" :clearFilter="clearFilter" />
            </div>
          </div>

          <hr>
          <ajax-table url="/v2/pcr/get-data" :oid="'pcr-hasil-pemeriksaan'"
            :disableSort="['sampel_status','kesimpulan_pemeriksaan']" :params="params" :config="{
							autoload: true,
							has_number: true,
							has_entry_page: true,
							has_pagination: true,
							has_action: true,
							has_search_input: true,
							custom_header: '',
							default_sort: 'waktu_pcr_sample_analyzed',
							page:'list_hasil_pemeriksaan',
							status_pemeriksaan:'semua',
							default_sort_dir: 'desc',
							custom_empty_page: true,
							class: {
								table: [],
								wrapper: ['table-responsive'],
							}
						}" :rowtemplate="'tr-pcr-hasil-pemeriksaan'" :columns="{
							nomor_register: 'NOMOR REGISTER',
							nomor_sampel : 'NOMOR SAMPEL',
							waktu_pcr_sample_analyzed:'WAKTU PERIKSA',
							sampel_status : 'STATUS',
							catatan_pengiriman:'CATATAN PENGIRIMAN',
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
        dataKesimpulanPemeriksaan: KesimpulanPemeriksaan.data.filter(el => el.id !== 'swab_ulang'),
        params: {
          lab_pcr_id: "",
          lab_pcr_nama: "",
          sampel_status: "analyzed",
          filter_inconclusive: false,
          kesimpulan_pemeriksaan: "",
          tanggal_mulai_pemeriksaan: "",
          is_musnah_pcr: "",
          no_sampel_start: "",
          no_sampel_end: "",
          status_pemeriksaan: "semua"
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
    created() {
      this.$store.commit('detail/remove')
    },
    methods: {
      refreshDebounce: debounce(function () {
        this.$bus.$emit('refresh-ajaxtable', 'pcr-hasil-pemeriksaan')
      }, 500),
      doFilter() {
        this.$bus.$emit("refresh-ajaxtable", "pcr-hasil-pemeriksaan", this.params);
      },
      clearFilter() {
        this.params.sampel_status = "analyzed";
        this.params.no_sampel_start = "";
        this.params.no_sampel_end = "";
        this.params.lab_pcr_id = "";
        this.params.lab_pcr_nama = "";
        this.params.filter_inconclusive = false
        this.params.kesimpulan_pemeriksaan = "";
        this.params.tanggal_mulai_pemeriksaan = "";
        this.params.is_musnah_pcr = "";
        this.params.status_pemeriksaan = "semua";
        this.$bus.$emit("clear-dropdown");
        this.$bus.$emit("refresh-ajaxtable", "pcr-hasil-pemeriksaan");
      },
    },
    head() {
      return {
        title: "List Hasil Pemeriksaan PCR"
      };
    }
  };
</script>