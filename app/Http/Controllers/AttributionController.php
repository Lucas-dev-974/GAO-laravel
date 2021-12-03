<?php

namespace App\Http\Controllers;

use App\Http\Resources\AttributionCollection;
use App\Models\Attribution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AttributionController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.verify');
    }

    public function add(Request $request){
        $validator = Validator::make($request->all(), [
            'computerID' =>  'numeric|required',
            'userID'     =>  'numeric|required',
            'horraire'    => 'numeric|required',
            'date'        => 'string|required'
        ]);

        if($validator->fails()) return response()->json(['message' => 'Des champs sont manquant !'], 403);
        
        $attribution =  Attribution::create([
            'user_id'     => $validator->validated()['userID'],
            'computer_id' => $validator->validated()['computerID'],
            'horraire'    => $validator->validated()['horraire'],
            'date'        => $validator->validated()['date']
        ]);
        $attribution->client;

        return $this->_jsonRsp(['attribution' => new AttributionCollection($attribution)], 200);
    }

    public function remove($id){
        if(empty($id) && !is_numeric($id)) return $this->_jsonRsp(['message' => 'Des champs sont manquant !'], 403);
        $attribution = Attribution::find($id);
        if(!$attribution) return $this->_jsonRsp(['message' => 'Une erreur est survenue il s`\'emblerais que cet attribution n\'existe pas !'], 403);
        if(!$attribution->delete()) $this->_jsonRsp(['message' => 'Une erreur est survenue'], 403);
        $this->_jsonRsp(['id' => $id], 200);
    }
}
