<template>
  <tr>
    <td v-text="(pagination.page - 1) * pagination.perpage + 1 + index"></td>
    <td>
      <input
        type="checkbox"
        name="list-sampel"
        v-bind:value="item.id"
        v-bind:id="'selected-sampel-' + item.id"
        v-bind:nomor_sampel="item.nomor_sampel"
        v-model="selected"
        @click="sampelOnChangeSelect"
      />
    </td>
    <td nowrap>
      <div v-if="item.nama_pasien" style="text-transform: capitalize;"><strong>{{ item.nama_pasien }}</strong></div>
      <div v-if="item.nik" class="text-muted">{{ item.nik }}</div>
      <div class="text-muted">{{ usiaPasien || null }}</div>
    </td>
    <td>
      {{ item.kota && item.kota.nama ? item.kota.nama : '' }}
    </td>
    <td>
      {{ item.kategori }}
    </td>
    <td>
      {{ item.tanggal_kunjungan ? momentFormatDate(item.tanggal_kunjungan) : '' }}
    </td>
    <td>
      <div class="badge badge-white" style="text-align:left; padding:10px">
        {{item.nomor_sampel}}
      </div>
    </td>
    <td v-if="config.has_action">
      <button class="mb-1 btn btn-primary btn-sm" @click="pilihData(item)"
        title="klik untuk pilih data">
          Pilih
      </button>
    </td>
  </tr>
</template>

<script>
import {
    getHumanAge,
    getAlertPopUp,
    momentFormatDate,
    momentFormatTime,
    getKeteranganData
  } from '~/utils'
export default {
  props: ["item", "pagination", "rowparams", "index", "config"],
  data() {
    let datas = this.$store.state.pasien_tes_masif.selectedSampels
    return {
      checked: false,
      selected: [],
      dataArr: datas,
      momentFormatDate,
      momentFormatTime
    }
  },
  computed: {
    usiaPasien() {
      if (this.item.usia_tahun && this.item.usia_bulan) {
        return `${this.item.usia_tahun} tahun ${this.item.usia_bulan} bulan`
      }
      if (this.item.tanggal_lahir) {
        return getHumanAge(this.item.tanggal_lahir)
      }
      return ""
    }
  },
  methods: {
    async pilihData(item) {
        const content = `
          <div class="row col-lg-12 flex-content-center mt-4" style="font-size: 12px">
            <div class="form-group row col-md-10">
              <div class="col-md-5 text-blue flex-left">
                Nomor Registrasi
              </div>
              <div class="col-md-7 flex-left">
                ${item.registration_code || ''}
              </div>
            </div>
            <div class="form-group row col-md-10">
              <div class="col-md-5 text-blue flex-left">
                Pasien
              </div>
              <div class="col-md-7">
                <div class=" flex-left" style="text-transform: capitalize">${item.nama_pasien || ''}</div>
                <div class=" flex-left">${item.nik || ''}</div>
                <div class=" flex-left">${getHumanAge(item.tanggal_lahir)}</div>
              </div>
            </div>
            <div class="form-group row col-md-10">
              <div class="col-md-5 text-blue flex-left">
                Domisili
              </div>
              <div class="col-md-7 flex-left">
                ${item.kota['nama'] || ''}
              </div>
            </div>
            <div class="form-group row col-md-10">
              <div class="col-md-5 text-blue flex-left">
                Kategori
              </div>
              <div class="col-md-7 flex-left">
                ${item.kategori || ''}
              </div>
            </div>
            <div class="form-group row col-md-10">
              <div class="col-md-5 text-blue flex-left">
                Sampel
              </div>
              <div class="col-md-7 flex-left">
                <div class="badge badge-white" padding:10px">
                  # ${item.nomor_sampel || ''} ${item.sampel_status || ''}
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
                ${getKeteranganData(item.nik, item.nama_pasien)}
              </div>
            </div>
          </div>
        `
        let swal = this.$swal
        let toast = this.$toast
        let bus = this.$bus
        let formData = new FormData()
        formData.append('id[]', item.id)
        const swalCustom = swal.mixin({
          customClass: {
            confirmButton: 'btn btn-success btn-md ml-2',
            cancelButton: 'btn btn-clear btn-md'
          },
          buttonsStyling: false
        })
        const {
          value: isConfirm
        } = await swalCustom.fire(getAlertPopUp('process-preview', content))
        if (isConfirm) {
          try {
            await this.$axios.post(`v1/tes-masif/registering`, formData, {
            headers: {
              'Content-Type': 'multipart/form-data'
            }
          })
            toast.success('Berhasil memproses data', {
              icon: 'check',
              iconPack: 'fontawesome',
              duration: 5000
            })
            await bus.$emit('refresh-ajaxtable', 'pasien-tes-masif')
          } catch (e) {
            swal.fire("Terjadi kesalahan", "Silakan hubungi Admin", "error")
          }
        }
      },
    sampelOnChangeSelect(e) {
      const newDomchecked = e.target.checked
      const el = e ? e.currentTarget : null
      const nomorSampel = el ? el.getAttribute("value") : null
      this.checked = newDomchecked
      if (this.checked) {
        this.$store.commit("pasien_tes_masif/add", nomorSampel)
      }
      if (!this.checked) {
        this.$store.commit("pasien_tes_masif/remove", nomorSampel)
      }
    },
  },

  watch: {
    'selected': function () {
      const sampel = document.getElementById("selected-sampel-" + this.item.id).value
      const findinArr = this.dataArr.length > 0 ? this.dataArr.find((el) => el === sampel) : null
      if (findinArr) {
        document.getElementById("selected-sampel-" + this.item.id).checked = true
      } else {
        document.getElementById("selected-sampel-" + this.item.id).checked = false
      }
    },
  },
}
</script>
