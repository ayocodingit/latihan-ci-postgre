<template>
  <div class="wrapper wrapper-content">
    <portal to="title-name">Registrasi Rujukan (R)</portal>
    <portal to="title-action">
      <div class="title-action">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addRegistrasiRujukan">
          <em class="fa fa-plus" /> Register Baru
        </button>
        <nuxt-link tag="button" to="/registrasi/rujukan/import-excel" class="btn btn-import-export">
          <em class="fa fa-download" /> Import
        </nuxt-link>
        <button tag="button" class="btn btn-import-export" data-toggle="modal" data-target="#exportRR">
          <em class="fa fa-download" /> Export
        </button>
      </div>
    </portal>

    <div class="row">
      <div class="col-lg-12">
        <filter-registrasi :oid="`registrasi-rujukan`" />
      </div>
    </div>

    <div class="row">
      <div class="col-lg-12">
        <Ibox title="Daftar Pasien Rujukan">
          <ajax-table ref="rujukan" urlexport="/registrasi-rujukan/export-excel-rujukan" url="/registrasi-mandiri"
            :oid="'registrasi-rujukan'" :params="params" :disableSort="['keterangan']" :config="{
							autoload: true,
							has_number: true,
							has_entry_page: true,
							has_pagination: true,
							has_action: true,
							has_search_input: true,
							custom_header: '',
							default_sort: 'tgl_input',
							default_sort_dir:'desc',
							custom_empty_page: true,
							class: {
								table: [],
								wrapper: ['table-responsive'],
							}
						}" :rowtemplate="'tr-data-regis-rujukan'" :columns="{
							nomor_register: 'NO REGISTRASI',
							nama_pasien: 'PASIEN',
							status: $t('label.pasien.status').toUpperCase(),
							nama_kota: 'DOMISILI',
							sumber_pasien: 'KATEGORI',
							fasyankes_nama: 'INSTANSI PENGIRIM',
							no_sampel:'SAMPEL',
							tgl_input:'WAKTU INPUT',
							keterangan: 'KETERANGAN'
						}" />
        </Ibox>
      </div>
    </div>

    <custom-modal modal_id="addRegistrasiRujukan" title="Register Pasien Rujukan">
      <div slot="body">
        <div class="col-lg-12">
          <p class="sub-header">
            Scan / masukan nomor barcode salah satu sampel untuk register pasien rujukan
          </p>
          <form id="scanbarcode row" action method="post" @submit.prevent="submit" @keydown="form.onKeydown($event)">
            <div class="form-group">
              <div class="col-md-12 flex-text-center">
                <input id="barcodesampel" class="form-control" name placeholder="Scan..." type="text" tabindex="1"
                  required autofocus v-model="form.nomor_sampel" />
              </div>
              <div class="col-md-12 flex-text-center mt-3">
                <v-button :loading="form.busy" class="btn btn-md btn-primary">
                  <em class="fa fa-plus" /> Tambahkan Informasi Register
                </v-button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </custom-modal>

    <custom-modal modal_id="exportRR" title="Export Data">
      <div slot="body">
        <div class="col-lg-12">
          <div class="form-group">
            <label>
              Get a copy of your data to an offline excel file
            </label>
          </div>
          <div class="form-group">
            <download-export-button :parentRefs="$refs" ajaxTableRef="rujukan" />
          </div>
        </div>
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
  import Form from "vform";
  import JQuery from "jquery";
  import CustomModal from "~/components/CustomModal";
  export default {
    middleware: "auth",
    components: {
      CustomModal,
    },
    data() {
      return {
        loading: false,
        dataError: [],
        form: new Form({
          nomor_sampel: null
        }),
        params: {
          jenis_registrasi: "rujukan"
        },
        params1: {
          sam_barcodenomor_sampel: null
        },
        params2: {
          sam_barcodenomor_sampel: null
        }
      };
    },
    head() {
      return {
        title: "Penerimaan Sampel"
      };
    },
    methods: {
      async submit() {
        try {
          const response = await this.form.post("/registrasi-rujukan/cek");
          if (response.status == 200) {
            this.$router.push('/registrasi/rujukan/add/' + this.form.nomor_sampel);
            JQuery('#addRegistrasiRujukan').modal('hide');
          }
        } catch (e) {
           if (e.response.status == 422) {
            this.$toast.error(e.response.data.message, {
              icon: 'times',
              iconPack: 'fontawesome',
              duration: 5000
            })
          }
        }
      }
    }
  };
</script>