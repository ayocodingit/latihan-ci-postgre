<template>
  <div class="row">
    <div class="col-md-12">
      <div class="mb-3">
        <h2 class="font-weight-bold text-nowrap mr-3">
          <em class="uil-atom" />
          rRT-PCR
        </h2>
      </div>
    </div>

    <div class="col-md-12">
      <Ibox title="rRT-PCR">
        <div class="row tracking-row">

          <div class="col-md-3 col-md-offset-1 mb-3 mb-md-0">
            <div class="text-center">
              <h5 class="font-weight-bold text-blue">Sampel Baru</h5>
              <h2 class="font-weight-bold">
                {{ data.sampel_baru | formatCurrency}}
              </h2>
              <small>Pcs</small>
            </div>
          </div>

          <div class="col-md-3 col-md-offset-1 mb-3 mb-md-0">
            <div class="text-center">
              <h5 class="font-weight-bold text-blue">Sampel yang telah di PCR</h5>
              <h2 class="font-weight-bold">
                {{ data.hasil_pemeriksaan | formatCurrency}}
              </h2>
              <small>Pcs</small>
            </div>
          </div>

          <div class="col-md-3 col-md-offset-1 mb-3 mb-md-0">
            <div class="text-center">
              <h5 class="font-weight-bold text-blue">Sampel yang telah di PCR hari ini</h5>
              <h2 class="font-weight-bold">
                {{ data.jumlah_perhari_pcr | formatCurrency}}
              </h2>
              <small>Pcs</small>
            </div>
          </div>

          <div class="col-md-3 col-md-offset-1 mb-3 mb-md-0">
            <div class="text-center">
              <h5 class="font-weight-bold text-yellow">Re-PCR</h5>
              <h2 class="font-weight-bold">
                {{ data.re_pcr | formatCurrency}}
              </h2>
              <small>Pcs</small>
            </div>
          </div>

        </div>
      </Ibox>
    </div>
  </div>
</template>

<script>
  export default {
    name: "data-rrt-pcr",
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
          let resp = await this.$axios.get("/v2/dashboard/pcr");
          this.data = resp.data.result || {};
        } catch (e) {
          this.sampel_baru = "-";
          this.hasil_pemeriksaan = "-";
          this.jumlah_perhari_pcr = "-";
          this.re_pcr = "-";
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