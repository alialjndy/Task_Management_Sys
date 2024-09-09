<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\AuthRequest;
use App\Http\Requests\Auth\UpdateAuthRequest;
use App\Http\Requests\User\AssignRoleRequest;
use App\Models\User;
use App\Service\User\UserService;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserManagementController extends Controller
{
    protected $userService;
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $users = $this->userService->filterUser($request);
        return response()->json([
            'status'=>'success',
            'message'=>'all users',
            'data'=>[
                $users
            ]
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(AuthRequest $request)
    {
        $validationData = $request->validated();
        $user = $this->userService->create($validationData);
        return response()->json([
            'status'=>'success',
            'message'=>'user created successfully',
            'data'=>[
                'name'=>$user->name,
                'email'=>$user->email,
                'role'=>$user->getRoleNames()->first()
            ]
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::findOrFail($id);
        return response()->json([
            'status'=>'success',
            'data'=>[
                'name'=>$user->name,
                'email'=>$user->email,
                'role'=>$user->getRoleNames()->first()
            ]
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAuthRequest $request, string $id)
    {

        $validationData = $request->validated();
        $updatedUser = $this->userService->update($validationData, $id);
            return response()->json([
                'status'=>'success',
                'message'=>'user updated successfully',
                'data'=>[
                    'name'=>$updatedUser->name,
                    'email'=>$updatedUser->email
                ]
            ],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->userService->delete($id);
        return response()->json([
            'status'=>'success',
            'message'=>'user deleted soft successfully'
        ],200);
    }
    public function restoreUser(string $id)
    {
        $restoredUser = $this->userService->restoreUser($id);
        return response()->json([
            'status'=>'success',
            'message'=>'user restored successfully',
            'name'=>$restoredUser->name,
            'email'=>$restoredUser->email
        ],200);
    }
    public function forceDelete($id){
        $this->userService->forceDelete($id);
        return response()->json([
            'status'=>'success',
            'message'=>'Permanently delete the user successfully'
        ],200);
    }
    /**
     * Summary of assignRole
     * @param \App\Http\Requests\User\AssignRoleRequest $assignRoleRequest
     * @param mixed $UserId
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function assignRole(AssignRoleRequest $assignRoleRequest ,$UserId){
        //Get Incoming Data
        $validatedData = $assignRoleRequest->validated();

        // Find User by ID
        $user = User::findOrFail($UserId);

        //Remove all role user and create new role
        $user->syncRoles([$validatedData['role']]);

        return response()->json([
            'message' => 'Role assigned successfully!',
            'user' => $user,
            'role' => $validatedData['role'],
        ]);
    }
}
