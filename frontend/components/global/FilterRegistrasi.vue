<template>
  <Ibox title="Filter Data">
    <div class="row">
      <div class="col-md-6">
        <div class="form-group row">
          <div class="col-md-4 flex-text-center">
            <div for="nama_pasien">Nama Pasien</div>
          </div>
          <div class="col-md-8">
            <input type="text" name="nama_pasien" v-model="params.nama_pasien" id="" class="form-control"
              placeholder="Ketikkan Nama Pasien">
          </div>
        </div>
        <div class="form-group row">
          <div class="col-md-4 flex-text-center">
            <div>Nomor Sampel</div>
          </div>
          <div class="col-md-8 input-group">
            <input type="text" class="form-control" placeholder="Nomor awal" v-model="params.start_nomor_sampel">
            &nbsp;
            <input type="text" class="form-control" placeholder="Nomor akhir" v-model="params.end_nomor_sampel">
          </div>
        </div>
        <div class="form-group row">
          <div class="col-md-4 flex-text-center">
            <div for="nama_pasien">Tanggal Input</div>
          </div>
          <div class="col-md-8 input-group">
            <rangedate-picker @selected="onDateSelected" ref="rangedatepicker" />
          </div>
        </div>
      </div>

      <div class="col-md-6">
        <div class="form-group row">
          <div class="col-md-4 flex-text-center">
            <div for="kategori">Kategori</div>
          </div>
          <div class="col-md-8">
            <input type="text" class="form-control" v-model="params.kategori" preventForm />
          </div>
        </div>
        <div class="form-group row">
          <div class="col-md-4 flex-text-center">
            <div for="kota">Domisili</div>
          </div>
          <div class="col-md-8">
            <vue-bootstrap-typeahead v-model="nama_kota" ref="autocompletedomisili" placeholder="Cari Kota / Kabupaten"
              :minMatchingChars="1" :data="kotaArray" backgroundVariant="white" textVariant="dark"
              :inputClass="getDomisiliValue(nama_kota)" />
          </div>
        </div>
        <div class="form-group row" v-if="oid=='registrasi-rujukan'">
          <div class="col-md-4 flex-text-center">
            <div for="nama_pasien">Instansi Pengirim</div>
          </div>
          <div class="col-md-8">
            <input-option-instansi-pengirim :form="params" field="fasyankes_tipe" />
          </div>
        </div>
        <div class="form-group row" v-if="oid=='registrasi-rujukan'">
          <div class="col-md-4 flex-text-center">
            <div for="nama_pasien">Nama Rumah Sakit / Fasyankes</div>
          </div>
          <div class="col-md-8">
            <vue-bootstrap-typeahead
              ref="autocompleteinstansi"
              placeholder="Cari Rumah Sakit / Fasyankes"
              :minMatchingChars="1"
              :serializer="s => s.nama"
              :data="optionsFasyankes"
              @hit="onSelectedFasyankes($event)"
              backgroundVariant="white"
              textVariant="dark" />
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-12 mt-2 flex-right">
        <apply-clear-filter-button :doFilter="doFilter" :clearFilter="clearFilter" />
      </div>
    </div>


  </Ibox>
</template>

<script>
  import VueBootstrapTypeahead from 'vue-bootstrap-typeahead'
  import {
    humanize,
    getDateIsoString
  } from '~/utils'
  export default {
    name: 'FilterRegistrasi',
    props: ['oid'],
    components: {
      VueBootstrapTypeahead,
    },
    data() {
      return {
        params: {
          jenis_registrasi: null,
          nama_pasien: null,
          nomor_register: null,
          nomor_sampel: null,
          start_date: null,
          end_date: null,
          sumber_pasien: null,
          sumber_sampel: null,
          kategori: null,
          kota: null,
          fasyankes: null,
          fasyankes_tipe: null,
          start_nomor_sampel: null,
          end_nomor_sampel: null,
        },
        optionsFasyankes: [],
        optionKota: [],
        kotaArray: [],
        nama_kota: '',
      }
    },
    methods: {
      humanize,
      doFilter() {
        this.$bus.$emit('refresh-ajaxtable2', this.oid, this.params);
      },
      clearFilter() {
        if (this.oid === "registrasi-mandiri") {
          this.params.jenis_registrasi = 'mandiri'
        } else if (this.oid === "registrasi-rujukan") {
          this.params.jenis_registrasi = 'rujukan'
        }
        this.params.nama_pasien = null
        this.params.nomor_register = null
        this.params.nomor_sampel = null
        this.params.start_date = null
        this.params.end_date = null
        this.params.sumber_pasien = null
        this.params.sumber_sampel = null
        this.params.kategori = null
        this.params.kota = null
        this.params.fasyankes = null
        this.params.fasyankes_tipe = null
        this.params.start_nomor_sampel = null
        this.params.end_nomor_sampel = null
        if (this.oid === "registrasi-rujukan") {
          this.$refs.autocompleteinstansi.inputValue = ''
        }
        this.$refs.autocompletedomisili.inputValue = ''
        this.nama_kota = null
        this.$refs.rangedatepicker.$data.dateRange = {}
        this.$bus.$emit("clear-input-instansi");
        this.$bus.$emit('refresh-ajaxtable2', this.oid, this.params)
      },
      async getKota() {
        const resp = await this.$axios.get('/v1/list-kota-jabar');
        this.optionKota = resp.data;
        this.optionKota.map((list) => {
          const {
            nama
          } = list;
          if (nama) {
            this.kotaArray.push(nama);
          }
        });
        return this.kotaArray;
      },
      async getFasyankes(tipe) {
        let resp = await this.$axios.get(`/v1/list-fasyankes-jabar?tipe=${tipe}`)
        this.optionsFasyankes = resp.data
      },
      onSelectedFasyankes(result) {
        this.params.fasyankes = result.id;
      },
      getDomisiliValue(result) {
        const findKota = this.optionKota.find(element => element.nama === result);
        if (findKota) {
          this.params.kota = findKota.id;
        }
      },
      onDateSelected: function (daterange) {
        this.params.start_date = getDateIsoString(daterange.start);
        this.params.end_date = getDateIsoString(daterange.end);
      }
    },
    mounted() {
      if (this.oid == 'registrasi-rujukan') {
        this.params.jenis_registrasi = 'rujukan';
      } else {
        this.params.jenis_registrasi = "mandiri";
      }
    },
    created() {
      this.getKota();
    },
    watch: {
      "params.fasyankes_tipe"(newVal) {
        this.$refs.autocompleteinstansi.inputValue = ''
        this.params.fasyankes = null
        this.getFasyankes(newVal)
      },
    }
  }
</script>