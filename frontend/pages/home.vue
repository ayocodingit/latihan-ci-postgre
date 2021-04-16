<template>
  <div class="wrapper wrapper-content">
    <portal to="title-name">Dashboard</portal>

    <tracking />
    <data-positif-negatif />
    <pasien-diperiksa />
    <charts />
    <jumlah-sampel />

    <data-registrasi v-if="checkPermission('registrasi')" />
    <data-pengambilan-sampel v-if="checkPermission('sample')" />
    <data-ekstraksi v-if="checkPermission('ekstraksi')" />
    <data-rrt-pcr v-if="checkPermission('pcr')" />
    <data-verifikasi v-if="checkPermission('verifikasi')" />
    <data-validasi v-if="checkPermission('validasi')" />

  </div>
</template>

<script>
  import {
    mapGetters
  } from "vuex";
  import Tracking from './dashboard/tracking'
  import DataPositifNegatif from './dashboard/data-positif-negatif'
  import PasienDiperiksa from './dashboard/pasien-diperiksa'
  import Charts from './dashboard/charts'
  import Ekstraksi from './dashboard/ekstraksi'
  import Pcr from './dashboard/pcr'
  import JumlahSampel from './dashboard/jumlah-sampel-domisili'
  import DataRegistrasi from './dashboard/data-registrasi'
  import DataPengambilanSampel from './dashboard/data-pengambilan-sampel'
  import DataEkstraksi from './dashboard/data-ekstraksi'
  import DataRrtPcr from './dashboard/data-rrt-pcr'
  import DataVerifikasi from './dashboard/data-verifikasi'
  import DataValidasi from './dashboard/data-validasi'

  export default {
    middleware: "auth",
    components: {
      Ekstraksi,
      Pcr,
      Tracking,
      DataPositifNegatif,
      PasienDiperiksa,
      Charts,
      JumlahSampel,
      DataRegistrasi,
      DataPengambilanSampel,
      DataEkstraksi,
      DataRrtPcr,
      DataVerifikasi,
      DataValidasi
    },

    computed: mapGetters({
      user: "auth/user"
    }),

    data() {
      return {
        params: {
          nama: null
        }
      };
    },
    head() {
      return {
        title: this.$t("home")
      };
    },
    methods: {
      checkPermission(menu) {
        let allow_role_id
        switch (menu) {
          case 'registrasi':
            allow_role_id = [1, 6, 7, 2]
            break;
          case 'sample':
            allow_role_id = [1, 6, 7, 3]
            break;
          case 'ekstraksi':
            allow_role_id = [1, 6, 7, 4]
            break;
          case 'pcr':
            allow_role_id = [1, 6, 7, 5]
            break;
          case 'verifikasi':
            allow_role_id = [1, 6, 7]
            break;
          case 'validasi':
            allow_role_id = [1, 7]
            break;
          case 'master':
            allow_role_id = [1]
            break;
        }
        if (this.user && this.user.role_id) {
          return Array.isArray(allow_role_id) ? allow_role_id.includes(this.user.role_id) : []
        }
        return
      },
    }
  };
</script>