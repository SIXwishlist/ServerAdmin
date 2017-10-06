<?php

namespace Richardds\ServerAdmin\Http\Controllers;

use Illuminate\Http\Request;
use Richardds\ServerAdmin\DnsZone;
use Richardds\ServerAdmin\Http\CrudAssistance;

class DnsZoneController extends Controller
{
    use CrudAssistance;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function showZones()
    {
        return view('sections.dns.zones');
    }

    public function index()
    {
        return api_response()->success(DnsZone::all()->toArray())->response();
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|min:1|max:63',
            'admin' => 'required|min:1|max:253',
            'serial' => 'required|number',
            'refresh' => 'required|number',
            'retry' => 'required|number',
            'expire' => 'required|number',
            'ttl' => 'required|number',
            'enabled' => 'boolean',
        ]);

        $dnsZone = DnsZone::create([
            'name' => $request->get('name'),
            'admin' => $request->get('admin'),
            'serial' => $request->get('serial'),
            'refresh' => $request->get('refresh'),
            'retry' => $request->get('retry'),
            'expire' => $request->get('expire'),
            'ttl' => $request->get('ttl'),
            'enabled' => $request->get('enabled') ?? true,
        ]);

        return api_response()->success(['id' => $dnsZone->id])->response();
    }

    public function show(DnsZone $dnsZone)
    {
        return api_response()->success($dnsZone->toArray())->response();
    }

    public function update(Request $request, DnsZone $dnsZone)
    {
        $rules = [
            'name' => 'min:1|max:63',
            'admin' => 'min:1|max:253',
            'serial' => 'number',
            'refresh' => 'number',
            'retry' => 'number',
            'expire' => 'number',
            'ttl' => 'number',
            'enabled' => 'boolean',
        ];

        $this->validate($request, $rules);

        $this->updateModel($dnsZone, $request, array_keys($rules));
        $dnsZone->save();

        return api_response()->success($dnsZone->toArray())->response();
    }

    public function destroy(DnsZone $dnsZone)
    {
        $dnsZone->delete();

        return api_response()->success()->response();
    }
}
