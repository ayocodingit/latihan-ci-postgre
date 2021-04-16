<template>
  <tr>
    <td v-text="(pagination.page - 1) * pagination.perpage + 1 + index" />
    <td>
      <input type="checkbox" class="mt-1" name="list-sampel" v-bind:value="item.nomor_sampel"
        v-bind:id="'selected-sampel-'+item.id" v-model="selected" @click="sampelOnChangeSelect">
    </td>
    <td>
      {{item.nomor_register}}
    </td>
    <td>
      <div class="badge badge-white" style="text-align:left; padding:5px">
        {{item.nomor_sampel}}
      </div>
    </td>
    <td>
      {{item.jenis_sampel_nama}}
    </td>
    <td>
      {{ item.deskripsi }}<br>
      <p class="badge badge-info" v-if="item.re_ekstraksi">{{item.re_ekstraksi}}</p>
    </td>
    <td>
      <span v-if="item.ekstraksi">{{ item.ekstraksi.operator_ekstraksi }}</span>
      <span v-if="item.ekstraksi && !item.ekstraksi.operator_ekstraksi">{{ '-' }}</span>
    </td>
    <td nowrap>
      <div>
        <strong>{{ item.waktu_extraction_sample_extracted ? momentFormatDate(item.waktu_extraction_sample_extracted) : null }}</strong>
      </div>
      <div class="text-muted">
        {{ item.waktu_extraction_sample_extracted ? 'pukul ' + momentFormatTime(item.waktu_extraction_sample_extracted) : null }}
      </div>
    </td>
    <td width="20%">
      <nuxt-link tag="a" class="btn btn-yellow btn-sm mb-1" :to="`/ekstraksi/detail/${item.id}`"
        title="Klik untuk melihat detail"><em class="uil-info-circle" /></nuxt-link>
      <nuxt-link :to="`/ekstraksi/edit/${item.id}`" class="btn btn-primary btn-sm mb-1" tag="a"
        title="Klik untuk mengubah data"><em class="fa fa-edit" /></nuxt-link>
      <button type="button" class="mb-1 btn btn-clear btn-sm" title="Klik untuk menandai sampel sebagai invalid"
        @click="promptInvalid(item)" :disabled="loading" :class="{'btn-loading': loading}">
        <em class="uil-flask-potion" /> Invalid
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
      let datas = this.$store.state.ekstraksi_dilakukan.selectedSampels;
      return {
        loading: false,
        checked: false,
        selected: [],
        dataArr: datas,
        momentFormatDate,
        momentFormatTime
      }
    },
    computed: {
      selectedSampels: {
        set(val) {
          this.$store.state.selectedSampels = val
        },
        get() {
          return this.$store.state.selectedSampels
        }
      }
    },
    methods: {
      promptInvalid(item) {
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
                Status
              </div>
              <div class="col-md-7 flex-left">
                ${item.deskripsi || '-'}
              </div>
            </div>
            <div class="form-group row col-md-10">
              <div class="col-md-5 text-blue flex-left">
                Operator
              </div>
              <div class="col-md-7 flex-left">
                ${item.ekstraksi ? item.ekstraksi.operator_ekstraksi : '-'}
              </div>
            </div>
            <div class="form-group row col-md-10">
              <div class="col-md-5 text-blue flex-left">
                Waktu Penerimaan
              </div>
              <div class="col-md-7">
                <div class=" flex-left">
                  ${momentFormatDate(item.waktu_extraction_sample_extracted)}, ${momentFormatTime(item.waktu_extraction_sample_extracted)}
                </div>
              </div>
            </div>
          </div>
          <div class="row flex-content-center">
            ${this.$t("alert_confirm_invalid_text")}
          </div>
        `;
        const swalWithBootstrapButtons = this.$swal.mixin({
          customClass: {
            confirmButton: 'btn btn-danger btn-md ml-2',
            cancelButton: 'btn btn-clear btn-md'
          },
          buttonsStyling: false
        });
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
                let resp = await this.$axios.post(`/v1/ekstraksi/set-swab-ulang/${item.id}`, {
                  alasan: result.value
                });
                swalWithBootstrapButtons.fire(
                  'Selesai!',
                  'Sampel berhasil ditandai sebagai invalid',
                  'success'
                )
                this.$bus.$emit('refresh-ajaxtable', 'ekstraksi-dilakukan')
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
      sampelOnChangeSelect(e) {
        const newDomchecked = e.target.checked;
        const el = e ? e.currentTarget : null;
        const nomorSampel = el ? el.getAttribute('value') : null;
        this.checked = newDomchecked;
        if (this.checked) {
          this.$store.commit('ekstraksi_dilakukan/add', nomorSampel)
        }
        if (!this.checked) {
          this.$store.commit('ekstraksi_dilakukan/remove', nomorSampel)
        }
      }
    },

    watch: {
      'selected': function (newVal, oldVal) {
        const sampel = document.getElementById('selected-sampel-' + this.item.id).value;
        const findinArr = this.dataArr.length > 0 ? this.dataArr.find(el => el === sampel) : null;
        if (findinArr) {
          document.getElementById('selected-sampel-' + this.item.id).checked = true;
        } else {
          document.getElementById('selected-sampel-' + this.item.id).checked = false;
        }
      }
    },
  }
</script>
<style scoped>
  .checkbox-ekstraksi-dilakukan {
    transform: scale(1.7);
  }
</style>