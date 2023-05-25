<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class transaksi extends Model
{
    use HasFactory;
    public $timestamps=null;
    protected $table="transaksi";
    protected $primaryKey="id_transaksi";
    protected $fillable=['id_keranjang', 'id_user', 'id_menu','id_meja','nama_pelanggan','tgl_pesan','total_pesanan', 'total_harga', 'status'];
}
