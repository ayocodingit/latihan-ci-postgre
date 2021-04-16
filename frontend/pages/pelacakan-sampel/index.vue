<template>
  <div class="wrapper wrapper-content">
    <portal to="title-name">Pelacakan Sampel</portal>

    <Ibox title="Lacak Sampel">
      <div class="row">
        <div class="col-md-6" style="padding-right: 20px;">
          <div class="form-group row">
            <div class="col-md-4 flex-text-center">
              <div for="nama_pasien">Nomor Register</div>
            </div>
            <div class="col-md-8">
              <input v-model="params.nomor_register" class="form-control" />
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-4 flex-text-center">
              <div for="nama_pasien">Nama Pasien</div>
            </div>
            <div class="col-md-8">
              <input v-model="params.nama_pasien" class="form-control" />
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-4 flex-text-center">
              <div>Nomor Sampel</div>
            </div>
            <div class="col-md-8 input-group">
              <input type="text" class="form-control" placeholder="Nomor Awal" v-model="params.no_sampel_start">
              &nbsp;
              <input type="text" class="form-control" placeholder="Nomor Akhir" v-model="params.no_sampel_end">
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-4 flex-text-center">
              <div for="nama_pasien">Tanggal Penerimaan</div>
            </div>
            <div class="col-md-8 input-group">
              <rangedate-picker @selected="onDateSelected" ref="rangedatepicker" />
            </div>
          </div>
        </div>

        <div class="col-md-6" style="padding-left: 10px;">
          <div class="form-group row">
            <div class="col-md-5 flex-text-center">
              <div for="nama_pasien">Nomor Induk Kependudukan</div>
            </div>
            <div class="col-md-6">
              <input v-model="params.nik" class="form-control" />
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 flex-text-center">
              <div for="kategori">Kategori</div>
            </div>
            <div class="col-md-6">
              <input class="form-control" type="text" v-model="params.kategori" />
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 flex-text-center">
              <div for="nama_pasien">Instansi Pengirim</div>
            </div>
            <div class="col-md-6">
              <input-option-instansi-pengirim :form="params" field="fasyankes_tipe" />
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 flex-text-center">
              <div for="nama_pasien">Nama Rumah Sakit / Fasyankes</div>
            </div>
            <div class="col-md-6">
              <vue-bootstrap-typeahead v-model="nama_rs" ref="ref_nama_rs" placeholder="Cari Rumah Sakit / Fasyankes"
                :minMatchingChars="1" :serializer="s => s.nama" :data="optionsFasyankes" backgroundVariant="white"
                textVariant="dark" @hit="onSelectedFasyankes($event)" />
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-12 mt-2 flex-right">
          <apply-clear-filter-button-pelacakan :doFilter="onSearch" :clearFilter="clearFilter" />
        </div>
      </div>
    </Ibox>

    <div class="row">
      <div class="col-lg-12">
        <Ibox title="Sampel Tersedia">
          <ajax-table url="/v2/pelacakan-sampel/list" :oid="'pelacakan-sampel'" :params="params" :config="{
						autoload: true,
						has_number: true,
						has_entry_page: true,
						has_pagination: true,
						has_action: true,
						has_search_input: false,
						custom_header: '',
						default_sort: 'tanggal_taken',
						default_sort_dir: 'desc',
						custom_empty_page: true,
						class: {
							table: [],
							wrapper: ['table-responsive'],
						}
					}" :rowtemplate="'tr-pelacakan-sampel'" :columns="{
						nomor_register: 'NO REGISTRASI',
						pasien_nama : 'PASIEN',
						sumber_pasien: 'SUMBER PASIEN',
						fasyankes_nama: 'INSTANSI PENGIRIM',
						kota_nama : 'DOMISILI',
						nomor_sampel : 'NO SAMPEL',
						kesimpulan_pemeriksaan: 'KESIMPULAN PEMERIKSAAN',
						status_sampel: 'STATUS',
						validator: 'VALIDATOR',
						kunjungan_ke: 'RIWAYAT KUNJUNGAN',
						tanggal_taken: 'TANGGAL MASUK SAMPEL',
					}" />
        </Ibox>
      </div>
    </div>
  </div>
</template>

<script>
  import VueBootstrapTypeahead from 'vue-bootstrap-typeahead';
  import {
    getDateIsoString
  } from '~/utils'
  export default {
    middleware: "auth",
    components: {
      VueBootstrapTypeahead
    },
    data() {
      return {
        optionsFasyankes: [],
        nama_rs: '',
        params: {
          nomor_register: "",
          nik: "",
          nama_pasien: "",
          no_sampel_start: "",
          no_sampel_end: "",
          tanggal_taken_start: null,
          tanggal_taken_end: null,
          kategori: null,
          kategori_isian: null,
          fasyankes_tipe: null,
          fasyankes: null,
        }
      };
    },
    head() {
      return {
        title: "Pelacakan Sampel"
      };
    },
    methods: {
      async onSearch() {
        this.$bus.$emit("refresh-ajaxtable", "pelacakan-sampel", this.params);
      },
      getInstansiValue(result) {
        const findInstansi = this.optFasyankes.find(element => element.nama === result);
        if (findInstansi) {
          this.params.nama_rs_id = findInstansi.id;
          this.params.reg_fasyankes_id = findInstansi.id;
          this.params.fasyankes = findInstansi.id;
        }
      },
      clearFilter() {
        document.querySelectorAll("[id*='options_tipe_dinkes']").forEach(el => el.checked = false)
        this.params.no_sampel_start = "";
        this.params.no_sampel_end = "";
        this.params.nomor_register = "";
        this.params.nik = "";
        this.params.nama_pasien = "";
        this.params.tanggal_taken_start = null;
        this.params.tanggal_taken_end = null;
        this.params.kategori = null;
        this.params.kategori_isian = null;
        this.params.fasyankes_tipe = null;
        this.params.fasyankes = null;
        this.params.nama_rs_id = null;
        this.params.reg_fasyankes_id = null;
        this.$refs.ref_nama_rs.inputValue = "";
        this.nama_rs = null
        this.$refs.rangedatepicker.$data.dateRange = {};
        this.$bus.$emit("clear-input-instansi");
        this.$bus.$emit("refresh-ajaxtable", "pelacakan-sampel", this.params);
      },
      async getFasyankes(tipe) {
        if (this.$refs.ref_nama_rs) {
          this.$refs.ref_nama_rs.inputValue = ''
        }
        this.params.fasyankes = null
        const resp = await this.$axios.get(`/v1/list-fasyankes-jabar?tipe=${tipe}`)
        const { data } = resp || []
        this.optionsFasyankes = data
      },
      onSelectedFasyankes(item) {
        this.params.nama_rs_id = item.id;
        this.params.reg_fasyankes_id = item.id;
        this.params.fasyankes = item.id;
      },
      onDateSelected: function (daterange) {
        this.params.tanggal_taken_start = getDateIsoString(daterange.start);
        this.params.tanggal_taken_end = getDateIsoString(daterange.end);
      }
    },
    watch: {
      "params.fasyankes_tipe"(newVal) {
        this.getFasyankes(newVal)
      }
    }
  };
</script>