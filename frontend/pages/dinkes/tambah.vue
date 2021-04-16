<template>
  <div class="wrapper wrapper-content">
    <portal to="title-name">
      Tambah Instansi Pengirim
    </portal>
    <portal to="title-action">
      <div class="title-action">
        <nuxt-link to="/dinkes" class="btn btn-default btn-sm">List Instansi Pengirim</nuxt-link>
      </div>
    </portal>
    <div class="row">
      <div class="col-lg-10">
        <Ibox title="Form Tambah Instansi Pengirim">
          <form @submit.prevent="submitForm" @keydown="form.onKeydown($event)">
            <!-- Type -->
            <div class="form-group row">
              <label class="col-md-3 col-form-label text-md-right">Tipe</label>
              <div class="col-md-7">
                <div class="form-check" v-for="(item, idx) in optionInstansiPengirim" :key="idx">
                  <input class="form-check-input" type="radio" name="tipe" :id="item.key" :value="item.key"
                    v-model="form.tipe">
                  <label class="form-check-label" for="fasyanrs">{{ item.value }}</label>
                </div>
                <p class="text-danger" v-if="errors.tipe">{{errors.tipe[0]}}</p>
              </div>
            </div>

            <!-- Nama -->
            <div class="form-group row">
              <label class="col-md-3 col-form-label text-md-right">Nama Fasyankes</label>
              <div class="col-md-7">
                <input v-model="form.nama" :class="{ 'is-invalid': errors.nama!=null }" type="text" name="nama"
                  class="form-control">
                <p class="text-danger" v-if="errors.nama">{{errors.nama[0]}}</p>
              </div>
            </div>


            <!-- Kota -->
            <div class="form-group row">
              <label class="col-md-3 col-form-label text-md-right">Kota</label>
              <div class="col-md-7">
                <vue-bootstrap-typeahead v-model="nama_kota" ref="autocompletekota" placeholder="Cari Kota"
                  :minMatchingChars="1" :data="kotaArray" backgroundVariant="white" textVariant="dark"
                  :inputClass="getKotaValue(nama_kota)" />
                <p class="text-danger" v-if="errors.kota_id">{{errors.kota_id[0]}}</p>
              </div>
            </div>

            <div class="form-group row">
              <div class="col-md-7 offset-md-3 d-flex">
                <!-- Submit Button -->
                <button :loading="form.busy" class="btn btn-md btn-primary">
                  <em class="fa fa-check" /> Simpan
                </button>
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
  } from 'vform'
  import {
    optionInstansiPengirim
  } from "~/assets/js/constant/enum"
  import VueBootstrapTypeahead from 'vue-bootstrap-typeahead';
  import {
    humanize
  } from "~/utils"
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
      optionKota: [],
      kotaArray: [],
      optionInstansiPengirim,
      nama_kota: '',
      form: new Form({
        nama: '',
        tipe: '',
        kota_id: '',
      }),
    }),

    created() {
      this.getKota();
    },

    methods: {
      humanize,
      async getKota() {
        const resp = await this.$axios.get('/v1/list-kota-all');
        this.optionKota = resp.data;
        const dataArr = this.optionKota.map((list) => {
          const {
            nama
          } = list;
          if (nama) {
            this.kotaArray.push(nama);
          }
        });
        return this.kotaArray;
      },

      async submitForm() {
        // Tambah User
        const findKota = this.optionKota.find(element => element.nama === this.$refs.autocompletekota.inputValue);
        this.form.kota_id = findKota ? findKota.id : this.form.kota_id;
        this.form.post('/dinkes')
          .then(({
            data
          }) => {
            this.$swal.fire(
              'Berhasil Tambah Data',
              'Data Dinkes Berhasil ditambahkan',
              'success'
            );
            this.$router.push('/dinkes');
          })
          .catch((error) => {
            this.errors = error.response.data.errors;
          });
      },

      getKotaValue(result) {
        const findKota = this.optionKota.find(element => element.nama === result) || null;
        if (findKota) {
          this.form.kota_id = findKota.id;
        }
      },
    }
  }
</script>