<template>
  <tr>
    <td v-text="(pagination.page - 1) * pagination.perpage + 1 + index"></td>
    <td>
      {{ item.name }}
      <br>
      {{ item.validator ? ('Validator: ' + item.validator.nama) : '' }}
    </td>
    <td>
      {{ item.username }}
      <br>
      {{ item.validator ? ('NIP: ' + item.validator.nip) : '' }}
    </td>
    <td>{{ item.email }}</td>
    <td>
      {{ item.roles ? item.roles.roles_name : '-' }}
      <br>
      {{ item.lab_pcr ? ('Lab PCR: ' + item.lab_pcr.nama) : '' }}
    </td>
    <td>
      <nuxt-link :to="`pengguna/detail/${item.id}`" class="mb-1 btn btn-yellow btn-sm">
        <em class="fa fa-eye" />
      </nuxt-link>
      <nuxt-link :to="`pengguna/update/${item.id}`" class="mb-1 btn btn-primary btn-sm">
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
  } from '~/utils';
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
                Nama
              </div>
              <div class="col-md-7 flex-left">
                ${item.name || '-'}
              </div>
            </div>
            <div class="form-group row col-md-10">
              <div class="col-md-5 text-blue flex-left">
                Username
              </div>
              <div class="col-md-7 flex-left">
                ${item.username}
              </div>
            </div>
            <div class="form-group row col-md-10">
              <div class="col-md-5 text-blue flex-left">
                Email
              </div>
              <div class="col-md-7 flex-left">
                ${item.email}
              </div>
            </div>
            <div class="form-group row col-md-10">
              <div class="col-md-5 text-blue flex-left">
                Role
              </div>
              <div class="col-md-7 flex-left">
                ${item.roles ? item.roles.roles_name : '-'}
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
          this.$axios.delete(`pengguna/${item.id}`)
            .then((response) => {
              swal.fire(
                'Berhasil Menghapus Data',
                'Data Pengguna Berhasil dihapus',
                'success'
              );
              bus.$emit('refresh-ajaxtable', 'master-user')
            })
            .catch((error) => {
              swal.fire(
                'Terjadi Kesalahan',
                error.response.data.error,
                'error'
              );
              console.log('error Hapus pengguna : ', error)
            })
        }
      }
    }
  }
</script>