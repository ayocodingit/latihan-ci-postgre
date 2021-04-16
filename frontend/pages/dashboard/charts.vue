<template>
  <div>
    <div class="row">
      <div class="col-md-6" id="col-registrasi">
        <Ibox :title="`Total Registrasi (${params.registrasi})`">
          <template v-slot:tools>
            <select class="form-control h-auto mb-1 p-0 w-auto" v-model="params.registrasi">
              <option value="Daily">Daily</option>
              <option value="Monthly">Monthly</option>
            </select>
          </template>
          <ChartRegistrasi :barId="'RegistrasiMasuk'" />
        </Ibox>
      </div>

      <div class="col-md-6" id="col-admin-sampel">
        <Ibox :title="`Total Sampel Masuk (${params.sampelmasuk})`">
          <template v-slot:tools>
            <select class="form-control h-auto mb-1 p-0 w-auto" v-model="params.sampelmasuk">
              <option value="Daily">Daily</option>
              <option value="Monthly">Monthly</option>
            </select>
          </template>
          <ChartSampel :barId="'TotalSampelMasuk'" />
        </Ibox>
      </div>
    </div>

    <div class="row">
      <div class="col-md-6" id="col-ekstraksi-pcr">
        <Ibox :title="`Total Ekstraksi-PCR (${params.ekstraksipcr})`">
          <template v-slot:tools>
            <select class="form-control h-auto mb-1 p-0 w-auto" v-model="params.ekstraksipcr">
              <option value="Daily">Daily</option>
              <option value="Monthly">Monthly</option>
            </select>
          </template>
          <ChartEkstraksiPcr :barId="'sampleEkstraksiPCR'" />
        </Ibox>
      </div>
      <div class="col-md-6" id="col-hasil-pemeriksaan">
        <Ibox :title="`Hasil Pemeriksaan (${params.hasil})`">
          <template v-slot:tools>
            <select class="form-control h-auto mb-1 p-0 w-auto" v-model="params.hasil">
              <option value="Daily">Daily</option>
              <option value="Monthly">Monthly</option>
            </select>
          </template>
          <ChartHasilPemeriksaan :barId="'HasilPemeriksaan'" />
        </Ibox>
      </div>
    </div>
  </div>
</template>

<script>
  import ChartRegistrasi from "./chart/ChartRegistrasi";
  import ChartSampel from "./chart/ChartSampel";
  import ChartEkstraksiPcr from "./chart/ChartEkstraksiPcr";
  import ChartHasilPemeriksaan from "./chart/ChartHasilPemeriksaan";
  export default {
    name: "charts",
    components: {
      ChartRegistrasi,
      ChartSampel,
      ChartEkstraksiPcr,
      ChartHasilPemeriksaan,
    },
    data() {
      return {
        loading: true,
        data: {
          status: {},
          labs: [],

        },
        params: {
          registrasi: 'Daily',
          sampelmasuk: 'Daily',
          ekstraksipcr: 'Daily',
          hasil: 'Daily',
        },
      };
    },
    watch: {
      "params.registrasi": function (newVal, oldVal) {
        this.$bus.$emit('refresh-chart-registrasi', this.params.registrasi)
        if (this.params.registrasi === 'Monthly') {
          $('#col-registrasi').removeClass('col-md-6').addClass('col-md-12')
        } else if (this.params.registrasi === 'Daily') {
          $('#col-registrasi').removeClass('col-md-12').addClass('col-md-6')
        }
      },
      "params.sampelmasuk": function (newVal, oldVal) {
        this.$bus.$emit('refresh-chart-admin-sampel', this.params.sampelmasuk)
        if (this.params.sampelmasuk === 'Monthly') {
          $('#col-admin-sampel').removeClass('col-md-6').addClass('col-md-12')
        } else if (this.params.sampelmasuk === 'Daily') {
          $('#col-admin-sampel').removeClass('col-md-12').addClass('col-md-6')
        }
      },
      "params.ekstraksipcr": function (newVal, oldVal) {
        this.$bus.$emit('refresh-chart-ekstraksi-pcr', this.params.ekstraksipcr)
        if (this.params.ekstraksipcr === 'Monthly') {
          $('#col-ekstraksi-pcr').removeClass('col-md-6').addClass('col-md-12')
        } else if (this.params.ekstraksipcr === 'Daily') {
          $('#col-ekstraksi-pcr').removeClass('col-md-12').addClass('col-md-6')
        }
      },
      "params.hasil": function (newVal, oldVal) {
        this.$bus.$emit('refresh-chart-hasil-pemeriksaan', this.params.hasil)
        if (this.params.hasil === 'Monthly') {
          $('#col-hasil-pemeriksaan').removeClass('col-md-6').addClass('col-md-12')
        } else if (this.params.hasil === 'Daily') {
          $('#col-hasil-pemeriksaan').removeClass('col-md-12').addClass('col-md-6')
        }
      },
    }
  }
</script>