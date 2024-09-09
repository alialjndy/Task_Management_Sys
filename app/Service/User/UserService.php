<?php
namespace App\Service\User;

use App\Http\Requests\Auth\AuthRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Symfony\Component\Console\Input\Input;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserService{
    public function filterUser(Request $request)
    {
        $query = User::query();
        if($request->has('role')){
            $query->whereHas('roles', function($q) use ($request) {
                $q->where('name', $request->get('role'));
            });
        }
        if($request->has('with_trashed') && filter_var($request->get('with_trashed'), FILTER_VALIDATE_BOOLEAN)){
            $query->withTrashed();
        }
        if($request->has('only_trashed') && filter_var($request->get('only_trashed'), FILTER_VALIDATE_BOOLEAN)){
            $query->onlyTrashed();
        }
        return $query->get();
    }
    /**
     * Summary of create
     * @param array $data
     * @return
     */
    public function create(array $data)
    {
        $user = new User([
            'name'=>$data['name'],
            'email'=>$data['email'],

        ]);
        $user->password=Hash::make($data['password']);
        $user->save();
        $user->assignRole('user');
        return $user ;
    }
    /**
     * Summary of update
     * @param array $data
     * @param string $id
     * @return mixed
     */
    public function update(array $data , string $id){
        $user = User::findOrFail($id);
        if(isset($data['role'])){
            if (Role::where('name', $data['role'])->exists()) {
                $user->syncRoles([$data['role']]);
            }else{
                abort(404, 'role does not exist');
            }

        }
        $user->update($data);
        return $user ;
    }
    /**
     * Summary of delete
     * @param string $id
     * @return void
     */
    public function delete(string $id)
    {
        if($this->checkUserRole()){
            $user = User::findOrFail($id);
            $user->delete();
        }
    }
    /**
     * Summary of checkUserRole
     * @return bool
     */
    public function checkUserRole(){
        $user = JWTAuth::parseToken()->authenticate();
        if($user && $user->hasRole('admin')){
            return true ;
        }else{
            return false ;
        }
    }
    /**
     * Summary of restoreUser
     * @param mixed $id
     * @return
     */
    public function restoreUser($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        $user->restore();
        return $user;
    }
    public function forceDelete($id){
        $user = User::withTrashed()->findOrFail($id);
        $user->forceDelete();
    }

}
?>
