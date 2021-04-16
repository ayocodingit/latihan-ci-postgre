<template>
  <div class="wrapper wrapper-content">
    <portal to="title-name">
      Edit Jenis VTM
    </portal>
    <portal to="title-action">
      <div class="title-action">
        <a href="#" @click.prevent="$router.back()" class="btn btn-black">
          <em class="uil-arrow-left" />Kembali
        </a>
      </div>
    </portal>
    <div class="row">
      <div class="col-lg-8">
        <Ibox title="Form Tambah Jenis VTM">
          <form @submit.prevent="submitForm" @keydown="form.onKeydown($event)">
            <div class="form-group row">
              <label class="col-md-3 col-form-label text-md-right">Jenis VTM</label>
              <div class="col-md-7">
                <input v-model="form.nama" type="text" name="nama" class="form-control" required :class="{
                  'is-invalid': form.errors.has('nama')}">
                <has-error :form="form" field="nama" />
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-7 offset-md-3 d-flex">
                <v-button :loading="form.busy">
                  OK
                </v-button>
              </div>
            </div>
          </form>
        </Ibox>
      </div>
    </div>
  </div>
</template>

<script>
  import {
    Form,
  } from 'vform';
  import axios from 'axios'
  export default {
    middleware: ['auth', 'checkrole'],
    meta: {
      allow_role_id: [1]
    },
    computed: {
      id_vtm() {
        return this.$route.params.id;
      },
    },
    async asyncData({
      store,
      route
    }) {
      let resp = await axios.get('/v1/jenis-vtm/' + route.params.id)
      let data = resp ? resp.data.result : {};

      return {
        form: new Form({
          nama: data && data.nama ? data.nama : '',
        }),
      }
    },

    methods: {
      initForm() {
        this.form = new Form({
          nama: null,
        })
      },
      async submitForm() {
        await axios.put('/v1/jenis-vtm/' + this.id_vtm, this.form)
          .then((response) => {
            this.$swal.fire(
              'Berhasil Update Data',
              'Jenis VTM Berhasil diubah',
              'success'
            );
            this.$router.push('/jenis-vtm');
          })
          .catch((err) => {
            this.$nextTick(() => {
              this.form.errors.set(err.response.data.error)
            })
            this.$toast.error('Mohon cek kembali formulir Anda', {
              icon: 'times',
              iconPack: 'fontawesome',
              duration: 5000
            })
          });
      }
    },
  }
</script>