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
        $request->validate([                            // validação dos campos para garantir que os dados sejam corretos antes de criar a tarefa
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'boolean',
        ]);

        $task = Task::create($request->only(['title', 'description', 'status'])); // cria a tarefa usando os dados validados do request

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

    public function attachPeople(Request $request, int $taskId)
    {   
        $request->validate([
            'people_ids' => 'required|array',
            'people_ids.*' => 'integer|exists:people,id',
        ]);

        $task = Task::findOrFail($taskId);
        
        $task->people()->syncWithoutDetaching($request->people_ids);
        
        return response()->json([
            'status' => 'sucesso',
            'message' => 'Pessoa(s) vinculada(s) à tarefa com sucesso',
            'vinculados' => $request->people_ids,
            ]);
    }

    public function detachPeople(int $taskId, int $personId)
    {
        $task = Task::findOrFail($taskId);

        $task->people()->detach($personId);

        return response()->json(['message' => 'Pessoa(s) desvinculada(s) da tarefa com sucesso']);
    }

}
