<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Task\AssignTaskRequest;
use App\Http\Requests\Task\FilterTaskRequest;
use App\Http\Requests\Task\TaskRequest;
use App\Http\Requests\Task\UpdateTaskRequest;
use App\Models\Task;
use App\Service\Task\TaskService;
use Exception;

class TaskManagementController extends Controller
{
    protected $taskService;
    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    /**
     * Summary of index
     * @param \App\Http\Requests\Task\FilterTaskRequest $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index(FilterTaskRequest $request)
    {
        $tasks = $this->taskService->filterTask($request);
        return response()->json([
            'data'=>$tasks
        ],200);
    }

    /**
     * Summary of store
     * @param \App\Http\Requests\Task\TaskRequest $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function store(TaskRequest $request)
    {
        try{
            $validatedData = $request->validated();
            $task = $this->taskService->create($validatedData);
            return response()->json([
                'status'=>'success',
                'message'=>'Task Created successfully',
                'data'=>[
                    'title'=>$task->title,
                    'description'=>$task->description,
                    'due_date'=>$task->due_date,
                    'priority'=>$task->priority,
                    'status'=>$task->status,
                ]
            ],201);
        }catch(Exception $e){
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create task: ' . $e->getMessage()
            ], 400);
        }
    }

    /**
     * Summary of show
     * @param string $id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function show(string $id)
    {
        $task = Task::findOrFail($id);
        return response()->json([
            'status'=>'success',
            'data'=>[
                'title'=>$task->title,
                'description'=>$task->description,
                'due_date'=>$task->due_date,
                'priority'=>$task->priority,
                'status'=>$task->status,
            ]
        ],201);
    }

    /**
     * Summary of update
     * @param \App\Http\Requests\Task\UpdateTaskRequest $request
     * @param string $id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function update(UpdateTaskRequest $request, string $id)
    {
        try{
            $validationData = $request->validated();
            $updatedTask = $this->taskService->update($validationData , $id);
            $updatedTask = Task::findOrFail($id);
            return response()->json([
                'status'=>'success',
                'message'=>'Task updated successfully',
                'data'=>[
                    'title'=>$updatedTask->title,
                    'description'=>$updatedTask->description,
                    'due_date'=>$updatedTask->due_date,
                    'priority'=>$updatedTask->priority,
                    'status'=>$updatedTask->status,
                ]
            ],200);
        }catch(Exception $e){
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update task: ' . $e->getMessage()
            ], 400);
        }
    }

    /**
     * Summary of destroy
     * Delete Soft Task
     * @param string $id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function destroy(string $id)
    {
        $this->taskService->delete($id);
        return response()->json([
            'status'=>'usccess',
            'message'=>'task soft deleted successfully'
        ],200);
    }
    /**
     * Summary of restore
     * Restore soft delete task
     * @param mixed $id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function restore($id){
        $restoredTask = $this->taskService->restoreTask($id);
        return response()->json([
            'status'=>'success',
            'message'=>'Task restored successfully',
        ],200);
    }
    /**
     * Summary of forceDelete
     * Permanently delete the task
     * @param mixed $id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function forceDelete($id){
        $this->taskService->forceDelete($id);
        return response()->json([
            'status'=>'success',
            'message'=>'Permanently delete the task successfully',
        ],200);
    }
    public function assign(AssignTaskRequest $assignTaskRequest,$task_id){
        $validationData = $assignTaskRequest->validated();
        $user_id = $validationData['user_id'];
        $this->taskService->assignTask($user_id,$task_id);
        return response()->json([
            'status'=>'success',
            'message'=>'Task assigned successfully'
        ],200);
    }
}
