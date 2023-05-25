<?php

namespace App\Http\Controllers;
use App\Models\menu;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\UploadedFile;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

class MenuController extends Controller
{
    public function getmenu(){
        $dt_menu=menu::get();
        return response()->json($dt_menu);
    }
    public function getmenuid(Request $req, $id){
        $dt_menu=menu::where('id_menu', $id)
        ->get();
        return response()->json($dt_menu);
    }
    public function createmenu(Request $req){
        // dd($req->all());
        $validator = validator::make($req->all(),[
            'nama_menu'=>'required',
            'jenis'=>'required',
            'deskripsi'=>'required',
            'harga'=>'required',
            'gambar'=>'required',
        ]);
        if($validator->fails()){
            return Response()->json($validator->errors()->toJson());
        }

        $gambar = time() . '.' . $req->gambar->extension();
        $req->gambar->move(public_path('images'), $gambar);

        $save=DB::table('menu')->insert([
            'nama_menu' =>$req->get('nama_menu'),
            'jenis' =>$req->get('jenis'),
            'deskripsi' =>$req->get('deskripsi'),
            'harga' =>$req->get('harga'),
            'gambar'=>$gambar,
            'jumlah_pesan' => 0
        ]);
        if($save){
            return Response()->json([
                'status'=>true, 'message' => 'Success Menambah Menu'
            ]);
        }
        else{
            return Response()->json([
                'status'=>false, 'message' => 'Gagal Menambah Menu'
            ]);
        }
    }
    public function updatemenu(Request $req, $id){
        $validator = validator::make($req->all(),[
            'nama_menu'=>'required',
            'jenis'=>'required',
            'deskripsi'=>'required',
            // 'gambar'=>'required',
            'harga'=>'required',
        ]);
        if($validator->fails()){
            return Response()->json($validator->errors()->tojson()); 
        }
        // $gambar = time() . '.' . $req->gambar->extension();
        // $req->gambar->move(public_path('images'), $gambar);
        
        $ubah=menu::where('id_menu', $id)->update([
            'nama_menu' =>$req->get('nama_menu'),
            'jenis' =>$req->get('jenis'),
            'deskripsi' =>$req->get('deskripsi'),
            'harga' =>$req->get('harga'),
            // 'gambar' ->$gambar             
        ]);
        if($ubah){
            return Response()->json([
                'status' =>true, 'message' => 'Sukses Mengubah Menu'
            ]);
        }
        else{
            return Response()->json([
                'status' => false, 'message' => 'Gagal Mengubah Menu'
            ]);
        }
    }
    
    public function updategambar(Request $req, $id)
    {
        $gambar = time() . '.' . $req->gambar->extension();
        $req->gambar->move(public_path('images'), $gambar);

        $update =  Menu::where('id_menu', $id)->update([
            'gambar' => $gambar
        ]);

        return response()->json([
            "Message" => "Berhasil",
            "Result" => $update
        ]);
    }

    public function deletemenu($id){
        $hapus=menu::where('id_menu', $id)->delete();
        if($hapus){
            return Response()->json([
                'status' =>true, 'message' => 'Sukses Menghapus Menu'
            ]);
        } 
        else{
            return Response()->json([
                'status' =>true, 'message' => 'Gagal Menghapus Menu'
            ]);
        } 
    }
}