<template>
  <div class="wrapper wrapper-content">
    <portal to="title-name">List PCR</portal>
    <portal to="title-action">
      <div class="title-action">
        <router-link to="/pcr/import-excel-hasil" class="btn btn-import-export">
          <em class="uil-file-download" /> Import Hasil Pemeriksaan
        </router-link>
      </div>
    </portal>

    <div class="row">
      <div class="col-lg-12">
        <Ibox title="List PCR">
          <p class="sub-header">Berikut ini adalah daftar dari status sampel RNA yang sudah diterima, silahkan
            lakukan analisa RT-PCR terhadap data ini</p>
          <div class="row">
            <div class="col-md-12">
              <label>No Sampel</label>
              <div class="form-group row">
                <div class="col-md-6">
                  <div class="input-group">
                    <input class="form-control" type="text" placeholder="Nomor awal" v-model="params.no_sampel_start" />
                    &nbsp;
                    <input class="form-control" type="text" placeholder="Nomor akhir" v-model="params.no_sampel_end" />
                  </div>
                </div>
                <div class="col-md-6">
                  <apply-clear-filter-button :doFilter="doFilter" :clearFilter="clearFilter" />
                </div>
              </div>
            </div>
          </div>

          <hr>
          <ajax-table url="/v2/pcr/get-data" :oid="oid" :disableSort="['jenis_sampel_nama']" :params="params"
            :config="{
							autoload: true,
							has_number: true,
							has_entry_page: true,
							has_pagination: true,
							has_action: true,
							has_search_input: true,
							custom_header: '',
							default_sort: 'waktu_pcr_sample_received',
							default_sort_dir: 'desc',
							custom_empty_page: true,
							class: {
								table: [],
								wrapper: ['table-responsive'],
							}
						}" :rowtemplate="'tr-pcr-analisis'" :columns="{
							nomor_register: 'NOMOR REGISTER',
							nomor_sampel : 'NOMOR SAMPEL',
							status : 'STATUS',
							jenis_sampel_nama : 'JENIS SAMPEL',
							waktu_pcr_sample_received:'WAKTU DITERIMA',
							catatan_pengiriman:'CATATAN PENGIRIMAN',
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
  var debounce = require('lodash/debounce')

  export default {
    middleware: "auth",
    computed: mapGetters({
      user: "auth/user",
      lab_pcr: "options/lab_pcr",
    }),
    data() {
      return {
        params: {
          lab_pcr_id: "",
          lab_pcr_nama: "",
          sampel_status: "pcr_sample_received",
          no_sampel_start: "",
          no_sampel_end: "",
        },
        oid: 'pcr-analisis',
      }
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
      this.$store.commit('detail/add', this.oid)
    },
    methods: {
      refreshDebounce: debounce(function () {
        this.$bus.$emit('refresh-ajaxtable', 'pcr-analisis')
      }, 500),
      doFilter() {
        this.$bus.$emit("refresh-ajaxtable", "pcr-analisis", this.params);
      },
      clearFilter() {
        this.params.sampel_status = "pcr_sample_received";
        this.params.no_sampel_start = "";
        this.params.no_sampel_end = "";
        this.params.lab_pcr_id = "";
        this.params.lab_pcr_nama = "";
        this.$bus.$emit("refresh-ajaxtable", "pcr-analisis");
      },
    },
    head() {
      return {
        title: "List PCR"
      };
    }
  };
</script>