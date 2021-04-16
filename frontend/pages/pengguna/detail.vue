<template>
  <div class="wrapper wrapper-content">
    <portal to="title-name">
      Detail Pengguna
    </portal>
    <portal to="title-action">
      <div class="title-action">
        <nuxt-link :to="`../update/${form.id}`" class="mb-1 btn btn-primary btn-sm">
          <em class="fa fa-pencil" /> Edit Data
        </nuxt-link>
        <button class="mb-1 btn btn-clear btn-sm" @click="deleteData(form.id)">
          <em class="fa fa-trash" /> Hapus Data
        </button>
        <nuxt-link to="/pengguna" class="btn btn-default">List Pengguna</nuxt-link>
      </div>
    </portal>

    <div class="col-lg-10 row">
      <div class="col-md-12">
        <Ibox title="Informasi Pengguna">
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Nama Pengguna
            </div>
            <div class="col-md-7 flex-text-center">
              {{form.name || null}}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Username
            </div>
            <div class="col-md-7 flex-text-center">
              {{form.username || null}}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              {{ $t('email') }}
            </div>
            <div class="col-md-7 flex-text-center">
              {{form.email || null}}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Role
            </div>
            <div class="col-md-7 flex-text-center">
              <div v-for="item in roles" :key="item.id">
                <div v-if="form.role_id == item.id">
                  {{ item.text }}
                </div>
              </div>
            </div>
          </div>
        </Ibox>
      </div>
    </div>
  </div>
</template>

<script>
  import {
    Form,
    HasError,
    AlertError
  } from 'vform'
  import axios from 'axios'
  import {
    mapGetters
  } from "vuex";

  export default {
    middleware: ['auth', 'checkrole'],
    meta: {
      allow_role_id: [1]
    },
    computed: {
      id_user() {
        return this.$route.params.id;
      },
    },
    async asyncData({
      store,
      route
    }) {
      const response = await axios.get('/pengguna/' + route.params.id)
      const roles = await axios.get('/roles-option');
      const data = response.data.result
      return {
        roles: roles.data,
        form: new Form({
          id: data.id,
          name: data.name,
          email: data.email,
          username: data.username,
          role_id: data.role_id,
          lab_pcr_id: data.lab_pcr_id,
          lab_satelit_id: data.lab_satelit_id,
          validator_id: data.validator_id,
          password: '',
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
            this.$axios.delete('pengguna/' + id)
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
              })
          }
        })
      }
    }
  }
</script>