<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\BulkTesMasifRequest;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\TesMasif;
use App\Traits\TesMasifTrait;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class StoreTesMasifController extends Controller
{
    use TesMasifTrait;

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(BulkTesMasifRequest $request)
    {
        $dataTesMasif = $request->input('data');
        foreach ($dataTesMasif as $row) {
            try {
                if (!$this->isValidTesMasif($row)) {
                    continue;
                }
                $row['kecamatan_id'] = Kecamatan::where('nama', strtoupper($row['kecamatan_id']))->value('nama');
                $row['kelurahan_id'] = Kelurahan::where('nama', strtoupper($row['kelurahan_id']))->value('nama');
                $row['kewarganeraan'] = $row['kewarganegaraan'];
                TesMasif::create($row);
                $this->berhasil[] = $row['nomor_sampel'];
            } catch (\Throwable $th) {
                $this->setResultGagal($row['nomor_sampel'], 'errors', 'Internal Server Error');
                Log::alert($th->getMessage());
            }
        }
        return $this->getResponse(count($dataTesMasif));
    }

    private function getResponse($countDataTesMasif)
    {
        $statusCode = Response::HTTP_OK;
        $message = 'Berhasil';
        if (count($this->gagal) >= $countDataTesMasif) {
            $statusCode = Response::HTTP_UNPROCESSABLE_ENTITY;
            $message = 'Gagal';
        }
        return response()->json([
            'message' => "Tes Masif $message Ditambahkan",
            'result' => $this->getResult()
        ], $statusCode);
    }
}
