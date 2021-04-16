<template>
  <div class="wrapper wrapper-content">
    <portal to="title-name">
      Edit Data Reagen PCR
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
        <Ibox title="Form Tambah Reagen PCR">
          <form @submit.prevent="submitForm" @keydown="form.onKeydown($event)">
            <div class="form-group row">
              <label class="col-md-3 col-form-label text-md-right">Reagen PCR</label>
              <div class="col-md-7">
                <input v-model="form.nama" type="text" name="nama" class="form-control" required :class="{
                  'is-invalid': form.errors.has('nama')}">
                <has-error :form="form" field="nama" />
              </div>
            </div>
            <div class="form-group row">
              <label class="col-md-3 col-form-label text-md-right">Nilai CT Normal</label>
              <div class="col-md-7">
                <input v-model="form.ct_normal" type="number" name="ct_normal" class="form-control" required :class="{
                  'is-invalid': form.errors.has('ct_normal')}">
                <has-error :form="form" field="ct_normal" />
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
    mapGetters
  } from "vuex";
  export default {
    middleware: ['auth', 'checkrole'],
    meta: {
      allow_role_id: [1]
    },
    computed: {
      id_reagen_pcr() {
        return this.$route.params.id;
      },
    },
    async asyncData({
      store,
      route
    }) {
      let resp = await axios.get('/v1/reagen-pcr/' + route.params.id)
      let data = resp ? resp.data.result : {};

      return {
        form: new Form({
          nama: data && data.nama ? data.nama : '',
          ct_normal: data && data.ct_normal ? data.ct_normal : '',
        }),
      }
    },

    methods: {
      initForm() {
        this.form = new Form({
          nama: null,
          ct_normal: null,
        })
      },
      async submitForm() {
        await axios.put('/v1/reagen-pcr/' + this.id_reagen_pcr, this.form)
          .then((response) => {
            this.$swal.fire(
              'Berhasil Update Data',
              'Reagen PCR Berhasil diubah',
              'success'
            );
            this.$router.push('/reagen-pcr');
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