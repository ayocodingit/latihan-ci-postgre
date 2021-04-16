<template>
  <tr>
    <td v-text="(pagination.page - 1) * pagination.perpage + 1 + index"></td>
    <td>{{item.nomor_register}}</td>
    <td>
      <p><strong>NIK Pasien : </strong>{{ item.nik }}</p>
      <p><strong>Nama Pasien : </strong>{{ item.nama_lengkap }}</p>
      <p><strong>Usia Pasien : </strong>{{ item.usia }}</p>
    </td>
    <td>
      {{item.nama_kota}}
    </td>
    <td>
      {{item.sumber_pasien}}
    </td>
    <td>
      <span class="badge badge-success mr-2" style="text-align:left;margin-bottom:10px" v-for="s in item.samples"
        :key="s"># {{s.nomor_sampel}} <br>
        Status : {{s.sampel_status}}
      </span>
    </td>
    <td>
      {{item.tgl_input}}
    </td>
    <td>
      <p class="badge badge-danger"
        v-if="(item.nik==null || item.nik=='') || (item.nama_lengkap==null || item.nama_lengkap=='')">data_belum_lengkap
      </p>
      <p class="badge badge-primary"
        v-if="(item.nik!=null && item.nik!='') && (item.nama_lengkap!=null && item.nama_lengkap!='')">data_lengkap</p>
    </td>
    <td v-if="config.has_action">
      <nuxt-link :to="`/registrasi/mandiri/detail/${item.register_id}/${item.pasien_id}`"
        class="mb-1 btn btn-success btn-sm">
        <em class="fa fa-eye" />
      </nuxt-link>
      <nuxt-link :to="`/registrasi/mandiri/update/${item.register_id}/${item.pasien_id}`"
        class="mb-1 btn btn-primary btn-sm">
        <em class="fa fa-edit" />
      </nuxt-link>
      <a href="#" class="mb-1 btn btn-danger btn-sm" @click="deleteData(item.register_id, item.pasien_id)">
        <em class="fa fa-trash" />
      </a>
    </td>
  </tr>
</template>

<script>
  export default {
    props: ['item', 'pagination', 'rowparams', 'index', 'config'],
    data() {
      return {}
    },
    methods: {
      async deleteData(id, pasien) {
        await this.$axios.delete(`v1/register/mandiri/${id}/${pasien}`)
          .then((response) => {
            this.$toast.success(response.data.message, {
              icon: 'check',
              iconPack: 'fontawesome',
              duration: 5000
            })

            this.$bus.$emit('refresh-ajaxtable', 'registrasi-mandiri')
          })
          .catch((error) => {
            this.$swal.fire(
              'Terjadi Kesalahan',
              'Gagal menghapus data, silakan hubungi admin',
              'error'
            );
          })
      },
      showDetail(item) {
        const payload = {
          id: item.nomor_register,
          data: item
        }
        this.$store.dispatch('register/saveData', payload);
        this.$router.push('/registrasi/mandiri/detail')
      }
    }
  }
</script>