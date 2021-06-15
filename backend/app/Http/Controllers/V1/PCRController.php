<?php

namespace App\Http\Controllers\V1;

use App\Enums\KesimpulanPemeriksaanEnum;
use App\Enums\LabPCREnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\EditPCRRequest;
use App\Http\Requests\InputPCRRequest;
use App\Http\Requests\InvalidPCRRequest;
use App\Http\Requests\TerimaPCRRequest;
use Illuminate\Http\Request;
use App\Models\Sampel;
use App\Models\PemeriksaanSampel;
use App\Models\LabPCR;
use Illuminate\Support\Arr;
use App\Traits\PemeriksaanTrait;
use App\Traits\SampelTrait;

class PCRController extends Controller
{
    use PemeriksaanTrait;
    use SampelTrait;

    public function detail(Request $request, $id)
    {
        $model = Sampel::with(['pcr', 'status', 'ekstraksi', 'pemeriksaanSampel'])->find($id);
        $model->log_pcr = $model->logs()
            ->whereIn('sampel_status', ['pcr_sample_received', 'pcr_sample_analyzed', 'extraction_sample_reextract'])
            ->orderByDesc('created_at')
            ->get();
        $model['re_pcr'] = Sampel::find($id)->logs->where('sampel_status', 'pcr_sample_received')->count() >= 2 ? 're-PCR' : null;

        return response()->json(['status' => 200, 'message' => 'success', 'data' => $model]);
    }

    public function edit(EditPCRRequest $request, Sampel $sampel)
    {
        $lab_pcr = LabPCR::find($request->lab_pcr_id);

        $pcr = PemeriksaanSampel::firstOrNew(['sampel_id' => $sampel->id]);
        $pcr->user_id = $pcr->user_id ?? $request->user()->id;
        $pcr->sampel_id = $sampel->id;
        $pcr->fill($request->validated() + [
            'petugas_penerima_sampel_rna' => $request->petugas_penerima_sampel
        ]);
        $pcr->save();

        $sampel->lab_pcr_id = $request->lab_pcr_id;
        $sampel->lab_pcr_nama = $lab_pcr->id == LabPCREnum::lainnya()->getIndex() ? $request->lab_pcr_nama : $lab_pcr->nama;
        if ($pcr->tanggal_mulai_ekstraksi && $pcr->jam_mulai_ekstraksi) {
            $sampel->waktu_pcr_sample_received = $pcr->tanggal_mulai_ekstraksi . ' ' .  $pcr->jam_mulai_ekstraksi;
        }

        if ($pcr->tanggal_pengiriman_rna && $pcr->jam_pengiriman_rna) {
            $sampel->waktu_extraction_sample_sent =  $pcr->tanggal_pengiriman_rna . ' ' .  $pcr->jam_pengiriman_rna;
        }
        $sampel->save();
        return response()->json(['message' => 'Perubahan berhasil disimpan']);
    }

    public function invalid(InvalidPCRRequest $request, Sampel $sampel)
    {
        $user = $request->user();
        $pemeriksaanSampel = PemeriksaanSampel::firstOrNew(['sampel_id' => $sampel->id]);
        $pemeriksaanSampel->user_id = $pemeriksaanSampel->user_id ?? $user->id;
        $pemeriksaanSampel->sampel_id = $sampel->id;
        $pemeriksaanSampel->catatan_pemeriksaan = $request->catatan_pemeriksaan;
        $pemeriksaanSampel->kesimpulan_pemeriksaan = null;
        $pemeriksaanSampel->save();

        $sampel->updateState('extraction_sample_reextract', [
            'user_id' => $user->id,
            'metadata' => $pemeriksaanSampel,
            'description' => 'Sampel perlu dilakukan re-ekstraksi',
        ]);

        return response()->json(['message' => 'Perubahan berhasil disimpan']);
    }

    public function input(InputPCRRequest $request, Sampel $sampel)
    {
        $user = $request->user();
        $pemeriksaanSampel = PemeriksaanSampel::firstOrNew(['sampel_id' => $sampel->id]);
        $pemeriksaanSampel->user_id = $pemeriksaanSampel->user_id ?? $user->id;
        $pemeriksaanSampel->sampel_id = $sampel->id;
        $pemeriksaanSampel->fill($request->validated());
        $pemeriksaanSampel->save();

        $logs = [
            'user_id' => $user->id,
            'metadata' => $pemeriksaanSampel,
            'description' => $this->getDescriptionAnalysisComplete($request->kesimpulan_pemeriksaan)
        ];

        if ($sampel->sampel_status == 'pcr_sample_received') {
            $sampel->updateState($this->getStatusSampelInput($request), $logs);
        } else {
            $sampel->addLog($logs);
            $sampel->waktu_pcr_sample_analyzed = date('Y-m-d H:i:s');
            $sampel->save();
        }

        return response()->json(['message' => 'Hasil analisa berhasil disimpan']);
    }

    public function getStatusSampelInput($request)
    {
        $sampel_status = 'pcr_sample_analyzed';

        if ($request->kesimpulan_pemeriksaan == 'inkonklusif') {
            $sampel_status = 'inkonklusif';
        }

        if ($request->kesimpulan_pemeriksaan == KesimpulanPemeriksaanEnum::invalid()) {
            $sampel_status = 'sample_invalid';
        }

        return $sampel_status;
    }

    /**
     * terima sampel PCR
     *
     * @param  mixed $request
     * @return void
     */
    public static function terima(TerimaPCRRequest $request)
    {
        $user = $request->user();
        $samples = Sampel::whereIn('nomor_sampel', Arr::pluck($request->samples, 'nomor_sampel'))->get();
        foreach ($samples as $sampel) {
            $pemeriksaanSampel = PemeriksaanSampel::firstOrNew(['sampel_id' => $sampel->id]);
            $pemeriksaanSampel->user_id = $pemeriksaanSampel->user_id ?? $user->id;
            $pemeriksaanSampel->sampel_id = $sampel->id;
            $pemeriksaanSampel->fill($request->validated());
            $pemeriksaanSampel->save();

            $sampel->waktu_pcr_sample_received = $pemeriksaanSampel->tanggal_mulai_ekstraksi . ' ' . $pemeriksaanSampel->jam_mulai_ekstraksi;
            $sampel->updateState('pcr_sample_received', [
                'user_id' => $user->id,
                'metadata' => $pemeriksaanSampel,
                'description' => 'RNA dianalisis oleh mesin PCR',
            ]);
        }
        return response()->json(['message' => 'Penerimaan sampel berhasil dicatat'], 200);
    }

    public function destroy(Request $request, Sampel $sampel)
    {
        $sampel->is_musnah_pcr = true;
        $sampel->save();

        $sampel->addLog([
            'user_id' => $request->user()->id,
            'metadata' => $sampel,
            'description' => 'Sampel ditandai sebagai dihancurkan di ruang PCR',
        ]);

        return response()->json(['message' => 'Sampel berhasil ditandai telah dimusnahkan']);
    }
}
