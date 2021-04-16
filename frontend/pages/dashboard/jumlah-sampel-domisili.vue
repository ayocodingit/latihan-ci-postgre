<template>
  <div class="row">
    <div class="col-md-12">
      <Ibox title="Jumlah Sampel per Domisili">
            <ajax-table url="v2/dashboard/list/sampel-perdomisili" :disableSort="[]" :oid="'jumlah_sampel_per_domisili'" :config="{
              autoload: true,
              has_number: true,
              has_entry_page: false,
              has_pagination: true,
              has_action: false,
              has_search_input: false,
              custom_header: '',
              default_sort: '',
              custom_empty_page: true,
              class: {
                table: [],
                wrapper: ['table-responsive'],
              }
            }" :rowtemplate="'tr-jumlah-sampel'" :columns="{
              domisili: 'Kabupaten / Kota',
              jumlah : 'Jumlah Sampel',
            }" />
      </Ibox>
    </div>
  </div>
</template>

<script>
  export default {
    name: "jumlah-sampel-doisli",
    data() {
      return {
        loading: true,
        unverifyCounter: 0,
        verifiedCounter: 0,
      };
    },
    methods: {
      async loadData() {
        this.loading = true;
        try {
          // let respUnverify = await this.$axios.get("/v1/dashboard/counter-belum-verifikasi");
          // this.unverifyCounter = respUnverify.data.data;

        } catch (e) {
          this.unverifyCounter = '-';
          this.verifiedCounter = '-';
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