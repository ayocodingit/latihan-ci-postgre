<template>
  <div class="wrapper wrapper-content">
    <portal to="title-name">Sampel Re-Ekstraksi</portal>

    <div class="row">
      <div class="col-lg-12">
        <Ibox title="Data sampel yang telah diekstraksi namun dikembalikan Lab Pemeriksaan">
          <p class="sub-header">
            Berikut ini adalah daftar dari status sampel yang dikembalikan dari Lab Pemeriksaan
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
          <ajax-table url="/v2/ekstraksi/get-data" :oid="'ekstraksi-dikembalikan'"
            :disableSort="['catatan_pemeriksaan','jenis_sampel','lab_pcr_nama']" :params="params" :config="{
							autoload: true,
							has_number: true,
							has_entry_page: true,
							has_pagination: true,
							has_action: true,
							has_search_input: true,
							custom_header: '',
							default_sort: 'waktu_extraction_sample_reextract',
							default_sort_dir: 'desc',
							custom_empty_page: true,
							class: {
								table: [],
								wrapper: ['table-responsive'],
							}
						}" :rowtemplate="'tr-ekstraksi-dikembalikan'" :columns="{
							nomor_register: 'NOMOR REGISTRER',
							nomor_sampel : 'NOMOR SAMPEL',
							jenis_sampel : 'JENIS SAMPEL',
							lab_pcr_nama : 'LAB PCR',
							waktu_extraction_sample_reextract: 'PERMINTAAN RE-EKSTRAKSI PADA',
							catatan_pemeriksaan : 'KETERANGAN PEMERIKSAAN',
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
      return {
        params: {
          sampel_status: 'extraction_sample_reextract',
          no_sampel_start: '',
          no_sampel_end: ''
        },
      };
    },
    head() {
      return {
        title: "Penerimaan Sampel"
      };
    },
    methods: {
      clearFilter() {
        this.params.sampel_status = "extraction_sample_reextract";
        this.params.no_sampel_start = "";
        this.params.no_sampel_end = "";
        this.$bus.$emit("refresh-ajaxtable", "ekstraksi-dikembalikan");
      },
      doFilter() {
        this.$bus.$emit("refresh-ajaxtable", "ekstraksi-dikembalikan", this.params);
      },
    },
  };
</script>