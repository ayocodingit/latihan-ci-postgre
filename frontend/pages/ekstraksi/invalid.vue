<template>
  <div class="wrapper wrapper-content">
    <portal to="title-name">Sampel Invalid</portal>
    <portal to="title-action">
    </portal>

    <div class="row">
      <div class="col-lg-12">
        <Ibox title="Data sampel yang telah dikembalikan Lab Pemeriksaan Namun Invalid">
          <p class="sub-header">
            Berikut ini adalah daftar dari status sampel yang telah dikembalikan dari Lab Pemeriksaan namun
            invalid
          </p>

          <div class="col-md-8">
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
          <ajax-table url="/v1/ekstraksi/get-data" :oid="'ekstraksi-invalid'"
            :disableSort="['jenis_sampel','waktu_sample_invalid','catatan_pemeriksaan','catatan_pengiriman']"
            :params="params" :config="{
							autoload: true,
							has_number: true,
							has_entry_page: true,
							has_pagination: true,
							has_action: true,
							has_search_input: true,
							custom_header: '',
							default_sort: 'nomor_register',
							custom_empty_page: true,
							class: {
								table: [],
								wrapper: ['table-responsive'],
							}}" :rowtemplate="'tr-ekstraksi-invalid'" :columns="{
								nomor_register: 'Nomor Register',
								nomor_sampel : 'Nomor Sampel',
								jenis_sampel : 'Jenis Sampel',
								waktu_sample_invalid: 'Ditandai invalid pada',
								catatan_pemeriksaan : 'Keterangan Pemeriksaan',
								catatan_pengiriman : 'Keterangan Invalid',
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
          sampel_status: 'sample_invalid',
          no_sampel_start: '',
          no_sampel_end: ''
        },
      };
    },
    head() {
      return {
        title: "Sampel Invalid"
      };
    },
    methods: {
      clearFilter() {
        this.params.sampel_status = "sample_invalid";
        this.params.no_sampel_start = "";
        this.params.no_sampel_end = "";
        this.$bus.$emit("refresh-ajaxtable", "ekstraksi-invalid", this.params);
      },
      doFilter() {
        this.$bus.$emit("refresh-ajaxtable", "ekstraksi-invalid", this.params);
      },
    },
  };
</script>