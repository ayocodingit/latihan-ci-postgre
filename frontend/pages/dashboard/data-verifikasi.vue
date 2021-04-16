<template>
  <div class="row">
    <div class="col-md-12">
      <div class="mb-3">
        <h2 class="font-weight-bold text-nowrap mr-3">
          <em class="uil-eye" />
          Verifikasi
        </h2>
      </div>
    </div>
    <div class="col-md-12">
      <Ibox title="Verifikasi">
        <div class="row tracking-row">

          <div class="col-md-6 col-md-offset-1 mb-3 mb-md-0">
            <div class="text-center">
              <h5 class="font-weight-bold text-blue">Belum Diverifikasi</h5>
              <h2 class="font-weight-bold">
                {{ data.belum_diverifikasi | formatCurrency}}
              </h2>
              <small>Orang</small>
            </div>
          </div>

          <div class="col-md-6 col-md-offset-1 mb-3 mb-md-0">
            <div class="text-center">
              <h5 class="font-weight-bold text-blue">Terverifikasi</h5>
              <h2 class="font-weight-bold">
                {{ data.terverifikasi | formatCurrency}}
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
    name: "data-verifikasi",
    data() {
      return {
        loading: true,
        data: {}
      };
    },
    methods: {
      async loadData() {
        this.loading = true;
        try {
          let resp = await this.$axios.get("/v2/dashboard/verifikasi");
          this.data = resp.data.result || {};
        } catch (e) {
          this.data.belum_diverifikasi = '-';
          this.data.terverifikasi = '-';
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