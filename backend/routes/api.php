<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

Route::get('/', 'HomeController');

Route::middleware('guest:api')->group(function () {
    Route::post('login', 'Auth\LoginController@login');
});

Route::middleware('auth:api')->group(function () {
    Route::post('logout', 'Auth\LoginController@logout');
    Route::get('user', 'Settings\ProfileController@index');
    Route::patch('settings/profile', 'Settings\ProfileController@update');
    Route::patch('settings/password', 'Settings\PasswordController@update');
    Route::apiResource('pengguna', 'PenggunaController');
    Route::apiResource('dinkes', 'FasyankesController');
    Route::apiResource('kota', 'KotaController');
    Route::get('roles-option', 'OptionController@getRoles');
    Route::get('lab-pcr-option', 'OptionController@getLabPCR');
    Route::get('jenis-sampel-option', 'OptionController@getJenisSampel');
    Route::get('jenis-vtm', 'OptionController@getJenisVTM');
    Route::get('validator-option', 'OptionController@getValidator');
    Route::get('negara-option', 'OptionController@getNegara');
    Route::get('/provinsi', 'OptionController@listProvinsi');
    Route::get('/kecamatan', 'OptionController@listKecamatan');
    Route::get('/kelurahan', 'OptionController@listKelurahan');
});

Route::middleware('auth:api')->prefix('sample')->group(function () {
    Route::get('get-data', 'SampleController@getData');
    Route::post('add', 'SampleController@store');
    Route::get('edit/{nomor_sampel}', 'SampleController@show');
    Route::delete('delete/{sampel}', 'SampleController@destroy');
    Route::post('update/{sampel}', 'SampleController@update');
    Route::get('get-sample/{nomor_sampel}', 'SampleController@getSampel');
});

Route::middleware('auth:api')->prefix('registrasi-mandiri')->group(function () {
    Route::get('/', 'RegistrasiMandiri@getData');
    Route::get('export-excel', 'RegistrasiMandiri@exportMandiri')->middleware('can:isAdmin');
});

Route::middleware('auth:api')->prefix('registrasi-rujukan')->group(function () {
    Route::post('cek', 'RegistrasiRujukanController@cekData');
    Route::post('store', 'RegistrasiRujukanController@store');
    Route::get('export-excel-rujukan', 'RegistrasiMandiri@exportRujukan')->middleware('can:isAdmin');
    Route::delete('delete/{register}/{pasien}', 'RegistrasiRujukanController@destroy');
    Route::post('update/{register}/{pasien}', 'RegistrasiRujukanController@update');
    Route::get('update/{register}/{pasien}', 'RegistrasiRujukanController@show');
});

Route::middleware('auth:api')->namespace('V1')->prefix('v1/dashboard')->group(function () {
    Route::get('/notifications', 'NotificationController');
});

Route::middleware('auth:api')->namespace('V1')->prefix('v1/ekstraksi')->group(function () {
    Route::get('detail/{sampel}', 'EkstraksiController@detail');
    Route::post('edit/{sampel}', 'EkstraksiController@edit');
    Route::post('set-invalid/{sampel}', 'EkstraksiController@setInvalid');
    Route::post('set-proses/{sampel}', 'EkstraksiController@setProses');
    Route::post('terima', 'EkstraksiController@terima');
    Route::post('kirim', 'EkstraksiController@kirim');
    Route::post('kirim-ulang', 'EkstraksiController@kirimUlang');
    Route::post('musnahkan/{sampel}', 'EkstraksiController@musnahkan');
    Route::post('set-kurang/{sampel}', 'EkstraksiController@setKurang');
    Route::post('set-swab-ulang/{sampel}', 'EkstraksiController@setSwabUlang');
});

Route::middleware('auth:api')->namespace('V1')->prefix('v1/pcr')->group(function () {
    Route::get('detail/{id}', 'PCRController@detail');
    Route::post('edit/{id}', 'PCRController@edit');
    Route::post('terima', 'PCRController@terima');
    Route::post('invalid/{id}', 'PCRController@invalid');
    Route::post('input/{id}', 'PCRController@input');
    Route::post('musnahkan/{id}', 'PCRController@musnahkan');
    Route::post('import-hasil-pemeriksaan', 'ImportPemeriksaanSampelController@importHasilPemeriksaan');
    Route::post('import-data-hasil-pemeriksaan', 'ImportPemeriksaanSampelController@importDataHasilPemeriksaan');
});

