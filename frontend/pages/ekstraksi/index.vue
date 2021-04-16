<template>
  <div class="wrapper wrapper-content">
    <portal to="title-name">Sampel yang Telah Di-Ekstraksi</portal>
    <portal to="title-action">
      <div class="title-action">
        <router-link to="/ekstraksi/kirim" class="btn btn-primary">
          <em class="fa fa-paper-plane" /> Kirim Sampel
        </router-link>
      </div>
    </portal>

    <div class="row">
      <div class="col-lg-12">
        <Ibox title="Sampel yang Telah Di-Ekstraksi">
          <p class="sub-header">
            Berikut ini adalah daftar dari sudah di-Ekstraksi
          </p>
          <div class="col-md-12">
            <label>Nomor Sampel</label>
            <div class="form-group row">
              <div class="col-md-6">
                <div class="input-group">
                  <input type="text" class="form-control" placeholder="Nomor awal" v-model="params.no_sampel_start">
                  &nbsp;
                  <input type="text" class="form-control" placeholder="Nomor akhir" v-model="params.no_sampel_end">
                </div>
              </div>
              <div class="col-sm-6">
                <apply-clear-filter-button :doFilter="doFilter" :clearFilter="clearFilter" />
              </div>
            </div>
          </div>
          <hr>
          <ajax-table url="/v2/ekstraksi/get-data" :oid="'ekstraksi-dilakukan'"
            :disableSort="['checkbox_sampel_id','jenis_sampel','operator','sampel_status']" :params="params" :config="{
							autoload: true,
							has_number: true,
							has_entry_page: true,
							has_pagination: true,
							has_action: true,
							has_search_input: true,
							custom_header: '',
							default_sort: 'waktu_extraction_sample_extracted',
							default_sort_dir: 'desc',
							custom_empty_page: true,
							class: {
								table: [],
								wrapper: ['table-responsive'],
							}}" :rowtemplate="'tr-ekstraksi-dilakukan'" :columns="{
								checkbox_sampel_id: '#',
								nomor_register: 'NOMOR REGISTER',
								nomor_sampel : 'NOMOR SAMPEL',
								jenis_sampel : 'JENIS SAMPEL',
								sampel_status: 'STATUS',
								operator: 'OPERATOR',
								waktu_extraction_sample_extracted: 'EKSTRAKSI DILAKUKAN PADA',
							}" />
        </Ibox>
      </div>
    </div>

  </div>
</template>

<script>
  export default {
    middleware: "auth",
    data() {
      this.$store.commit('ekstraksi_dilakukan/clear');

      return {
        params: {
          sampel_status: 'extraction_sample_extracted',
          no_sampel_start: '',
          no_sampel_end: ''
        },
      };
    },
    head() {
      return {
        title: "Sampel yang Telah Di-Ekstraksi"
      };
    },
    methods: {
      clearFilter() {
        this.params.sampel_status = "extraction_sample_extracted";
        this.params.no_sampel_start = "";
        this.params.no_sampel_end = "";
        this.$bus.$emit("refresh-ajaxtable", "ekstraksi-dilakukan", this.params);
      },
      doFilter() {
        this.$bus.$emit("refresh-ajaxtable", "ekstraksi-dilakukan", this.params);
      },
    },
  };
</script>