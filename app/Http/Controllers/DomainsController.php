<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DomainsController extends BaseController
{
    public function addDomain(Request $request)
    {
        $inputData = $request->input('url', 'wrong');
        if (DB::table('domains')->where('name', $inputData)->doesntExist()) {
            $id = DB::table('domains')->insertGetId(['name' => $inputData]);
            return redirect()->route('domain', ['id' => $id]);
        }
        $id = DB::table('domains')->where('name', $inputData)->value('id');
        return redirect()->route('domain', ['id' => $id]);
    }

    public function getDomain(Request $request, $id)
    {
        $domainData = DB::table('domains')
            ->select('id', 'name')
            ->where('id', '=', $id)
            ->get();
        return view('domain', ['data' => $domainData]);
    }
}
