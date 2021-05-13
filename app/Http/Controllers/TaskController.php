<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddTaskRequest;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TaskController extends Controller
{
    public function tasks()
    {
        return response()->json(Auth::user()->tasks()->latest()->get(), 201);
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

        return response()->json([
            'success' => "Nouvelle tache enregistrée !"
        ], 201);
    }
}
