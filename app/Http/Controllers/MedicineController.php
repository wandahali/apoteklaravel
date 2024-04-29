<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use Illuminate\Http\Request;

class MedicineController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Medicine::query();


        if ($request->has('cari')) {
            $keyword = $request->input('cari');
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', '%' . $keyword . '%')
                     ->orWhere('type', 'like', '%' . $keyword . '%')
                     ->orWhere('price', 'like', '%' . $keyword . '%')
                     ->orWhere('stock', 'like', '%' . $keyword . '%');

        });
    }
        $medicines = $query->get();
        
        return view('medicine.index', compact('medicines')); //mengambil dari index.blade
    
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {//menampilkan layouting html pda folder resources-view
      return view('medicine.create');
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([ //mengambil value dari name suatu tag
            //required nya harus di isi di webnya
            'name' => 'required|min:3',
            'type' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|numeric',
            //
        ]);

        Medicine::create([ //menambahkan table medicine
            'name' => $request->name, //mengambil dari nama kolom
            'type' => $request->type,
            'price' => $request->price,
            'stock' => $request->stock,
        ]);

        //jika seluruh data input akan dimasukan langsung ke db bsa dengan
        //perintah medicine:create($request->all())

        return redirect()->back()->with('success', 'Berhasil menambahkan data obat!'); //kembali ke pag sebelumnya
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $medicine = Medicine::find($id); //untuk menenukan edit yag dicari
        
        return view('medicine.edit', compact('medicine')); 
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|min:3',
            'type' => 'required',
            'price' => 'required|numeric',
        ]);

        Medicine::where('id', $id)->update([
            'name' => $request->name,
            'type' => $request->type,
            'price' => $request->price,
        ]);

        return redirect()->route('medicine.home')->with('success', 'Berhasil mengubah data!');//
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Medicine::where('id', $id)->delete(); //

        return redirect()->back()->with('deleted', 'berhasil menghapus data!');
    }
     

    public function stock()
    {
        $medicines = Medicine::orderBy('stock', 'ASC',)->get();

         return view('medicine.stock', compact('medicines'));
    }

    public function stockEdit($id)
    {
        $medicine = Medicine::find($id);

        return response()->json($medicine);
    }

    public function stockUpdate(Request $request, $id)
    {
        $request->validate([
            'stock' => 'required|numeric',
        ]);

        $medicine = Medicine::find($id);

        if ($request->stock <= $medicine['stock']) {
            return response()->json(["message" => "stock yang diinput tidak boleh kurang dari stock sebelumnya"], 400);
        } else {
            $medicine->update(["stock" => $request->stock]);
            return response()->json("berhasil", 200);
        }
    }
}

//kalo ga pake redirect ruote akan memunculkan slace doang
//kalo pake bakal ada nama route nya