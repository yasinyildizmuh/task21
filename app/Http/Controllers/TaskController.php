<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$tasks = Auth::user()->tasks; tasklerin atanan kişileri dönmesi gerekirse
        $tasks = Task::all();
        return response()->json(["status" => "success", "error" => false, "count" => count($tasks), "data" => $tasks],200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "title" => "required"
        ]);

        if($validator->fails()) {
            return response()->json(["status" => "error", "validation_errors" => $validator->errors(),"errors"=>['title'=>$validator->errors()]],422);
        }

        try {
            $task = Task::create([
                "title" => $request->title,
                "description" => $request->description,
                "status" => $request->status,
                "user_id" => Auth::user()->id
            ]);
            return response()->json(["status" => "success", "error" => false, "message" => "Success! task created.","data" => $task], 201);
        }
        catch(Exception $exception) {
            return response()->json(["status" => "failed", "error" => $exception->getMessage()], 404);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $task = Task::find($id);

        if($task) {
            return response()->json(["status" => "success", "error" => false, "data" => $task], 200);
        }
        return response()->json(["status" => "failed", "error" => true, "message" => "Failed! no task found."], 422);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();

        $task = Task::find($id);
        $task->update($data);

        $response = [
            "status" => "success",
            "data" => $task
        ];

        return response()->json($response, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $task = Task::find($id);
        if($task){
            $task->delete();

            $response = [
                "status" => "success",
                "error" => false,
                "data" => $task
            ];

            return response()->json($response, 200);
        }else{
            $response = [
                "status" => "error",
                "error" => true,
                "data" => $task
            ];
            return response()->json($response, 404);
        }

    }
}
