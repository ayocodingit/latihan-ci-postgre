<template>
  <div class="wrapper wrapper-content">
    <portal to="title-name">
      Edit Pasien Rujukan
    </portal>
    <portal to="title-action">
      <div class="title-action">
        <nuxt-link to="/registrasi/rujukan" class="btn btn-black">
          <em class="uil-arrow-left" /> Kembali
        </nuxt-link>
      </div>
    </portal>

    <div class="row">
      <div class="col-lg-12">
        <form @submit.prevent="submit" @keydown="form.onKeydown($event)">
          <Ibox title="Penerimaan atau Pengambilan Sampel">
            <div class="form-group row">
              <label class="col-md-3 col-lg-2 flex-text-center">
                Nomor Registrasi
                <span style="color:red">*</span>
              </label>
              <div class="col-md-8 col-lg-6" :class="{ 'is-invalid': form.errors.has('reg_no') }">
                <input class="form-control" type="text" name="reg_no" placeholder="Nomor Registrasi" required
                  v-model="form.reg_no" disabled />
                <has-error :form="form" field="reg_no" />
              </div>
            </div>
            <div class="form-group row">
              <label class="col-md-3 col-lg-2 flex-text-center">Kewarganegaraan </label>
              <div class="col-md-8 col-lg-6">
                <select v-model="form.reg_kewarganegaraan" class="form-control" name="reg_kewarganegaraan">
                  <option value="WNI">WNI</option>
                  <option value="WNA">WNA</option>
                </select>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-md-3 col-lg-2 flex-text-center">
                Kategori
              </label>
              <div class="col-md-8 col-lg-6">
                <div class="form-check form-check-inline">
                  <input class="form-check-input" v-model="form.reg_sumberpasien" value="Umum" type="radio">
                  <span>Umum</span>
                </div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" v-model="form.reg_sumberpasien" value="Other" type="radio"
                    v-on:click="otherCategoryHandler">
                  <span>Isian</span>
                </div>
              </div>
            </div>
            <div class="form-group row" v-if="form.reg_sumberpasien=='Other'">
              <label for="" class="col-md-3 col-lg-2 flex-text-center"></label>
              <div class="col-md-8 col-lg-6">
                <input type="text" name="reg_sumberpasien_isian" placeholder="Ketikkan Kategori" id=""
                  class="form-control" v-model="form.reg_sumberpasien_isian">
              </div>
            </div>
          </Ibox>

          <Ibox title="Identitas Pengirim">
            <div class="form-group row">
              <label class="col-md-3 col-lg-2 flex-text-center">Instansi Pengirim
                <span style="color:red">*</span>
              </label>
              <div class="col-md-8 col-lg-6" :class="{'is-invalid':form.errors.has('reg_fasyankes_pengirim')}">
                <input-option-instansi-pengirim :form="form" field="reg_fasyankes_pengirim" />
                <has-error :form="form" field="reg_fasyankes_pengirim" />
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-3 col-lg-2 flex-text-center">
                Rumah Sakit / Fasyankes
                <span style="color:red">*</span>
              </div>
              <div class="col-md-8 col-lg-6" :class="{'is-invalid':form.errors.has('reg_nama_rs')}">
                <vue-bootstrap-typeahead v-model="form.reg_nama_rs" ref="autocompleteinstansi"
                  placeholder="Nama Rumah Sakit / Fasyankes" :minMatchingChars="1" :data="instansiArray"
                  backgroundVariant="white" textVariant="dark" required />
                <has-error :form="form" field="reg_nama_rs" />
              </div>
            </div>
            <div class="form-group row">
              <label class="col-md-3 col-lg-2 flex-text-center">Dokter Penanggung Jawab</label>
              <div class="col-md-8 col-lg-6">
                <input :class="{ 'is-invalid':form.errors.has('reg_nama_dokter') }" class="form-control" type="text"
                  name="reg_nama_dokter" v-model="form.reg_nama_dokter" placeholder="Dokter Penanggung Jawab" />
                <has-error :form="form" field="reg_nama_dokter" />
              </div>
            </div>
            <div class="form-group row">
              <label class="col-md-3 col-lg-2 flex-text-center">No Telepon Fasyankes Pengirim
                (Dokter)</label>
              <div class="col-md-8 col-lg-6">
                <input :class="{ 'is-invalid':form.errors.has('reg_telp_fas_pengirim') }" class=" form-control"
                  type="number" v-model="form.reg_telp_fas_pengirim" name="reg_telp_fas_pengirim"
                  placeholder="Nomor Telepon Fasyankes Pengirim / Dokter yang merawat" />
              </div>
            </div>
          </Ibox>

          <Ibox title="Identitas Pasien">
            <div class="form-group row">
              <label class="col-lg-2 col-md-3 flex-text-center">
                {{ $t('label.pasien.status') }}
              </label>
              <div class="col-md-8 col-lg-6">
                <select v-model="form.status" :class="{ 'is-invalid':form.errors.has('status') }"
                  class=" form-control col-md-8 col-lg-6" name="status">
                  <option v-for="option in pasien_status_option" v-bind:key="option.value" :value="option.value">
                    {{option.text}}</option>
                </select>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-md-3 col-lg-2 flex-text-center">
                Nama Pasien
                <span style="color:red">*</span>
              </label>
              <div class="col-md-8 col-lg-6" :class="{ 'is-invalid': form.errors.has('reg_nama_pasien') }">
                <input class="form-control" type="text" name="reg_nama_pasien" placeholder="Nama Pasien"
                  v-model="form.reg_nama_pasien" required />
                <has-error :form="form" field="reg_nama_pasien" />
              </div>
            </div>
            <div class="form-group row">
              <label class="col-md-3 col-lg-2 flex-text-center">
                NIK
              </label>
              <div class="col-md-8 col-lg-6" :class="{ 'is-invalid': form.errors.has('reg_nik') }">
                <input class="form-control" type="text" name="reg_nik" placeholder="NIK" v-model="form.reg_nik"
                  maxlength="16" />
                <has-error :form="form" field="reg_nik" />
              </div>
            </div>
            <div class="form-group row">
              <label class="col-md-3 col-lg-2 flex-text-center">
                Tempat Lahir
              </label>
              <div class="col-md-8 col-lg-6" :class="{ 'is-invalid': form.errors.has('reg_tempatlahir') }">
                <input class="form-control" type="text" name="reg_tempatlahir" placeholder="Tempat Lahir"
                  v-model="form.reg_tempatlahir" />
                <has-error :form="form" field="reg_tempatlahir" />
              </div>
            </div>
            <div class="form-group row">
              <label class="col-md-3 col-lg-2 flex-text-center">
                Tanggal Lahir
              </label>
              <div class="col-md-8 col-lg-6" :class="{ 'is-invalid': form.errors.has('reg_tgllahir') }">
                <date-picker placeholder="Tanggal Lahir" format="dd MMMM yyyy" ref="tgl_lahir"
                  input-class="form-control" :monday-first="true" v-model="form.reg_tgllahir" />
                <has-error :form="form" field="reg_tgllahir" />
              </div>
            </div>
            <div class="form-group row">
              <label class="col-md-3 col-lg-2 flex-text-center">
                Usia
              </label>
              <div class="col-md-3 col-lg-2 flex-text-center"
                :class="{ 'is-invalid': form.errors.has('reg_usia_tahun') }">
                <input class="form-control" type="number" name="reg_usia_tahun" placeholder="Tahun"
                  v-model="form.reg_usia_tahun" min="0" step="1" max="999" />
                <has-error :form="form" field="reg_usia_tahun" />
              </div>
              <div class="col-md-3 col-lg-2 flex-text-center"
                :class="{ 'is-invalid': form.errors.has('reg_usia_bulan') }">
                <input class="form-control" type="number" name="reg_usia_bulan" placeholder="Bulan"
                  v-model="form.reg_usia_bulan" min="0" step="1" max="12" />
                <has-error :form="form" field="reg_usia_bulan" />
              </div>
            </div>
            <div class="form-group row">
              <label class="col-md-3 col-lg-2 flex-text-center">
                Jenis Kelamin
              </label>
              <div class="col-md-8 col-lg-6">
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="reg_jk" v-model="form.reg_jk" value="L" id="L">
                  <label class="form-check-label" for="L">Laki-laki</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="reg_jk" v-model="form.reg_jk" value="P" id="P">
                  <label class="form-check-label" for="P">Perempuan</label>
                </div>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-md-3 col-lg-2 flex-text-center">
                Suhu (°C)
              </label>
              <div class="col-md-8 col-lg-6">
                <masked-input class="form-control" v-model="form.reg_suhu " mask="11.1" placeholder="Suhu"
                  :class="{ 'is-invalid': form.errors.has('reg_suhu') }" />
                <has-error :form="form" field="reg_suhu" />
              </div>
            </div>
          </Ibox>

          <Ibox title="Alamat Pasien">
            <div class="form-group row">
              <label class="col-md-3 col-lg-2 flex-text-center">
                No. Telp / HP
              </label>
              <div class="col-md-8 col-lg-6" :class="{ 'is-invalid': form.errors.has('reg_nohp') }">
                <input class="form-control" type="number" name="reg_nohp" placeholder="Nomor Telepon / HP"
                  v-model="form.reg_nohp" />
                <has-error :form="form" field="reg_nohp" />
              </div>
            </div>
            <div class="form-group row">
              <label class="col-md-3 col-lg-2 flex-text-center">
                Alamat
                <span style="color:red">*</span>
              </label>
              <div class="col-md-8 col-lg-6" :class="{ 'is-invalid': form.errors.has('reg_alamat') }">
                <textarea class=" form-control" type="text" name="reg_alamat" required v-model="form.reg_alamat"
                  placeholder="Alamat" />
                <has-error :form="form" field="reg_alamat" />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-lg-2 flex-text-center">
                                RT / RW
                            </label>
                            <div class="input-group col-md-3 col-lg-2 flex-text-center"
                                :class="{ 'is-invalid':form.errors.has('reg_rt') }">
                                <input class="form-control" placeholder="RT" readonly="readonly" />
                                <input class="form-control" type="text" name="reg_rt" v-model="form.reg_rt"
                                    id="reg_rt"/>
                            </div>
                            <div class="input-group col-md-3 col-lg-2 flex-text-center"
                                :class="{ 'is-invalid':form.errors.has('reg_rw') }">
                                <input class="form-control" placeholder="RW" readonly="readonly" />
                                <input class="form-control" type="text" name="reg_domisilirw" v-model="form.reg_rw"
                                    id="reg_rw" />
                            </div>
                            <has-error :form="form" field="reg_rt" />
                            <has-error :form="form" field="reg_rw" />
                        </div>
                        <div class="form-group row mt-4">
                            <label class="col-md-3 col-lg-2">
                                Provinsi
                            </label>
                            <div class="col-md-8 col-lg-6" :class="{ 'is-invalid': form.errors.has('reg_provinsi_id') }">
                                <vue-bootstrap-typeahead
                                    ref="ref_provinsi"
                                    placeholder="Cari Provinsi"
                                    @hit="onSelectedProvinsi($event)"
                                    :serializer="s => s.nama"
                                    :minMatchingChars="1"
                                    :data="dataProvinsi"
                                    backgroundVariant="white"
                                    textVariant="dark"
                                    v-model="provinsi_text"
                                />
                                <has-error :form="form" field="reg_provinsi_id" />
                            </div>
                        </div>

                        <div class="form-group row mt-4">
                            <label class="col-md-3 col-lg-2">
                                Kota / Kabupaten
                                <span style="color:red">*</span>
                            </label>
                            <div class="col-md-8 col-lg-6" :class="{ 'is-invalid': form.errors.has('reg_kota_id') }">
                                <vue-bootstrap-typeahead
                                    ref="ref_kota"
                                    placeholder="Pilih Kota / Kabupaten"
                                    @hit="onSelectedKota($event)"
                                    :serializer="s => s.nama"
                                    :minMatchingChars="1"
                                    :data="dataKota"
                                    backgroundVariant="white"
                                    textVariant="dark"
                                    v-model="kota_text"
                                />
                                <has-error :form="form" field="reg_kota_id" />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-lg-2 flex-text-center">
                                Kecamatan
                            </label>
                            <div class="col-md-8 col-lg-6" :class="{ 'is-invalid': form.errors.has('reg_kecamatan_id') }">
                                <vue-bootstrap-typeahead
                                    ref="ref_kecamatan"
                                    placeholder="Pilih Kecamatan"
                                    @hit="onSelectedKecamatan($event)"
                                    :serializer="s => s.nama"
                                    :minMatchingChars="1"
                                    :data="dataKecamatan"
                                    backgroundVariant="white"
                                    textVariant="dark"
                                />
                                <has-error :form="form" field="reg_kecamatan_id" />
                            </div>
                        </div>

                        <div class="form-group row mt-4">
                            <label class="col-md-3 col-lg-2">
                                Kelurahan / Desa
                            </label>
                            <div class="col-md-8 col-lg-6" :class="{ 'is-invalid': form.errors.has('reg_kelurahan_id') }">
                                <vue-bootstrap-typeahead
                                    ref="ref_kelurahan"
                                    placeholder="Pilih Kelurahan"
                                    @hit="onSelectedKelurahan($event)"
                                    :serializer="s => s.nama"
                                    :minMatchingChars="1"
                                    :data="dataKelurahan"
                                    backgroundVariant="white"
                                    textVariant="dark"
                                />
                                <has-error :form="form" field="reg_kelurahan_id" />
                            </div>
                        </div>
                    </Ibox>

                    <Ibox title="Riwayat Kunjungan">
                        <div class="form-group row">
                            <div class="col-md-3 col-lg-2 flex-text-center">
                                Kunjungan Ke
                            </div>
                            <div class="col-md-8 col-lg-6"
                                :class="{ 'is-invalid': form.errors.has('reg_kunke') }">
                                <div class="form-check form-check-inline" v-for="index of 10" v-bind:key="index">
                                    <label>
                                        <input name="reg_kunke" :id="`reg_kunke-${index}`" :value="index"
                                            v-model="form.reg_kunke" type="radio">
                                        <span>Ke-{{index}}</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-3 col-lg-2 flex-text-center">
                                Tanggal Kunjungan
                            </div>
                            <div class="col-md-8 col-lg-6"
                                :class="{ 'is-invalid': form.errors.has('reg_tanggalkunjungan') }">
                                <date-picker placeholder="Pilih Tanggal Kunjungan" format="d MMMM yyyy"
                                    input-class="form-control" :monday-first="true"
                                    v-model="form.reg_tanggalkunjungan" />
                                <has-error :form="form" field="reg_tanggalkunjungan" />
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-3 col-lg-2 flex-text-center">
                                Rumah Sakit / Fasyankes
                            </div>
                            <div class="col-md-8 col-lg-6" :class="{ 'is-invalid':form.errors.has('reg_rsfasyankes') }">
                                <input class="form-control" type="text" name="reg_rsfasyankes"
                                    placeholder="Nama Rumah Sakit / Fasyankes" v-model="form.reg_rsfasyankes" />
                                <has-error :form="form" field="reg_rsfasyankes" />
                            </div>
                        </div>
                    </Ibox>

                    <Ibox title="Identitas Sampel">
                        <div class="form-group row" v-for="(item,idx) in form.samples" v-bind:key="idx">
                            <label class="col-md-3 col-lg-2 flex-text-center">
                                Nomor Sampel
                                <span style="color:red">*</span>
                            </label>
                            <div class="col-md-8 col-lg-6">
                                <input class="form-control" type="text" name="nomor_sampel" placeholder="Nomor Sampel"
                                    required v-model="item.nomor_sampel" disabled />
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-lg-2 flex-text-center">
                                Keterangan Lainnya
                            </label>
                            <div class="col-md-8 col-lg-6" :class="{ 'is-invalid': form.errors.has('reg_keterangan') }">
                                <textarea class="form-control" type="text" name="reg_keterangan"
                                placeholder="Keterangan Lainnya" v-model="form.reg_keterangan" rows="6" />
                                <has-error :form="form" field="reg_keterangan" />
                            </div>
                        </div>
                    </Ibox>

                    <div class="col-md-12 mb-4">
                        <button :loading="form.busy" class="btn btn-md btn-primary">
                            <em class="fa fa-check" /> Simpan Data Register
                        </button>
                        <nuxt-link to="/registrasi/rujukan" class="btn btn-clear">
                            <em class="fa fa-close" /> Batal
                        </nuxt-link>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from "axios";
    import Form from "vform";
    import {
        mapGetters
    } from "vuex";
    import { pasienStatus } from '~/assets/js/constant/enum';
    import VueBootstrapTypeahead from 'vue-bootstrap-typeahead';
    import MaskedInput from 'vue-masked-input';
    import { checkDataFalsy } from '~/utils';
    import { humanize } from '~/utils';
    import moment from 'moment';

    let _this = null;
    export default {
        middleware: "auth",
        components: {
            VueBootstrapTypeahead,
            MaskedInput
        },
        data() {
            return {
                pasien_status_option: pasienStatus,
                dataProvinsi: [],
                dataKecamatan: [],
                dataKota: [],
                dataKelurahan: [],
                dataPasien: [],
                search_pasien: null,
            }
        },
        computed: mapGetters({
            user: "auth/user",
            sampel: "register/data"
        }),
        async asyncData({
            route,
            store
        }) { 
            let resp = await axios.get(`/registrasi-rujukan/update/${route.params.register_id}/${route.params.pasien_id}`);
            let error = false;
            const data = resp.data.result;
            return {
                form: new Form({
                    samples: data.samples,
                    reg_fasyankes_pengirim:data.reg_fasyankes_pengirim,
                    reg_telp_fas_pengirim:data.reg_telp_fas_pengirim,
                    reg_nama_dokter:data.reg_nama_dokter,
                    reg_nama_rs : data.nama_rs,
                    reg_nama_rs_lainnya:data.reg_nama_rs_lainnya,
                    daerahlain: data.daerahlain,
                    reg_dinkes_pengirim: data.reg_dinkes_pengirim,
                    reg_tanggalkunjungan: data.reg_tanggalkunjungan,
                    reg_kunke: data.reg_kunke,
                    reg_rsfasyankes: data.reg_rsfasyankes,
                    reg_no: data.reg_no,
                    reg_kewarganegaraan: data.reg_kewarganegaraan,
                    reg_sumberpasien: data.reg_sumberpasien == 'Umum' ? 'Umum' : 'Other',
                    reg_sumberpasien_isian: data.reg_sumberpasien_isian,
                    reg_nama_pasien: data.reg_nama_pasien,
                    reg_nik: data.reg_nik,
                    reg_tempatlahir: data.reg_tempatlahir,
                    reg_tgllahir: data.reg_tgllahir,
                    reg_nohp: data.reg_nohp,
                    reg_provinsi_id: data.reg_provinsi_id,
                    reg_kota_id: data.reg_kota_id,
                    reg_kecamatan_id: data.reg_kecamatan_id,
                    reg_kelurahan_id: data.reg_kelurahan_id,
                    reg_kecamatan: data.reg_kecamatan,
                    reg_kelurahan: data.reg_kelurahan,
                    reg_alamat: data.reg_alamat,
                    reg_rt: data.reg_rt,
                    reg_rw: data.reg_rw,
                    reg_suhu: checkDataFalsy(data.reg_suhu),
                    reg_keterangan: data.reg_keterangan,
                    status: data.status,
                    reg_jk: data.reg_jk,
                    reg_usia_tahun:data.reg_usia_tahun,
                    reg_usia_bulan:data.reg_usia_bulan,
                    reg_fasyankes_id: data.reg_fasyankes_id,
                    reg_nama_rs_id: data.reg_fasyankes_id,
                    reg_kota: data.reg_kota,
                    reg_provinsi_id: data.reg_provinsi_id,
                    reg_kecamatan_id: data.reg_kecamatan_id,
                    reg_kelurahan_id: data.reg_kelurahan_id,
                    pelaporan_id: data.pelaporan_id,
                    pelaporan_id_case: data.pelaporan_id_case

                }),
                selected_reg: {},
                optFasyankes:[],
                dataProvinsi : [],
                dataKota : [],
                dataKecamatan : [],
                dataKelurahan : [],
                nik_tgl: null,
                instansiArray: [],
                provinsi_text: null,
                kota_text: null,
            };
        },
        methods: {
            humanize,
            async onSelectedPasien(item) {
                this.form.pelaporan_id = item.id
                this.form.pelaporan_id_case = item.id_case
                this.form.reg_kewarganegaraan = item.nationality
                this.form.reg_nama_pasien = item.name
                this.form.reg_nik = item.nik
                this.form.reg_nohp = item.phone_number
                this.form.reg_jk = item.gender
                this.form.reg_alamat = item.address_detail
                this.form.reg_tgllahir = item.birth_date
                this.form.reg_kecamatan_id = item.address_subdistrict_code.split(".").join("")
                this.form.reg_kota_id = item.address_district_code.split(".").join("")
                this.form.reg_provinsi_id = item.address_district_code.split(".")[0]
                this.form.reg_kelurahan_id = item.address_village_code.split(".").join("")
                this.form.status = item.status_code
                this.form.reg_rt = item.no_rt
                this.form.reg_rw = item.no_rw
                this.form.reg_usia_tahun = item.usia_tahun
                this.form.reg_usia_bulan = item.usia_bulan

                let provinsi = this.form.reg_provinsi_id && this.dataProvinsi ? this.dataProvinsi.find(el => el.id == this.form.reg_provinsi_id) : null
                this.$refs.ref_provinsi.inputValue = provinsi ? provinsi.nama : ''

                await this.getKota(this.form.reg_provinsi_id)
                let kota = await this.form.reg_kota_id && this.dataKota ? this.dataKota.find(el => el.id == this.form.reg_kota_id) : null
                this.$refs.ref_kota.inputValue = kota ? kota.nama : ''
                
                await this.getKecamatan(this.form.reg_kota_id)
                let kecamatan = await  this.form.reg_kecamatan_id && this.dataKecamatan ? this.dataKecamatan.find(el => el.id == this.form.reg_kecamatan_id) : null
                this.$refs.ref_kecamatan.inputValue = kecamatan ? kecamatan.nama : ''

                await this.getKelurahan(this.form.reg_kecamatan_id)
                let kelurahan =  await this.form.reg_kelurahan_id && this.dataKelurahan ? this.dataKelurahan.find(el => el.id == this.form.reg_kelurahan_id) : null
                this.$refs.ref_kelurahan.inputValue = kelurahan ? kelurahan.nama : ''
            },
            otherCategoryHandler(){
                this.form.reg_sumberpasien_isian = null;
            },
             onSelectedProvinsi(item) {
                this.form.reg_provinsi_id = item.id
                this.getKota(item.id)
            },
            onSelectedKota(item) {          
                this.form.reg_kota_id = item.id
                this.getKecamatan(item.id)
            },
            onSelectedKecamatan(item) {
                this.form.reg_kecamatan_id = item.id
                this.getKelurahan(item.id)
            },
            onSelectedKelurahan(item) {
                this.form.reg_kelurahan_id = item.id
            },
            async getProvinsi() {
                let resp = await this.$axios.get('/provinsi');
                this.dataProvinsi = resp.data;
                let item = this.form.reg_provinsi_id && this.dataProvinsi ? this.dataProvinsi.find(el => el.id === this.form.reg_provinsi_id ) : null
                this.$refs.ref_provinsi.inputValue = item ? item.nama : ''
            },
            async getKota(provinsi_id) {
                let resp = await this.$axios.get('/v1/list-kota-jabar', {params: {provinsi_id}});
                this.dataKota = resp.data;
                let item = this.form.reg_kota_id && this.dataKota ? this.dataKota.find(el => el.id === this.form.reg_kota_id ) : null
                this.$refs.ref_kota.inputValue = item ? item.nama : ''
            },
            async getKecamatan(kota_id) {
                let response = await this.$axios.get('/kecamatan', {params: {kota_id}})
                this.dataKecamatan = response.data
                let item = this.form.reg_kecamatan_id && this.dataKecamatan ? this.dataKecamatan.find(el => el.id === this.form.reg_kecamatan_id ) : null
                this.$refs.ref_kecamatan.inputValue = item ? item.nama : ''
            },
            async getKelurahan(kecamatan_id) {
                let response = await this.$axios.get('/kelurahan', {params: {kecamatan_id}})
                this.dataKelurahan = response.data
                let item = this.form.reg_kelurahan_id && this.dataKelurahan ? this.dataKelurahan.find(el => el.id === this.form.reg_kelurahan_id ) : null
                this.$refs.ref_kelurahan.inputValue = item ? item.nama : ''
            },
            async getFasyankes(tipe) {
                let resp = await axios.get(`/v1/list-fasyankes-jabar?tipe=${tipe}`)
                this.optFasyankes = resp.data;
                const dataArr = this.optFasyankes.map((list) => {
                    const { nama } = list;
                    if (nama) {
                        this.instansiArray.push(nama);
                    }
                });
                return this.instansiArray;
            },
            async changeFasyankes(tipe) {
                this.$refs.autocompleteinstansi.inputValue = '';
                this.instansiArray = [];
                const listFasyankes = await this.getFasyankes(tipe);
            },
            async defaultFasyankes(tipe) {
                const listFasyankes = await this.getFasyankes(tipe);
                const findInstansi = listFasyankes.find(element => element === this.form.reg_nama_rs);
                this.$refs.autocompleteinstansi.inputValue = findInstansi || '';
            },
            async submit() {
                const findInstansi = this.optFasyankes.find(element => element.nama === this.$refs.autocompleteinstansi.inputValue);
                try {
                    this.form.reg_fasyankes_id = findInstansi ? findInstansi.id : this.form.reg_nama_rs_id;
                    this.form.reg_nama_rs = findInstansi ? findInstansi.nama : this.form.reg_nama_rs;
                    const response = await this.form.post(`/registrasi-rujukan/update/${this.$route.params.register_id}/${this.$route.params.pasien_id}`);
                    this.$swal.fire("Berhasil Ubah Data", "Data Pasien Register Berhasil Diubah", "success");
                    this.$router.push('/registrasi/rujukan');
                } catch (err) {
                    if (err.response && err.response.data.code == 422) {
                        this.$nextTick(() => {
                            this.form.errors.set(err.response.data.error)
                        })
                        this.$toast.error('Mohon cek kembali formulir Anda', {
                            icon: 'times',
                            iconPack: 'fontawesome',
                            duration: 5000
                        })
                    } else {
                        this.$swal.fire("Terjadi kesalahan", "Silakan hubungi Admin", "error");
                    }
                    return;
                }
            },
            toggleDisable(enabled = false) {
              let ref_kecamatan = this.$refs.ref_kecamatan.$el.querySelector('.form-control')
              let ref_kelurahan = this.$refs.ref_kelurahan.$el.querySelector('.form-control')

              if (enabled) {
                ref_kecamatan.removeAttribute('disabled')
                ref_kelurahan.removeAttribute('disabled')
              } else {
                ref_kecamatan.setAttribute('disabled', '')
                ref_kelurahan.setAttribute('disabled', '')
              }
            }
        },
        head() {
            return {
                title: "Pengambilan / Penerimaan Sampel Baru"
            };
        },
        created() {
            _this = this;
            this.defaultFasyankes(this.form.reg_fasyankes_pengirim);
            this.getProvinsi();
            this.getKota(this.form.reg_provinsi_id);
            this.getKecamatan(this.form.reg_kota_id);
            this.getKelurahan(this.form.reg_kecamatan_id);
        },
        mounted() {
          let enabled = this.form.reg_provinsi_id && this.form.reg_kota_id ? true : false
          this.toggleDisable(enabled)
        },
        watch: {
             "form.reg_fasyankes_pengirim":function(newVal, oldVal){
                this.changeFasyankes(this.form.reg_fasyankes_pengirim)
            },
            "form.reg_nik": function (newVal, oldVal) {
                if (newVal && newVal.length >= 12) {
                    let dd = parseInt(newVal.substr(6,2))
                    if (dd >= 40) {
                        this.form.reg_jk = 'P'
                        dd -= 40
                    } else {
                        this.form.reg_jk = 'L'
                    }
                    let mm = parseInt(newVal.substr(8,2))
                    let yy = parseInt(newVal.substr(10,2))
                    if (yy <= 30) {
                        let str = '' + (2000+yy) +'-'+ ('' + mm).padStart(2,'0') +'-'+ ('' + dd).padStart(2,'0')
                        this.form.reg_tgllahir = str
                        this.nik_tgl = str
                    } else {
                        let str = '' + (1900+yy) +'-'+ ('' + mm).padStart(2,'0') +'-'+ ('' + dd).padStart(2,'0')
                        this.form.reg_tgllahir = str
                        this.nik_tgl = str
                    }
                    this.$nextTick(() => {
                        this.$refs.tgl_lahir.init();
                    })
                }
            },
            "form.reg_tgllahir": function(newVal, oldVal) {
                var birthday = new Date(this.form.reg_tgllahir);
                this.form.reg_usia_tahun = moment().diff(birthday, 'years');
                this.form.reg_usia_bulan = moment().diff(birthday, 'months') % 12;
            },
            "form.reg_rt":function(newVal, oldVal){
                document.getElementById('reg_rw').required = newVal ? true : false
            },
            "form.reg_rw":function(newVal, oldVal){
                document.getElementById('reg_rt').required = newVal ? true : false
            },
            "form.reg_sumberpasien": function(newVal, oldVal){
                this.form.reg_sumberpasien_isian = null
            },
            "search_pasien": function(newVal, oldVal) {
                this.getPasien(newVal);
            },
            "provinsi_text": function (newVal, oldVal) {
              if(!newVal) {
                this.form.reg_provinsi_id = null
              } 
            },
            "kota_text": function (newVal, oldVal) {
              if(!newVal) {
                this.form.reg_kota_id = null
              } 
            },
            "form.reg_provinsi_id": function(newVal, oldVal) {
              if(this.form.reg_kota_id && newVal) {
                this.toggleDisable(true)
              } else {
                this.toggleDisable()
              }
            },
            "form.reg_kota_id": function(newVal, oldVal) {
              if(this.form.reg_provinsi_id && newVal) {
                this.toggleDisable(true)
              } else {
                this.toggleDisable()
              }
            }
        }
    };
</script>