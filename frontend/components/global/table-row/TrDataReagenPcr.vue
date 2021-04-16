<template>
  <tr>
    <td v-text="(pagination.page - 1) * pagination.perpage + 1 + index"></td>
    <td>
      {{ item.nama }}
    </td>
    <td>
      {{ item.ct_normal }}
    </td>
    <td>
      <nuxt-link :to="`reagen-pcr/update/${item.id}`" class="mb-1 btn btn-primary btn-sm">
        <em class="fa fa-pencil" />
      </nuxt-link>
      <a href="#" class="mb-1 btn btn-danger btn-sm" @click="deleteData(item)">
        <em class="fa fa-trash" />
      </a>
    </td>
  </tr>
</template>

<script>
  import {
    getAlertPopUp
  } from '~/utils'
  export default {
    props: ['item', 'pagination', 'rowparams', 'index'],
    data() {
      return {}
    },
    methods: {
      async deleteData(item) {
        const content = `
          <div class="row flex-content-center">
            ${this.$t("alert_confirm_delete_text")}
          </div>
          <div class="row col-lg-12 flex-content-center mt-4" style="font-size: 12px">
            <div class="form-group row col-md-10">
              <div class="col-md-5 text-blue flex-left">
                Reagen PCR
              </div>
              <div class="col-md-7 flex-left">
                ${item.nama || '-'}
              </div>
            </div>
            <div class="form-group row col-md-10">
              <div class="col-md-5 text-blue flex-left">
                Nilai CT Normal
              </div>
              <div class="col-md-7 flex-left">
                ${item.ct_normal || '-'}
              </div>
            </div>
          </div>
        `;
        let swal = this.$swal;
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
          this.$axios.delete(`v1/reagen-pcr/${item.id}`)
            .then((response) => {
              swal.fire(
                'Berhasil Menghapus Data',
                'Reagen PCR Berhasil dihapus',
                'success'
              );
              bus.$emit('refresh-ajaxtable', 'master-reagen-pcr')
            })
            .catch((error) => {
              swal.fire(
                'Terjadi Kesalahan',
                'Gagal menghapus data, silakan coba kembali',
                'error'
              );
            })
        }
      }
    }
  }
</script>