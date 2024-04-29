<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Medicine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\OrderExport;
use PDF;
// use Excel;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $keyword = $request->date;
        $orders = Order::with('user')->where('created_at', 'LIKE', '%' . $keyword . '%')->simplePaginate(10);
        return view('order.kasir.index', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $medicines = Medicine::all(); 
        // 
        return view("order.kasir.create", compact('medicines'));
    }

    public function downloadPDF($id) 
    {
        $order = Order::find($id)->toArray();

        view()->share('order', $order);

        $pdf = PDF::loadView('order.kasir.download-pdf', $order);

        return $pdf->download('receipt.pdf');

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'name_customer'=>'required',
            'medicines'=> 'required',
        ]);

        $arrayDistinct = array_count_values($request->medicines); //menghitung banyak nya tiap" data

        $arrayAssocMedicines = []; //

        foreach ($arrayDistinct as $id => $count) { //
            $medicine = Medicine::where('id', $id)->first(); //di where berdasarkan id

            $subPrice = $medicine['price'] * $count;

            $arrayItem = [  //
                "id" => $id,
                "name_medicine"=> $medicine['name'],
                "qty"=> $count,
                "price"=>$medicine['price'],
                "sub_price"=>$subPrice,
            ];

            array_push($arrayAssocMedicines, $arrayItem);
        }
        $totalPrice = 0;

        foreach ($arrayAssocMedicines as $item) {
            $totalPrice += (int)$item['sub_price']; //menjumlahkan dua value yg valuenya integer atau lebih
            //.= value yang bertipe string menggabungkan dua string atau lebih
        }

    $priceWithPPN = $totalPrice + ($totalPrice * 0.01);

    $proses = Order::create([ 
        'user_id' => Auth::user()->id, //yang melakukan kasir
        'medicines' => $arrayAssocMedicines,
        'name_customer' => $request->name_customer,
        'total_price' => $priceWithPPN,
    ]);

    if ($proses) {

        $order = Order::where('user_id', Auth::user()->id)->orderBy('created_at', 'DESC')->first();
        
        return redirect()->route('kasir.order.print', $order['id']);
        
    } else {
        return redirect()->back()->with('failed', 'Gagal membuat data pembelian, Silahkan coba kembali dengan data yang sesuai ');
    }
}
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
        $order = Order::find($id);
        return view('order.kasir.print', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function data()
    {
        //mengambil hasil relasi dari pk dan fknya, value nya == nama func relasi hasmenu/belongsto yg ada di modelnya
        $orders = Order::with('user')->simplePaginate(5); //membuat paginasi
        //ngambil relasi pake with
        return view("order.admin.index", compact('orders'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function exportExcel()
    {
        //
        $file_name = 'data_pembelian'.'.xlsx';

        return Excel::download(new OrderExport, $file_name);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }
}
