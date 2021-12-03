<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserCollection;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ClientController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.verify');
    }

    public function get(){
        $clients = User::whereNull('password')->get();
        return $this->_jsonRsp(['clients' => UserCollection::collection($clients)], 200);
    }
    
    public function create(Request $request){
        $validator = Validator::make($request->all(), ['name' => 'string|required']);
        if($validator->fails()) return $this->_jsonRsp(['message' => 'Veuillez renseigné tous les champs !'], 403);
        $client = User::create(['name' => $request->name]);
        return $this->_jsonRsp(['client' => new UserCollection($client)], 200);
    }
    
    public function delete($id){
        if(empty($id) || !is_numeric($id)) return $this->_jsonRsp(['message' => 'Veuillez complété tous les champs'], 403);   
        $user = User::find($id);
        if(!$user)  return $this->_jsonRsp(['message' => 'L\'ordinateur n\'existe pas ! '], 403);
        if(!$user->delete()) return $this->_jsonRsp(['message' => 'Une erreur c\'est produite !'], 403);
        return $this->_jsonRsp(['id' => $id], 200);
    }

    public function search($Squery){
        if(empty($Squery) || !is_string($Squery)) return $this->_jsonRsp(['message' => ""], 403);
        $clients = User::where('name', 'like', '%' . $Squery . '%')->get();
        return $this->_jsonRsp(['clients' => $clients], 200);
    }
}
