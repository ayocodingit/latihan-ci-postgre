<?php

namespace App\Http\Controllers\V1;

use App\Enums\JenisRegistrasiEnum;
use App\Enums\RoleEnum;
use App\Http\Controllers\Controller;
use App\Models\Sampel;
use App\Models\TesMasif;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public $result = [
        'data' => [],
        'count' => 0
    ];

    public function __invoke(Request $request)
    {
        $this->ekstraksiNotification();
        $this->pendaftaranNotification();
        return response()->json($this->result);
    }

    public function ekstraksiNotification()
    {
        $count = 0;
        if (in_array(Auth::user()->role_id, [RoleEnum::EKSTRAKSI()->getIndex(), RoleEnum::ADMIN()->getIndex()])) {
            $count = Sampel::where('sampel_status', 'extraction_sample_reextract')->count('id');
        }
        if ($count > 0) {
            $this->result['data'][] = [
                'link' => '/ekstraksi/dikembalikan',
                'message' => "Ada $count sampel yang dikembalikan",
                'icon' => 'uil-flask',
            ];
            $this->result['count'] += $count;
        }
    }

    public function pendaftaranNotification()
    {
        $count = 0;
        if (in_array(Auth::user()->role_id, [RoleEnum::REGISTER()->getIndex(), RoleEnum::ADMIN()->getIndex()])) {
            $count = TesMasif::where('jenis_registrasi', JenisRegistrasiEnum::mandiri())
                             ->where('available', true)
                             ->count('id');
        }
        if ($count > 0) {
            $this->result['data'][] = [
                'link' => '/registrasi/mandiri/daftar-pasien',
                'message' => "Ada $count sampel dari aplikasi pendaftaran",
                'icon' => 'uil-flask',
            ];
            $this->result['count'] += $count;
        }
    }
}
