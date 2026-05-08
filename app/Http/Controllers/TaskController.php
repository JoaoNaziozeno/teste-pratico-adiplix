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
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'boolean',
            'people_ids' => 'nullable|array',
            'people_ids.*' => 'integer|exists:people,id',
        ]);

        $task = Task::create($request->only(['title', 'description', 'status']));

        if ($request->has('people_ids')) {
            $task->people()->sync($request->people_ids);
        }

        return response()->json($task->load('people')); // Retorna com os vínculos para o Vue ver
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
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'boolean',
            'people_ids' => 'nullable|array',
        ]);

        $task = Task::findOrFail($id);

        $task->update([
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status ?? false,
        ]);

        if ($request->has('people_ids')) {
            $task->people()->sync($request->people_ids);
        }

        return response()->json($task->load('people'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        $task = Task::findOrFail($id);

        if ($task->people()->exists()) {

            if (!$request->has('force') || $request->force != true) {
                return response()->json([
                    'status' => 'attention',
                    'message' => 'Esta tarefa possui pessoas vinculadas. Deseja desvincular tudo e apagar mesmo assim?',
                    'requires_confirmation' => true, 
                    'linked_count' => $task->people()->count()
                ], 422);
            }

            $task->people()->detach();
        }

        $task->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Tarefa e seus vínculos foram removidos com sucesso.'
        ]);
    }

    public function listPeople(Task $task)
    {
        return response()->json($task->people);
    }

    public function attachPeople(Request $request, Task $task)
    {   
        $request->validate([
            'people_ids' => 'required|array',
            'people_ids.*' => 'integer|exists:people,id',
        ]);
        
        $task->people()->syncWithoutDetaching($request->people_ids);
        
        return response()->json([
            'status' => 'sucesso',
            'message' => 'Pessoa(s) vinculada(s) à tarefa com sucesso',
            'vinculados' => $request->people_ids,
            ]);
    }

    public function detachPeople(Task $task, int $personId)
    {
        $task->people()->detach($personId);

        return response()->json(['message' => 'Pessoa(s) desvinculada(s) da tarefa com sucesso']);
    }

}
