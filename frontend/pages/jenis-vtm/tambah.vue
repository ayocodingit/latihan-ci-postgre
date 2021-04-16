<template>
  <div class="wrapper wrapper-content">
    <portal to="title-name">
      Tambah Jenis VTM
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
    Form
  } from 'vform';

  export default {
    middleware: ['auth', 'checkrole'],
    meta: {
      allow_role_id: [1]
    },
    data() {
      return {
        form: new Form({
          nama: ''
        }),
      }
    },
    methods: {
      async submitForm() {
        try {
          const response = await this.form.post("/v1/jenis-vtm");
          this.$toast.success('Jenis VTM berhasil ditambahkan', {
            icon: 'check',
            iconPack: 'fontawesome',
            duration: 5000
          })
          setTimeout(() => this.$router.push({
            path: '/jenis-vtm'
          }), 1000);
        } catch (err) {
          if (err.response && err.response.data.code == 422) {
            this.$nextTick(() => {
              this.form.errors.set(err.response.data.error)
            })
            this.$toast.error('Mohon cek kembali formulir Anda', {
              icon: 'times',
              iconPack: 'fontawesome',
              duration: 5000
            })
          } else {
            this.$swal.fire("Terjadi kesalahan", "Silakan hubungi Admin", "error");
          }
        }
      },
    }
  }
</script>