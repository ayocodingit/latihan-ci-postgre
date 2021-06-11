<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ImportExcelRequest;
use App\Imports\PCRImport;
use App\Models\PemeriksaanSampel;
use App\Models\Sampel;
use App\Traits\SampelTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ImportPemeriksaanSampelController extends Controller
{
    use SampelTrait;

    public function importHasilPemeriksaan(ImportExcelRequest $request)
    {
        $import = new PCRImport();
        $import->import($request->file('register_file'));
        return response()->json($import->result);
    }

    public function importDataHasilPemeriksaan(Request $request)
    {
        DB::beginTransaction();
        try {
            foreach ($request->input('data') as $row) {
                $sampel = Sampel::find($row['sampel_id']);
                $pemeriksaanSampel = PemeriksaanSampel::firstOrNew(['sampel_id' => $sampel->id]);
                $pemeriksaanSampel->sampel_id = $sampel->id;
                $pemeriksaanSampel->user_id = $pemeriksaanSampel->user_id ?? $request->user();
                $pemeriksaanSampel->tanggal_input_hasil = $row['tanggal_input_hasil'];
                $pemeriksaanSampel->tanggal_mulai_pemeriksaan = $row['tanggal_input_hasil'];
                $pemeriksaanSampel->jam_input_hasil = '12:00';
                $pemeriksaanSampel->hasil_deteksi = $row['target_gen'];
                $pemeriksaanSampel->kesimpulan_pemeriksaan = $row['kesimpulan_pemeriksaan'];
                $pemeriksaanSampel->save();

                $description = $this->getDescriptionAnalysisComplete($row['kesimpulan_pemeriksaan']);
                $this->updateState($sampel, 'pcr_sample_analyzed', $pemeriksaanSampel, $description);
                $sampel->waktu_pcr_sample_analyzed = $pemeriksaanSampel->tanggal_mulai_pemeriksaan .' '. $pemeriksaanSampel->jam_input_hasil;
                $sampel->save();
            }
            DB::commit();
            return response()->json(['message' => 'Sukses import data.']);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }
}
