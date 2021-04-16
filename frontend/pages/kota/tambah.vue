<template>
  <div class="wrapper wrapper-content">
    <portal to="title-name">
      Tambah Kota
    </portal>
    <portal to="title-action">
      <div class="title-action">
        <nuxt-link to="/kota" class="btn btn-primary">List Kota</nuxt-link>
      </div>
    </portal>
    <div class="row">
      <div class="col-lg-8">
        <Ibox title="Form Tambah Kota">
          <form @submit.prevent="submitForm" @keydown="form.onKeydown($event)">
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
                <vue-bootstrap-typeahead v-model="nama_provinsi" ref="autocompleteprovinsi" placeholder="Cari Provinsi"
                  :minMatchingChars="1" :data="provinsiArray" backgroundVariant="white" textVariant="dark"
                  :inputClass="getProvinsiValue(nama_provinsi)" />
                <p class="text-danger" v-if="errors.provinsi_id">{{errors.provinsi_id[0]}}</p>
              </div>
            </div>

            <div class="form-group row">
              <div class="col-md-7 offset-md-3 d-flex">
                <!-- Submit Button -->
                <v-button :loading="form.busy">
                  Tambah
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
  import VueBootstrapTypeahead from 'vue-bootstrap-typeahead';

  export default {
    middleware: ['auth', 'checkrole'],
    meta: {
      allow_role_id: [1]
    },
    components: {
      VueBootstrapTypeahead,
    },
    data: () => ({
      errors: [],
      optionProvinsi: [],
      provinsiArray: [],
      nama_provinsi: '',
      form: new Form({
        id: '',
        nama: '',
        provinsi_id: '',
      }),
    }),

    created() {
      this.getProvinsi();
    },

    methods: {
      async getProvinsi() {
        const resp = await this.$axios.get('/provinsi');
        this.optionProvinsi = resp.data;
        const dataArr = this.optionProvinsi.map((list) => {
          const {
            nama
          } = list;
          if (nama) {
            this.provinsiArray.push(nama);
          }
        });
        return this.provinsiArray;
      },

      async submitForm() {
        // Tambah User
        const findProvinsi = this.optionProvinsi.find(element => element.nama === this.$refs.autocompleteprovinsi
          .inputValue);
        this.form.provinsi_id = findProvinsi ? findProvinsi.id : this.form.provinsi_id;
        this.form.post('/kota')
          .then(({
            data
          }) => {
            this.$swal.fire(
              'Berhasil Tambah Data',
              'Data Kota Berhasil ditambahkan',
              'success'
            );
            this.$router.push('/kota');
          })
          .catch((error) => {
            this.errors = error.response.data.errors;
          });
      },

      getProvinsiValue(result) {
        const findProvinsi = this.optionProvinsi.find(element => element.nama === result) || null;
        if (findProvinsi) {
          this.form.provinsi_id = findProvinsi.id;
        }
      },
    }
  }
</script>