<template>
  <tr>
    <td v-text="(pagination.page - 1) * pagination.perpage + 1 + index"></td>
    <td>{{item.nomor_register}}</td>
    <td nowrap>
      <div v-if="item.nama_lengkap" style="text-transform: capitalize;"><strong>{{ item.nama_lengkap }}</strong></div>
      <div v-if="item.nik" class="text-muted">{{ item.nik }}</div>
      <div class="text-muted">{{ usiaPasien || null }}</div>
    </td>
    <td>
      {{item.status ? pasienStatus.find(x => x.value == item.status).text : null}}
    </td>
    <td>
      {{item.nama_kota}}
    </td>
    <td>
      {{item.sumber_pasien}}
    </td>
    <td>{{ item.fasyankes_nama || '-' }}</td>
    <td width="10%">
      <div class="badge badge-white" style="text-align:left; padding:10px"># {{item.nomor_sampel}} <br>
        {{item.deskripsi}}
      </div>
    </td>
    <td nowrap>
      <div><strong>{{ item.tgl_input ? momentFormatDate(item.tgl_input) : null }}</strong></div>
      <div class="text-muted">{{ item.tgl_input ? 'pukul ' + momentFormatTime(item.tgl_input) : null }}</div>
    </td>
    <td>
      <div class="text-red"
        v-if="(item.nik==null || item.nik=='') || (item.nama_lengkap==null || item.nama_lengkap=='')">
        <strong>Data Belum Lengkap</strong>
      </div>
      <div class="text-yellow"
        v-if="(item.nik!=null && item.nik!='') && (item.nama_lengkap!=null && item.nama_lengkap!='')">
        <strong>Data Lengkap</strong>
      </div>
    </td>
    <td v-if="config.has_action">
      <nuxt-link :to="`/registrasi/rujukan/detail/${item.register_id}/${item.pasien_id}`"
        class="mb-1 btn btn-yellow btn-sm" title="Klik untuk melihat detail">
        <em class="fa fa-eye" />
      </nuxt-link>
      <nuxt-link :to="`/registrasi/rujukan/update/${item.register_id}/${item.pasien_id}`"
        class="mb-1 btn btn-primary btn-sm" title="Klik untuk mengubah data">
        <em class="fa fa-edit" />
      </nuxt-link>
      <a href="#" class="mb-1 btn btn-danger btn-sm" @click="deleteData(item, usiaPasien)"
        title="klik untuk menghapus data">
        <em class="fa fa-trash" />
      </a>
    </td>
  </tr>
</template>

<script>
  import {
    pasienStatus
  } from '~/assets/js/constant/enum';
  import {
    getHumanAge,
    getAlertPopUp,
    momentFormatDate,
    momentFormatTime,
    getKeteranganData
  } from '~/utils';

  export default {
    props: ['item', 'pagination', 'rowparams', 'index', 'config'],
    data() {
      return {
        pasienStatus,
        momentFormatDate,
        momentFormatTime
      }
    },
    computed: {
      usiaPasien() {
        if (this.item.usia_tahun && this.item.usia_bulan) {
          return `${this.item.usia_tahun} tahun ${this.item.usia_bulan} bulan`;
        }
        if (this.item.reg_tgllahir) {
          return getHumanAge(this.item.reg_tgllahir);
        }
        return "";
      }
    },
    methods: {
      async deleteData(item, usia) {
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
                ${item.nomor_register}
              </div>
            </div>
            <div class="form-group row col-md-10">
              <div class="col-md-5 text-blue flex-left">
                Pasien
              </div>
              <div class="col-md-7">
                <div class=" flex-left" style="text-transform: capitalize">${item.nama_lengkap}</div>
                <div class=" flex-left">${item.nik}</div>
                <div class=" flex-left">${usia}</div>
              </div>
            </div>
            <div class="form-group row col-md-10">
              <div class="col-md-5 text-blue flex-left">
                Domisili
              </div>
              <div class="col-md-7 flex-left">
                ${item.nama_kota}
              </div>
            </div>
            <div class="form-group row col-md-10">
              <div class="col-md-5 text-blue flex-left">
                Kategori
              </div>
              <div class="col-md-7 flex-left">
                ${item.sumber_pasien}
              </div>
            </div>
            <div class="form-group row col-md-10">
              <div class="col-md-5 text-blue flex-left">
                Sampel
              </div>
              <div class="col-md-7 flex-left">
                <div class="badge badge-white" padding:10px">
                  # ${item.nomor_sampel} ${item.sampel_status}
                </div>
              </div>
            </div>
            <div class="form-group row col-md-10">
              <div class="col-md-5 text-blue flex-left">
                Waktu Input
              </div>
              <div class="col-md-7">
                <div class=" flex-left">
                  ${momentFormatDate(item.tgl_input)}, ${momentFormatTime(item.tgl_input)}
                </div>
              </div>
            </div>
            <div class="form-group row col-md-10">
              <div class="col-md-5 text-blue flex-left">
                Keterangan
              </div>
              <div class="col-md-7 flex-left">
                ${getKeteranganData(item.nik, item.nama_lengkap)}
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
            await this.$axios.delete(`registrasi-rujukan/delete/${item.register_id}/${item.pasien_id}`);
            toast.success('Berhasil menghapus data', {
              icon: 'check',
              iconPack: 'fontawesome',
              duration: 5000
            })
            await bus.$emit('refresh-ajaxtable', 'registrasi-rujukan');
          } catch (e) {
            swal.fire("Terjadi kesalahan", "Silakan hubungi Admin", "error");
          }
        }
      },
      showDetail(item) {
        const payload = {
          id: item.nomor_register,
          data: item
        }
        this.$store.dispatch('register/saveData', payload);
        this.$router.push('/registrasi/mandiri/detail')
      }
    }
  }
</script>