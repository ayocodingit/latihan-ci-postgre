<template>
  <tr>
    <td v-text="(pagination.page - 1) * pagination.perpage + 1 + index"></td>
    <td>
      {{ item.nama }}
    </td>
    <td>
      {{ getTipeInstansiValue(item.tipe) }}
    </td>
    <td>
      {{ item.kota ? item.kota.nama : '-' }}
    </td>
    <td>
      <nuxt-link :to="`dinkes/detail/${item.id}`" class="mb-1 btn btn-yellow btn-sm">
        <em class="fa fa-eye" />
      </nuxt-link>
      <nuxt-link :to="`dinkes/update/${item.id}`" class="mb-1 btn btn-primary btn-sm">
        <em class="fa fa-pencil" />
      </nuxt-link>
      <button class="mb-1 btn btn-danger btn-sm" @click="deleteData(item)">
        <em class="fa fa-trash" />
      </button>
    </td>
  </tr>
</template>

<script>
  import {
    humanize,
    getAlertPopUp
  } from '~/utils'
  import {
    optionInstansiPengirim
  } from "~/assets/js/constant/enum"
  export default {
    props: ['item', 'pagination', 'rowparams', 'index'],
    data() {
      return {}
    },
    methods: {
      deleteData(item) {
        const content = `
          <div class="row flex-content-center">
            ${this.$t("alert_confirm_delete_text")}
          </div>
          <div class="row col-lg-12 flex-content-center mt-4" style="font-size: 12px">
            <div class="form-group row col-md-10">
              <div class="col-md-5 text-blue flex-left">
                Nama
              </div>
              <div class="col-md-7 flex-left">
                ${item.nama || '-'}
              </div>
            </div>
            <div class="form-group row col-md-10">
              <div class="col-md-5 text-blue flex-left">
                Tipe
              </div>
              <div class="col-md-7 flex-left">
                ${item.tipe ? humanize(item.tipe) : '-'}
              </div>
            </div>
            <div class="form-group row col-md-10">
              <div class="col-md-5 text-blue flex-left">
                Kota
              </div>
              <div class="col-md-7 flex-left">
                ${item.kota ? item.kota.nama : '-'}
              </div>
            </div>
          </div>
        `;
        const swalCustom = this.$swal.mixin({
          customClass: {
            confirmButton: 'btn btn-danger btn-md ml-2',
            cancelButton: 'btn btn-clear btn-md'
          },
          buttonsStyling: false
        });
        this.$axios.get(`dinkes/${item.id}`).then(() => {
          swalCustom.fire(getAlertPopUp('delete', content)).then((result) => {
            if (result.value) {
              this.$axios.delete(`dinkes/${item.id}`)
                .then((response) => {
                  this.$swal.fire(
                    'Berhasil Menghapus Data',
                    'Data Dinkes Berhasil dihapus',
                    'success'
                  );
                  this.$bus.$emit('refresh-ajaxtable', 'dinkes')
                })
                .catch((error) => {
                  this.$swal.fire(
                    'Terjadi Kesalahan',
                    error.response.data.error,
                    'error'
                  );
                })
            }
          })
        });
      },
      getTipeInstansiValue(tipe){
        const findData = Array.isArray(optionInstansiPengirim) ? optionInstansiPengirim.find((el) => el.key === tipe) : null
        if (findData && findData.value) {
          return findData.value
        }
        return
      }
    }
  }
</script>