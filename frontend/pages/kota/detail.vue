<template>
  <div class="wrapper wrapper-content">
    <portal to="title-name">
      Detail Kota
    </portal>
    <portal to="title-action">
      <div class="title-action">
        <nuxt-link to="/kota" class="btn btn-default">List Kota</nuxt-link>
      </div>
    </portal>

    <div class="col-lg-10 row">
      <div class="col-md-12">
        <Ibox title="Informasi Kota">
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Nama Kota
            </div>
            <div class="col-md-7 flex-text-center">
              {{form.nama || null}}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Provinsi
            </div>
            <div class="col-md-7 flex-text-center">
              {{form.provinsi || null}}
            </div>
          </div>
        </Ibox>
      </div>
    </div>

  </div>
</template>

<script>
  import {
    Form
  } from 'vform'
  import axios from 'axios'

  export default {
    middleware: ['auth', 'checkrole'],
    meta: {
      allow_role_id: [1]
    },
    computed: {
      id_kota() {
        return this.$route.params.id;
      },
    },
    async asyncData({
      route
    }) {
      var f4
      f4 = axios.get(`/kota/${route.params.id}`)
      let data = (await f4).data.result
      return {
        form: new Form({
          id: data.id,
          nama: data.nama,
          provinsi: data.provinsi.nama,
        }),
      }
    },
    data: () => ({
      roles: [],
      errors: [],
    }),

    methods: {
      deleteData(id) {
        this.$swal.fire({
          title: 'Apakah Anda Yakin ?',
          text: "untuk menghapus Akun",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Ya',
          cancelButtonText: 'Tidak',
        }).then((result) => {
          if (result.value) {
            this.$axios.delete(`kota/${id}`)
              .then((response) => {
                this.$swal.fire(
                  'Berhasil Menghapus Data',
                  'Data Pengguna Berhasil dihapus',
                  'success'
                );
                this.$bus.$emit('refresh-ajaxtable', 'master-user')
              })
              .catch((error) => {
                this.$swal.fire(
                  'Terjadi Kesalahan',
                  'Gagal menghapus data, silakan coba kembali',
                  'error'
                );
                console.log('error Hapus kota : ', error)
              })
          }
        })
      }
    }
  }
</script>