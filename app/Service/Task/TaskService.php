<?php
namespace App\Service\Task;

use App\Http\Requests\Task\AssignTaskRequest;
use App\Http\Requests\Task\UpdateTaskRequest;
use App\Models\Task;
use App\Models\User;
use Exception;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth as FacadesAuth;
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
        if($user_id === $task->manager_id){
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
        $task = Task::findOrFail($id);
        $task->delete();
    }
    /**
     * Summary of restoreTask
     * @param mixed $id
     * @return mixed||\Illuminate\Database\Eloquent\Collection
     */
    public function restoreTask($id){
        $restoreTask = Task::withTrashed()->findOrFail($id);
        $restoreTask->restore();
        return $restoreTask ;
    }
    /**
     * Summary of forceDelete
     * @param mixed $id
     * @return void
     */
    public function forceDelete($id){
        $task = Task::withTrashed()->findOrFail($id);
        $task->forceDelete();
    }
    /**
     * Summary of assignTask
     * @param mixed $user_id
     * @param mixed $task_id
     * @return void
     */
    public function assignTask($user_id , $task_id)
    {
        // $user_id = $request->user_id ;
        $task = Task::findOrFail($task_id);
        $task->to_assigned = $user_id ;
        $task->status = 'in_progress';
        $task->save();
    }
}
?>
