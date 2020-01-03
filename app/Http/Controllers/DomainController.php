<?php

namespace App\Http\Controllers;

use App\Domain;
use App\Jobs\CollectAdditionalData;
use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
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
        $domain = Domain::where('name', $urlFromInput)->first();

        if (!$domain) {
            $domain = new Domain();
            $domain->name = $urlFromInput;
            $domain->record_state = 'init';
            $domain->save();
            dispatch(new CollectAdditionalData($urlFromInput, $domain->id));
        }
        
        return $this->show($request, $domain->id);
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
        
        $domain = Domain::findOrFail($id);
        $state = $domain->record_state;
        
        return $domainViewMap[$state]($domain);
    }

    public function index(Request $request)
    {
        $domains = Domain::paginate(5);
        return view('domains', ['domains' => $domains]);
    }
}
