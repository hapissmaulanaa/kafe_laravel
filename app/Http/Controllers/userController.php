<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\user;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class userController extends Controller
{
    //Get Data User berdasarkan ID
    public function getUserID(Request $req, $id_user) 
    {
        $dt_user=user::where('user.id_user', '=', $id_user)->get();
        return response()->json($dt_user);
    }

    // Get Data User
    public function getUser()
    {
        $dt_user=user::get();
        return response()->json($dt_user);
    }

    // Create Data User
    public function createUser(Request $req)
    {
        $validator = Validator::make($req->all(),[
            'nama_user' => 'required',
            'role'      => 'required',
            'username'  => 'required',
            'password'  => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson());
        }
        $save = user::create([
            'nama_user' => $req->get('nama_user'),
            'role'      => $req->get('role'),
            'username'  => $req->get('username'),
            'password'  =>  Hash::make( $req->get('password')),
        ]);
        if ($save) {
            return Response()->json(['status'=>true, 'message' => 'Sukses Menambah User']);
        } else {
            return Response()->json(['status'=>false, 'message' => 'Gagal Menambah User']);
        }
    }

    // Update Data User
    public function updateUser(Request $req, $id_user)
    {
        $validator = Validator::make($req->all(),[
            'nama_user' => 'required',
            'role'      => 'required',
            'username'  => 'required',
            'password'  => 'required',
        ]);
        if ($validator->fails()) {
            return Response()->json($validator->errors()->toJson());
        }
        $ubah=user::where('id_user', $id_user)->update([
            'nama_user' => $req->get('nama_user'),
            'role'      => $req->get('role'),
            'username'  => $req->get('username'),
            'password'  => $req->get('password'),
        ]);
        if ($ubah) {
            return Response()->json(['status'=>true, 'message' =>'Sukses Edit User']);
        } else {
            return Response()->json(['status'=>false, 'message' =>'Gagal Edit User']);
        }
    }

    // Delete Data User
    public function deleteUser($id_user)
    {
        $hapus=user::where('id_user', $id_user)->delete();
        if ($hapus) {
            return Response()->json(['status'=>true, 'message' =>'Sukses Hapus User']);
        }else {
            return Response()->json(['status'=>false, 'message' =>'Gagal Hapus User']);
        }
    }

    // public function login(Request $req)
    // {
    //     $credentials = $req->only ('username', 'password');

    //     try{
    //         if (! $token = JWTAuth::attempt($credentials)) 
    //         {
    //             return response()->json(['error'=>'invalid_credentials'], 400);
    //         }
    //     } catch (JWTException $e) {
    //         return response()->json(['error'=> 'could_not_create_toke'], 500);
    //     }
    //     return response()->json(compact['token']);
    // }

    // public function register(Request $req)
    // {
    //     $validator = Validator::make($req->all(),[
    //         'nama_user' => 'required|string|max:100',
    //         'username' => 'required|string|username|max:100|unique:user',
    //         'password' => 'required|string|min:6|confirmed'
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json($validator->errors()->toJson(), 400);
    //     }

    //     $user = user::create([
    //         'nama_user' => $req->get('nama_user'),
    //         'username' => $req->get('username'),
    //         'password' => Hash::make($req->get('password')),
    //     ]);

    //     $token = JWTAuth::fromUser($user);

    //     return response()->json(compact('user', 'token'), 201);
    // }

    // public function getAuthenticatedUser()
    // {
    // try {if (! $user = JWTAuth::parseToken()->authenticate()) 
    //     {
    //         return response()->json(['user_not_found'], 404);
    //     }
    // }catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e)
    //     {
    //         return response()->json(['token_expired'], $e->getStatusCode());
    //     }catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e)
    //     {
    //         return response()->json(['token_invalid'], $e->getStatusCode());
    //     }catch (Tymon\JWTAuth\Exceptions\JWTException $e)
    //     {
    //         return response()->json(['token_absent'], $e->getStatusCode());
    //     }
        
    // return response()->json(compact('user'));
    // }
    
    public function login(Request $request)
    {
        $token = Str::random(50);

        $user = user::where('username', $request->input('username'))->first();

        if (!$user || !Hash::check($request->input('password'), $user->password)) {
            return response()->json(['message' => 'Password mu salah oi'], 401);
        }


        $role = $user->role;

        return response()->json(compact('token', 'role'));
    }

}
