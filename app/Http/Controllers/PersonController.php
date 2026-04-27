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
        $person = Person::create([
            'name' => $request->name,
            'email' => $request->email,
        ]);
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


    public function attachTask(Request $request, $personId)
    {
        $person = Person::findOrFail($personId);

        $person->tasks()->attach($request->task_id);

        return response()->json(['message' => 'Tarefa vinculada à pessoa com sucesso']);
    }

    public function detachTask(Request $request, $personId)
    {
        $person = Person::findOrFail($personId);

        $person->tasks()->detach($request->task_id);

        return response()->json(['message' => 'Tarefa desvinculada da pessoa com sucesso']);
    }
}
