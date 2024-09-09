<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrdinaryUser\changeStatusRequest;
use App\Service\OrdinaryUser\OrdinaryUserService;
use Illuminate\Http\Request;

class userController extends Controller
{
    protected $ordinaryUserService;
    public function __construct(OrdinaryUserService $ordinaryUserService){
        $this->ordinaryUserService = $ordinaryUserService;
    }
    public function index(Request $changeStatusRequest)
    {
        $allTask = $this->ordinaryUserService->filter($changeStatusRequest);
        return response()->json([
            'isSucess'=>true,
            'message'=>'all Task Assigned To you',
            'data'=>$allTask
        ], 200);
    }
    public function changeStatusOfTask(changeStatusRequest $changeStatusRequest,string $task_id){
        $validationData = $changeStatusRequest->validated();
        $status = $validationData['status'];

        $result = $this->ordinaryUserService->changeStatusTask($status , $task_id);
        if($result['isSuccess']) {
            return response()->json([
                'isSuccess' => true,
                'message' => $result['message']
            ], 200);
        }else{
            return response()->json([
                'isSuccess' => false,
                'message' => $result['message']
            ], 400);
        }
        }
}
