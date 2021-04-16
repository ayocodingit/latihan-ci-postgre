<template>
  <div class="wrapper wrapper-content">
    <portal to="title-name">
      Detail Instansi Pengirim
    </portal>
    <portal to="title-action">
      <div class="title-action">
        <nuxt-link :to="`../update/${form.id}`" class="btn btn-import-export btn-sm">
          <em class="fa fa-pencil" /> Edit Data
        </nuxt-link>
        <button class="btn btn-clear btn-sm" @click="deleteData(form.id)">
          <em class="fa fa-trash" /> Hapus Data
        </button>
        <nuxt-link to="/dinkes" class="btn btn-default btn-sm">List Instansi Pengirim</nuxt-link>
      </div>
    </portal>

    <div class="col-lg-10 row">
      <div class="col-md-12">
        <Ibox title="Informasi Pengguna">
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              ID
            </div>
            <div class="col-md-7 flex-text-center">
              {{form.id || null}}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Nama Fasyankes
            </div>
            <div class="col-md-7 flex-text-center">
              {{form.nama || null}}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Tipe
            </div>
            <div class="col-md-7 flex-text-center">
              {{form.tipe ? getTipeInstansiValue(form.tipe) : null}}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Kota
            </div>
            <div class="col-md-7 flex-text-center">
              {{form.kota || null}}
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
  import {
    optionInstansiPengirim
  } from "~/assets/js/constant/enum"

  export default {
    middleware: ['auth', 'checkrole'],
    meta: {
      allow_role_id: [1]
    },
    computed: {
      id_dinkes() {
        return this.$route.params.id;
      },
    },
    async asyncData({
      route
    }) {
      const resp = await axios.get(`/dinkes/${route.params.id}`)
      const {
        result
      } = resp.data
      return {
        form: new Form({
          id: result.id,
          nama: result.nama,
          tipe: result.tipe,
          kota: result.kota?.nama ? result.kota.nama : '-',
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
            this.$axios.delete('dinkes/' + id)
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
                console.log('error Hapus dinkes : ', error)
              })
          }
        })
      },
      getTipeInstansiValue(tipe) {
        const findData = Array.isArray(optionInstansiPengirim) ? optionInstansiPengirim.find((el) => el.key === tipe) :
          null
        if (findData?.value) {
          return findData.value
        }
        return
      }
    }
  }
</script>