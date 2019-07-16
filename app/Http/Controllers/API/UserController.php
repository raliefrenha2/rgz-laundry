<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

use App\User;

use App\Http\Resources\UserCollection;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with(['outlet'])->orderBy('created_at', 'DESC')->courier();
        if (request()->q != '') {
            $users = $users->where('name', 'LIKE', '%' . request()->q . '%');
        }
        $users = $users->paginate(10);
        return new UserCollection($users);
    }

    public function store(Request $request)
    {
        //VALIDASI
        $this->validate($request, [
            'name' => 'required|string|max:150',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|string',
            'outlet_id' => 'required|exists:outlets,id',
            'photo' => 'required|image'
        ]);

        DB::beginTransaction();
        try {
            $name = NULL;
            //APABILA ADA FILE YANG DIKIRIMKAN
            if ($request->hasFile('photo')) {
                //MAKA FILE TERSEBUT AKAN DISIMPAN KE STORAGE/APP/PUBLIC/COURIERS
                $file = $request->file('photo');
                $name = $request->email . '-' . time() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/couriers', $name);
            }
            //BUAT DATA BARUNYA KE DATABASE
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
                'role' => $request->role,
                'photo' => $name,
                'outlet_id' => $request->outlet_id,
                'role' => 3
            ]);
            DB::commit();
            return response()->json(['status' => 'success'], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => 'error', 'data' => $e->getMessage()], 200);
        }
    }
}
