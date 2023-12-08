<?php

namespace App\Http\Controllers;

use App\helpers\ApiResponse;
use App\Http\Requests\RegisterRequest;
use App\Models\Organization;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PassportAuthController extends Controller
{
    use ApiResponse;

    /**
     * Registration Req
     */
    public function register(RegisterRequest $request)
    {

        $organization = Organization::create([
            'name' => $request->organization_name
        ]);

        $organization->addMediaFromRequest('organization_logo')->toMediaCollection((new \App\Models\Organization())->getPrimaryMediaCollection());

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'organization_id' => $organization->id,
            'role_id'=>Role::query()->first()->id,

        ]);

        $token = $user->createToken('Laravel-9-Passport-Auth')->accessToken;
        $user->loadMissing([
           'organization.image'
        ]);
        return response()->json(['token' => $token, 'user' => $user], 200);
    }

    /**
     * Login Req
     */
    public function login(Request $request)
    {
        $data = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if (auth()->attempt($data)) {
            $token = auth()->user()->createToken('Laravel-9-Passport-Auth');

            return response()->json(['token' => $token], 200);
        } else {
            return response()->json(['error' => 'Unauthorised'], 401);
        }
    }

    public function userInfo()
    {
        $user = auth()->user();
        return response()->json(['user' => $user], 200);
    }

    /**
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        auth()->user()->tokens()->delete();


        return self::ResponseSuccess(message: __('auth.logout_successfully'));
    }

}
