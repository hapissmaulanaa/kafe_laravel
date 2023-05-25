<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\meja;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class mejaController extends Controller
{
    //Get Data Meja Berdasarkan ID
    public function getmejaID(Request $req, $id_meja)
    {
        $dt_meja=meja::where('meja.id_meja', '=', $id_meja)->get();
        return response()->json($dt_meja);
    }

    // Get Data Meja
    public function getMeja()
    {
        $dt_meja=meja::get();
        return response()->json($dt_meja);
    }

    // Create Data Meja
    public function createMeja(Request $req)
    {
        $validator=Validator::make($req->all(),[
            'nomor_meja' => 'required',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
        return response()->json($validator->errors()->toJson());
        }

        $save = meja::create([
            'nomor_meja' => $req->get('nomor_meja'),
            'status' => $req->get('status')
        ]);

        if ($save) {
            return Response()->json(['status'=>true, 'message' => 'Sukses Menambah Meja']);
        } else {
            return Response()->json(['status'=>false, 'message' => 'Gagal Menambah Meja']);
        }
    }

    // Update Data Meja
    public function updateMeja(Request $req, $id_meja)
    {
        $validator=Validator::make($req->all(),[
            'nomor_meja' => 'required',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson());
        }

        $ubah = meja::where('id_meja', $id_meja)->update([
            'nomor_meja' => $req->get('nomor_meja'),
            'status' => $req->get('status'),
        ]);

        if ($ubah) {
            return Response()->json(['status'=>true, 'message' => 'Sukses Merubah Meja']);
        } else {
            return Response()->json(['status'=>false, 'message' => 'Gagal Merubah Meja']);
        }
    }

    // Delete Data Meja
    public function deleteMeja($id_meja)
    {
        $hapus=meja::where('id_meja', $id_meja)->delete();

        if ($hapus) {
            return Response()->json(['status'=>true, 'message' => 'Sukses Menghapus Nomor Meja']);
        } else {
            return Response()->json(['status'=>false, 'message' => 'Gagal Menghapus Nomor Meja']);
        }
    }
}
