<?php

namespace App\Http\Controllers\V1;

use App\Enums\StatusPasienEnum;
use App\Enums\StatusPasienPelaporanEnum;
use App\Http\Controllers\Controller;
use App\Http\Services\PelaporanService;
use App\Models\Pasien;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\App;

class PelaporanController extends Controller
{
    /**
     * defaultDataByPasien
     *
     * @var array
     */
    protected $defaultDataByPasien = [
        'id' => null,
        'id_case' => null,
        'nationality_name' => null,
        'final_result' => null,
        'test_location_type' => null,
        'test_location' => null,
        'test_date' => null,
        'id_match' => null,
    ];

    /**
     * defaultDataByPelaporan
     *
     * @var array
     */
    protected $defaultDataByPelaporan = [
        'no_rt' => null,
        'no_rw' => null,
        'usia_bulan' => null,
        'usia_tahun' => null,
        'suhu' => null,
    ];

    /**
     * addressProvinceCode
     *
     * @var int
     */
    protected $addressProvinceCode = 32;

    /**
     * items
     *
     * @var array
     */
    protected $items = [];

    /**
     * limit
     *
     * @var int
     */
    protected $limit = 10;


    /**
     * fetchData
     *
     * @param  mixed $request
     * @return void
     */
    public function fetchData(Request $request)
    {
        $pelaporanService = App::makeWith(PelaporanService::class, [
            'keyword' => $request->input('search'),
            'limit' => $request->input('limit', $this->limit)
        ]);
        if ($pelaporanService->response['status_code'] == Response::HTTP_OK) {
            foreach ($pelaporanService->response['data']['content'] as $key => $item) {
                $this->mappingSearchByPelaporan($item, $key);
            }
        }

        $this->searchByPasien($request->input('search'), count($this->items));

        return response()->json($this->items, Response::HTTP_OK);
    }

    /**
     * mappingSearchByPelaporan
     *
     * @param  mixed $item
     * @param  mixed $key
     * @return void
     */
    protected function mappingSearchByPelaporan($item, $key)
    {
        $this->items[$key]['status_code'] = $this->getStatus($item['status']);
        $this->items[$key]['address_subdistrict_code'] = getConvertCodeDagri($item['address_subdistrict_code']);
        $this->items[$key]['address_district_code'] = getConvertCodeDagri($item['address_district_code']);
        $this->items[$key]['address_village_code'] = getConvertCodeDagri($item['address_village_code']);
        $this->items[$key]['address_province_code'] = $this->addressProvinceCode;
        $this->items[$key] += $item;
        $this->items[$key] += $this->defaultDataByPelaporan;
    }

    /**
     * searchByPasien
     *
     * @param  mixed $search
     * @param  mixed $key
     * @return void
     */
    protected function searchByPasien($search, $key)
    {
        $pasien = Pasien::where(function ($query) use ($search) {
                        $query->where('nama_lengkap', 'ilike', '%'. $search .'%')
                            ->orWhere('nik', $search)
                            ->orWhere('no_hp', $search);
                        })
                        ->distinct('nama_lengkap', 'nik')
                        ->orderByRaw('nama_lengkap desc, nik desc, updated_at desc')
                        ->limit($this->limit - $key)
                        ->get();

        foreach ($pasien as $item) {
            $this->mappingSearchByPasien($item, $key);
            $key++;
        }
    }

    /**
     * mappingSearchByPasien
     *
     * @param  mixed $item
     * @param  mixed $key
     * @return void
     */
    protected function mappingSearchByPasien($item, $key)
    {
        $this->items[$key]['nik'] = $item->nik;
        $this->items[$key]['name'] = $item->nama_lengkap;
        $this->items[$key]['birth_date'] = $item->tanggal_lahir;
        $this->items[$key]['age'] = $item->usia_tahun;
        $this->items[$key]['gender'] = $item->jenis_kelamin;
        $this->items[$key]['address_detail'] = $item->alamat_lengkap;
        $this->items[$key]['address_district_code'] = $item->kota_id;
        $this->items[$key]['address_district_name'] = optional($item->kota)->nama;
        $this->items[$key]['address_subdistrict_code'] = $item->kecamatan_id;
        $this->items[$key]['address_subdistrict_name'] = optional($item->kecamatan)->nama;
        $this->items[$key]['address_village_code'] = $item->kelurahan_id;
        $this->items[$key]['address_village_name'] = optional($item->kelurahan)->nama;
        $this->items[$key]['nationality'] = $item->kewarganegaraan;
        $this->items[$key]['status'] = $item->status;
        $this->items[$key]['status_code'] = $item->status;
        $this->items[$key]['phone_number'] = $item->no_hp;
        $this->items[$key]['no_rt'] = $item->no_rt;
        $this->items[$key]['no_rw'] = $item->no_rw;
        $this->items[$key]['usia_bulan'] = $item->usia_bulan;
        $this->items[$key]['usia_tahun'] = $item->usia_tahun;
        $this->items[$key]['suhu'] = $item->suhu;
        $this->items[$key] += $this->defaultDataByPasien;
    }

    /**
     * getStatus
     *
     * @param  mixed $status
     * @return void
     */
    public function getStatus($status)
    {
        $status = strtoupper($status);
        if (!in_array($status, ['SUSPECT', 'CLOSEDCONTACT', 'PROBABLE', 'CONFIRMATION'])) {
            return StatusPasienEnum::tanpa_kriteria()->getIndex();
        }

        return StatusPasienPelaporanEnum::make($status)->getIndex();
    }
}
