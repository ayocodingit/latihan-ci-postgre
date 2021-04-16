<template>
  <div class="wrapper wrapper-content">
    <portal to="title-name">Terverifikasi</portal>
    <portal to="title-action">
      <div class="title-action">
        <download-export-button :parentRefs="$refs" ajaxTableRef="terverifikasi" class="btn btn-import-export" />
      </div>
    </portal>

    <div class="row">
      <div class="col-lg-12">
        <Ibox title="Sampel Terverifikasi">
          <p class="sub-header">
            Berikut ini adalah daftar sampel dari hasil pemeriksaan
          </p>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group row">
                <div class="col-md-4 flex-text-center">
                  <div>Kategori</div>
                </div>
                <div class="col-md-8 input-group">
                  <input class="form-control" type="text" v-model="params.kategori" />
                </div>
              </div>
              <div class="form-group row">
                <div class="col-md-4 flex-text-center">
                  <label>Tanggal Verifikasi</label>
                </div>
                <div class="col-md-8 input-group">
                  <rangedate-picker @selected="onDateSelected" ref="rangedatepicker" />
                </div>
              </div>
              <div class="form-group row">
                <div class="col-md-4 flex-text-center">
                  <div for="nama_pasien">Instansi Pengirim</div>
                </div>
                <div class="col-md-8">
                  <input-option-instansi-pengirim :form="params" field="fasyankes_tipe" />
                </div>
              </div>
              <div class="form-group row">
                <div class="col-md-4 flex-text-center">
                  <div for="nama_pasien">Nama Rumah Sakit / Fasyankes</div>
                </div>
                <div class="col-md-8">
                  <vue-bootstrap-typeahead
                    ref="ref_nama_rs"
                    placeholder="Cari Rumah Sakit / Fasyankes"
                    :minMatchingChars="1"
                    :serializer="s => s.nama"
                    :data="optionsFasyankes"
                    @hit="onSelectedFasyankes($event)"
                    backgroundVariant="white"
                    textVariant="dark" />
                </div>
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group row">
                <div class="col-md-4 flex-text-center">
                  <div>Nomor Sampel</div>
                </div>
                <div class="col-md-7 input-group">
                  <input type="text" class="form-control" placeholder="Nomor awal" v-model="params.no_sampel_start">
                  &nbsp;
                  <input type="text" class="form-control" placeholder="Nomor akhir" v-model="params.no_sampel_end">
                </div>
              </div>
              <div class="form-group row">
                <div class="col-md-4 flex-text-center">
                  <label>Kota Domisili</label>
                </div>
                <div class="col-md-7">
                  <vue-bootstrap-typeahead v-model="nama_kota" ref="autocompletedomisili"
                    placeholder="Cari Kota / Kabupaten" :minMatchingChars="1" :data="kotaArray"
                    backgroundVariant="white" textVariant="dark" :inputClass="getDomisiliValue(nama_kota)" />
                </div>
              </div>
              <div class="form-group row">
                <div class="col-md-4 flex-text-center">
                  <label> Hasil Pemeriksaan</label>
                </div>
                <div class="col-md-7">
                  <dynamic-input :form="params" field="kesimpulan_pemeriksaan" v-model="params.kesimpulan_pemeriksaan"
                    :options="dataKesimpulanPemeriksaan" :hasSemua="true">
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

          <ajax-table
            ref="terverifikasi"
            urlexport="/v1/verifikasi/export-excel-terverifikasi"
            url="/v1/verifikasi/list-verified"
            :oid="'terverifikasi'"
            :disableSort="['parameter_lab','kondisi_sampel','status']"
            :params="params"
            :config="{
              autoload: true,
              has_number: true,
              has_entry_page: true,
              has_pagination: true,
              has_action: true,
              has_search_input: true,
              custom_header: '',
              default_sort: 'tanggal_diverifikasi',
              default_sort_dir: 'desc',
              custom_empty_page: true,
              class: {
                table: [],
                wrapper: ['table-responsive'],
              }
            }"
            :rowtemplate="'tr-verified'"
            :columns="{
              nomor_register: 'NO REGISTRASI',
              nama_pasien: 'PASIEN',
              sumber_pasien: 'KATEGORI',
              nama_kota: 'DOMISILI',
              fasyankes_nama: 'INSTANSI PENGIRIM',
              nomor_sampel : 'NO SAMPEL',
              parameter_lab: 'PARAMETER LAB',
              kondisi_sampel: 'KONDISI SAMPEL',
              kesimpulan_pemeriksaan: 'KESIMPULAN PEMERIKSAAN',
              status: 'STATUS',
              tanggal_diverifikasi: 'TANGGAL VERIFIKASI'
            }" />
        </Ibox>
      </div>
    </div>

  </div>
</template>

