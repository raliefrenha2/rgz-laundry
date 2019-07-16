<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\OutletCollection;
use App\Http\Requests\OutletRequest;
use App\Outlet;

class OutletController extends Controller
{
   public function index()
	{
	    request()->q != '' ? $outlets = $this->searchOutlet(request()->q) : $outlets = Outlet::latest();
	   
	    return new OutletCollection($outlets->paginate(10));
	}

	public function store(OutletRequest $request)
	{
	    Outlet::create($request->all());
	    return response()->json(['status' => 'success'], 200);
	}

	public function edit($id)
	{
	    $outlet = Outlet::whereCode($id)->first();
	    return response()->json(['status' => 'success', 'data' => $outlet], 200);
	}

	public function update(OutletRequest $request, $id)
	{
	    $outlet = Outlet::whereCode($id)->first();
	    $outlet->update($request->except('code'));
	    return response()->json(['status' => 'success'], 200);
	}

	public function destroy(Outlet $outlet)
	{
	    $outlet->delete();
	    return response()->json(['status' => 'success'], 200);
	}

	private function searchOutlet($q)
	{
		return Outlet::where('name', 'LIKE', '%' . $q . '%')->latest();
	}
}
