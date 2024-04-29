<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'medicines',
        'name_customer',
        'total_price',

    ];

    //hasil property ketika diambil atau diupdate dibuat dalam bentuk tipe dataa apa

    protected $casts = [ //perubahan tipe data di rubah
        'medicines' => 'array', //memberikan penekanan bahwa kolom medicine bertipe data array tipe data akan menjadi array
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class); //foregen key

        //kolom foregen key harus gabungan dari table yang memiliki primary key tanpa huruv s digabungkan dengan _ lalu kolom primary key
        //bakal digabung dengan user_id

        //untuk nama relasi tanpa huruf s
        
    }
}

//up dijalankan ketika artisan migrate di running
//down dijalankan ketika migrate:rolback di running
