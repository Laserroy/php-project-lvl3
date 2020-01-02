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
            'url' => 'required|url'
        ]);

        if ($validator->fails()) {
            return view('main', ['errors' => $validator->errors()]);
        }

        $urlFromInput = $request->input('url');
        $isDomainInDatabase = DB::table('domains')->where('name', $urlFromInput)->exists();
        $domainId = DB::table('domains')->where('name', $urlFromInput)->value('id');

        if (!$isDomainInDatabase) {
            $domainId = DB::table('domains')->insertGetId(['name' => $urlFromInput, 'record_state' => 'init']);
            dispatch(new CollectAdditionalData($urlFromInput, $domainId));
        }
        
        return $this->show($request, $domainId);
    }

    public function show(Request $request, $id)
    {
        $domainViewMap = [
            'init' => function ($domain) {
                return view('main', ['message' => 'Please wait for results']);
            },
            'fail' => function ($domain) {
                return view('domain_fail', ['domain' => $domain]);
            },
            'complete' => function ($domain) {
                return view('domain', ['domain' => $domain]);
            }
        ];
        
        $domain = DB::table('domains')->find($id);

        if (!$domain) {
            abort(404);
        }
        
        $state = $domain->record_state;
        
        return $domainViewMap[$state]($domain);
    }

    public function index(Request $request)
    {
        $domains = DB::table('domains')->simplePaginate(5);
        return view('domains', ['domains' => $domains]);
    }
}
