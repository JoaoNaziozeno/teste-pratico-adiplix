<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(
            Task::with('people')->get()
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $task = Task::create($request->only([
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status ?? false,
        ]));

        return response()->json($task);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return response()->json(
            Task::with('people')->findOrFail($id)
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $task = Task::findOrFail($id);

        $task->update([
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status ?? false,
        ]);

        return response()->json($task);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Task::destroy($id);

        return response()->json(['message' => 'Tarefa deletada com sucesso']);
    }


    public function attachPerson(Request $request, $taskId)
    {
        $task = Task::findOrFail($taskId);

        $task->people()->attach($request->person_id);

        return response()->json(['message' => 'Pessoa vinculada à tarefa com sucesso']);
    }

    public function detachPerson(Request $request, $taskId)
    {
        $task = Task::findOrFail($taskId);

        $task->people()->detach($request->person_id);

        return response()->json(['message' => 'Pessoa desvinculada da tarefa com sucesso']);
    }

}
