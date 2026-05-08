<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Person;

class PersonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
        return response()->json(
            Person::with('tasks')->get()
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
            'email' => 'required|email|unique:people',
            'name' => 'required|string|max:255',
        ]);

        $person = Person::create($request->only(['name', 'email'])); 

        return response()->json($person);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return response()->json(
            Person::with('tasks')->findOrFail($id)
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
            'email' => 'required|email|unique:people,email,' . $id,
            'name' => 'required|string|max:255',
            'task_ids' => 'nullable|array',
        ]);

        $person = Person::findOrFail($id);

        $person->update($request->only(['name', 'email']));

        if ($request->has('task_ids')) {
            $person->tasks()->sync($request->task_ids);
        }

        return response()->json($person->load('tasks'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        $person = Person::findOrFail($id);

        if ($person->tasks()->exists()) {

            if (!$request->has('force') || $request->force != true) {
                return response()->json([
                    'status' => 'Atençao',
                    'message' => 'Esta pessoa possui tarefas vinculadas. Deseja desvincular tudo e apagar mesmo assim?',
                    'requires_confirmation' => true,
                    'linked_count' => $person->tasks()->count()
                ], 422);
            }

            $person->tasks()->detach();
        }

        $person->delete();

        return response()->json([
            'status' => 'Sucesso',
            'message' => 'Pessoa e seus vínculos foram removidos com sucesso.'
        ]);
    }

    public function listTasks(Person $person)
    {
        return response()->json($person->tasks);
    }

    public function attachTask(Request $request, Person $person)
    {   
        $request->validate([
            'task_ids' => 'required|array',
            'task_ids.*' => 'integer|exists:tasks,id',
        ]);

        $person->tasks()->syncWithoutDetaching($request->task_ids);

        return response()->json([
            'status' => 'Sucesso',
            'message' => 'Tarefa(s) vinculada(s) com sucesso',
            'vinculados' => $request->task_ids,
        ]);
    }

    public function detachTask(Person $person, int $taskId)
    {
        $person->tasks()->detach($taskId);

        return response()->json([
            'status' => 'Sucesso',
            'message' => 'Tarefa desvinculada com sucesso',
        ]);
    }
}
