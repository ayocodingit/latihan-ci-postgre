<template>
  <div class="wrapper wrapper-content">
    <portal to="title-name">
      Daftar Reagen Ekstraksi
    </portal>
    <portal to="title-action">
      <div class="title-action">
        <nuxt-link to="/reagen-ekstraksi/tambah" class="btn btn-primary">
          <em class="uil-plus" /> Tambah Data
        </nuxt-link>
      </div>
    </portal>
    <div class="row">
      <div class="col-lg-10">
        <Ibox>
          <ajax-table url="/v1/reagen-ekstraksi" :oid="'master-reagen-ekstraksi'" :params="params" :config="{
            autoload: true,
            has_number: true,
            has_entry_page: true,
            has_pagination: true,
            has_action: true,
            has_search_input: true,
            has_search_input2: true,
            custom_header: '',
            default_sort: 'nama',
            custom_empty_page: true,
            class: {
              table: [],
              wrapper: ['table-responsive'],
            }
          }" :rowtemplate="'tr-data-reagen-ekstraksi'" :columns="{
            nama: 'REAGEN EKSTRAKSI',
            metode_ekstraksi: 'METODE EKSTRAKSI',
          }" />
        </Ibox>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    middleware: ['auth', 'checkrole'],
    meta: {
      allow_role_id: [1]
    },
    data() {
      return {
        params: {
          nama: null,
          metode_ekstraksi: null,
        },
      }
    },
    head() {
      return {
        title: this.$t('home')
      }
    }
  }
</script>