<script>
  import axios from "axios";
  import Form from "vform";
  import VueBootstrapTypeahead from 'vue-bootstrap-typeahead';
  import {
    KesimpulanPemeriksaan
  } from './../../assets/js/constant/enum';
  import {
    getDateIsoString
  } from '~/utils'

  var debounce = require('lodash/debounce')

  export default {
    middleware: "auth",
    components: {
      VueBootstrapTypeahead,
    },
    data() {
      return {
        dataKesimpulanPemeriksaan: KesimpulanPemeriksaan.data,
        optionsFasyankes: [],
        optionKota: [],
        kotaArray: [],
        nama_kota: '',
        params: {
          fasyankes: "",
          kota_domisili: "",
          tanggal_verifikasi_start: "",
          tanggal_verifikasi_end: "",
          kesimpulan_pemeriksaan: "",
          no_sampel_start: "",
          no_sampel_end: "",
          jenis_registrasi: null,
          nomor_register: null,
          nomor_sampel: null,
          sumber_pasien: null,
          sumber_sampel: null,
          kategori: null,
          kategori_isian: null,
          kota: null,
          fasyankes_tipe: null,
        },
      };
    },
    head() {
      return {
        title: "Sampel Hasil Pemeriksaan"
      };
    },
    methods: {
      async getKota() {
        const resp = await axios.get('/v1/list-kota-jabar');
        this.optionKota = resp.data;
        const dataArr = this.optionKota.map((list) => {
          const {
            nama
          } = list;
          if (nama) {
            this.kotaArray.push(nama);
          }
        });
        return this.kotaArray;
      },
      async getFasyankes(tipe) {
        let resp = await this.$axios.get(`/v1/list-fasyankes-jabar?tipe=${tipe}`)
        this.optionsFasyankes = resp.data
      },
      onSelectedFasyankes(result) {
        this.params.fasyankes = result.id;
      },
      getDomisiliValue(result) {
        const findKota = this.optionKota.find(element => element.nama === result);
        if (findKota) {
          this.params.kota_domisili = findKota.id;
        }
      },
      doFilter() {
        this.$bus.$emit('refresh-ajaxtable', 'terverifikasi', this.params);
      },
      clearFilter() {
        document.querySelectorAll("[id*='options_tipe_dinkes']").forEach(el => el.checked = false)
        this.params.no_sampel_start = "";
        this.params.no_sampel_end = "";
        this.params.fasyankes = "";
        this.params.kota_domisili = "";
        this.params.tanggal_verifikasi_start = "";
        this.params.tanggal_verifikasi_end = "";
        this.params.kesimpulan_pemeriksaan = "";
        this.params.kategori = "";
        this.params.fasyankes_tipe = "";
        this.$refs.ref_nama_rs.inputValue = ''
        this.$refs.autocompletedomisili.inputValue = ''
        this.nama_kota = null
        this.$refs.rangedatepicker.$data.dateRange = {};
        this.$bus.$emit("clear-input-instansi");
        this.$bus.$emit("clear-dropdown");
        this.$bus.$emit("refresh-ajaxtable", "terverifikasi", this.params);
      },
      async onExport(type) {
        try {
          this.loading = true;

          let form = new Form({
            ...this.params,
            type: type
          })

          axios({
            url: process.env.apiUrl + "/v1/verifikasi/export-excel",
            params: form,
            method: 'GET',
            responseType: 'blob',
          }).then((response) => {
            const blob = new Blob([response.data], {
              type: response.data.type
            });
            const url = window.URL.createObjectURL(blob);
            const link = document.createElement('a');
            link.href = url;
            const contentDisposition = response.headers['content-disposition'];
            let fileName = 'hasil-verifikasi.xlsx';
            if (contentDisposition) {
              const fileNameMatch = contentDisposition.match(/filename=(.+)/);
              if (fileNameMatch.length === 2)
                fileName = fileNameMatch[1];
            }
            link.setAttribute('download', fileName);
            document.body.appendChild(link);
            link.click();
            link.remove();
            window.URL.revokeObjectURL(url);
            this.isLoadingExp = false;
          });

        } catch (err) {
          if (err.response && err.response.data.code == 422) {
            this.$nextTick(() => {
              this.form.errors.set(err.response.data.error);
            });
            this.$toast.error("Mohon cek kembali formulir Anda", {
              icon: "times",
              iconPack: "fontawesome",
              duration: 5000
            });
          } else {
            this.$swal.fire(
              "Terjadi kesalahan",
              "Silakan hubungi Admin",
              "error"
            );
          }
        }
        this.loading = false;
      },
      refreshDebounce: debounce(function () {
        this.$bus.$emit('refresh-ajaxtable', 'terverifikasi')
      }, 500),
      onDateSelected: function (daterange) {
        this.params.tanggal_verifikasi_start = getDateIsoString(daterange.start);
        this.params.tanggal_verifikasi_end = getDateIsoString(daterange.end);
      }
    },
    created() {
      this.getKota();
    },
    watch: {
      "params.fasyankes_tipe": function (newVal) {
        this.$refs.ref_nama_rs.inputValue = ''
        this.params.fasyankes = null
        this.getFasyankes(newVal)
      },
    },
  };
</script>