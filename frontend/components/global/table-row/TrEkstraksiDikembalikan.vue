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
        <strong>{{ item.waktu_extraction_sample_reextract ? momentFormatDate(item.waktu_extraction_sample_reextract) : null }}</strong>
      </div>
      <div class="text-muted">
        {{ item.waktu_extraction_sample_reextract ? 'pukul ' + momentFormatTime(item.waktu_extraction_sample_reextract) : null }}
      </div>
    </td>
    <td>{{ item.pcr ? item.pcr.catatan_pemeriksaan : '-' }}</td>
    <td width="20%">
      <nuxt-link tag="a" class="btn btn-yellow btn-sm mb-1 text-nowrap" :to="`/ekstraksi/detail/${item.id}`"
        title="Klik untuk melihat detail">
        <em class="uil-info-circle" />
      </nuxt-link>
      <button type="button" class="btn btn-primary btn-sm mb-1 text-nowrap"
        title="Klik untuk menandai sampel untuk diproses" @click="promptProses(item)" :disabled="loading"
        :class="{'btn-loading': loading}">
        <em class="uil-flask" /> Proses
      </button>
      <button type="button" class="btn btn-clear btn-sm mb-1 text-nowrap"
        title="Klik untuk menandai sampel sebagai invalid" @click="promptInvalid(item)" :disabled="loading"
        :class="{'btn-loading': loading}">
        <em class="uil-flask-potion" /> Invalid
      </button>
    </td>
  </tr>
</template>

<script>
  import axios from "axios";
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
      getContent(tipe, item) {
        let textContent;
        if (tipe === 'invalid') {
          textContent = this.$t("alert_confirm_invalid_text");
        } else if (tipe === 'proses') {
          textContent = this.$t("alert_confirm_proses_text");
        }
        const content = `
          <div class="row col-lg-12 flex-content-center mb-2 mt-2" style="font-size: 12px">
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
                Keterangan
              </div>
              <div class="col-md-7 flex-left">
                ${item.pcr ? item.pcr.catatan_pemeriksaan : '-'}
              </div>
            </div>
            <div class="form-group row col-md-10">
              <div class="col-md-5 text-blue flex-left">
                Waktu Penerimaan
              </div>
              <div class="col-md-7">
                <div class=" flex-left">
                  ${momentFormatDate(item.waktu_extraction_sample_reextract)}, ${momentFormatTime(item.waktu_extraction_sample_reextract)}
                </div>
              </div>
            </div>
          </div>
          <div class="row flex-content-center">
            ${textContent}
          </div>
        `;
        return content;
      },
      promptInvalid(item) {
        const content = this.getContent('invalid', item);
        const swalWithBootstrapButtons = this.$swal.mixin({
          customClass: {
            confirmButton: 'btn btn-danger btn-md ml-2',
            cancelButton: 'btn btn-clear btn-md'
          },
          buttonsStyling: false
        })
        swalWithBootstrapButtons.fire(getAlertPopUp('invalid', content))
          .then(async (result) => {
            if (result.value == '') {
              swalWithBootstrapButtons.fire(
                'Gagal',
                'Alasan tidak boleh kosong',
                'error'
              )
            } else if (result.value) {
              try {
                this.loading = true
                let resp = await axios.post(`/v1/ekstraksi/set-swab-ulang/${item.id}`, {
                  alasan: result.value
                });
                swalWithBootstrapButtons.fire(
                  'Selesai!',
                  'Sampel berhasil ditandai sebagai invalid',
                  'success'
                )
                this.$bus.$emit('refresh-ajaxtable', 'ekstraksi-dikembalikan')
              } catch (err) {
                if (err.response && err.response.data.code == 422) {
                  swalWithBootstrapButtons.fire(
                    'Gagal',
                    err.response.data.message,
                    'error'
                  )
                } else {
                  swalWithBootstrapButtons.fire(
                    'Gagal',
                    'Gagal menandai sampel menjadi invalid',
                    'error'
                  )
                }
              }
              this.loading = false
            } else if (
              /* Read more about handling dismissals below */
              result.dismiss === this.$swal.DismissReason.cancel
            ) {}
          })
      },
      promptProses(item) {
        const content = this.getContent('proses', item);
        const swalWithBootstrapButtons = this.$swal.mixin({
          customClass: {
            confirmButton: 'btn btn-success btn-md ml-2',
            cancelButton: 'btn btn-clear btn-md'
          },
          buttonsStyling: false
        })
        swalWithBootstrapButtons.fire(getAlertPopUp('proses', content))
          .then(async (result) => {
            console.log(result)
            if (result.value == '') {
              swalWithBootstrapButtons.fire(
                'Gagal',
                'Alasan tidak boleh kosong',
                'error'
              )
            } else if (result.value) {
              try {
                this.loading = true
                let resp = await axios.post(`/v1/ekstraksi/set-proses/${item.id}`, {
                  alasan: result.value
                });
                swalWithBootstrapButtons.fire(
                  'Selesai!',
                  'Sampel berhasil ditandai sebagai sampel yang diproses',
                  'success'
                )
                this.$bus.$emit('refresh-ajaxtable', 'ekstraksi-dikembalikan')
              } catch (err) {
                if (err.response && err.response.data.code == 422) {
                  swalWithBootstrapButtons.fire(
                    'Gagal',
                    err.response.data.message,
                    'error'
                  )
                } else {
                  swalWithBootstrapButtons.fire(
                    'Gagal',
                    'Gagal menandai sampel menjadi sampel yang diproses',
                    'error'
                  )
                }
              }
              this.loading = false
            } else if (
              /* Read more about handling dismissals below */
              result.dismiss === this.$swal.DismissReason.cancel
            ) {}
          })
      }
    }
  }
</script>