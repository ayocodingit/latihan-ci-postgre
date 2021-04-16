import Vue from 'vue'
import Router from 'vue-router'
import { scrollBehavior } from '~/utils'

Vue.use(Router)

const page = path => () => import(`~/pages/${path}`).then(m => m.default || m)

const routes = [
  { path: '/', redirect:'home' },

  { path: '/login', name: 'login', component: page('auth/login.vue') },
  { path: '/home', name: 'home', component: page('home.vue') },
  { path: '/error-role', name: 'error-role', component: page('error-role.vue'), meta: {parentName: 'home'} },
  { path: '/sample', name: 'sample.index', component: page('sample/index.vue'), meta: {parentName: 'AdminSampel'} },
  { path: '/sample/daftar', name: 'sample.daftar', component: page('sample/daftar-sample.vue'), meta: {parentName: 'AdminSampel'} },
  { path: '/sample/add/:id?', name: 'sample.add', component: page('sample/add.vue'), meta: {parentName: 'AdminSampel'} },
  { path: '/sample/edit/:id', name: 'sample.edit', component: page('sample/edit.vue'), meta: {parentName: 'AdminSampel'} },
  { path: '/sample/get-samples/:id', name: 'sample.take', component: page('sample/edit.vue'), meta: {parentName: 'AdminSampel'} },
  { path: '/settings',
    component: page('settings/index.vue'),
    children: [
      { path: '', redirect: { name: 'settings.profile' } },
      { path: 'profile', name: 'settings.profile', component: page('settings/profile.vue'), meta: {parentName: 'home'} },
      { path: 'password', name: 'settings.password', component: page('settings/password.vue'), meta: {parentName: 'home'} }
    ]},

  // Data Master
  { path: '/pengguna', name: 'pengguna.index', component: page('pengguna/index.vue'), meta: {parentName: 'home'} },
  { path: '/pengguna/tambah', name:'pengguna.tambah', component:page('pengguna/tambah.vue'), meta: {parentName: 'pengguna.index'} },
  { path: '/pengguna/update/:id', name:'pengguna.update', component:page('pengguna/update.vue'),  meta: {parentName: 'pengguna.index'} },
  { path: '/pengguna/detail/:id', name:'pengguna.detail', component:page('pengguna/detail.vue'),  meta: {parentName: 'pengguna.index'} },

  { path: '/kota', name: 'kota.index', component: page('kota/index.vue'), meta: {parentName: 'home'} },
  { path: '/kota/tambah', name:'kota.tambah', component:page('kota/tambah.vue'), meta: {parentName: 'kota.index'} },
  { path: '/kota/update/:id', name:'kota.update', component:page('kota/update.vue'),  meta: {parentName: 'kota.index'} },
  { path: '/kota/detail/:id', name:'kota.detail', component:page('kota/detail.vue'),  meta: {parentName: 'kota.index'} },

  { path: '/dinkes', name: 'dinkes.index', component: page('dinkes/index.vue'), meta: {parentName: 'home'} },
  { path: '/dinkes/tambah', name:'dinkes.tambah', component:page('dinkes/tambah.vue'), meta: {parentName: 'dinkes.index'} },
  { path: '/dinkes/update/:id', name:'dinkes.update', component:page('dinkes/update.vue'),  meta: {parentName: 'dinkes.index'} },
  { path: '/dinkes/detail/:id', name:'dinkes.detail', component:page('dinkes/detail.vue'),  meta: {parentName: 'dinkes.index'} },

  { path: '/jenis-vtm', name: 'jenis-vtm.index', component: page('jenis-vtm/index.vue'), meta: {parentName: 'home'} },
  { path: '/jenis-vtm/tambah', name:'jenis-vtm.tambah', component:page('jenis-vtm/tambah.vue'), meta: {parentName: 'jenis-vtm.index'} },
  { path: '/jenis-vtm/update/:id', name:'jenis-vtm.update', component:page('jenis-vtm/update.vue'),  meta: {parentName: 'jenis-vtm.index'} },

  { path: '/reagen-ekstraksi', name: 'reagen-ekstraksi.index', component: page('reagen-ekstraksi/index.vue'), meta: {parentName: 'home'} },
  { path: '/reagen-ekstraksi/tambah', name:'reagen-ekstraksi.tambah', component:page('reagen-ekstraksi/tambah.vue'), meta: {parentName: 'reagen-ekstraksi.index'} },
  { path: '/reagen-ekstraksi/update/:id', name:'reagen-ekstraksi.update', component:page('reagen-ekstraksi/update.vue'),  meta: {parentName: 'reagen-ekstraksi.index'} },

  { path: '/reagen-pcr', name: 'reagen-pcr.index', component: page('reagen-pcr/index.vue'), meta: {parentName: 'home'} },
  { path: '/reagen-pcr/tambah', name:'reagen-pcr.tambah', component:page('reagen-pcr/tambah.vue'), meta: {parentName: 'reagen-pcr.index'} },
  { path: '/reagen-pcr/update/:id', name:'reagen-pcr.update', component:page('reagen-pcr/update.vue'),  meta: {parentName: 'reagen-pcr.index'} },

  // Registrasi
  { path: '/registrasi/mandiri', name: 'registrasi.mandiri', component: page('registrasi-mandiri/index.vue'), meta: {parentName: 'home'} },
  { path: '/registrasi/mandiri/tambah', name: 'registrasi.index.tambah', component: page('registrasi-mandiri/tambah.vue'), meta: {parentName: 'registrasi.mandiri'} },
  { path: '/registrasi/mandiri/detail/:register_id/:pasien_id', name: 'registrasi.index.detail', component: page('registrasi-mandiri/detail.vue')},
  { path: '/registrasi/mandiri/update/:register_id/:pasien_id', name:'registrasi.index.update', meta: {parentName: 'registrasi.mandiri'}, component: page('registrasi-mandiri/update.vue')},
  { path: '/registrasi/mandiri/export-excel', name:'registrasi.index.export-excel', component: page('registrasi-mandiri/export-excel.vue'), meta: {parentName: 'registrasi.mandiri'} },
  { path: '/registrasi/mandiri/import-excel', name:'registrasi.index.import-excel', component: page('registrasi-mandiri/import-excel.vue'), meta: {parentName: 'registrasi.mandiri'} },
  { path: '/registrasi/mandiri/daftar-pasien', name: 'registrasi.index.list-patient', component: page('registrasi-mandiri/list-patient.vue'), meta: {parentName: 'registrasi.mandiri'} },
  
  { path: '/registrasi/rujukan', name:'registrasi.rujukan', component: page('registrasi-rujukan/index.vue'), meta: {parentName: 'home'} },
  { path: '/registrasi/rujukan/add/:nomor_sampel', name:'registrasi.index-rujukan.tambah', component: page('registrasi-rujukan/add.vue'), meta: {parentName: 'home'} },
  { path: '/registrasi/rujukan/update/:register_id/:pasien_id', name:'registrasi.index-rujukan.update', component: page('registrasi-rujukan/update.vue'), meta: {parentName: 'home'} },
  { path: '/registrasi/rujukan/detail/:register_id/:pasien_id', name:'registrasi.index-rujukan.detail', component: page('registrasi-rujukan/detail.vue'), meta: {parentName: 'home'} },
  { path: '/registrasi/rujukan/import-excel', name:'registrasi.index-rujukan.import-excel', component: page('registrasi-rujukan/import-excel.vue'), meta: {parentName: 'registrasi.rujukan'} },
  { path: '/registrasi/rujukan/export-excel', name:'registrasi.index-rujukan.export-excel', component: page('registrasi-rujukan/export-excel.vue'), meta: {parentName: 'registrasi.mandiri'} },


  // Ekstraksi
  { path: '/ekstraksi', name:'ekstraksi.index', component: page('ekstraksi/index.vue'), meta: {parentName: 'home'} },
  { path: '/ekstraksi/detail/:id', name:'ekstraksi.detail', component: page('ekstraksi/detail.vue'), meta: {parentName: 'ekstraksi.index'} },
  { path: '/ekstraksi/edit/:id', name:'ekstraksi.edit', component: page('ekstraksi/edit.vue'), meta: {parentName: 'ekstraksi.index'} },
  { path: '/ekstraksi/dikembalikan', name:'ekstraksi.Re-Ekstraksi', component: page('ekstraksi/dikembalikan.vue'), meta: {parentName: 'ekstraksi.index'} },
  { path: '/ekstraksi/penerimaan', name:'ekstraksi.penerimaan', component: page('ekstraksi/penerimaan.vue'), meta: {parentName: 'ekstraksi.index'} },
  { path: '/ekstraksi/terima', name:'ekstraksi.terima', component: page('ekstraksi/terima.vue'), meta: {parentName: 'ekstraksi.penerimaan'} },
  { path: '/ekstraksi/terkirim', name:'ekstraksi.terkirim', component: page('ekstraksi/terkirim.vue'), meta: {parentName: 'ekstraksi.index'} },
  { path: '/ekstraksi/invalid', name:'ekstraksi.invalid', component: page('ekstraksi/invalid.vue'), meta: {parentName: 'ekstraksi.index'} },
  { path: '/ekstraksi/kirim', name:'ekstraksi.kirim', component: page('ekstraksi/kirim.vue'), meta: {parentName: 'ekstraksi.index'} },
  { path: '/ekstraksi/kirim-ulang', name:'ekstraksi.kirim-ulang', component: page('ekstraksi/kirim-ulang.vue'), meta: {parentName: 'ekstraksi.dikembalikan'} },

  // PCR
  { path: '/pcr/list-rna', name:'pcr.list-rna', component: page('pcr/list-rna.vue'), meta: {parentName: 'home'} },
  { path: '/pcr/list-pcr', name:'pcr.list-pcr', component: page('pcr/list-pcr.vue'), meta: {parentName: 'home'} },
  { path: '/pcr/list-hasil-pemeriksaan', name:'pcr.list-hasil-pemeriksaan', component: page('pcr/list-hasil-pemeriksaan.vue'), meta: {parentName: 'home'} },
  { path: '/pcr/list-hasil-inkonklusif', name:'pcr.list-hasil-inkonklusif', component: page('pcr/list-hasil-inkonklusif.vue'), meta: {parentName: 'home'} },
  { path: '/pcr/terima/:nomor_sampels?', name:'pcr.terima', component: page('pcr/terima.vue'), meta: {parentName: 'pcr.list-rna'} },
  { path: '/pcr/detail/:id', name:'pcr.detail', component: page('pcr/detail.vue'), meta: {parentName: 'pcr.list-pcr'} },
  { path: '/pcr/invalid/:id', name:'pcr.invalid', component: page('pcr/invalid.vue'), meta: {parentName: 'pcr.list-pcr'} },
  { path: '/pcr/input/:id', name:'pcr.input', component: page('pcr/input.vue'), meta: {parentName: 'pcr.list-pcr'} },
  { path: '/pcr/import-excel-hasil', name:'pcr.import-excel-hasil', component: page('pcr/import-excel-hasil.vue'), meta: {parentName: 'pcr.list-pcr'} },

  // Swab Antigen
  { path: '/swab-antigen/hasil-pemeriksaan', name:'swab.hasil-pemeriksaan', component: page('swab-antigen/HasilPemeriksaan.vue'), meta: {parentName: 'home'} },
  { path: '/swab-antigen/tambah', name: 'swab.tambah', component: page('swab-antigen/tambah.vue'), meta: {parentName: 'swab.hasil-pemeriksaan'} },
  { path: '/swab-antigen/edit/:id', name: 'swab.edit', component: page('swab-antigen/edit.vue'), meta: {parentName: 'swab.hasil-pemeriksaan'} },
  { path: '/swab-antigen/detail/:id', name: 'swab.detail', component: page('swab-antigen/detail.vue'), meta: {parentName: 'swab.hasil-pemeriksaan'} },
  { path: '/swab-antigen/validated', name:'swab.validated', component: page('swab-antigen/Validated.vue'), meta: {parentName: 'home'} },

  // Verifikasi
  { path: '/verifikasi/list-unverified', name:'verifikasi.index.unverified', component: page('verifikasi/list-unverified.vue'), meta: {parentName: 'home'} },
  { path: '/verifikasi/list-verified', name:'verifikasi.index.verified', component: page('verifikasi/list-verified.vue'), meta: {parentName: 'home'} },
  { path: '/verifikasi/detail/:id', name:'verifikasi.detail-unverified', component: page('verifikasi/detail.vue'), meta: {parentName: 'verifikasi.index.unverified'} },
  { path: '/verifikasi/detail-verified/:id', name:'verifikasi.detail-verified', component: page('verifikasi/detail.vue'), meta: {parentName: 'verifikasi.index.verified'} },
  { path: '/verifikasi/edit/:id', name:'verifikasi.edit', component: page('verifikasi/edit.vue'), meta: {parentName: 'verifikasi.index.unverified'} },
  { path: '/verifikasi/export-excel', name:'verifikasi.export-excel', component: page('verifikasi/export-excel.vue'), meta: {parentName: 'verifikasi.index.verified'} },

  // Validasi
  { path: '/validasi/list-unvalidate', name:'validasi.index.unvalidate', component: page('validasi/list-unvalidate.vue'), meta: {parentName: 'home'} },
  { path: '/validasi/list-validated', name:'validasi.index.validated', component: page('validasi/list-validated.vue'), meta: {parentName: 'home'} },
  { path: '/validasi/detail/:id', name:'validasi.detail-unverified', component: page('validasi/detail.vue'), meta: {parentName: 'validasi.index.unvalidate'} },
  { path: '/validasi/detail-validated/:id', name:'validasi.detail-verified', component: page('validasi/detail.vue'), meta: {parentName: 'validasi.index.validated'} },
  { path: '/validasi/edit/:id', name:'validasi.edit', component: page('validasi/edit.vue'), meta: {parentName: 'validasi.index.unvalidate'} },
  { path: '/validasi/export-excel', name:'validasi.export-excel', component: page('validasi/export-excel.vue'), meta: {parentName: 'validasi.index.validated'} },

  // Pelacakan Sampel
  { path: '/pelacakan-sampel', name:'pelacakan-sampel.index', component: page('pelacakan-sampel/index.vue'), meta: {parentName: 'home'} },
  { path: '/pelacakan-sampel/detail/:id', name:'pelacakan-sampel.detail', component: page('pelacakan-sampel/detail.vue'), meta: {parentName: 'pelacakan-sampel.index'} },
  
]

export function createRouter () {
  return new Router({
    routes,
    scrollBehavior,
    mode: 'history'
  })
}
