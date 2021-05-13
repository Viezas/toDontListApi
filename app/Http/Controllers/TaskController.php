<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TaskController extends Controller
{
    public function tasks()
    {
        return response()->json(Auth::user()->tasks()->latest()->get(), 201);
    }

    public function task(int $id)
    {
        $task = Task::where('id', $id)->where('user_id', Auth::id())->get();

        if(!$task)
        {
            return response()->json(["error" => "La tache n'existe pas !"], 404);
        }
        return response()->json([
            'created_at' => $task[0]->created_at,
            'updated_at' => $task[0]->updated_at,
            'body' => $task[0]->body,
            'done' => $task[0]->done,
        ], 200);
    }

    public function add(AddTaskRequest $request)
    {
        Task::create([
            'body' => $request->body,
            'done' => false,
            'user_id' => Auth::id(),
            'created_at' => Carbon::now()->toDateTimeString(),
            'updated_at' => Carbon::now()->toDateTimeString()
        ]);

        return response()->json(['success' => "Nouvelle tache enregistrée !"], 201);
    }

    public function update(UpdateTaskRequest $request, int $id)
    {
        $task = Task::where('id', $id)->where('user_id', Auth::id())->update(['body' => $request->body, 'done' => $request->done]);

        if(!$task)
        {
            return response()->json(['error' => "La tache n'existe pas !"], 404);
        }

        return response()->json(['success' => "La tache a bien été modifée !"], 200);
    }

    public function delete(int $id)
    {
        $task = Task::where('id', $id)->where('user_id', Auth::id())->delete();

        if(!$task)
        {
            return response()->json(['error' => "La tache n'existe pas !"], 404);
        }

        return response()->json(['success' => "La tache a bien été effacée !"], 200);
    }
}
