<template>
  <div class="row">
    <div class="col-md-12">
      <div class="mb-3">
        <h2 class="font-weight-bold text-nowrap mr-3">
          <em class="uil-flask" />
          Ekstraksi
        </h2>
      </div>
    </div>

    <div class="col-md-9">
      <Ibox title="Ekstraksi">
        <div class="row tracking-row">

          <div class="col-md-3 col-md-offset-1 mb-3 mb-md-0">
            <div class="text-center">
              <h5 class="font-weight-bold text-blue">Total Sampel Baru</h5>
              <h2 class="font-weight-bold">
                {{ data.sampel_baru | formatCurrency}}
              </h2>
              <small>Pcs</small>
            </div>
          </div>

          <div class="col-md-3 col-md-offset-1 mb-3 mb-md-0">
            <div class="text-center">
              <h5 class="font-weight-bold text-blue">Sampel Sudah Di Ekstraksi</h5>
              <h2 class="font-weight-bold">
                {{ data.ekstraksi | formatCurrency}}
              </h2>
              <small>Pcs</small>
            </div>
          </div>

          <div class="col-md-3 col-md-offset-1 mb-3 mb-md-0">
            <div class="text-center">
              <h5 class="font-weight-bold text-blue">Sampel Sudah Dikirim</h5>
              <h2 class="font-weight-bold">
                {{ data.kirim | formatCurrency}}
              </h2>
              <small>Pcs</small>
            </div>
          </div>

          <div class="col-md-3 col-md-offset-1 mb-3 mb-md-0">
            <div class="text-center">
              <h5 class="font-weight-bold text-yellow">Total Sampel di Ekstraksi hari ini</h5>
              <h2 class="font-weight-bold">
                {{ data.jumlah_perhari_ektraksi | formatCurrency}}
              </h2>
              <small>Pcs</small>
            </div>
          </div>

        </div>
      </Ibox>
    </div>

    <div class="col-md-3">
      <Ibox title="Sampel Invalid">
        <div class="row tracking-row">
          <div class="col-md-12 col-md-offset-1 mb-3 mb-md-0">
            <div class="text-center">
              <h5 class="font-weight-bold text-red">Total Invalid</h5>
              <h2 class="font-weight-bold">
                {{ data.sampel_invalid | formatCurrency}}
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
          let resp = await this.$axios.get("/v2/dashboard/ekstraksi");
          this.data = resp.data.result || {};
        } catch (e) {
          this.data.sampel_baru = "-";
          this.data.ekstraksi = "-";
          this.data.kirim = "-";
          this.data.jumlah_perhari_ektraksi = "-";
          this.data.sampel_invalid = "-";
        }
        this.loading = false;
      }
    },
    created() {
      this.loadData();
    }
  }
</script>