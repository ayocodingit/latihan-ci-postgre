<template>
  <div class="wrapper wrapper-content">
    <portal to="title-name">Daftar Sampel</portal>
    <portal to="title-action">
      <div class="title-action">
        <router-link to="/sample/add" class="btn btn-primary">
          <em class="fa fa-plus" /> Tambah Sampel Baru
        </router-link>
      </div>
    </portal>

    <div class="row">
      <div class="col-lg-12">
        <Ibox title="Daftar Sampel">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Jenis Sampel</label>
                <dynamic-input :form="params" field="jenis_sampel_nama" :options="jenis_sampel" :hasLainnya="true"
                  :ignoreParam="'999999'" :hasSemua="true">
                </dynamic-input>
              </div>
              <div class="form-group">
                <label>Nomor Sampel</label>
                <div class="input-group">
                  <input type="text" class="form-control" placeholder="Nomor awal" v-model="params.start_nomor_sampel">
                  &nbsp;
                  <input type="text" class="form-control" placeholder="Nomor akhir" v-model="params.end_nomor_sampel">
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Tanggal Penerimaan Sampel</label>
                <date-picker placeholder="Pilih Tanggal" format="d MMMM yyyy" input-class="form-control"
                  :monday-first="true" v-model="params.waktu_sample_taken" />
              </div>
              <div class="form-group">
                <label>Kondisi Sampel</label>
                <dynamic-input :form="params" field="petugas_pengambil"
                  :options="['Baik','Sampel Sedikit','Tabung Rusak']" :hasLainnya="true" :hasSemua="true"
                  placeholder="Masukkan kondisi sampel" preventForm />
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12 mt-2 flex-right">
              <apply-clear-filter-button :doFilter="doFilterParams" :clearFilter="refreshFilterparams" />
            </div>
          </div>
          <hr>
          <div class="rowcol-12">
            <button
              class="btn font-bold"
              :class="params.sampel_lengkap ? 'btn-primary' : 'btn-default'"
              @click="toggleData(true)"
            >
              Data Lengkap
            </button>
            <button
              class="btn font-bold"
              :class="!params.sampel_lengkap ? 'btn-primary' : 'btn-default'"
              @click="toggleData(false)"
            >
              Data Belum Lengkap
            </button>
          </div>
          <div class="row">
            <div class="col-12">
              <ajax-table
                url="/sample/get-data"
                :oid="'sample-sent'"
                :params="params"
                :config="{
                  autoload: true,
                  has_number: true,
                  has_entry_page: true,
                  has_pagination: true,
                  has_action: true,
                  has_search_input2: false,
                  custom_header: '',
                  default_sort: 'waktu_sample_taken',
                  default_sort_dir:'desc',
                  custom_empty_page: true,
                  class: {
                    table: [],
                    wrapper: ['table-responsive'],
                  }
                }"
                :rowtemplate="'tr-sample-sent'"
                :columns="{
                  nomor_sampel: 'SAMPEL',
                  nomor_register: 'NOMOR REGISTRASI',
                  jenis_sampel_nama : 'JENIS SAMPEL',
                  waktu_sample_taken : 'WAKTU PENERIMAAN SAMPEL',
                  petugas_pengambil : 'KONDISI SAMPEL',
                }"
              />
            </div>
          </div>

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
    getDateIsoString
  } from '~/utils'

  export default {
    middleware: "auth",
    computed: mapGetters({
      jenis_sampel: "options/jenis_sampel",
    }),
    data() {
      return {
        params: {
          sampel_lengkap : true,
          sampel_status: "sample_sent",
          waktu_sample_taken: "",
          petugas_pengambil: "",
          jenis_sampel_nama: "",
          start_nomor_sampel: "",
          end_nomor_sampel: "",
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
        this.params.sampel_status = "sample_sent";
        this.params.waktu_sample_taken = "";
        this.params.petugas_pengambil = "";
        this.params.jenis_sampel_nama = "";
        this.params.start_nomor_sampel = "";
        this.params.end_nomor_sampel = "";
        this.$bus.$emit("clear-dropdown");
        this.$bus.$emit('refresh-ajaxtable', 'sample-sent', this.params);
      },
      doFilterParams() {
        this.params.waktu_sample_taken = this.params.waktu_sample_taken !== '' ? getDateIsoString(this.params.waktu_sample_taken) : null
        this.$bus.$emit('refresh-ajaxtable2', 'sample-sent', this.params);
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
      },
      toggleData(condition) {
        this.params.sampel_lengkap = condition
        this.$bus.$emit('refresh-ajaxtable', 'sample-sent', this.params)
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
        title: "Daftar Sampel"
      };
    }
  };
</script>