
<?php

use App\Http\Requests\RegisterRequest;
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
public function login(){
    
}
}
