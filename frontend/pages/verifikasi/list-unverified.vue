<template>
  <div class="wrapper wrapper-content">
    <portal to="title-name">Menunggu Verifikasi</portal>
    <portal to="title-action">
      <div class="title-action">
        <button v-if="!isHasAction" tag="button" class="btn btn-primary" data-toggle="modal" data-target="#pilihSampel"
          title="Proses data terpilih">
          Verifikasi Sampel Terpilih
          <span>{{ selectedNomorSampels ? `(${selectedNomorSampels.length})` : ''}}</span>
        </button>
        <download-export-button :parentRefs="$refs" ajaxTableRef="verifikasi" class="btn btn-import-export" />
      </div>
    </portal>

    <div class="row">
      <div class="col-lg-12">
        <Ibox title="Sampel Menunggu Verifikasi">
          <p class="sub-header">Berikut ini adalah daftar sampel dari hasil pemeriksaan</p>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group row">
                <div class="col-md-4 flex-text-center">
                  <div>Kategori</div>
                </div>
                <div class="col-md-7">
                  <input class="form-control" type="text" v-model="params.kategori" />
                </div>
              </div>
              <div class="form-group row">
                <div class="col-md-4 flex-text-center">
                  <div for="nama_pasien">Instansi Pengirim</div>
                </div>
                <div class="col-md-7">
                  <input-option-instansi-pengirim :form="params" field="fasyankes_tipe" />
                </div>
              </div>
              <div class="form-group row">
                <div class="col-md-4 flex-text-center">
                  <div for="nama_pasien">Nama Rumah Sakit / Fasyankes</div>
                </div>
                <div class="col-md-7">
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
            ref="verifikasi"
            urlexport="/v1/verifikasi/export-excel-verifikasi"
            url="/v1/verifikasi/list"
            :oid="'verifikasi'"
            :disableSort="['parameter_lab','kondisi_sampel']"
            :params="params"
            :config="{
              autoload: true,
              has_number: true,
              has_entry_page: true,
              has_pagination: true,
              has_action: isHasAction,
              has_search_input: true,
              custom_header: '',
              default_sort: '',
              custom_empty_page: true,
              class: {
                table: [],
                wrapper: ['table-responsive'],
                }
            }"
            :rowtemplate="'tr-verifikasi'"
            :columns="{
              checkbox_id: '#',
              nomor_register: 'NO REGISTRASI',
              nama_pasien: 'PASIEN',
              sumber_pasien: 'KATEGORI',
              nama_kota: 'DOMISILI',
              fasyankes_nama: 'INSTANSI PENGIRIM',
              nomor_sampel : 'NO SAMPEL',
              parameter_lab: 'PARAMETER LAB',
              kondisi_sampel: 'KONDISI SAMPEL',
              kesimpulan_pemeriksaan: 'KESIMPULAN PEMERIKSAAN',
              }" />
        </Ibox>
      </div>
    </div>


    <custom-modal modal_id="pilihSampel" title="Hapus Semua Sampel Registrasi Terpilih">
      <div slot="body">
        <div class="col-lg-12">
          <p>Jumlah Sampel: {{ selectedNomorSampels.length }}</p>
          <div class="badge badge-white mr-2 mt-1" style="padding:5px;" v-for="(item,idx) in selectedNomorSampels"
            :key="idx">
            <span class="flex-text-center">
              {{ getSampel(item) }}
            </span>
          </div>
        </div>
        <button @click="verifikasiSampel()" :disabled="loading" :class="{'btn-loading': loading}"
          class="btn btn-md btn-primary block mt-2 pull-right" type="button">
          <em class="fa fa-check" /> Verifikasi
        </button>
      </div>
    </custom-modal>

  </div>
</template>

