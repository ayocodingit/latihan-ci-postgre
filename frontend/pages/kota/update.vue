<template>
  <div class="wrapper wrapper-content">
    <portal to="title-name">
      Update Kota
    </portal>
    <portal to="title-action">
      <div class="title-action">
        <nuxt-link to="/kota" class="btn btn-primary">List Kota</nuxt-link>
      </div>
    </portal>
    <div class="row">
      <div class="col-lg-8">
        <Ibox title="Form Tambah Kota">
          <form @submit.prevent="submitForm">
            <!-- Id -->
            <div class="form-group row">
              <label class="col-md-3 col-form-label text-md-right">Id</label>
              <div class="col-md-7">
                <input v-model="form.id" :class="{ 'is-invalid': errors.id!=null }" type="text" name="id"
                  class="form-control">
                <p class="text-danger" v-if="errors.id">{{errors.id[0]}}</p>
              </div>
            </div>
            <!-- Nama -->
            <div class="form-group row">
              <label class="col-md-3 col-form-label text-md-right">Nama Kota</label>
              <div class="col-md-7">
                <input v-model="form.nama" :class="{ 'is-invalid': errors.nama!=null }" type="text" name="nama"
                  class="form-control">
                <p class="text-danger" v-if="errors.nama">{{errors.nama[0]}}</p>
              </div>
            </div>

            <!-- Provinsi -->
            <div class="form-group row">
              <label class="col-md-3 col-form-label text-md-right">Provinsi</label>
              <div class="col-md-7">
                <vue-bootstrap-typeahead v-model="form.nama_provinsi" ref="autocompleteprovinsi"
                  placeholder="Cari Kota / Kabupaten" :minMatchingChars="1" :data="provinsiArray"
                  backgroundVariant="white" textVariant="dark" />
                <p class="text-danger" v-if="errors.provinsi_id">{{errors.provinsi_id[0]}}</p>
              </div>
            </div>

            <div class="form-group row">
              <div class="col-md-7 offset-md-3 d-flex">
                <!-- Submit Button -->
                <v-button :loading="form.busy">
                  Update
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
  import axios from 'axios'
  import VueBootstrapTypeahead from 'vue-bootstrap-typeahead';

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
    components: {
      VueBootstrapTypeahead,
    },
    async asyncData({
      route
    }) {
      let resp = await axios.get('/kota/' + route.params.id)
      let data = resp ? resp.data.result : null;
      const {
        provinsi
      } = data || null;

      return {
        form: new Form({
          id: data.id,
          nama: data.nama,
          provinsi_id: data.provinsi_id,
        }),
        optionProvinsi: [],
        provinsiArray: [],
        nama_provinsi: provinsi ? provinsi.nama : '',
      }
    },
    data: () => ({
      roles: [],
      errors: [],
    }),

    methods: {
      initForm() {
        this.form = new Form({
          id: null,
          nama: null,
          provinsi_id: null,
        })
      },
      async getProvinsi() {
        const resp = await this.$axios.get('/provinsi');
        this.optionProvinsi = resp.data;
        this.optionProvinsi.map((list) => {
          const {
            nama
          } = list;
          if (nama) {
            this.provinsiArray.push(nama);
          }
        });
        return this.provinsiArray;
      },
      async defaultProvinsi(nama_provinsi) {
        const listProv = await this.getProvinsi();
        const findProv = listProv.find(element => element === nama_provinsi) || null;
        this.$refs.autocompleteprovinsi.inputValue = findProv || '';
      },
      async submitForm() {
        const findProv = this.optionProvinsi.find(element => element.nama === this.$refs.autocompleteprovinsi
          .inputValue) || null;
        this.form.provinsi_id = findProv ? findProv.id : this.form.provinsi_id;
        // Tambah Kota
        await axios.put('/kota/' + this.id_kota, this.form)
          .then((response) => {
            this.$swal.fire(
              'Berhasil Update Data',
              'Data Kota Berhasil dibah',
              'success'
            );
            this.$router.push('/kota');
          })
          .catch((error) => {
            this.errors = error.response.data.errors;
          });
      }
    },

    created() {
      this.defaultProvinsi(this.nama_provinsi);
    },
  }
</script>