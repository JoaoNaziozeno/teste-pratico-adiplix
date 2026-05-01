<?php

namespace Database\Factories;

use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        /*return [
            'title' => fake('pt_BR')->sentence(3),
            'description' => fake('pt_BR')->paragraph(),
            'status' => fake()->boolean(),
        ];*/

        return [
            'title' => fake()->randomElement([
                'Atualizar sistema',
                'Corrigir bug no login',
                'Criar API de tarefas',
                'Implementar autenticação',
                'Ajustar layout do dashboard',
                'Refatorar código',
                'Otimizar consultas no banco',
            ]),
    
            'description' => fake()->randomElement([
                'Necessário revisar funcionamento geral do sistema.',
                'Problema identificado durante testes.',
                'Melhoria solicitada pelo cliente.',
                'Ajustes para melhor performance.',
            ]),
    
            'status' => fake()->boolean(),
        ];
    }
}
