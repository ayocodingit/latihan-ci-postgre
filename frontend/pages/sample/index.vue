<template>
  <div class="wrapper wrapper-content">
    <portal to="title-name">Sampel Registrasi Mandiri</portal>

    <div class="row">
      <div class="col-lg-12">
        <Ibox title="Sampel Registrasi Mandiri">
          <ajax-table
            url="/sample/get-data"
            :oid="'sample-register'"
            :params="params"
            :config="{
              autoload: true,
              has_number: true,
              has_entry_page: true,
              has_pagination: true,
              has_action: true,
              has_search_input2: true,
              custom_header: '',
              default_sort: 'tgl_input_sampel',
              default_sort_dir:'asc',
              custom_empty_page: true,
              class: {
                table: [],
                wrapper: ['table-responsive'],
              }
            }"
            :rowtemplate="'tr-sample-register'"
            :columns="{
              nomor_sampel: 'SAMPEL',
              nomor_register: 'NOMOR REGISTRASI',
              tgl_input_sampel: 'TANGGAL REGISTRASI',
            }"
          />
        </Ibox>
      </div>
    </div>
  </div>
</template>

<script>
  import {
    mapGetters
  } from "vuex";

  export default {
    middleware: "auth",
    computed: mapGetters({
      jenis_sampel: "options/jenis_sampel",
    }),
    data() {
      return {
        params: {
          sampel_status: "waiting_sample",
          start_nomor_sampel: null,
          end_nomor_sampel: null,
        },
        input_nomor_sampel: '',
        loading: false,
      };
    },
    async asyncData({
      store
    }) {
      if (!store.getters['options/jenis_sampel'].length) {
        await store.dispatch('options/fetchJenisSampel')
      }
      return {}
    },
    methods: {
      refreshFilterparams() {
        this.params.sampel_status = "waiting_sample";
        this.params.start_nomor_sampel = null;
        this.params.end_nomor_sampel = null;
      },
      async scanBarcode() {
        let input_nomor_sampel = this.input_nomor_sampel
        this.loading = true
        let resp = (
          await this.$axios.get("v1/sampel/cek-nomor-sampel", {
            params: {
              nomor_sampel: this.input_nomor_sampel,
            }
          })
        ).data;
        this.loading = false
        if (resp.valid) {
          this.$router.push('/sample/edit/' + input_nomor_sampel)
        } else {
          this.$router.push('/sample/add/' + input_nomor_sampel)
        }
      }
    },
    watch: {
      'params.start_nomor_sampel': function (newVal, oldVal) {
        this.$bus.$emit('refresh-ajaxtable', 'sample-register')
      },
      'params.end_nomor_sampel': function (newVal, oldVal) {
        this.$bus.$emit('refresh-ajaxtable', 'sample-register')
      },
    },
    head() {
      return {
        title: "Penerimaan Sampel"
      };
    }
  };
</script>