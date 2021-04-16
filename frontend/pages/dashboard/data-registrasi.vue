<template>
  <div class="row">
    <div class="col-md-12">
      <div class="mb-3">
        <h2 class="font-weight-bold text-nowrap mr-3">
          <em class="uil-user-plus" />
          Registrasi
        </h2>
      </div>
    </div>
    <div class="col-md-12">
      <Ibox title="Pasien Registrasi">
        <div class="row tracking-row">

          <div class="col-md-4 col-md-offset-1 mb-3 mb-md-0">
            <div class="text-center">
              <h5 class="font-weight-bold text-blue">Registrasi Mandiri</h5>
              <h2 class="font-weight-bold">
                {{ data.mandiri | formatCurrency}}
              </h2>
              <small>Orang</small>
            </div>
          </div>

          <div class="col-md-4 col-md-offset-1 mb-3 mb-md-0">
            <div class="text-center">
              <h5 class="font-weight-bold text-blue">Registrasi Rujukan</h5>
              <h2 class="font-weight-bold">
                {{ data.rujukan | formatCurrency}}
              </h2>
              <small>Orang</small>
            </div>
          </div>

          <div class="col-md-4 col-md-offset-1 mb-3 mb-md-0">
            <div class="text-center">
              <h5 class="font-weight-bold text-yellow">Total Pasien</h5>
              <h2 class="font-weight-bold">
                {{ data.total | formatCurrency}}
              </h2>
              <small>Orang</small>
            </div>
          </div>

        </div>
      </Ibox>
    </div>

    <div class="col-md-12">
      <Ibox title="Registrasi Mandiri">
        <div class="row tracking-row">

          <div class="col-md-4 col-md-offset-1 mb-3 mb-md-0">
            <div class="text-center">
              <h5 class="font-weight-bold text-blue">Registrasi Hari Ini</h5>
              <h2 class="font-weight-bold">
                {{ data.jumlah_perhari_mandiri | formatCurrency}}
              </h2>
              <small>Orang</small>
            </div>
          </div>

          <div class="col-md-4 col-md-offset-1 mb-3 mb-md-0">
            <div class="text-center">
              <h5 class="font-weight-bold text-red">Data Belum Lengkap</h5>
              <h2 class="font-weight-bold">
                {{ data.data_belum_lengkap_mandiri | formatCurrency}}
              </h2>
              <small>Orang</small>
            </div>
          </div>

          <div class="col-md-4 col-md-offset-1 mb-3 mb-md-0">
            <div class="text-center">
              <h5 class="font-weight-bold text-blue">Pemeriksaan Selesai</h5>
              <h2 class="font-weight-bold">
                {{ data.pemeriksaan_selesai_mandiri | formatCurrency}}
              </h2>
              <small>Orang</small>
            </div>
          </div>

        </div>
      </Ibox>
    </div>

    <div class="col-md-12">
      <Ibox title="Registrasi Rujukan">
        <div class="row tracking-row">

          <div class="col-md-3 col-md-offset-1 mb-3 mb-md-0">
            <div class="text-center">
              <h5 class="font-weight-bold text-blue">Registrasi Hari Ini</h5>
              <h2 class="font-weight-bold">
                {{ data.jumlah_perhari_rujukan | formatCurrency}}
              </h2>
              <small>Orang</small>
            </div>
          </div>

          <div class="col-md-3 col-md-offset-1 mb-3 mb-md-0">
            <div class="text-center">
              <h5 class="font-weight-bold text-red">Data Belum Lengkap</h5>
              <h2 class="font-weight-bold">
                {{ data.data_belum_lengkap_rujukan | formatCurrency}}
              </h2>
              <small>Orang</small>
            </div>
          </div>

          <div class="col-md-3 col-md-offset-1 mb-3 mb-md-0">
            <div class="text-center">
              <h5 class="font-weight-bold text-blue">Pemeriksaan Selesai</h5>
              <h2 class="font-weight-bold">
                {{ data.pemeriksaan_selesai_rujukan | formatCurrency}}
              </h2>
              <small>Orang</small>
            </div>
          </div>

          <div class="col-md-3 col-md-offset-1 mb-3 mb-md-0">
            <div class="text-center">
              <h5 class="font-weight-bold text-blue">Data Belum Diinput</h5>
              <h2 class="font-weight-bold">
                {{ data.belum_input_rujukan | formatCurrency}}
              </h2>
              <small>Orang</small>
            </div>
          </div>

        </div>
      </Ibox>
    </div>
  </div>
</template>

<script>
  export default {
    name: "data-registrasi",
    data() {
      return {
        data: {
          mandiri: 0,
          rujukan: 0,
          total: 0,
          jumlah_perhari_mandiri: 0,
          jumlah_perhari_rujukan: 0,
          data_belum_lengkap_mandiri: 0,
          data_belum_lengkap_rujukan: 0,
          pemeriksaan_selesai_mandiri: 0,
          pemeriksaan_selesai_rujukan: 0,
          belum_input_rujukan: 0,
        }
      }
    },
    methods: {
      async loadData() {
        this.loading = true;
        try {
          let resp = await this.$axios.get("/v2/dashboard/registrasi");
          this.data = resp.data.result || {};
        } catch (e) {
          console.log(e)
        }
        this.loading = false;
      }
    },
    created() {
      this.loadData();
    }
  }
</script>

<style scoped>
  .tracking-row>div {
    position: relative;
  }

  @media (min-width: 768px) {
    .tracking-row>div:not(:last-child)>div:after {
      position: absolute;
      width: 1px;
      height: 100%;
      top: 0;
      right: 0;
      background: #E0E0E0;
      content: "";
    }
  }

  @media (max-width: 767px) {
    .tracking-row>div:nth-child(2n+1)>div:after {
      position: absolute;
      width: 1px;
      height: 100%;
      top: 0;
      right: 0;
      background: #E0E0E0;
      content: "";
    }
  }
</style>