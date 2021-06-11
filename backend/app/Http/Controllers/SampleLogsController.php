<?php

namespace App\Http\Controllers;

use App\Http\Resources\SampleLogsResource;
use App\Models\Sampel;
use App\Traits\PaginateTrait;
use Illuminate\Http\Request;

class SampleLogsController extends Controller
{
    use PaginateTrait;

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, Sampel $sampel)
    {
        $perpage = $this->getValidPerpage($request->input('perpage'));

        $logs = $sampel->logs()->orderByDesc('created_at');

        return SampleLogsResource::collection($logs->paginate($perpage));
    }
}
