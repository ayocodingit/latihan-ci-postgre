<template>
  <div class="wrapper wrapper-content">
    <portal to="title-name">
      Daftar Pengguna
    </portal>
    <portal to="title-action">
      <div class="title-action">
        <nuxt-link to="/pengguna/tambah" class="btn btn-primary">
          <em class="uil-plus" /> Tambah Data
        </nuxt-link>
      </div>
    </portal>
    <div class="row">
      <div class="col-lg-12">
        <Ibox title="Daftar Pengguna">
          <ajax-table url="/pengguna" :oid="'master-user'" :params="params" :config="{
            autoload: true,
            has_number: true,
            has_entry_page: true,
            has_pagination: true,
            has_action: true,
            has_search_input: true,
            has_search_input2: true,
            custom_header: '',
            default_sort: 'name',
            custom_empty_page: true,
            class: {
              table: [],
              wrapper: ['table-responsive'],
            }
          }" :rowtemplate="'tr-data-user'" :columns="{
            name: 'NAMA',
            username: 'USERNAME',
            email: 'EMAIL',
            role_id: 'ROLE',
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
          nama: null
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