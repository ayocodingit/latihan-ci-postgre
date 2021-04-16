<template>
  <tr>
    <td v-text="(pagination.page - 1) * pagination.perpage + 1 + index"></td>
    <td>
      <div class="badge badge-white" style="text-align:left; padding:5px">
        # {{item.nomor_sampel}}
      </div>
    </td>
    <td>
      {{item.nomor_register}}
    </td>
    <td>{{item.jenis_sampel_nama}}</td>
    <td nowrap>
      <div>
        <strong>{{ item.waktu_sample_taken ? momentFormatDate(item.waktu_sample_taken) : null }}</strong>
      </div>
      <div class="text-muted">
        {{ item.waktu_sample_taken ? 'pukul ' + momentFormatTime(item.waktu_sample_taken) : null }}</div>
    </td>
    <td>{{item.petugas_pengambilan_sampel}}</td>
    <td>
      <nuxt-link :to="`/sample/edit/${item.nomor_sampel}`" class="btn btn-primary btn-sm mb-1"
        title="Ubah Keterangan Sampel">
        <em class="fa fa-edit" />
      </nuxt-link>
      <button v-if="canDelete(item.nomor_register)" class="btn btn-danger btn-sm mb-1" @click="deleteData(item)"
        title="Hapus sampel">
        <em class="fa fa-trash" />
      </button>
    </td>
  </tr>
</template>

<script>
  import {
    getAlertPopUp,
    momentFormatDate,
    momentFormatTime
  } from '~/utils';
  export default {
    props: ['item', 'pagination', 'rowparams', 'index'],
    data() {
      return {
        momentFormatDate,
        momentFormatTime
      }
    },
    methods: {
      canDelete(arg) {
        return arg === null
      },
      async deleteData(item) {
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
                ${item.nomor_register || '-'}
              </div>
            </div>
            <div class="form-group row col-md-10">
              <div class="col-md-5 text-blue flex-left">
                Sampel
              </div>
              <div class="col-md-7 flex-left">
                <div class="badge badge-white" padding:10px">
                  # ${item.nomor_sampel}
                </div>
              </div>
            </div>
            <div class="form-group row col-md-10">
              <div class="col-md-5 text-blue flex-left">
                Jenis Sampel
              </div>
              <div class="col-md-7 flex-left">
                ${item.jenis_sampel_nama}
              </div>
            </div>
            <div class="form-group row col-md-10">
              <div class="col-md-5 text-blue flex-left">
                Waktu Penerimaan
              </div>
              <div class="col-md-7">
                <div class=" flex-left">
                  ${momentFormatDate(item.waktu_sample_taken)}, ${momentFormatTime(item.waktu_sample_taken)}
                </div>
              </div>
            </div>
            <div class="form-group row col-md-10">
              <div class="col-md-5 text-blue flex-left">
                Kondisi Sampel
              </div>
              <div class="col-md-7 flex-left">
                ${item.petugas_pengambilan_sampel || '-'}
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
            await this.$axios.delete(`/sample/delete/${item.id}`);
            toast.success('Berhasil menghapus data', {
              icon: 'check',
              iconPack: 'fontawesome',
              duration: 5000
            })
            await bus.$emit('refresh-ajaxtable', 'sample-sent');
          } catch (e) {
            swal.fire("Terjadi kesalahan", "Silakan hubungi Admin", "error");
          }
        }
      }
    }
  }
</script>