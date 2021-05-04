<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EkstraksiDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $statusSampel = ['extraction_sample_extracted', 'extraction_sample_sent', 'extraction_sample_reextract'];
        $logsEkstraksi = $this->logs()
                              ->whereIn('sampel_status', $statusSampel)
                              ->orderByDesc('created_at')
                              ->take(5)
                              ->get();
        $sampel = parent::toArray($request);
        return [
            'data' => $sampel + [
                'jenis_sampel' => $this->jenisSampel,
                'ekstraksi' => $this->ekstraksi,
                'status' => $this->status,
                'log_ekstraksi' => $logsEkstraksi,
                'pengambilan_sampel' => $this->pengambilanSampel,
            ],
        ];
    }
}
