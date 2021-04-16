<template>
  <div class="wrapper wrapper-content">
    <portal to="title-name">
      Daftar Kota
    </portal>
    <portal to="title-action">

    </portal>
    <div class="row">
      <div class="col-lg-12">
        <Ibox title="Daftar kota">
          <ajax-table url="/kota" :oid="'kota'" :params="params" :config="{
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
          }" :rowtemplate="'tr-data-kota'" :columns="{
            nama: 'NAMA',
            provinsi: 'PROVINSI',
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
          provinsi: null,
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