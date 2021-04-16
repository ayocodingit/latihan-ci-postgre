<template>
  <div class="wrapper wrapper-content">
    <portal to="title-name">Tervalidasi</portal>
    <portal to="title-action">
      <div class="title-action">
        <download-export-button :parentRefs="$refs" ajaxTableRef="validated" class="btn btn-import-export" />
      </div>
    </portal>

    <div class="row">
      <div class="col-lg-12">
        <Ibox title="Filter">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group row">
                <div class="col-md-3 flex-text-center">
                  <div>Nama Pasien</div>
                </div>
                <div class="col-md-8">
                  <input class="form-control" type="text" v-model="params.nama_pasien" />
                </div>
              </div>
              <div class="form-group row">
                <div class="col-md-3 flex-text-center">
                  <div>Tanggal Periksa</div>
                </div>
                <div class="col-md-8 input-group p-0">
                  <div class="col-md-6">
                    <date-picker placeholder="Tanggal Awal" format="dd MMMM yyyy" ref="tanggal_periksa_start"
                      input-class="form-control" :monday-first="true" v-model="params.tanggal_periksa_start" />
                  </div>
                  <div class="col-md-6">
                    <date-picker placeholder="Tanggal Akhir" format="dd MMMM yyyy" ref="tanggal_periksa_end"
                      input-class="form-control" :monday-first="true" v-model="params.tanggal_periksa_end" />
                  </div>
                </div>
              </div>
              <div class="form-group row">
                <div class="col-md-3 flex-text-center">
                  <div>Tanggal Validasi</div>
                </div>
                <div class="col-md-8 input-group p-0">
                  <div class="col-md-6">
                    <date-picker placeholder="Tanggal Awal" format="dd MMMM yyyy" ref="tanggal_validasi_start"
                      input-class="form-control" :monday-first="true" v-model="params.tanggal_validasi_start" />
                  </div>
                  <div class="col-md-6">
                    <date-picker placeholder="Tanggal Akhir" format="dd MMMM yyyy" ref="tanggal_validasi_end"
                      input-class="form-control" :monday-first="true" v-model="params.tanggal_validasi_end" />
                  </div>
                </div>
              </div>
            </div>

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
                  <div>Hasil Pemeriksaan</div>
                </div>
                <div class="col-md-7">
                  <dynamic-input :form="params" field="hasil_periksa" v-model="params.hasil_periksa"
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
        </Ibox>
        <Ibox title="Hasil Pemeriksaan Swab Antigen">
          <ajax-table ref="validated" url="/v1/swab-antigen" :oid="'swab-antigen-validasi'" urlexport="/v1/swab-antigen/export-excel"
            :disableSort="['checkbox_input']"
            :params="params" :config="{
              autoload: true,
              has_number: true,
              has_entry_page: true,
              has_pagination: true,
              has_action: true,
              has_search_input: false,
              custom_empty_page: false,
              default_sort: 'tanggal_validasi',
              default_sort_dir: 'desc',
              class: {
                table: [],
                wrapper: ['table-responsive'],
              }
            }" :rowtemplate="'tr-swab-antigen-validated'" :columns="{
              nomor_registrasi: 'NO REGISTRASI',
              nama_pasien : 'PASIEN',
              tanggal_lahir : 'TANGGAL LAHIR',
              domisili: 'DOMISILI',
              kategori: 'KATEGORI',
              tanggal_periksa: 'TANGGAL PERIKSA',
              no_hasil: 'NO HASIL',
              hasil_periksa : 'HASIL PEMERIKSAAN',
              tanggal_validasi: 'TANGGAL VALIDASI'
            }" />
        </Ibox>
      </div>
    </div>

  </div>
</template>

<script>
  import {
    KesimpulanPemeriksaan
  } from '~/assets/js/constant/enum'
  import {
    momentFormatDateYmd
  } from '~/utils'

  export default {
    middleware: ['auth', 'checkrole'],
    meta: {
      allow_role_id: [1, 7]
    },
    head() {
      return {
        title: "Hasil Pemeriksaan Swab Antigen"
      }
    },
    data() {
      return {
        loading: false,
        dataError: [],
        dataKesimpulanPemeriksaan: KesimpulanPemeriksaan.data,
        params: {
          nama_pasien: "",
          tanggal_periksa_start: "",
          tanggal_periksa_end: "",
          tanggal_validasi_start: "",
          tanggal_validasi_end: "",
          kategori: "",
          hasil_periksa: "",
          status: "tervalidasi"
        }
      }
    },
    created() {
      this.$store.commit('swab_antigen_hasil/clear')
    },
    methods: {
      doFilter() {
        if (this.params.tanggal_periksa_start) {
          this.params.tanggal_periksa_start = momentFormatDateYmd(this.params.tanggal_periksa_start)
        }
        if (this.params.tanggal_periksa_end) {
          this.params.tanggal_periksa_end = momentFormatDateYmd(this.params.tanggal_periksa_end)
        }
        if (this.params.tanggal_validasi_start) {
          this.params.tanggal_validasi_start = momentFormatDateYmd(this.params.tanggal_validasi_start)
        }
        if (this.params.tanggal_validasi_end) {
          this.params.tanggal_validasi_end = momentFormatDateYmd(this.params.tanggal_validasi_end)
        }
        this.$bus.$emit('refresh-ajaxtable2', 'swab-antigen-validasi', this.params)
      },
      clearFilter() {
        this.params.nama_pasien = ""
        this.params.tanggal_periksa_start = ""
        this.params.tanggal_periksa_end = ""
        this.params.tanggal_validasi_start = ""
        this.params.tanggal_validasi_end = ""
        this.params.kategori = ""
        this.params.hasil_periksa = ""
        this.params.status = "tervalidasi"
        this.$bus.$emit("clear-dropdown")
        this.$bus.$emit("refresh-ajaxtable2", "swab-antigen-validasi", this.params)
      }
    }
  }
</script>
