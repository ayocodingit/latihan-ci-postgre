<template>
  <tr>
    <td v-text="(pagination.page - 1) * pagination.perpage + 1 + index" />
    <td v-show="!config.disabled_checkbox">
      <input type="checkbox" name="list-sampel" v-bind:value="item.id" v-bind:id="'selected-sampel-'+item.id"
        v-model="selected" @click="sampelOnChangeSelect">
    </td>
    <td>
      {{ item.nomor_registrasi || '' }}
    </td>
    <td nowrap>
      <div v-if="item.nama_pasien">
        <strong>{{ item.nama_pasien || '' }}</strong>
      </div>
      <div v-if="item.no_identitas" class="text-muted">
        {{ item.no_identitas }}
      </div>
      <div class="text-muted">
        {{ item.usia_tahun ? `${item.usia_tahun} tahun` : '' }}
      </div>
    </td>
    <td>
      {{ item.tanggal_lahir ? momentFormatDate(item.tanggal_lahir) : '' }}
    </td>
    <td>
      {{ item.kategori || '' }}
    </td>
    <td>
      {{ item.no_hasil || '' }}
    </td>
    <td>
      {{ item.tanggal_periksa ? momentFormatDate(item.tanggal_periksa) : '' }}
    </td>
    <td>
      {{ item.hasil_periksa ? humanize(item.hasil_periksa) : '' }}
    </td>
    <td>
      <nuxt-link tag="a" class="mb-1 text-nowrap btn btn-yellow btn-sm" title="Klik untuk melihat detail"
        :to="`/swab-antigen/detail/${item.id}`">
        <em class="uil-info-circle" />
      </nuxt-link>
      <nuxt-link class="mb-1 btn btn-primary btn-sm" title="Klik untuk mengubah data" :to="`/swab-antigen/edit/${item.id}`">
        <em class="fa fa-edit" />
      </nuxt-link>
      <a href="#" class="mb-1 btn btn-danger btn-sm" title="klik untuk menghapus data" @click="deleteData(item)">
        <em class="fa fa-trash" />
      </a>
    </td>
  </tr>
</template>

<script>
  import {
    humanize,
    getAlertPopUp,
    momentFormatDate
  } from '~/utils'
  export default {
    props: ['item', 'pagination', 'rowparams', 'index', 'config'],
    data() {
      const datas = this.$store.state.swab_antigen_hasil.selectedSampels
      return {
        loading: false,
        checked: false,
        selected: [],
        dataArr: datas,
        humanize,
        momentFormatDate
      }
    },
    methods: {
      sampelOnChangeSelect(e) {
        const newDomchecked = e.target.checked
        const el = e ? e.currentTarget : null
        const nomorSampel = el ? el.getAttribute('value') : null
        this.checked = newDomchecked
        if (this.checked) {
          this.$store.commit('swab_antigen_hasil/add', nomorSampel)
        }
        if (!this.checked) {
          this.$store.commit('swab_antigen_hasil/remove', nomorSampel)
        }
      },
      async deleteData(data) {
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
                ${data.nomor_registrasi || '-'}
              </div>
            </div>
            <div class="form-group row col-md-10">
              <div class="col-md-5 text-blue flex-left">
                Pasien
              </div>
              <div class="col-md-7">
                <div class=" flex-left" style="text-transform: capitalize">${data.nama_pasien || '-'}</div>
                <div class=" flex-left">${data.no_identitas || ''}</div>
              </div>
            </div>
            <div class="form-group row col-md-10">
              <div class="col-md-5 text-blue flex-left">
                Tanggal Lahir
              </div>
              <div class="col-md-7 flex-left">
                ${data.tanggal_lahir || '-'}
              </div>
            </div>
            <div class="form-group row col-md-10">
              <div class="col-md-5 text-blue flex-left">
                Kategori
              </div>
              <div class="col-md-7 flex-left">
                ${data.kategori || '-'}
              </div>
            </div>
            <div class="form-group row col-md-10">
              <div class="col-md-5 text-blue flex-left">
                Tanggal Hasil
              </div>
              <div class="col-md-7">
                <div class=" flex-left">
                  ${data.tanggal_periksa || '-'}
                </div>
              </div>
            </div>
            <div class="form-group row col-md-10">
              <div class="col-md-5 text-blue flex-left">
                Hasil Pemeriksaan
              </div>
              <div class="col-md-7 flex-left font-weight-bold">
                  ${data.hasil_periksa || '-'}
              </div>
            </div>
          </div>
        `;
        let swal = this.$swal
        let toast = this.$toast
        let bus = this.$bus
        const swalCustom = swal.mixin({
          customClass: {
            confirmButton: 'btn btn-danger btn-md ml-2',
            cancelButton: 'btn btn-clear btn-md'
          },
          buttonsStyling: false
        })
        const {
          value: isConfirm
        } = await swalCustom.fire(getAlertPopUp('delete', content))
        if (isConfirm) {
          try {
            await this.$axios.delete(`/v1/swab-antigen/${data.id}`)
            toast.success('Berhasil menghapus data', {
              icon: 'check',
              iconPack: 'fontawesome',
              duration: 5000
            })
            await bus.$emit('refresh-ajaxtable', 'swab-antigen-hasil')
          } catch (e) {
            swal.fire("Terjadi kesalahan", "Silakan hubungi Admin", "error")
          }
        }
      }
    }
  }
</script>
