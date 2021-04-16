<template>
  <div class="row">
    <div class="col-md-6">
      <Ibox title="Positif">
        <div class="row">
          <div class="col-md-6">
            <div class="full-width-center">
              <img src="@/assets/img/positif.jpg" style="width:87px" alt="">
            </div>
          </div>
          <div class="col-md-6">
            <div class="full-width-center">
              <small class="font-weight-bold text-blue">Total</small>
              <h2 v-if="!loading" class="font-weight-bold">{{ data.positif | formatCurrency }}</h2>
              <img alt="loading" v-if="loading" src="~/assets/css/plugins/blueimp/img/loading.gif" width="36" height="36" />
              <small v-if="!loading">Orang</small>
            </div>
          </div>
        </div>
      </Ibox>
    </div>
    <div class="col-md-6">
      <Ibox title="Negatif">
        <div class="row">
          <div class="col-md-6">
            <div class="full-width-center">
              <img src="@/assets/img/negatif.jpg" style="width:87px" alt="">
            </div>
          </div>
          <div class="col-md-6">
            <div class="full-width-center">
              <small class="font-weight-bold text-blue">Total</small>
              <h2 v-if="!loading" class="font-weight-bold">{{ data.negatif | formatCurrency }}</h2>
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
    name: "data-positif-negatif",
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
          let resp = await this.$axios.get("/v2/dashboard/positif-negatif");
          this.data = resp.data.result || {};
        } catch (e) {
          this.data.positif = 0
          this.data.negatif = 0
        }
        this.loading = false;
      },
    },
    created() {
      this.loadData();
    }
  }
</script>