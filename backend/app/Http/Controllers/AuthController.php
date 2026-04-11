
<?php

use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
class AuthController extends Controller
{
public function register(RegisterRequest $request)
{

    $role = User::count() === 0 ? 'Admin' : 'Donor';


    $data = $request->validated();
    $data['password'] = Hash::make($request->password);
    $data['role'] = $role;


    if ($role === 'Donor') {
        $data['blood_group'] = $request->blood_group ?? 'Unknown';
        $data['status_availabality'] = true;
        $data['is_verified'] = false;
    } else {
        $data['is_verified'] = null;
    }

    $user = User::create($data);

    return response()->json([
        'message' => "Vous êtes inscrit avec succès",
        'user' => $user
    ]);

}
public function login(LoginRequest $request)
{

    $credentials = $request->only('email', 'password');

    if (!Auth::attempt($credentials)) {
        return response()->json([
            'message' => 'Email ou mot de passe incorrect'
        ], 401);
    }

    $user = Auth::user();


    if ($user->is_banned === true) {
        Auth::logout();
        return response()->json([
            'message' => 'Votre compte est banni. Contactez l\'administrateur.'
        ], 403);
    }


    $token = $user->createToken('auth_token')->plainTextToken;

    return response()->json([
        'message' => 'Connexion réussie',
        'access_token' => $token,
        'token_type' => 'Bearer',
        'user' => $user
    ]);
}

     public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout successful'
        ]);
    }

}
