<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
     /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware(['jwt.verify'], ['except' => ['login', 'register']]);
        $this->middleware('cors');
    }

    /**
     * Get a JWT via given credentials.
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request){

        // Lets check type and get user params if they types is valid
    	$validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        // Check if user params have errors  and send json error response
        if ($validator->fails())  return response()->json($validator->errors(), 422); 

        // Get user by mail / Check if user doesnt exist if it return json error response
        $user = User::where(['email' => $validator->validated()['email']])->first();
        if(!$user) return response()->json([ 'message' => 'Mot de passe ou email invalide !' ], 403);       

        if(!$this->checkTentative($user)) return response()->json([
            'message' => 'Désoler, vous avez essayer de vous connecté trop de fois sans succès, vos requêtes, sont désormer bloquer. \n ' .
                         'Si vous souhaitez vous connecter un mail vous à été envoyer.'
        ]);

        // Lets check if user credentials is not valid valid
        if (! $token = auth()->attempt($validator->validated())) {
            $this->checkTentative($user, true);
            return response()->json([ 'message' => 'Mot de passe ou email invalide !' ], 403);
        }
        
        return $this->createNewToken($token);
    }

    /**
     * Register a User.
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request) {
        // return $request;
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|confirmed|min:6',
        ]);

        $user = User::create(array_merge(
                    $validator->validated(),
                    ['password' => bcrypt($request->password)]
                ));

        return response()->json([
            'message' => 'User successfully registered',
            'user' => $user
        ], 201);
    }


    /**
     * Log the user out (Invalidate the token).
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout() {
        auth()->logout();
        return response()->json(['message' => 'User successfully signed out']);
    }

    /**
     * Refresh a token.
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh() {
        return $this->createNewToken(auth()->refresh());
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userProfile() {
        return response()->json(auth()->user());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($token){
        return response()->json([
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => auth()->factory()->getTTL() * 60,
            'user'         => auth()->user()
        ], 200);
    }

    public function TestToken(Request $request){
        return response()->json(['success' => true]);
    }

    /**
     * Check if user connexion tentative is less then five 
     * @param User $user
     * @param Boolean $loginFailed
     * @param Boolean $reset
     */
    private function checkTentative($user, $loginFailed = false, $reset = false){
        if($reset === true) {
            $user->tentative = 0;
            $user->save();
        }
        if($user->tentative >=  5 && $loginFailed === false){
            Log::info('USER with email:' . $user->email . ' try to connect while blocked');
            return false;
        }

        if($loginFailed == true){
            $user->tentative += 1;
            $user->save();
        }
        return true;
    }
}
