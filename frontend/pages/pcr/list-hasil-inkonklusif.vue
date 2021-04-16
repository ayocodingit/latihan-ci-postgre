<template>
  <div class="wrapper wrapper-content">
    <portal to="title-name">List Hasil Invalid PCR</portal>

    <div class="row">
      <div class="col-lg-12">
        <Ibox title="List Hasil Invalid PCR">
          <p class="sub-header">Berikut ini adalah daftar analisa RT-PCR terhadap sampel</p>
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
          <ajax-table url="/v2/pcr/get-data" :oid="'pcr-hasil-inkonklusif'"
            :disableSort="['jenis_sampel_nama','catatan_pemeriksaan']" :params="params" :config="{
              autoload: true,
              has_number: true,
              has_entry_page: true,
              has_pagination: true,
              has_action: true,
              has_search_input: true,
              custom_header: '',
              default_sort: 'waktu_pcr_sample_analyzed',
              default_sort_dir: 'desc',
              custom_empty_page: true,
              class: {
                table: [],
                wrapper: ['table-responsive'],
              }
            }" :rowtemplate="'tr-pcr-hasil-inkonklusif'" :columns="{
              nomor_register: 'NOMOR REGISTER',
              nomor_sampel : 'NOMOR SAMPEL',
              jenis_sampel_nama : 'JENIS SAMPEL',
              catatan_pemeriksaan : 'CATATAN PEMERIKSAAN',
              waktu_pcr_sample_analyzed:'WAKTU INPUT',
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
          sampel_status: "analyzed",
          filter_inconclusive: true,
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
    created() {
      this.$store.commit('detail/remove')
    },
    methods: {
      refreshDebounce: debounce(function () {
        this.$bus.$emit('refresh-ajaxtable', 'pcr-hasil-inkonklusif')
      }, 500),
      doFilter() {
        this.$bus.$emit("refresh-ajaxtable", "pcr-hasil-inkonklusif", this.params);
      },
      clearFilter() {
        this.params.sampel_status = "analyzed";
        this.params.no_sampel_start = "";
        this.params.no_sampel_end = "";
        this.params.lab_pcr_id = "";
        this.params.lab_pcr_nama = "";
        this.params.filter_inconclusive = true
        this.$bus.$emit("refresh-ajaxtable", "pcr-hasil-inkonklusif");
      },
    },
    head() {
      return {
        title: "List Hasil Invalid PCR"
      };
    }
  };
</script>