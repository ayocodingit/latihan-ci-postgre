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
      {{item.petugas_pengambilan_sampel}}
    </td>
    <td>
      {{ item.deskripsi }}
    </td>
    <td nowrap>
      <div>
        <strong>{{ item.waktu_sample_taken ? momentFormatDate(item.waktu_sample_taken) : null }}</strong>
      </div>
      <div class="text-muted">
        {{ item.waktu_sample_taken ? 'pukul ' + momentFormatTime(item.waktu_sample_taken) : null }}</div>
    </td>
    <td width="20%">
      <nuxt-link tag="a" class="mb-1 btn btn-yellow btn-sm" :to="`/ekstraksi/detail/${item.id}`"
        title="Klik untuk melihat detail">
        <em class="uil-info-circle" />
      </nuxt-link>
    </td>
  </tr>
</template>

<script>
  import {
    momentFormatDate,
    momentFormatTime
  } from '~/utils';
  export default {
    props: ['item', 'pagination', 'rowparams', 'index'],
    data() {
      let datas = this.$store.state.ekstraksi_penerimaan.selectedSampels;
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
      },
    },
    created() {},
    methods: {
      promptInvalid() {
        const swalWithBootstrapButtons = this.$swal.mixin({
          customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger'
          },
          buttonsStyling: false
        })
        swalWithBootstrapButtons.fire({
          title: 'Apakah Anda Yakin Mendandai Sampel Menjadi Invalid?',
          text: "Setelah sampel menjadi invalid, data tidak dapat dikembalikan. Masukkan keterangan invalid:",
          type: 'warning',
          input: 'text',
          showCancelButton: true,
          confirmButtonText: 'Ya, Tandai Sampel Invalid',
          cancelButtonText: 'Batalkan',
          reverseButtons: true
        }).then(async (result) => {
          if (result.value == '') {
            swalWithBootstrapButtons.fire(
              'Gagal',
              'Alasan tidak boleh kosong',
              'error'
            )
          } else if (result.value) {
            try {
              this.loading = true
              let resp = await this.$axios.post("/v1/ekstraksi/set-invalid/" + this.item.id, {
                alasan: result.value
              });
              swalWithBootstrapButtons.fire(
                'Selesai!',
                'Sampel berhasil ditandai sebagai invalid',
                'success'
              )
              this.$bus.$emit('refresh-ajaxtable', 'ekstraksi-penerimaan')
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
          this.$store.commit('ekstraksi_penerimaan/add', nomorSampel)
        }
        if (!this.checked) {
          this.$store.commit('ekstraksi_penerimaan/remove', nomorSampel)
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
  .checkbox-ekstraksi-penerimaan {
    transform: scale(1.7);
  }
</style>