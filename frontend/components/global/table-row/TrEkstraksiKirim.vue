<template>
  <tr>
    <td v-text="(pagination.page - 1) * pagination.perpage + 1 + index"></td>
    <td>{{item.nomor_register}}</td>
    <td>
      <div class="badge badge-white" style="text-align:left; padding:5px">
        {{item.nomor_sampel}}
      </div>
    </td>
    <td>{{item.jenis_sampel_nama}}</td>
    <td>{{item.lab_pcr_nama}}</td>
    <td nowrap>
      <div>
        <strong>{{ item.waktu_extraction_sample_sent ? momentFormatDate(item.waktu_extraction_sample_sent) : null }}</strong>
      </div>
      <div class="text-muted">
        {{ item.waktu_extraction_sample_sent ? 'pukul ' + momentFormatTime(item.waktu_extraction_sample_sent) : null }}
      </div>
    </td>
    <td>
      {{item.deskripsi ? humanize(item.deskripsi) : '-'}}
      <template v-if="item.is_musnah_ekstraksi">
        <br />
        <span class="badge badge-info">Sudah Dimusnahkan</span>
      </template>
      <template v-if="!item.is_musnah_ekstraksi">
        <br />
        <span class="badge badge-warning">Belum Dimusnahkan</span>
      </template>
    </td>
    <td>{{item.kesimpulan_pemeriksaan == 'swab_ulang' ? 'swab ulang' : item.kesimpulan_pemeriksaan}}</td>
    <td width="20%">
      <nuxt-link tag="a" class="mb-1 btn btn-yellow btn-sm" :to="`/ekstraksi/detail/${item.id}`"
        title="Klik untuk melihat detail">
        <em class="uil-info-circle" />
      </nuxt-link>
      <nuxt-link :to="`/ekstraksi/edit/${item.id}`" class="mb-1 btn btn-primary btn-sm" tag="a"
        title="Klik untuk mengubah data"
        v-if="item.sampel_status != 'sample_valid' && item.sampel_status != 'sample_verified'">
        <em class="fa fa-edit" />
      </nuxt-link>
      <button v-if="!item.is_musnah_ekstraksi" type="button" title="Klik untuk musnahkan sampel"
        class="mb-1 btn btn-clear btn-sm text-nowrap" @click="doMusnahkan(item)">
        <em class="uil-trash" /> Musnahkan
      </button>
    </td>
  </tr>
</template>

<script>
  import axios from "axios";
  import {
    momentFormatDate,
    momentFormatTime,
    getAlertPopUp,
    humanize
  } from '~/utils';
  export default {
    props: ['item', 'pagination', 'rowparams', 'index'],
    data() {
      return {
        loading: false,
        momentFormatDate,
        momentFormatTime,
        humanize
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
                Lab PCR
              </div>
              <div class="col-md-7 flex-left">
                ${item.lab_pcr_nama}
              </div>
            </div>
            <div class="form-group row col-md-10">
              <div class="col-md-5 text-blue flex-left">
                Waktu Penerimaan
              </div>
              <div class="col-md-7">
                <div class=" flex-left">
                  ${momentFormatDate(item.waktu_extraction_sample_sent)}, ${momentFormatTime(item.waktu_extraction_sample_sent)}
                </div>
              </div>
            </div>
            <div class="form-group row col-md-10">
              <div class="col-md-5 text-blue flex-left">
                Status
              </div>
              <div class="col-md-7 flex-left">
                ${item.deskripsi ? humanize(item.deskripsi) : '-'}
              </div>
            </div>
            <div class="form-group row col-md-10">
              <div class="col-md-5 text-blue flex-left">
                Hasil Pemeriksaan
              </div>
              <div class="col-md-7 flex-left">
                ${item.kesimpulan_pemeriksaan ? humanize(item.kesimpulan_pemeriksaan) : '-'}
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
            let resp = await axios.post(`/v1/ekstraksi/musnahkan/${id}`);
            toast.success("Sampel berhasil ditandai telah dimusnahkan", {
              icon: 'check',
              iconPack: 'fontawesome',
              duration: 5000
            })
            await bus.$emit('refresh-ajaxtable', 'ekstraksi-kirim')
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
      },
    }
  }
</script>