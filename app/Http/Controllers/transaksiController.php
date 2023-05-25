<?php

namespace App\Http\Controllers;

use App\Models\transaksi;
use App\Models\menu;
use App\Models\meja;
use App\Models\user;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

class TransaksiController extends Controller
{
    public function getkasir()
    {
        $get = user::where('role', 'kasir')->get();
        return response()->json($get);
    }

    //function memanggil semua data transaksi
    public function tampil(){
        $data=transaksi::get();
        return response()->json($data);
    }

    public function gettgl($tgl){
        $get = transaksi::where('tgl_pesan', $tgl)
        ->sum('total_harga');
        return response()->json($get);
    }

    public function getbulan($bulan){
        $get = transaksi::whereMonth('tgl_pesan', substr($bulan, 5, 2))
        ->sum('total_harga');
        return response()->json($get);
    }
    public function gethistory(){
        $get = DB::table('history')
        ->join('user', 'history.id_user', '=', 'user.id_user')
        ->get();
        return response()->json($get);
    }
    public function selecthistory($code){
        $get = DB::table('transaksi')
        ->where('id_keranjang',$code)
        ->join('user','transaksi.id_user','=','user.id_user')
        ->join('menu','transaksi.id_menu','=','menu.id_menu')
        ->get();
        return response()->json($get);
    }

    // data transaksi fase proses 
    public function ongoing(){
        $get = meja::where('status', 'digunakan')
        // ->join('transaksi', 'meja.id_meja','=','transaksi.id_meja    ')
        ->get();
        return response()->json($get);
    }
    public function getongoingtransaksi($id){
        $gettransaksi = transaksi::where('id_meja', $id)
        ->where('status', 'belum_bayar')
        ->first();
        return response()->json($gettransaksi);
    }

    public function total($code){
        $get = transaksi::where('id_keranjang', $code)
        ->sum('total_harga');
        return response()->json($get);
    }

    public function totalharga($id){
        $gettotal = transaksi::where('id_meja', $id)
        ->where('status', 'belum_bayar')
        ->sum('total_harga');
        return response()->json($gettotal);
    }
    public function getcart(){
        $cart = transaksi::where('id_meja', null)
        ->join('menu', 'transaksi.id_menu', '=', 'menu.id_menu')
        ->get();
        return response()->json($cart);
    }

    public function selecttransaksi($id){
        $gettransaksi = transaksi::where('id_keranjang', $id)
        ->get();
        return response()->json($gettransaksi);
    }
    // create transaksi
    public function createtransaksi(Request $req){
        $harga_menu = DB::table('menu')
        ->where('id_menu', $req->input('id_menu'))
        ->select('harga')
        ->first();
        $harga_menu = $harga_menu->harga;

        $tgl_pesan=carbon::now();
        $total_pesanan = $req->input('total_pesanan');
        $total_harga = $harga_menu * $total_pesanan;
        
        $tambah=transaksi::create([
            
            'id_menu'=>$req->input('id_menu'),
            'tgl_pesan'=> $tgl_pesan,
            'total_pesanan'=>$total_pesanan,
            'total_harga'=>$total_harga,
            'status'=>'belum_bayar',
        ]);

        $get = DB::table('menu')->where('id_menu', $req->input('id_menu'))->select('jumlah_pesan')->first();
        $jumlah_pesan = $get->jumlah_pesan;
        $addjumlahpesan = $jumlah_pesan + $total_pesanan;

        $add = DB::table('menu')->where('id_menu', $req->input('id_menu'))->update([
            'jumlah_pesan' => $addjumlahpesan
        ]);

        if($tambah){
            return Response()->json([
                'status'=>true, 'message' => 'Sukses Memesan Menu'
            ]);
        }
        else{
            return Response()->json([
                'status'=>false, 'message' => 'Gagal Memesan Menu'
            ]);
        }
    }
    
    // checkout transaksi
    public function checkout(Request $req){
        $id_keranjang = Str::random(5);

        $id_user =$req->input('id_user');
        $checkout = transaksi::where('id_keranjang', null)->update([
            'id_keranjang' => $id_keranjang,
            'id_user' => $id_user,
            'id_meja' =>$req->input('id_meja'),
            'nama_pelanggan' => $req->input('nama_pelanggan')
        ]);

        $history = DB::table('history')->insert([
            'id_keranjang' => $id_keranjang, 
            'tgl_transaksi' => carbon::now(),
            'id_user' => $id_user, 
            'nama_pelanggan' => $req->input('nama_pelanggan')
        ]);

        $updatemeja = meja::where('id_meja', $req->input('id_meja'))->update([
            'status' => 'digunakan'
        ]);

        if($checkout && $updatemeja){
            return Response()->json([
                'status'=>true, 'message' => 'Sukses'
            ]);
        }
        else{
            return Response()->json([
                'status'=>false, 'message' => 'Gagal'
            ]);
        }
    }

    public function transaksidone($id){
        $done = transaksi::where('id_meja', $id)
        ->where('status', 'belum_bayar') 
        ->update(['status' => 'lunas' ]);
        
        $meja = meja::where('id_meja', $id)
        ->update(['status' => 'kosong' ]);

        if($done && $meja){
            return Response()->json([
                'status'=>true, 'message' => 'Sukses'
            ]);
        }
        else{
            return Response()->json([
                'status'=>false, 'message' => 'Gagal'
            ]);
        }
    }

    public function deletetransaksi($id)
    {
        $hapus=transaksi::where('id_transaksi', $id)->delete();
        if($hapus){
            return Response()->json([
                'status' =>true, 'message' => 'Sukses Menghapus Pesanan'
            ]);
        } 
        else{
            return Response()->json([
                'status' =>true, 'message' => 'Gagal Menghapus Pesanan'
        ]);
            }
    }
}