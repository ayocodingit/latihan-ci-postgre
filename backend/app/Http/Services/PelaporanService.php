<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Http;

class PelaporanService
{
    public $response;

    protected $dataSource = [
        'data_source' => 'pelaporan',
        'mode' => 'bykeyword',
    ];

    public function __construct($item)
    {
        $url = config('services.pelaporan.url');
        $apiKey = config('services.pelaporan.api_key');

        $params = $this->dataSource + [
            'keyword' => $item['keyword'],
            'limit' => $item['limit'],
            'api_key' => $apiKey
        ];

        $this->response = Http::get($url, $params)->json();
    }
}
