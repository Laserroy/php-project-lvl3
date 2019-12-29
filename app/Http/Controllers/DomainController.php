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
        $givenUrl = $request->input('url');
        
        $validator = Validator::make($request->all(), [
            'url' => 'required|url'
        ]);

        if ($validator->fails()) {
            return view('main', ['errors' => $validator->errors()]);
        }

        if (DB::table('domains')->where('name', $givenUrl)->doesntExist()) {
            $domainId = DB::table('domains')->insertGetId(['name' => $givenUrl, 'record_state' => 'init']);
            dispatch(new CollectAdditionalData($givenUrl, $domainId));
        }
        $domainId = DB::table('domains')->where('name', $givenUrl)->value('id');
        $domain = DB::table('domains')->find($domainId);
        $domainState = $domain->record_state;
        
        return $this->doStateDependAction($domainState, $domain);
    }

    public function show(Request $request, $id)
    {
        $domainData = DB::table('domains')
                        ->where('id', '=', $id)
                        ->get();
        if ($domainData[0]->record_state === 'complete') {
            return view('domain', ['data' => $domainData]);
        }
    }

    public function index(Request $request)
    {
        $domains = DB::table('domains')->simplePaginate(5);
        return view('domains', ['domains' => $domains]);
    }

    private function doStateDependAction($state, $domain)
    {
        $stateActionMap = [
            'init' => function ($domain) {
                return redirect()->route('main_page');
            },
            'fail' => function ($domain) {
                DB::table('domains')->delete($domain->id);
                return view('main', ['message' => 'Aborted! Check your url or try later']);
            },
            'complete' => function ($domain) {
                return redirect()->route('domains.show', ['id' => $domain->id]);
            }
        ];

        return $stateActionMap[$state]($domain);
    }
}
