<template>
  <div class="wrapper wrapper-content">
    <portal to="title-name">
      Tambah Reagen Ekstraksi
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
        <Ibox title="Form Tambah Reagen Ekstraksi">
          <form @submit.prevent="submitForm" @keydown="form.onKeydown($event)">
            <div class="form-group row">
              <label class="col-md-3 col-form-label text-md-right">Metode Ekstraksi</label>
              <div class="col-md-7">
                <select v-model="form.metode_ekstraksi" name="metode_ekstraksi" required class="form-control" :class="{
                  'is-invalid': form.errors.has('metode_ekstraksi')}">
                  <option v-for="(item, idx) in metode" :key="idx" :value="item">
                    {{ item }}
                  </option>
                </select>
                <has-error :form="form" field="metode_ekstraksi" />
              </div>
            </div>
            <div class="form-group row">
              <label class="col-md-3 col-form-label text-md-right">Reagen Ekstraksi</label>
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
  import {
    metodeEkstraksi
  } from '~/assets/js/constant/enum'

  export default {
    middleware: ['auth', 'checkrole'],
    meta: {
      allow_role_id: [1]
    },
    data() {
      return {
        form: new Form({
          nama: '',
          metode_ekstraksi: '',
        }),
        metode: metodeEkstraksi,
      }
    },
    methods: {
      async submitForm() {
        try {
          const response = await this.form.post("/v1/reagen-ekstraksi");
          this.$toast.success('Reagen Ekstraksi berhasil ditambahkan', {
            icon: 'check',
            iconPack: 'fontawesome',
            duration: 5000
          })
          setTimeout(() => this.$router.push({
            path: '/reagen-ekstraksi'
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