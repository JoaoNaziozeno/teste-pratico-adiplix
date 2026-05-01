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
        ]);
        $person = Person::findOrFail($id);

        $person->update($request->only(['name', 'email']));

        return response()->json($person);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Person::destroy($id);

        return response()->json(['message' => 'Pessoa excluída com sucesso']);
    }

    public function attachTask(Request $request, int $personId)
    {   
        $request->validate([
            'task_ids' => 'required|array',
            'task_ids.*' => 'integer|exists:tasks,id',
        ]);

        $person = Person::findOrFail($personId);

        $person->tasks()->syncWithoutDetaching($request->task_ids);

        return response()->json([
            'status' => 'sucesso',
            'message' => 'Tarefa(s) vinculada(s) à pessoa com sucesso',
            'vinculados' => $request->task_ids,
            ]);
    }

    public function detachTask(int $personId, int $taskId)
    {
        $person = Person::findOrFail($personId);

        $person->tasks()->detach($taskId);

        return response()->json(['message' => 'Tarefa desvinculada da pessoa com sucesso']);
    }
}
