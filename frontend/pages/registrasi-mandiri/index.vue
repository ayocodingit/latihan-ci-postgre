<template>
  <div class="wrapper wrapper-content">
    <portal to="title-name">
      Registrasi Mandiri (L)
    </portal>
    <portal to="title-action">
      <div class="title-action">
        <nuxt-link tag="button" to="/registrasi/mandiri/daftar-pasien" class="btn btn-primary">
          <em class="fa fa-plus" /> Register Baru
        </nuxt-link>
        <button tag="button" class="btn btn-import-export" data-toggle="modal" data-target="#importRM">
          <em class="fa fa-download" /> Import
        </button>
        <button tag="button" class="btn btn-import-export" data-toggle="modal" data-target="#exportRM">
          <em class="fa fa-download" /> Export
        </button>
      </div>
    </portal>

    <div class="row">
      <div class="col-lg-12">
        <filter-registrasi :oid="`registrasi-mandiri`" />
      </div>
    </div>

    <div class="row">
      <div class="col-lg-12">
        <Ibox title="Daftar Pasien Mandiri">
          <ajax-table ref="mandiri" urlexport="/registrasi-mandiri/export-excel" url="/registrasi-mandiri"
            :oid="'registrasi-mandiri'" :params="params" :disableSort="['keterangan']" :config="{
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
						}" :rowtemplate="'tr-data-regis-mandiri'" :columns="{
							nomor_register: 'NO REGISTRASI',
							nama_pasien: 'PASIEN',
              status: $t('label.pasien.status').toUpperCase(),
							nama_kota: 'DOMISILI',
							sumber_pasien: 'KATEGORI',
							no_sampel:'SAMPEL',
							tgl_input:'WAKTU INPUT',
							keterangan:'KETERANGAN',
						}" />
        </Ibox>
      </div>
    </div>

    <custom-modal modal_id="importRM" title="Import Data">
      <div slot="body">
        <div class="col-lg-12">
          <div class="form-group">
            <dropzone-import-excel :previewFile="previewFile" />
          </div>
          <div class="form-group">
            <button @click="doImport()" :disabled="loading" :class="{'btn-loading': loading}"
              class="btn btn-md btn-primary block m-b pull-right" type="button">
              <em class="fa fa-check" /> Import Excel
            </button>
          </div>
        </div>
      </div>
    </custom-modal>

    <custom-modal modal_id="exportRM" title="Export Data">
      <div slot="body">
        <div class="col-lg-12">
          <div class="form-group">
            <label>
              Get a copy of your data to an offline excel file
            </label>
          </div>
          <div class="form-group">
            <download-export-button :parentRefs="$refs" ajaxTableRef="mandiri" />
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
  import $ from "jquery";
  import CustomModal from "~/components/CustomModal";
  const JQuery = $;
  export default {
    middleware: 'auth',
    components: {
      CustomModal,
    },
    data() {
      return {
        loading: false,
        dataError: [],
        params: {
          jenis_registrasi: "mandiri",
          nama_pasien: null,
          nomor_register: null,
          nomor_sampel: null,
          start_date: null,
          end_date: null
        },
      }
    },
    head() {
      return {
        title: this.$t('home')
      }
    },
    async asyncData({
      route,
      store
    }) {
      let form = new Form({
        register_file: null
      });
      return {
        form,
      };
    },
    methods: {
      async doImport() {
        let formData = new FormData();
        formData.append('register_file', this.form.register_file);
        this.loading = true;
        JQuery('#importRM').modal('hide');
        try {
          await this.$axios.post(`${process.env.apiUrl}/v1/register/import-mandiri`, formData, {
            headers: {
              'Content-Type': 'multipart/form-data'
            }
          });
          this.$toast.success('Sukses import data', {
            icon: "check",
            iconPack: "fontawesome",
            duration: 5000
          });
        } catch (err) {
          if (err.response && err.response.data.code == 422) {
            let errMessageArr = []
            for (const property in err.response.data.error) {
              errMessageArr.push({
                id: property,
                message: `Baris ${property}`,
                messageArr: err.response.data.error[property]
              });
            }
            this.dataError = errMessageArr;
            JQuery('#modalErrorMessage').modal('show');
          }
          if (err.response && err.response.data.code == 403) {
            this.$toast.error(err.response.data.error, {
              icon: "times",
              iconPack: "fontawesome",
              duration: 5000
            });
          }
          if (err.response && err.response.data.code == 500) {
            this.$swal.fire(
              "Terjadi kesalahan",
              "Silakan hubungi Admin",
              "error"
            );
          }
        }
        this.$bus.$emit('refresh-ajaxtable', 'registrasi-mandiri');
        $('#register_file').val('');
        this.form.reset();
        this.loading = false;
      },
      previewFile(file) {
        this.form.register_file = file;
      },
    }
  }
</script>