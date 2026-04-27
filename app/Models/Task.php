<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = ['title','description','status',];

    public function people()
    {
        return $this->belongsToMany(Person::class);
    }

    /*public function getStatusAttribute()
    {
        return $this->attributes['status'] ? 'Concluída' : 'Pendente'; // isso servirá para retornar o status como 'Concluída' ou 'Pendente' em vez de true/false
    }*/

}