Route::middleware('auth:api')->namespace('V1')->prefix('v1/sampel')->group(function () {
    Route::get('/cek-nomor-sampel', 'CekNomorSampelController');
});

Route::middleware('auth:api')->namespace('V1')->prefix('v1')->group(function () {
    Route::get('list-kota-jabar', 'KotaController@listKota');
    Route::get('list-kota-all', 'KotaController@listKotaAll');
    Route::get('kota/detail/{kota}', 'KotaController@show');
    Route::get('list-fasyankes-jabar', 'FasyankesController@listByProvinsi');
    Route::get('fasyankes/detail/{fasyankes}', 'FasyankesController@show');
    Route::apiResource('swab-antigen', 'SwabAntigenController');
    Route::get('download', 'FileDownloadController');
});

Route::middleware('auth:api')->namespace('V1')->prefix('v1/register')->group(function () {
    Route::post('mandiri', 'RegisterController@storeMandiri');
    Route::post('mandiri/update/{register}/{pasien}', 'RegisterController@storeUpdate');
    Route::get('mandiri/{register_id}/{pasien_id}', 'RegisterController@getById');
    Route::get('logs/{register_id}', 'RegisterController@logs');
    Route::delete('mandiri/{register}/{pasien}', 'RegisterController@delete');
    Route::get('noreg', 'RegisterController@requestNomor');

    Route::post('import-mandiri', 'ImportRegisterController@importRegisterMandiri');
    Route::post('import-rujukan', 'ImportRegisterController@importRegisterRujukan');
    Route::post('import-rujukan-tes-masif', 'ImportRegisterController@importRegisterRujukanTesMasif');
    Route::post('import-data-rujukan', 'ImportRegisterController@importDataRegisterRujukan');
    Route::post('import-data-tes-masif', 'ImportRegisterController@importDataRegisterRujukan');
});

Route::middleware('auth:api')->middleware('can:isAdminVerifikator')->namespace('V1')->prefix('v1/verifikasi')->group(function () {
    Route::post('mandiri', 'RegisterController@storeMandiri');
    Route::post('mandiri/update/{register}/{pasien}', 'RegisterController@storeUpdate');
    Route::get('mandiri/{register_id}/{pasien_id}', 'RegisterController@getById');
    Route::get('logs/{register_id}', 'RegisterController@logs');
    Route::delete('mandiri/{register}/{pasien}', 'RegisterController@delete');
    Route::get('noreg', 'RegisterController@requestNomor');

    Route::post('import-mandiri', 'ImportRegisterController@importRegisterMandiri');
    Route::post('import-rujukan', 'ImportRegisterController@importRegisterRujukan');
    Route::post('import-rujukan-tes-masif', 'ImportRegisterController@importRegisterRujukanTesMasif');
    Route::post('import-data-rujukan', 'ImportRegisterController@importDataRegisterRujukan');
    Route::post('import-data-tes-masif', 'ImportRegisterController@importDataRegisterRujukan');
});

Route::middleware('auth:api')->namespace('V1')->prefix('v1/verifikasi')->group(function () {
    Route::get('list-kategori', 'VerifikasiController@listKategori');
});

Route::middleware('auth:api')->middleware('can:isAdminValidator')->namespace('V1')->prefix('v1/validasi')->group(function ()
{
    Route::get('/', 'ValidasiController@index');
    Route::get('/export', 'ValidasiController@export');
    Route::get('list-validator', 'ValidasiController@getValidator');
    Route::post('bulk-validasi', 'ValidasiController@bulkValidate');
    Route::post('edit-status-sampel/{sampel}', 'ValidasiController@updateToValidate');
    Route::get('detail/{sampel}', 'ValidasiController@show');
    Route::post('reject-sample/{sampel}', 'ValidasiController@rejectSample');
    Route::get('export-pdf/{sampel}', 'ExportPDFController@downloadPdfHasil');
    Route::post('regenerate-pdf/{sampel}', 'ExportPDFController@regeneratePdfHasil');
});

