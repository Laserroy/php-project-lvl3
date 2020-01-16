<?php

namespace App\Http\Controllers;

use App\Domain;
use App\Jobs\CollectAdditionalData;
use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Validator;

class DomainController extends BaseController
{
    private $client;
    
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), ['url' => 'required|active_url']);
        if ($validator->fails()) {
            return view('main', ['errors' => $validator->errors()]);
        }

        $urlFromInput = $request->input('url');
        $domain = Domain::firstOrCreate(['name' => $urlFromInput]);
        
        if ($domain->stateMachine()->can('process')) {
            dispatch(new CollectAdditionalData($domain));
        }

        return redirect(route('domains.show', ['id' => $domain->id]));
    }

    public function show($id)
    {
        $domainViewMap = [
            'initiated' => function ($domain) {
                return view('main', ['message' => 'Please wait for results']);
            },
            'failed' => function ($domain) {
                return view('domain_fail', ['domain' => $domain]);
            },
            'completed' => function ($domain) {
                return view('domain', ['domain' => $domain]);
            }
        ];
        
        $domain = Domain::findOrFail($id);
        
        return $domainViewMap[$domain->state]($domain);
    }

    public function index(Request $request)
    {
        $domains = Domain::paginate(5);
        return view('domains', ['domains' => $domains]);
    }
}
