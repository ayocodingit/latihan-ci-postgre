<template>
  <div class="row">
    <div class="col-lg-12">
      <Ibox title="Pasien Diperiksa">
        <div class="row tracking-row">
          <div class="col-split-5 col-md-offset-1 mb-3 mb-md-0">
            <div class="text-center">
              <h5 class="font-weight-bold text-blue">Kontak Erat</h5>
              <h2 v-if="!loading" class="font-weight-bold">
                {{ data.status.kontak_erat | formatCurrency}}
              </h2>
              <img alt="loading" v-if="loading" src="~/assets/css/plugins/blueimp/img/loading.gif" width="36" height="36" />
              <small v-if="!loading">Orang</small>
            </div>
          </div>

          <div class="col-split-5 mb-3 mb-md-0">
            <div class="text-center">
              <h5 class="font-weight-bold text-blue">Suspek</h5>
              <h2 v-if="!loading" class="font-weight-bold">
                {{ data.status.suspek | formatCurrency}}</h2>
              <img alt="loading" v-if="loading" src="~/assets/css/plugins/blueimp/img/loading.gif" width="36" height="36" />
              <small v-if="!loading">Orang</small>
            </div>
          </div>

          <div class="col-split-5 mb-3 mb-md-0">
            <div class="text-center">
              <h5 class="font-weight-bold text-blue">Probable</h5>
              <h2 v-if="!loading" class="font-weight-bold">
                {{ data.status.probable | formatCurrency}}</h2>
              <img alt="loading" v-if="loading" src="~/assets/css/plugins/blueimp/img/loading.gif" width="36" height="36" />
              <small v-if="!loading">Orang</small>
            </div>
          </div>

          <div class="col-split-5 mb-3 mb-md-0">
            <div class="text-center">
              <h5 class="font-weight-bold text-blue">Konfirmasi</h5>
              <h2 v-if="!loading" class="font-weight-bold">
                {{ data.status.konfirmasi | formatCurrency}}</h2>
              <img alt="loading" v-if="loading" src="~/assets/css/plugins/blueimp/img/loading.gif" width="36" height="36" />
              <small v-if="!loading">Orang</small>
            </div>
          </div>

          <div class="col-split-5 mb-3 mb-md-0">
            <div class="text-center">
              <h5 class="font-weight-bold text-blue">Tanpa Kriteria</h5>
              <h2 v-if="!loading" class="font-weight-bold">
                {{ data.status.tanpa_kriteria | formatCurrency}}
              </h2>
              <img alt="loading" v-if="loading" src="~/assets/css/plugins/blueimp/img/loading.gif" width="36" height="36" />
              <small v-if="!loading">Orang</small>
            </div>
          </div>

        </div>
      </Ibox>
    </div>
  </div>
</template>

<script>
  export default {
    name: "pasien-diperiksa",
    data() {
      return {
        loading: true,
        data: {
          status: {},
        }
      };
    },
    methods: {
      async loadData() {
        this.loading = true;
        try {
          let resp = await this.$axios.get("/v2/dashboard/pasien-diperiksa");
          const {
            result
          } = resp.data || {};
          this.data.status = result;
        } catch (e) {
          this.data.status.kontak_erat = "-";
          this.data.status.suspek = "-";
          this.data.status.probable = "-";
          this.data.status.konfirmasi = "-";
          this.data.status.tanpa_kriteria = "-";
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