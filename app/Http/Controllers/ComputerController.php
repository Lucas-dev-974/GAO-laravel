<?php
namespace App\Http\Controllers;

use App\Http\Resources\ComputerCollection;
use App\Models\Computer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ComputerController extends Controller
{   

    public function __construct()
    {
        $this->middleware('jwt.verify');
    }
    public function get($date){
        if(empty($date)) return response()->json(['message' => 'Un champ de type Datetime est demandé'], 403);
        $computers = Computer::with(['attributions' => function($req) use ($date){
            $req->where('date', '=', $date)->get();
        }])->get();
        $this->_jsonRsp(['computers' => ComputerCollection::collection($computers)], 200);
    }

    public function create(Request $request){
        $validator = Validator::make($request->all(), ['name' => 'string|required']);
        if($validator->fails()) $this->_jsonRsp(['message' => 'Veuillez complété tous les champs'], 403);

        $computer = Computer::create(['name' => $validator->validated()['name']]);
        $computer->attributions;
        foreach($computer->attributions as $attribution) $attribution->client;
        $this->_jsonRsp(['computer' => new ComputerCollection($computer)], 200);
    }

    public function update($id, $name){
        if(!is_numeric($id) || !is_string($name)) return $this->_jsonRsp(['message' => 'Veuillez remplir pour le champ id une entré de type nombres et pour le champ nom une des lettres/nombres !'], 403);
        
        $computer = Computer::find($id);
        if(!$computer)  return $this->_jsonRsp(['success' => false, 'message' => 'L\'ordinateur n\'existe pas ! '], 403);

        if(isset($name)){
            $computer->name = $name;
        }
        $state = $computer->save();
        if(!$state) return $this->_jsonRsp(['message' => 'Une erreur c\'est produite'], 403);
        return $this->_jsonRsp(['computer' => $computer], 200);
    }

    public function delete(Request $request, $id){
        // return response()->json(['test']);
        if(empty($id)) return response()->json(['message' => 'Veuillez remplir le champ demandé !'], 403);
        $computer = Computer::find($request->id);
        if(!$computer)  return response()->json(['message' => 'L\'ordinateur n\'existe pas ! '], 406);
        $computer->delete();
        return response()->json([], 200);
    }
}
