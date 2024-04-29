<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = User::all();
        // digunakan bnyk data
        // kalo first 

        return view('akun.index', compact('user'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('akun.create');
    }

    public function loginAuth(Request $request)
    {
        $request->validate([
            'email' => 'required|email:dns',
            'password' => 'required',
        ]);

        $user = $request->only(['email', 'password']); //
        if (Auth::attempt($user)) { //auth untuk mengecek autentikasi yg ada di database 
            //memastikan bahwa data si user yang login
            return redirect()->route('home.page');
        } else {
            return redirect()->back()->with('failed', 'proses login gagal, silahkan coba kembali dengan data yang benar!' );
        }
    }

    public function logout()
    {
        Auth::logout(); //si kelas yang mengatur si user yang login 
        return redirect()->route('login')->with('logout', 'Anda Telah Logout'); //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users,email',
            'type' => 'required|in:admin,cashier',
        ]);

        $password = substr($request->email, 0, 3).substr($request->name, 0, 3);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($password),
            'type' => $request->type,
        ]);

        return redirect()->route('akun.index')->with('success', 'Berhasil Mengubah data!');
        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $users = User::find($id);
        
        return view('akun.edit', compact('users')); //harus sama dengan yng ada di usercontroller
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([ //dari parameter store
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users,email,'.$id,
            'role' => 'required|in:admin,cashier',
        ]);

        $password = substr($request->email, 0, 3).substr($request->name, 0, 3);
        if($request->password){
            User::where('id', $id)->update([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
            ]);
            
        }
        else{
            User::where('id', $id)->update([
                'name' => $request->name,
                'email' => $request->email,
                'role' => $request->role,
            ]);
        }

        return redirect()->route('akun.index')->with('success', 'Berhasil mengubah data!');//ke halaman yg diarahkan oleh route
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        User::where('id', $id)->delete();

        return redirect()->back()->with('deleted', 'berhasil menghapus data!'); //balik lagi ke halaman ke sebelumnya
    }
}