Route::middleware('auth:api')->namespace('V1')->prefix('v1/pelacakan-sampel')->group(function () {
    Route::get('list', 'PelacakanSampelController@index');
    Route::get('detail/{sampel}', 'PelacakanSampelController@show');
});

Route::middleware('auth:api')->namespace('V1')->prefix('v1/pelaporan')->group(function () {
    Route::get('data', 'PelaporanController@fetchData');
});

Route::middleware('auth:api')->namespace('V1')->prefix('v1/list')->group(function () {
    Route::get('reagen-ekstraksi', 'ReagenEkstraksiListController');
    Route::get('reagen-pcr', 'ReagenPCRListController');
});

Route::middleware('auth:api')->middleware('can:isAdmin')->namespace('V1')->prefix('v1')->group(function () {
    Route::apiResource('jenis-vtm', 'JenisVTMController');
    Route::apiResource('reagen-ekstraksi', 'ReagenEkstraksiController');
    Route::apiResource('reagen-pcr', 'ReagenPCRController');
});

Route::middleware('auth:api')->namespace('V1')->prefix('v1/tes-masif')->group(function () {
    Route::get('/', 'TesMasifController@index');
    Route::post('/registering', 'TesMasifController@bulk');
});

Route::middleware('auth:api')->namespace('V1')->prefix('v1/swab-antigen')->group(function () {
    Route::get('/nomor-registrasi', 'SwabAntigenController@getNomorRegistrasi');
    Route::post('/bulk', 'SwabAntigenController@bulkValidasi');
    Route::post('/import', 'SwabAntigenController@import');
    Route::get('/export-excel', 'SwabAntigenController@export');
    Route::get('/export-pdf/{swab_antigen}', 'ExportPDFController@downloadPdfSwabAntigen');
    Route::post('/regenerate-pdf/{swab_antigen}', 'ExportPDFController@regeneratePdfSwabAntigen');
});

Route::middleware('auth:api')->namespace('V2')->prefix('v2/ekstraksi')->group(function () {
    Route::get('/get-data', 'EkstraksiController@getData');
});

Route::middleware('auth:api')->namespace('V2')->prefix('v2/pcr')->group(function () {
    Route::get('/get-data', 'PCRController@index');
});

Route::middleware('auth:api')->namespace('V2')->prefix('v2/pelacakan-sampel')->group(function () {
    Route::get('list', 'PelacakanSampelController@index');
});

Route::middleware('auth:api')->namespace('V2')->prefix('v2/dashboard')->group(function () {
     Route::get('/tracking-progress', 'DashboardController@trackingProgress');
     Route::get('/positif-negatif', 'DashboardController@positifNegatif');
     Route::get('/pasien-diperiksa', 'DashboardController@pasienDiperiksa');
     Route::get('/registrasi', 'DashboardController@registrasi');
     Route::get('/admin-sampel', 'DashboardController@adminSampel');
     Route::get('/ekstraksi', 'DashboardController@ekstraksi');
     Route::get('/pcr', 'DashboardController@pcr');
     Route::get('/verifikasi', 'DashboardController@verifikasi');
     Route::get('/validasi', 'DashboardController@validasi');
});

Route::middleware('auth:api')->namespace('V2')->prefix('v2/dashboard/list')->group(function () {
    Route::get('/sampel-perdomisili', 'SampelPerDomisili');
});

Route::middleware('auth:api')->namespace('V2')->prefix('v2/chart')->group(function () {
    Route::get('/regis', 'DashboardChartController@registrasi');
    Route::get('/sampel', 'DashboardChartController@sampel');
    Route::get('/ekstraksi', 'DashboardChartController@ekstraksi');
    Route::get('/pcr', 'DashboardChartController@pcr');
    Route::get('/positif-negatif', 'DashboardChartController@positifNegatif');
});

Route::prefix('v1/tes-masif')->namespace('V1')->group(function () {
    Route::post('/bulk', 'StoreTesMasifController');
});
