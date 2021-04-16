<template>
  <div class="wrapper wrapper-content">
    <portal to="title-name">
      Detail Pasien
    </portal>
    <portal to="title-action">
      <div class="title-action">
        <nuxt-link tag="a" :to="`/registrasi/mandiri/update/${registerId}/${pasienId}`" class="btn btn-import-export">
          <em class="fa fa-pencil" /> Perbarui Data
        </nuxt-link>
        <button class="btn btn-clear" @click="deleteData(registerId, pasienId)">
          <em class="fa fa-trash" /> Hapus Data
        </button>
        <nuxt-link to="/registrasi/mandiri" class="btn btn-black">
          <em class="uil-arrow-left" /> Kembali
        </nuxt-link>
      </div>
    </portal>

    <div class="col-lg-12 row">
      <div class="col-md-6">
        <Ibox title="Informasi Registrasi">
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Nomor Registrasi
            </div>
            <div class="col-md-7 flex-text-center">
              {{data.nomor_register || null}}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Kewarganegaraan
            </div>
            <div class="col-md-7 flex-text-center">
              {{data.kewarganegaraan || null}}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Kategori
            </div>
            <div class="col-md-7 flex-text-center">
              {{data.sumber_pasien || null}}
            </div>
          </div>
        </Ibox>
        <Ibox title="Informasi Sampel">
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Nomor Sampel
            </div>
            <div class="col-md-7 flex-text-center">
              <span v-if="data.nomor_sampel">#</span>{{data.nomor_sampel || null}}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Keterangan
            </div>
            <div class="col-md-7 flex-text-center">
              {{data.keterangan_lain || null}}
            </div>
          </div>
        </Ibox>
        <Ibox title="Identitas Pasien">
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              NIK
            </div>
            <div class="col-md-7 flex-text-center">
              {{data.nik || null}}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Nama Lengkap Pasien
            </div>
            <div class="col-md-7 flex-text-center">
              {{data.nama_lengkap || null}}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Jenis Kelamin
            </div>
            <div class="col-md-7 flex-text-center">
              {{data && data.jenis_kelamin ? data.jenis_kelamin === "L" ? "Laki-Laki" : "Perempuan" : null }}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Tempat &amp; Tanggal Lahir
            </div>
            <div class="col-md-7 flex-text-center">
              <span v-if="data.tempat_lahir">{{ data.tempat_lahir }}&nbsp;</span>
              <span v-if="data.tanggal_lahir">{{ data.tanggal_lahir | formatDate }}</span>
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Usia Pasien
            </div>
            <div class="col-md-7 flex-text-center">
              {{ usiaPasien }}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              No. Telp/HP
            </div>
            <div class="col-md-7 flex-text-center">
              {{ data.no_hp }}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Suhu
            </div>
            <div class="col-md-7 flex-text-center">
              {{data.suhu || null}}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              {{ $t('label.pasien.status') }}
            </div>
            <div class="col-md-7 flex-text-center">
              {{ data.status ? pasienStatus.find(x => x.value == data.status).text : null}}
            </div>
          </div>
        </Ibox>
      </div>
      <div class="col-md-6">
        <Ibox title="Alamat Pasien">
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Alamat Lengkap
            </div>
            <div class="col-md-7 flex-text-center">
              {{data.alamat_lengkap || null}}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              RT/RW
            </div>
            <div class="col-md-7 flex-text-center">
              <span v-if="data.no_rt">RT {{data.no_rt}} / </span>
              <span v-if="data.no_rw">RW {{data.no_rw}}</span>
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Provinsi
            </div>
            <div class="col-md-7 flex-text-center">
              {{data.provinsi || null}}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Kota/Kabupaten
            </div>
            <div class="col-md-7 flex-text-center">
              {{data.kota || null}}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Kecamatan
            </div>
            <div class="col-md-7 flex-text-center">
              {{data.kecamatan || null}}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Kelurahan/Desa
            </div>
            <div class="col-md-7 flex-text-center">
              {{data.kelurahan || null}}
            </div>
          </div>
        </Ibox>
        <Ibox title="Riwayat Perubahan">
          <div class="col-12">
           <Timeline :data="logs" />
          </div>
        </Ibox>
        <Ibox title="Riwayat Kunjungan">
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Kunjungan ke
            </div>
            <div class="col-md-7 flex-text-center">
              {{data.kunjungan_ke || null}}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Tanggal Kunjungan
            </div>
            <div class="col-md-7 flex-text-center">
              <span v-if="data.tanggal_kunjungan">
                {{ data.tanggal_kunjungan | formatDate }}
              </span>
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Rumah Sakit / Fasyankes
            </div>
            <div class="col-md-7 flex-text-center">
              {{data.rs_kunjungan || null}}
            </div>
          </div>
        </Ibox>
        
      </div>
    </div>
  </div>
</template>

<script>
  import {
    mapActions,
    mapState
  } from 'vuex'
  import axios from 'axios'
  import {
    pasienStatus
  } from '~/assets/js/constant/enum'
  import {
    getHumanAge,
    getAlertPopUp,
  } from '~/utils';

  export default {
    middleware: 'auth',
    async asyncData({
      route,
      store
    }) {
      let error = false;
      let resp = await axios.get(`/v1/register/mandiri/${route.params.register_id}/${route.params.pasien_id}`);
      return {
        data: resp.data.result
      }
    },
    data() {
      return {
        pasienStatus,
        logs: null,
      }
    },
    computed: {
      registerId() {
        return this.$route.params.register_id;
      },
      pasienId() {
        return this.$route.params.pasien_id;
      },
      usiaPasien() {
        if (this.data && this.data.usia_tahun) {
          if (this.data && this.data.usia_bulan) {
            return `${this.data.usia_tahun} tahun ${this.data.usia_bulan} bulan`;
          }
          return `${this.data.usia_tahun} tahun`;
        }
        if (this.data.tanggal_lahir) {
          return getHumanAge(this.data.tanggal_lahir);
        }
        return "-";
      }
    },
    methods: {
      async deleteData(id, pasien) {
        let swal = this.$swal;
        let toast = this.$toast;
        let bus = this.$bus;
        const swalCustom = swal.mixin({
          customClass: {
            confirmButton: 'btn btn-danger btn-md ml-2',
            cancelButton: 'btn btn-clear btn-md'
          },
          buttonsStyling: false
        });
        const {
          value: isConfirm
        } = await swalCustom.fire(getAlertPopUp('delete'));
        if (isConfirm) {
          try {
            await this.$axios.delete(`v1/register/mandiri/${id}/${pasien}`);
            toast.success('Berhasil menghapus data', {
              icon: 'check',
              iconPack: 'fontawesome',
              duration: 5000
            })
            await bus.$emit('refresh-ajaxtable', 'registrasi-mandiri');
            this.$router.push('/registrasi/mandiri')
          } catch (e) {
            swal.fire("Terjadi kesalahan", "Silakan hubungi Admin", "error");
          }
        }
      },
      getLogs(id) {
        let url = `v1/register/logs/${id}`
        let self = this
        axios
          .get(url)
          .then(function (response) {
              self.logs = response.data.result; // Data existed
          })
          .catch(function (err) {
              console.log(err);
        });
      }
    },
    created() {
      this.getLogs(this.$route.params.register_id)
    },
    head() {
      return {
        title: this.$t('home')
      }
    },
  }
</script>