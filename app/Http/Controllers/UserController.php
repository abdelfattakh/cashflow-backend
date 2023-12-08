<?php

namespace App\Http\Controllers;

use App\helpers\ApiResponse;
use App\Http\Requests\CompanyRequest;
use App\Http\Requests\CompanyUpdateRequest;
use App\Http\Requests\CreateUserWithRolesRequest;
use App\Http\Requests\UserUpdatedSuccessfullyRequest;
use App\Models\Company;
use App\Models\Organization;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use ApiResponse;

    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $users = User::with(['role.permissions', 'organization.image'])->where('organization_id', auth()->user()->organization->id)->get();

        return response()->json(['message' => 'users Got successfully', 'users' => $users], 200);
    }

    /**
     * Store a newly created resource in storage.
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateUserWithRolesRequest $request)
    {

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'organization_id' => auth()->user()->organization->id,
            'role_id' => Role::query()->first()->id,
        ]);

        $user->role->permissions()->sync($request->permissions_id);

        $user->loadMissing([
            'role.permissions',
            'organization.image'
        ]);

        return response()->json(['message' => 'user added successfully', 'user' => $user], 200);
    }

    /**
     * Display the specified resource.
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $user = User::with(['role.permissions',
            'organization.image'])->find($id);

        $user->loadMissing([
            'role.permissions',
            'organization.image'
        ]);
        return response()->json(['message' => 'user Got successfully', 'user' => $user]);
    }

    /**
     * Update the specified resource in storage.
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UserUpdatedSuccessfullyRequest $request, User $user)
    {
        if ((request()->has('role_id') && $request->role_id != $user->role_id)) {
            $user->role->permissions()->detach($user->role->permissions);
        }
        $user->update($request->except('permissions_id'));
        if (request()->has('permissions_id')) {
            $permissions = $user->role->permissions()->get()->toArray();

            $permissions = array_map(function ($value) {
                return $value['id'];
            }, $permissions);

            $user->role->permissions()->detach($permissions);
            $user->role->permissions()->sync($request->permissions_id);

        }
        return response()->json(['message' => 'Company updated successfully', 'user' => $user], 200);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $user=User::find($id);

        $permissions = $user->role->permissions()->get()->toArray();

        $permissions = array_map(function ($value) {
            return $value['id'];
        }, $permissions);
        $user->role->permissions()->detach($permissions);
        User::destroy($id);


        return response()->json(['message' => 'User deleted successfully'], 200);
    }


}