<script>
  import axios from "axios";
  import Form from "vform";
  import $ from "jquery";
  import VueBootstrapTypeahead from 'vue-bootstrap-typeahead';
  import {
    KesimpulanPemeriksaan
  } from './../../assets/js/constant/enum';
  import CustomModal from "~/components/CustomModal"

  const JQuery = $;
  const debounce = require('lodash/debounce')

  export default {
    middleware: "auth",
    components: {
      VueBootstrapTypeahead,
      CustomModal,
    },
    data() {
      const selectedNomorSampels = this.$store.state.verifikasi_sampel.selectedSampels
      return {
        loading: false,
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
        listSampels: [],
        isHasAction: true,
        selectedNomorSampels,
        form: new Form({
          id: selectedNomorSampels
        })
      };
    },
    head() {
      return {
        title: "Sampel Hasil Pemeriksaan"
      };
    },
    methods: {
      async getSampels() {
        let resp = await this.$axios.get('/v1/verifikasi/list')
        const {
          data
        } = resp.data || []
        this.listSampels = data
      },
      getSampel(id) {
        const findSampel = this.listSampels.find((el) => el.id === parseInt(id))
        if (findSampel) {
          return findSampel.nomor_sampel
        }
        return ''
      },
      async verifikasiSampel() {
        JQuery('#pilihSampel').modal('hide')
        try {
          const response = await this.form.post("/v1/verifikasi/verifikasi-bulk-sampel/")
          this.isHasAction = true
          this.$store.commit('verifikasi_sampel/clear')
          this.$bus.$emit('refresh-ajaxtable', 'verifikasi')
          if (response?.data?.message === 'success') {
            const {
              value: isConfirm
            } = await this.$swal.fire({
              type: "success",
              title: "Berhasil verifikasi sampel"
            })
            if (isConfirm) {
              location.reload()
            }
          }
        } catch (error) {
          this.$swal.fire(
            "Terjadi kesalahan",
            error.response.data.error,
            "error"
          )
        }
      },
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
        const resp = await this.$axios.get(`/v1/list-fasyankes-jabar?tipe=${tipe}`)
        this.optionsFasyankes = resp.data
      },
      getDomisiliValue(result) {
        const findKota = this.optionKota.find(element => element.nama === result);
        if (findKota) {
          this.params.kota_domisili = findKota.id;
        }
      },
      onSelectedFasyankes(result) {
        this.params.fasyankes = result.id;
      },
      doFilter() {
        this.$bus.$emit('refresh-ajaxtable2', 'verifikasi', this.params);
      },
      clearFilter() {
        document.querySelectorAll("[id*='options_tipe_dinkes']").forEach(el => el.checked = false)
        this.$refs.ref_nama_rs.inputValue = '';
        this.params.no_sampel_start = "";
        this.params.no_sampel_end = "";
        this.params.kota_domisili = "";
        this.params.tanggal_verifikasi_start = "";
        this.params.tanggal_verifikasi_end = "";
        this.params.kesimpulan_pemeriksaan = "";
        this.params.kategori = "";
        this.params.fasyankes_tipe = "";
        this.params.fasyankes = "";
        this.$refs.autocompletedomisili.inputValue = ''
        this.nama_kota = null
        this.$bus.$emit("clear-input-instansi");
        this.$bus.$emit("clear-dropdown");
        this.$bus.$emit("refresh-ajaxtable", "verifikasi", this.params);
      },
      async onExport(type) {
        try {
          this.loading = true;

          let form = new Form({
            ...this.params,
            type: type
          })

          this.$axios({
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
            let fileName = 'belum-verifikasi.xlsx';
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
        this.$bus.$emit('refresh-ajaxtable', 'verifikasi')
      }, 500),
    },
    created() {
      this.getKota()
      this.getSampels()
    },
    watch: {
      "params.fasyankes_tipe": function (newVal) {
        this.$refs.ref_nama_rs.inputValue = ''
        this.params.fasyankes = null
        this.getFasyankes(newVal)
      },
      'selectedNomorSampels': function () {
        this.selectedNomorSampels.length === 0 ? this.isHasAction = true : this.isHasAction = false
      }
    },
  }
</script>