<template>
  <div class="row">
    <div class="col-lg-12">
      <Ibox title="Tracking Progress">
        <div class="row tracking-row">

          <div class="col-md-2 mb-3 mb-md-0">
            <div class="text-center">
              <h5 class="font-weight-bold text-blue">Registrasi</h5>
              <h2 v-if="!loading" class="font-weight-bold">
                {{ data.registration | formatCurrency}}
              </h2>
              <img alt="loading" v-if="loading" src="~/assets/css/plugins/blueimp/img/loading.gif" width="36" height="36" />
              <small v-if="!loading">Orang</small>
            </div>
          </div>

          <div class="col-md-2 mb-3 mb-md-0">
            <div class="text-center">
              <h5 class="font-weight-bold text-blue">Sampel</h5>
              <h2 v-if="!loading" class="font-weight-bold">
                {{ data.sampel | formatCurrency}}</h2>
              <img alt="loading" v-if="loading" src="~/assets/css/plugins/blueimp/img/loading.gif" width="36" height="36" />
              <small v-if="!loading">Pcs</small>
            </div>
          </div>

          <div class="col-md-2 mb-3 mb-md-0">
            <div class="text-center">
              <h5 class="font-weight-bold text-blue">Ekstraksi</h5>
              <h2 v-if="!loading" class="font-weight-bold">
                {{ data.ekstraksi | formatCurrency}}</h2>
              <img alt="loading" v-if="loading" src="~/assets/css/plugins/blueimp/img/loading.gif" width="36" height="36" />
              <small v-if="!loading">Pcs</small>
            </div>
          </div>

          <div class="col-md-2 mb-3 mb-md-0">
            <div class="text-center">
              <h5 class="font-weight-bold text-blue">rRT-PCR</h5>
              <h2 v-if="!loading" class="font-weight-bold">
                {{ data.rtpcr | formatCurrency}}</h2>
              <img alt="loading" v-if="loading" src="~/assets/css/plugins/blueimp/img/loading.gif" width="36" height="36" />
              <small v-if="!loading">Pcs</small>
            </div>
          </div>

          <div class="col-md-2 mb-3 mb-md-0">
            <div class="text-center">
              <h5 class="font-weight-bold text-blue">Verifikasi</h5>
              <h2 v-if="!loading" class="font-weight-bold">
                {{ data.verifikasi | formatCurrency}}
              </h2>
              <img alt="loading" v-if="loading" src="~/assets/css/plugins/blueimp/img/loading.gif" width="36" height="36" />
              <small v-if="!loading">Sampel</small>
            </div>
          </div>

          <div class="col-md-2 mb-3 mb-md-0">
            <div class="text-center">
              <h5 class="font-weight-bold text-blue">Validasi</h5>
              <h2 v-if="!loading" class="font-weight-bold">
                {{ data.validasi | formatCurrency}}
              </h2>
              <img alt="loading" v-if="loading" src="~/assets/css/plugins/blueimp/img/loading.gif" width="36" height="36" />
              <small v-if="!loading">Sampel</small>
            </div>
          </div>

        </div>
      </Ibox>
    </div>
  </div>
</template>

<script>
  export default {
    name: "tracking",
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
          let resp = await this.$axios.get("/v2/dashboard/tracking-progress");
          this.data = resp.data.result || {};
        } catch (e) {
          this.data.registration = "-";
          this.data.sample = "-";
          this.data.ekstraksi = "-";
          this.data.rtpcr = "-";
          this.data.verifikasi = "-";
          this.data.validasi = "-";
        }
        this.loading = false;
      }
    },
    created() {
      this.loadData();
    }
  };
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