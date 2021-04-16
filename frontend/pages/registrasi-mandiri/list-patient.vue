<template>
  <div class="wrapper wrapper-content">
    <portal to="title-name"> Daftar Pasien </portal>
    <portal to="title-action">
      <div class="title-action">
        <button
          v-if="!isHasAction"
          tag="button"
          class="btn btn-primary"
          data-toggle="modal"
          data-target="#pilihSampel"
          title="Proses data terpilih"
        >
          Proses Data Terpilih
          <span>{{ selectedNomorSampels ? `(${selectedNomorSampels.length})` : ''}}</span>
        </button>
        <nuxt-link tag="button" to="/registrasi/mandiri/tambah" class="btn btn-primary">
          <em class="fa fa-plus" /> Tambah Manual
        </nuxt-link>
        <a href="#" @click.prevent="$router.back()" class="btn btn-black">
          <em class="uil-arrow-left" /> Kembali
        </a>
      </div>
    </portal>

    <div class="row">
      <div class="col-lg-12">
        <Ibox title="Daftar Pasien">
          <p class="sub-header">
            {{ this.$t("subheader_daftar_pasien_registrasi_mandiri") }}
          </p>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group row">
                <div class="col-md-4 flex-text-center">
                  <div for="nama_pasien">Nama Pasien / NIK</div>
                </div>
                <div class="col-md-8">
                  <input class="form-control" type="text" placeholder="Cari berdasarkan Nama / NIK"
                    v-model="filterParams.nama_nik" />
                </div>
              </div>
              <div class="form-group row">
                <div class="col-md-4 flex-text-center">
                  <div for="nama_pasien">Tanggal Mendaftar / Check in</div>
                </div>
                <div class="col-md-8">
                  <rangedate-picker @selected="onDateSelected" ref="rangedatepicker" />
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group row">
                <div class="col-md-4 flex-text-center">
                  <div for="nama_pasien">Kategori</div>
                </div>
                <div class="col-md-8">
                  <input class="form-control" type="text" placeholder="Cari berdasarkan Kategori"
                    v-model="filterParams.kategori" />
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
            ref="mandiri"
            url="/v1/tes-masif"
            :oid="'pasien-tes-masif'"
            :params="filterParams"
            :urlProcessCheckAll="'v1/tes-masif/registering'"
            :disableSort="['checkbox_id']"
            :config="{
              autoload: true,
              has_number: true,
              has_entry_page: true,
              has_pagination: true,
              has_action: isHasAction,
              has_search_input: true,
              custom_header: '',
              default_sort: 'tgl_input',
              default_sort_dir: 'desc',
              custom_empty_page: true,
              class: {
                table: [],
                wrapper: ['table-responsive'],
              },
            }" :rowtemplate="'tr-data-pasien'"
              :columns="{
              checkbox_id: '#',
              nama_pasien: 'PASIEN',
              nama_kota: 'DOMISILI',
              sumber_pasien: 'KATEGORI',
              tgl_input: 'TGL MENDAFTAR',
              no_sampel:'SAMPEL',
            }" />
        </Ibox>
      </div>
    </div>

    <custom-modal modal_id="pilihSampel" title="Registrasi Data">
      <div slot="body">
        <div class="col-lg-12">
          <p>Jumlah Sampel: {{ selectedNomorSampels.length }}</p>
          <div
            class="badge badge-white mr-2 mt-1"
            style="padding:5px;"
            v-for="(item,idx) in selectedNomorSampels"
            :key="idx"
          >
            <span class="flex-text-center">
                {{ getSampel(item) }}
            </span>
          </div>
        </div>
        <button
          @click="submitSampel()"
          :disabled="loading"
          :class="{'btn-loading': loading}"
          class="btn btn-md btn-primary block mt-2 pull-right"
          type="button"
        >
          <em class="fa fa-check" /> Submit
        </button>
      </div>
    </custom-modal>

    <custom-modal modal_id="modalErrorMessage" title="Import Registrasi Error">
      <div slot="body">
        <div class="row">
          <div class="form-group">
            <div v-for="item in dataError" :key="item.id">
              {{ item.message }}
              <ul>
                <li v-for="err in item.messageArr" :key="err.id"> {{ err[0] }}</li>
              </ul>
            </div>
          </div>
        </div>
        <div class="row pull-right">
          <button class="btn btn-md btn-danger" type="button" data-dismiss="modal">
            OK
          </button>
        </div>
      </div>
    </custom-modal>

  </div>
</template>

<script>
  import Form from "vform"
  import $ from "jquery"
  import CustomModal from "~/components/CustomModal"
  import {
    getDateIsoString
  } from '~/utils'
  const JQuery = $
  export default {
    middleware: 'auth',
    components: {
      CustomModal,
    },
    data() {
      let selectedNomorSampels = this.$store.state.pasien_tes_masif.selectedSampels
      return {
        loading: false,
        tableId: 'pasien-tes-masif',
        dataError: [],
        form: new Form({
          id: selectedNomorSampels
        }),
        filterParams: {
          nama_nik: null,
          tanggal_kunjungan_mulai: null,
          tanggal_kunjungan_akhir: null,
          kategori: null,
        },
        selectedNomorSampels,
        isHasAction: true,
      }
    },
    head() {
      return {
        title: this.$t('home')
      }
    },
    watch: {
      'selectedNomorSampels': function () {
        this.selectedNomorSampels.length === 0 ? this.isHasAction = true : this.isHasAction = false
      }
    },
    methods: {
      doFilter() {
        this.$bus.$emit('refresh-ajaxtable', this.tableId, this.filterParams)
      },
      onDateSelected: function (daterange) {
        this.filterParams.tanggal_kunjungan_mulai = getDateIsoString(daterange.start)
        this.filterParams.tanggal_kunjungan_akhir = getDateIsoString(daterange.end)
      },
      clearFilter() {
        this.filterParams.nama_nik = null
        this.filterParams.tanggal_kunjungan_mulai = null
        this.filterParams.tanggal_kunjungan_akhir = null
        this.filterParams.kategori = null
        this.$refs.rangedatepicker.$data.dateRange = {}
        this.$bus.$emit('refresh-ajaxtable', this.tableId, this.filterParams)
      },
      async submitSampel() {
        JQuery('#pilihSampel').modal('hide')
        try {
          const response = await this.form.post("v1/tes-masif/registering")
          this.$toast.success(response.message, {
            icon: 'check',
            iconPack: 'fontawesome',
            duration: 5000
          })
          this.isHasAction = true
          this.$store.commit('pasien_tes_masif/clear')
          this.$bus.$emit('refresh-ajaxtable', 'pasien-tes-masif')
          location.reload()
        } catch (err) {
          this.$swal.fire("Terjadi kesalahan", "Silakan hubungi Admin", "error")
        }
      },
      getSampel(item) {
        return document.getElementById('selected-sampel-' + item).getAttribute('nomor_sampel')
      }
    },
  }
</script>