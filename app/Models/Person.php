<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Testing\Fluent\Concerns\Has;

class Person extends Model
{
    use HasFactory;

    protected $fillable = ['name','email'];

    public function tasks()
    {
        return $this->belongsToMany(Task::class, 'people_task');
    }
}
