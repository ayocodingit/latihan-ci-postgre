<template>
  <div class="row">
    <div class="col-md-12">
      <div class="mb-3">
        <h2 class="font-weight-bold text-nowrap mr-3">
          <em class="uil-eye" />
          Validasi
        </h2>
      </div>
    </div>
    <div class="col-md-12">
      <Ibox title="Validasi">
        <div class="row tracking-row">

          <div class="col-md-6 col-md-offset-1 mb-3 mb-md-0">
            <div class="text-center">
              <h5 class="font-weight-bold text-blue">Belum Divalidasi</h5>
              <h2 class="font-weight-bold">
                {{ data.belum_divalidasi | formatCurrency}}
              </h2>
              <small>Orang</small>
            </div>
          </div>

          <div class="col-md-6 col-md-offset-1 mb-3 mb-md-0">
            <div class="text-center">
              <h5 class="font-weight-bold text-blue">Tervalidasi</h5>
              <h2 class="font-weight-bold">
                {{ data.tervalidasi | formatCurrency}}
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
    name: "data-validasi",
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
          let resp = await this.$axios.get("/v2/dashboard/validasi");
          this.data = resp.data.result || {};
        } catch (e) {
          this.data.belum_divalidasi = '-';
          this.data.tervalidasi = '-';
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