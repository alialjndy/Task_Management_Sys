<?php
namespace App\Service\Task;

use App\Models\Task;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class TaskService{
    /**
     * Summary of filterTask
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function filterTask(Request $request)
    {
        $query = Task::query();


        $query->when($request->filled('priority'), function ($query) use ($request) {
            $query->priority($request->input('priority'));
        });

        $query->when($request->filled('status'), function ($query) use ($request) {
            $query->status($request->input('status'));
        });


        return $query->get();

    }
    /**
     * Summary of create
     * @param array $data
     * @throws \Exception
     * @return
     */
    public function create(array $data)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $user_id = $user->id;
        // Get the ID of the currently logged-in user
       if($user_id == $data['manager_id']){
           $task = Task::create($data);
           return $task;
        }else{
            throw new Exception('Task creation failed: Unauthorized manager ID');
        }

    }
    /**
     * Summary of update
     * @param array $data
     * @param string $id
     * @throws \Exception
     * @return mixed
     */
    public function update(array $data , string $id)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $user_id = $user->id;

        $task = Task::findOrFail($id);
        // أذا كان الشخص الذي أضاف هذا التاسك هو بدور أدمن
        if($task->manager->role === 'admin'){
            // اختبار هل هذا الأدمن الحالي هو نفسه الأدمن الذي أضاف التاسك
            if($user_id === $task->manager_id){
                $updatedTask = $task->update($data);
                return $updatedTask;
            }else{
            throw new Exception('Task Updated failed: Unauthorized manager ID');
            }
            // اختبار فيما اذا كان الشخص الذي يحاول الحذف هو أدمن وتأكدنا مسبقا أن الشخص الذي أضاف هذه التاسك ليس أدمن
        }else if ($user->role === 'admin'){
                $updatedTask = $task->update($data);
                return $updatedTask;
                // في حال الشخص الذي يحاول الحذف ليس أدمن بالتالي سوف نختبر أن هذه التاسك تنتمي إليه
        }else if($task->manager_id === $user_id){
                $updatedTask = $task->update($data);
                return $updatedTask;
        }else{
            throw new Exception('Task Updated failed: Unauthorized manager ID');

        }
    }
    /**
     * Summary of delete
     * @param string $id
     * @return void
     */
    public function delete(string $id)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $user_id = $user->id;

        $task = Task::findOrFail($id);
        // أذا كان الشخص الذي أضاف هذا التاسك هو بدور أدمن
        if($task->manager->role === 'admin'){
            // اختبار هل هذا الأدمن الحالي هو نفسه الأدمن الذي أضاف التاسك
            if($user_id === $task->manager_id){
                $task->delete();
            }else{
            throw new Exception('Task Delete failed: Unauthorized manager ID');
            }
            // اختبار فيما اذا كان الشخص الذي يحاول الحذف هو أدمن وتأكدنا مسبقا أن الشخص الذي أضاف هذه التاسك ليس أدمن
        }else if ($user->role === 'admin'){
                $task->delete();
                // في حال الشخص الذي يحاول الحذف ليس أدمن بالتالي سوف نختبر أن هذه التاسك تنتمي إليه
        }else if($task->manager_id === $user_id){
                $task->delete();
        }else{
            throw new Exception('Task Delete failed: Unauthorized manager ID');

        }


    }
    /**
     * Summary of restoreTask
     * @param mixed $id
     * @return mixed||\Illuminate\Database\Eloquent\Collection
     */
    public function restoreTask(string $id){
        try{
            $user = JWTAuth::parseToken()->authenticate();
            $user_id = $user->id ;
            $restoreTask = Task::withTrashed()->findOrFail($id);
            if($user_id === $restoreTask->id){
                $restoreTask->restore();
                return $restoreTask ;
            }
        }catch(Exception $e){
            throw new exception(403,'You can not restore this task because you are not who delete it');
        }
    }
    /**
     * Summary of forceDelete
     * @param mixed $id
     * @return void
     */
    public function forceDelete($id){
        try{
            $user = JWTAuth::parseToken()->authenticate();
            $user_id = $user->id ;
            $task = Task::withTrashed()->findOrFail($id);

            if($user_id === $task->manager_id){
                $task->forceDelete();
            }
        }catch(Exception $e){
            throw new exception(403,'You can not delete this task because you are not who create it');
        }
    }
    /**
     * Summary of assignTask
     * @param mixed $user_id
     * @param mixed $task_id
     * @return void
     */
    public function assignTask($user_id , $task_id)
    {
        try{
            $user = User::findOrFail($user_id);
            if($user->role === 'admin' || $user->role === 'manager'){
                throw new Exception('A task cannot be assigned to this person because you have the authority of a manager');
            }
            $task = Task::findOrFail($task_id);
            $task->to_assigned = $user_id ;
            $task->status = 'in_progress';
            $task->save();
        }catch(Exception $e){

        }
    }
    public function getSoftDeleteTasks(){
        $tasks = Task::withTrashed()->get();
        return $tasks;
    }
    public function getAllTaskAssignedToUser($user_id){
        $user = User::findOrFail($user_id);
        $tasks = $user->tasks()->get();
        return $tasks ;
    }
}
?>
