<?php

namespace App\Http\Controllers;

use App\Jobs\RetreiveRequestData;
use App\Jobs\RetrieveParsedTags;
use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Queue;
use Validator;

class DomainsController extends BaseController
{
    private $client;
    
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function showMainPage(Request $request)
    {
        return view('main');
    }

    public function addDomain(Request $request)
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
            
            Queue::push(new RetreiveRequestData($givenUrl, $id));
            Queue::push(new RetrieveParsedTags($id));
        }
        $id = DB::table('domains')->where('name', $givenUrl)->value('id');
        return redirect()->route('domain', ['id' => $id]);
    }

    public function getDomain(Request $request, $id)
    {
        $recordState = DB::table('domains')->where('id', $id)->value('record_state');
        if ($recordState === 'complete') {
            $domainData = DB::table('domains')
                ->select('*')
                ->where('id', '=', $id)
                ->get();
            return view('domain', ['data' => $domainData]);
        }
    }

    public function getDomains(Request $request)
    {
        $domains = DB::table('domains')->simplePaginate(5);
        return view('domains', ['domains' => $domains]);
    }
}
