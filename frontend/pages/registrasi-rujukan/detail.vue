<template>
  <div class="wrapper wrapper-content">
    <portal to="title-name">
      Detail Registrasi
    </portal>
    <portal to="title-action">
      <div class="title-action">
        <nuxt-link tag="a" :to="`/registrasi/rujukan/update/${registerId}/${pasienId}`" class="btn btn-import-export">
          <em class="fa fa-pencil" /> Perbarui Data
        </nuxt-link>
        <button class="btn btn-clear" @click="deleteData(registerId, pasienId)">
          <em class="fa fa-trash" /> Hapus Data
        </button>
        <nuxt-link to="/registrasi/rujukan" class="btn btn-black">
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
              {{data.reg_no || null}}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Kewarganegaraan
            </div>
            <div class="col-md-7 flex-text-center">
              {{data.reg_kewarganegaraan || null}}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Kategori
            </div>
            <div class="col-md-7 flex-text-center">
              {{data.reg_sumberpasien || null}}
            </div>
          </div>
        </Ibox>
        <Ibox title="Informasi Sampel">
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Nomor Sampel
            </div>
            <div class="col-md-7 flex-text-center">
              <span v-for="item in data.samples" v-bind:key="item.nomor_sampel">
                #{{item.nomor_sampel}}
              </span>
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Keterangan
            </div>
            <div class="col-md-7 flex-text-center">
              {{data.reg_keterangan || null}}
            </div>
          </div>
        </Ibox>
        <Ibox title="Informasi Pengirim">
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Instansi Pengirim
            </div>
            <div class="col-md-7 flex-text-center">
              {{ data.fasyankes_pengirim ? humanize(data.fasyankes_pengirim) : null }}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Nama Instansi
            </div>
            <div class="col-md-7 flex-text-center">
              {{data.nama_rs || null}}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Dokter Penanggung Jawab
            </div>
            <div class="col-md-7 flex-text-center">
              {{data.reg_nama_dokter || '-'}}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              No Telepon Fasyankes Pengirim (Dokter)
            </div>
            <div class="col-md-7 flex-text-center">
              {{data.reg_telp_fas_pengirim || '-'}}
            </div>
          </div>
        </Ibox>
      </div>
      <div class="col-md-6">
        <Ibox title="Identitas Pasien">
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              NIK
            </div>
            <div class="col-md-7 flex-text-center">
              {{data.reg_nik || null}}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Nama Lengkap Pasien
            </div>
            <div class="col-md-7 flex-text-center">
              {{data.reg_nama_pasien || null}}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Jenis Kelamin
            </div>
            <div class="col-md-7 flex-text-center">
              {{data && data.reg_jk ? data.reg_jk === "L" ? "Laki-Laki" : "Perempuan" : null }}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Tempat &amp; Tanggal Lahir
            </div>
            <div class="col-md-7 flex-text-center">
              <span v-if="data.reg_tempatlahir">{{ data.reg_tempatlahir }}&nbsp;</span>
              <span v-if="data.reg_tgllahir">{{ data.reg_tgllahir | formatDate }}</span>
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
              {{ data.reg_nohp }}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Suhu
            </div>
            <div class="col-md-7 flex-text-center">
              {{data.reg_suhu || null}}
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
        <Ibox title="Alamat Pasien">
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Alamat Lengkap
            </div>
            <div class="col-md-7 flex-text-center">
              {{data.reg_alamat || null}}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              RT/RW
            </div>
            <div class="col-md-7 flex-text-center">
              <span v-if="data.reg_rt">RT {{data.reg_rt}} / </span>
              <span v-if="data.reg_rw">RW {{data.reg_rw}}</span>
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Provinsi
            </div>
            <div class="col-md-7 flex-text-center">
              {{data.reg_provinsi || null}}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Kota/Kabupaten
            </div>
            <div class="col-md-7 flex-text-center">
              {{data.reg_kota || null}}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Kecamatan
            </div>
            <div class="col-md-7 flex-text-center">
              {{data.reg_kecamatan || null}}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Kelurahan/Desa
            </div>
            <div class="col-md-7 flex-text-center">
              {{data.reg_kelurahan || null}}
            </div>
          </div>
        </Ibox>
        <Ibox title="Riwayat Kunjungan">
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Kunjungan ke
            </div>
            <div class="col-md-7 flex-text-center">
              {{data.reg_kunke || null}}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Tanggal Kunjungan
            </div>
            <div class="col-md-7 flex-text-center">
              <span v-if="data.reg_tanggalkunjungan">
                {{ data.reg_tanggalkunjungan | formatDate }}
              </span>
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Rumah Sakit / Fasyankes
            </div>
            <div class="col-md-7 flex-text-center">
              {{data.reg_rs_kunjungan || null}}
            </div>
          </div>
        </Ibox>
        <Ibox title="Riwayat Perubahan">
          <div class="col-12">
           <Timeline :data="logs" />
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
  import { humanize, momentFormatDate, momentFormatTime, getKeteranganData } from "~/utils";


  export default {
    data() {
      return {
        pasienStatus,
        logs: null
      }
    },
    middleware: 'auth',
    async asyncData({
      route,
      store
    }) {
      let error = false;
      let resp = await axios.get(
        `registrasi-rujukan/update/${route.params.register_id}/${route.params.pasien_id}`);
      return {
        data: resp.data.result
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
        if (this.data && this.data.reg_usia_tahun) {
          if (this.data && this.data.reg_usia_bulan) {
            return `${this.data.reg_usia_tahun} tahun ${this.data.reg_usia_bulan} bulan`;
          }
          return `${this.data.reg_usia_tahun} tahun`;
        }
        if (this.data.reg_tgllahir) {
          return getHumanAge(this.data.reg_tgllahir);
        }
        return "-";
      }
    },
    methods: {
      humanize,
      momentFormatDate,
      momentFormatTime,
      getKeteranganData,
      async deleteData(id, pasien) {
        const content = `
          <div class="row flex-content-center">
            ${this.$t("alert_confirm_delete_text")}
          </div>
          <div class="row col-lg-12 flex-content-center mt-4" style="font-size: 12px">
            <div class="form-group row col-md-10">
              <div class="col-md-5 text-blue flex-left">
                Nomor Registrasi
              </div>
              <div class="col-md-7 flex-left">
                ${this.data.reg_no}
              </div>
            </div>
            <div class="form-group row col-md-10">
              <div class="col-md-5 text-blue flex-left">
                Pasien
              </div>
              <div class="col-md-7">
                <div class=" flex-left" style="text-transform: capitalize">${this.data.reg_nama_pasien}</div>
                <div class=" flex-left">${this.data.reg_nik}</div>
                <div class=" flex-left">${this.usiaPasien}</div>
              </div>
            </div>
            <div class="form-group row col-md-10">
              <div class="col-md-5 text-blue flex-left">
                Domisili
              </div>
              <div class="col-md-7 flex-left">
                ${this.data.reg_kota}
              </div>
            </div>
            <div class="form-group row col-md-10">
              <div class="col-md-5 text-blue flex-left">
                Kategori
              </div>
              <div class="col-md-7 flex-left">
                ${this.data.reg_fasyankes_pengirim}
              </div>
            </div>
            <div class="form-group row col-md-10">
              <div class="col-md-5 text-blue flex-left">
                Sampel
              </div>
              <div class="col-md-7 flex-left">
                <div class="badge badge-white" padding:10px">
                  # ${this.data.samples[0].nomor_sampel} ${humanize(this.data.samples[0].sampel_status)}
                </div>
              </div>
            </div>
            <div class="form-group row col-md-10">
              <div class="col-md-5 text-blue flex-left">
                Waktu Input
              </div>
              <div class="col-md-7">
                <div class=" flex-left">
                  ${momentFormatDate(this.data.tgl_input)}, ${momentFormatTime(this.data.tgl_input)}
                </div>
              </div>
            </div>
            <div class="form-group row col-md-10">
              <div class="col-md-5 text-blue flex-left">
                Keterangan
              </div>
              <div class="col-md-7 flex-left">
                ${getKeteranganData(this.data.nik, this.data.nama_lengkap)}
              </div>
            </div>
          </div>
        `;
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
        } = await swalCustom.fire(getAlertPopUp('delete', content));
        if (isConfirm) {
          try {
            await this.$axios.delete(`registrasi-rujukan/delete/${id}/${pasien}`);
            toast.success('Berhasil menghapus data', {
              icon: 'check',
              iconPack: 'fontawesome',
              duration: 5000
            })
            await bus.$emit('refresh-ajaxtable', 'registrasi-rujukan');
            this.$router.push('/registrasi/rujukan')
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