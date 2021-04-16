<template>
  <div class="row">
    <div class="col-md-12">
      <div class="mb-3">
        <h2 class="font-weight-bold text-nowrap mr-3">
          <em class="uil-flask-potion" />
          Admin Sampel
        </h2>
      </div>
    </div>
    <div class="col-md-12">
      <Ibox title="Admin Sampel">
        <div class="row tracking-row">

          <div class="col-md-4 col-md-offset-1 mb-3 mb-md-0">
            <div class="text-center">
              <h5 class="font-weight-bold text-blue">Sampel Masuk Hari Ini</h5>
              <h2 class="font-weight-bold">
                {{ data.jumlah_perhari_sampel | formatCurrency}}
              </h2>
              <small>Orang</small>
            </div>
          </div>

          <div class="col-md-4 col-md-offset-1 mb-3 mb-md-0">
            <div class="text-center">
              <h5 class="font-weight-bold text-blue">Sampel Dari Registrasi Mandiri</h5>
              <h2 class="font-weight-bold">
                {{ data.sampel_register_mandiri | formatCurrency}}
              </h2>
              <small>Orang</small>
            </div>
          </div>

          <div class="col-md-4 col-md-offset-1 mb-3 mb-md-0">
            <div class="text-center">
              <h5 class="font-weight-bold text-yellow">Total Sampel</h5>
              <h2 class="font-weight-bold">
                {{ data.total_sampel | formatCurrency}}
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
    name: "data-pengambilan-sampel",
    data() {
      return {
        loading: true,
        data: {
          jumlah_perhari_sampel: "",
          sampel_register_mandiri: "",
          total_sampel: ""
        }
      };
    },
    methods: {
      async loadData() {
        this.loading = true;
        try {
          let resp = await this.$axios.get("/v2/dashboard/admin-sampel");
          this.data = resp.data.result || {};
        } catch (e) {
          console.log(e);
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