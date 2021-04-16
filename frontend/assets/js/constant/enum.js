export const KesimpulanPemeriksaan = Object.freeze({
  data: [{
    id: 'positif',
    name: 'POSITIF'
  }, {
    id: 'negatif',
    name: 'NEGATIF'
  }, {
    id: 'inkonklusif',
    name: 'INKONKLUSIF'
  }, {
    id: 'swab_ulang',
    name: 'SWAB ULANG'
  }]
});

export const oidHasChecked = ['ekstraksi-penerimaan', 'ekstraksi-dilakukan', 'pcr-penerimaan', 'validasi', 'pasien-tes-masif', 'verifikasi', 'swab-antigen-hasil']

export const metodeEkstraksi = ['Manual', 'Otomatis']

export const optionsKitEkstraksi = ['Geneaid', 'Qiagen', 'Invitrogen', 'Roche', 'Addbio'];

export const optionsAlatEkstraksi = ['Kingfisher', 'Genolution'];

export const pasienStatus = [{
  'value': 1,
  'text': 'Kontak Erat'
}, {
  'value': 2,
  'text': 'Suspek'
}, {
  'value': 3,
  'text': 'Probable'
}, {
  'value': 4,
  'text': 'Konfirmasi'
}, {
  'value': 5,
  'text': 'Tanpa Kriteria'
}];

export const optionsPenerimaSampel = ['Aul', 'Viana', 'Hanif', 'Silvi', 'Agil', 'Arlisya', 'Adit', 'Ariza', 'Fahmy', 'Figur', 'Firman'].sort();

export const kodeJawaBarat = {
  'value': 32,
  'text': 'JAWA BARAT'
};

export const defaultStatus = 5 //Tanpa Kriteria

export const optionInstansiPengirim = [{
  key: 'puskesmas',
  value: 'Puskesmas'
}, {
  key: 'rumah_sakit',
  value: 'Rumah Sakit'
}, {
  key: 'dinkes',
  value: 'Dinkes Provinsi'
}, {
  key: 'dinkes_kota',
  value: 'Dinkes Kabupaten / Kota'
}, {
  key: 'klinik',
  value: 'Klinik'
}, {
  key: 'lab',
  value: 'Laboratorium'
}]

export const optionKewarganegaraan = [{
  key: 'WNI',
  value: 'Warga Negara Indonesia'
}, {
  key: 'WNA',
  value: 'Warga Negara Asing'
}]

export const optionKategori = [{
  key: 'Umum',
  value: 'Umum'
}, {
  key: 'Lainnya',
  value: 'Lainnya'
}]

export const optionIdentitas = [
  'ktp',
  'paspor'
]

export const optionJenisKelamin = [{
  key: 'L',
  value: 'Laki-Laki'
}, {
  key: 'P',
  value: 'Perempuan'
}]

export const optionKondisiPasien = [
  'bergejala',
  'tidak_bergejala'
]

export const optionJenisAntigen = [
  'nasofaring',
  'nasal'
]

export const optionHasilSwabAntigen = [
  'positif',
  'negatif'
]

export const jenisGejala = ['demam', 'anosmia']

export const tujuanPemeriksaan = [
  {
    id: 0,
    value: 'default'
  },
  {
    id: 1,
    value: 'suspek'
  },
  {
    id: 2,
    value: 'kontak erat'
  },
  {
    id: 3,
    value: 'screening'
  },
  {
    id: 4,
    value: 'pelaku perjalanan'
  },
  {
    id: 5,
    value: 'alasan lain'
  }
]
