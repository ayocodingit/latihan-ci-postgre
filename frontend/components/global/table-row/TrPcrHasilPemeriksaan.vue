<template>
  <tr>
    <td v-text="(pagination.page - 1) * pagination.perpage + 1 + index"></td>
    <td>
      {{item.nomor_register}}
    </td>
    <td>
      <div class="badge badge-white" style="text-align:left; padding:5px">
        {{item.nomor_sampel}}
      </div>
    </td>
    <td nowrap>
      <div>
        <strong>{{ item.waktu_pcr_sample_analyzed ? momentFormatDate(item.waktu_pcr_sample_analyzed) : null }}</strong>
      </div>
      <div class="text-muted">
        {{ item.waktu_pcr_sample_analyzed ? 'pukul ' + momentFormatTime(item.waktu_pcr_sample_analyzed) : null }}
      </div>
    </td>
    <td>
      {{item.deskripsi}}
      <template v-if="item.is_musnah_pcr">
        <br />
        <div class="badge badge-warning" style="padding: 5px">
          Sudah Dimusnahkan
        </div>
      </template>
      <template v-if="!item.is_musnah_pcr">
        <br />
        <div class="badge badge-danger" style="padding: 5px">
          Belum Dimusnahkan
        </div>
      </template>
    </td>
    <td>
      {{item.catatan_pengiriman}}
    </td>
    <td>
      {{item.kesimpulan_pemeriksaan}}
    </td>
    <td width="20%">
      <nuxt-link tag="a" class="text-nowrap btn btn-yellow btn-sm mb-1" :to="`/pcr/detail/${item.id}`"
        title="Klik untuk melihat detail">
        <em class="uil-info-circle" />
      </nuxt-link>
      <nuxt-link tag="a" class="text-nowrap btn btn-primary btn-sm mb-1" :to="`/pcr/input/${item.id}`"
        title="Klik untuk mengisi hasil analisis" v-if="item.sampel_status != 'sample_valid' && item.sampel_status != 'sample_verified'">
        <em class="uil-edit" />
      </nuxt-link>
      <button v-if="!item.is_musnah_pcr" type="button" class="text-nowrap btn btn-sm btn-clear mb-1"
        @click="doMusnahkan(item)" :disabled="loading" :class="{'btn-loading': loading}">
        <em class="uil-trash" /> Musnahkan
      </button>
    </td>
  </tr>
</template>

<script>
  import {
    momentFormatDate,
    momentFormatTime,
    getAlertPopUp
  } from '~/utils';
  export default {
    props: ['item', 'pagination', 'rowparams', 'index'],
    data() {
      return {
        loading: false,
        momentFormatDate,
        momentFormatTime
      }
    },
    methods: {
      async doMusnahkan(item) {
        const content = `
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
                Waktu Periksa
              </div>
              <div class="col-md-7">
                <div class=" flex-left">
                  ${item.waktu_pcr_sample_analyzed ? momentFormatDate(item.waktu_pcr_sample_analyzed) : '-'}
                  ${item.waktu_pcr_sample_analyzed ? ', ' + momentFormatTime(item.waktu_pcr_sample_analyzed) : ''}
                </div>
              </div>
            </div>
            <div class="form-group row col-md-10">
              <div class="col-md-5 text-blue flex-left">
                Status
              </div>
              <div class="col-md-7 flex-left">
                ${item.deskripsi || '-'}
              </div>
            </div>
            <div class="form-group row col-md-10">
              <div class="col-md-5 text-blue flex-left">
                Catatan Pengiriman
              </div>
              <div class="col-md-7 flex-left">
                ${item.catatan_pengiriman || '-'}
              </div>
            </div>
            <div class="form-group row col-md-10">
              <div class="col-md-5 text-blue flex-left">
                Hasil Pemeriksaan
              </div>
              <div class="col-md-7 flex-left">
                ${item.kesimpulan_pemeriksaan || '-'}
              </div>
            </div>
          </div>
          <div class="row flex-content-center">
            ${this.$t("alert_confirm_musnahkan_text")}
          </div>
        `;
        let id = item.id;
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
        } = await swalCustom.fire(getAlertPopUp('musnahkan', content));
        if (isConfirm) {
          try {
            await this.$axios.post(`/v1/pcr/musnahkan/${id}`);
            toast.success("Sampel berhasil ditandai telah dimusnahkan", {
              icon: 'check',
              iconPack: 'fontawesome',
              duration: 5000
            })
            await bus.$emit('refresh-ajaxtable', 'pcr-hasil-pemeriksaan')
          } catch (e) {
            if (e.response && e.response.data.code == 422) {
              toast.error(e.response.data.message, {
                icon: 'times',
                iconPack: 'fontawesome',
                duration: 5000
              })
              swalWithBootstrapButtons.fire(
                'Gagal',
                e.response.data.message,
                'error'
              )
            } else {
              toast.error("Gagal menandai sampel dimusnahkan", {
                icon: 'times',
                iconPack: 'fontawesome',
                duration: 5000
              })
            }
          }
        }
      }
    }
  }
</script>