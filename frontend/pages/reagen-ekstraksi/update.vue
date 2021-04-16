<template>
  <div class="wrapper wrapper-content">
    <portal to="title-name">
      Edit Reagen Ekstraksi
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
        <Ibox title="Form Edit Reagen Ekstraksi">
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
    Form,
  } from 'vform';
  import axios from 'axios'
  import {
    metodeEkstraksi
  } from '~/assets/js/constant/enum'
  export default {
    middleware: ['auth', 'checkrole'],
    meta: {
      allow_role_id: [1]
    },
    computed: {
      id_reagen_ekstraksi() {
        return this.$route.params.id;
      },
    },
    async asyncData({
      store,
      route
    }) {
      let resp = await axios.get('/v1/reagen-ekstraksi/' + route.params.id)
      let data = resp ? resp.data.result : {};

      return {
        form: new Form({
          nama: data && data.nama ? data.nama : '',
          metode_ekstraksi: data && data.metode_ekstraksi ? data.metode_ekstraksi : '',
        }),
        metode: metodeEkstraksi,
      }
    },

    methods: {
      initForm() {
        this.form = new Form({
          nama: null,
          metode_ekstraksi: null,
        })
      },
      async submitForm() {
        await axios.put('/v1/reagen-ekstraksi/' + this.id_reagen_ekstraksi, this.form)
          .then((response) => {
            this.$swal.fire(
              'Berhasil Update Data',
              'Reagen Ekstraksi Berhasil diubah',
              'success'
            );
            this.$router.push('/reagen-ekstraksi');
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