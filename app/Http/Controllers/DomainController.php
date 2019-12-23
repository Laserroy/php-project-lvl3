<?php

namespace App\Http\Controllers;

use App\Jobs\CollectAdditionalData;
use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;
use Validator;

class DomainController extends BaseController
{
    private $client;
    
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'url' => 'required|active_url'
        ]);

        if ($validator->fails()) {
            return view('main', ['errors' => $validator->errors()]);
        }

        $givenUrl = $request->input('url');
        
        if (DB::table('domains')->where('name', $givenUrl)->doesntExist()) {
            $id = DB::table('domains')->insertGetId(['name' => $givenUrl, 'record_state' => 'init']);
            
            dispatch(new CollectAdditionalData($givenUrl, $id));
        }
        $id = DB::table('domains')->where('name', $givenUrl)->value('id');
        return redirect()->route('domains.show', ['id' => $id]);
    }

    public function show(Request $request, $id)
    {
        $domainData = DB::table('domains')
                        ->where('id', '=', $id)
                        ->get();
               
        $recordState = $domainData[0]->record_state;
        if ($recordState === 'complete') {
            return view('domain', ['data' => $domainData]);
        }
    }

    public function index(Request $request)
    {
        $domains = DB::table('domains')->simplePaginate(5);
        return view('domains', ['domains' => $domains]);
    }
}
