<?php
namespace App\Service\OrdinaryUser;

use App\Http\Requests\OrdinaryUser\changeStatusRequest;
use App\Models\Task;
use Exception;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class OrdinaryUserService{
    public function filter(Request $request){
        $query = Task::query();
        $user_id = $this->getUserID();
        $query->where('to_assigned',$user_id);

        if($request->has('status')){
            $query->where('status',$request->get('status'));
        }
        return $query->get();
    }
    public function getUserID(){
        try{
            $user = JWTAuth::parseToken()->authenticate();
            $user_Id = $user->id ;
            return $user_Id;
        }catch(Exception $e){
            throw new Exception('Unauthorized');
        }
    }
    public function changeStatusTask($status,string $task_id)
    {
        try{
            $task = Task::findOrFail($task_id);
            $user_id = $this->getUserID();
            if($user_id === $task->to_assigned){
                $task->status = $status;
                if ($task->save()) {
                    return ['isSuccess' => true, 'message' => 'Task status updated successfully'];
                } else {
                    return ['isSuccess' => false, 'message' => 'Failed to update task status'];
                }
            } else {
                return ['isSuccess' => false, 'message' => 'Unauthorized to change the status of this task'];
            }
        }catch(Exception $e){
            throw new Exception('error occured!!');
        }
    }
}
?>
