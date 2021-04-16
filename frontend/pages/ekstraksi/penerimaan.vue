<template>
  <div class="wrapper wrapper-content">
    <portal to="title-name">Penerimaan Sampel</portal>
    <portal to="title-action">
      <div class="title-action">
        <router-link to="/ekstraksi/terima" class="btn btn-primary">
          <em class="uil-flask" /> Lakukan Ekstraksi
        </router-link>
      </div>
    </portal>
    <div class="row">
      <div class="col-lg-12">
        <Ibox title="Data Sampel yang telah dikirim Admin Sampel">
          <p class="sub-header">
            Berikut ini adalah daftar sampel yang telah dikirim Admin Sampel untuk diterima di Lab Ekstraksi
            dan dilakukan ekstraksi
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
          <ajax-table url="/v2/ekstraksi/get-data" :oid="'ekstraksi-penerimaan'"
            :disableSort="['checkbox_sampel_id','jenis_sampel_nama','petugas_pengambil','sampel_status']"
            :params="params" :config="{
							autoload: true,
							has_number: true,
							has_entry_page: true,
							has_pagination: true,
							has_action: true,
							has_search_input: true,
							custom_header: '',
							default_sort: 'waktu_sample_taken',
							default_sort_dir: 'desc',
							custom_empty_page: true,
							class: {
								table: [],
								wrapper: ['table-responsive'],
							}}" :rowtemplate="'tr-ekstraksi-penerimaan'" :columns="{
								checkbox_sampel_id: '#',
								nomor_register: 'NOMOR REGISTER',
								nomor_sampel : 'NOMOR SAMPEL',
								jenis_sampel_nama : 'JENIS SAMPEL',
								petugas_pengambil : 'KONDISI SAMPEL',
								sampel_status: 'STATUS',
								waktu_sample_taken: 'SAMPEL DIAMBIL PADA',
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
      this.$store.commit('ekstraksi_penerimaan/clear');
      return {
        params: {
          sampel_status: 'sample_taken',
          no_sampel_start: '',
          no_sampel_end: ''
        },
      };
    },
    methods: {
      clearFilter() {
        this.params.sampel_status = "sample_taken";
        this.params.no_sampel_start = "";
        this.params.no_sampel_end = "";
        this.$bus.$emit("refresh-ajaxtable", "ekstraksi-penerimaan", this.params);
      },
      doFilter() {
        this.$bus.$emit("refresh-ajaxtable", "ekstraksi-penerimaan", this.params);
      },
    },
    head() {
      return {
        title: "Penerimaan Sampel untuk Ekstraksi"
      };
    },
  };
</script>