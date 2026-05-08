<template>
  <div class="flex min-h-screen bg-[#F8FAFC] text-[#1E293B] font-['Inter']">
    <Sidebar />

    <div class="flex-1 ml-64">
      <Header />

      <main class="p-8 max-w-6xl mx-auto">
        <!-- 👤 HEADER DA PESSOA -->
        <div class="flex items-center justify-between mb-8">
          <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">{{ person.name }}</h1>
            <p class="text-slate-500 mt-1">{{ person.email }}</p>
          </div>
          <div class="flex gap-3">
            <span class="px-3 py-1 bg-blue-50 text-blue-600 rounded-full text-sm font-medium border border-blue-100">
              ID: #{{ $route.params.id }}
            </span>
          </div>
        </div>

        <!-- 📊 CARDS DE RESUMO -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
          <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
            <p class="text-sm font-medium text-slate-500 uppercase tracking-wider">Total de Tarefas</p>
            <p class="text-3xl font-bold mt-2">{{ tasks.length }}</p>
          </div>
          <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm border-l-4 border-l-green-500">
            <p class="text-sm font-medium text-slate-500 uppercase tracking-wider">Concluídas</p>
            <p class="text-3xl font-bold mt-2 text-green-600">{{ completedTasks }}</p>
          </div>
          <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm border-l-4 border-l-amber-500">
            <p class="text-sm font-medium text-slate-500 uppercase tracking-wider">Pendentes</p>
            <p class="text-3xl font-bold mt-2 text-amber-600">{{ pendingTasks }}</p>
          </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
          <!-- ➕ ATRIBUIR TAREFAS (Coluna Menor) -->
          <div class="lg:col-span-1">
            <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm sticky top-24">
              <h2 class="text-lg font-semibold mb-4">Nova Atribuição</h2>
              
              <label class="block text-sm font-medium text-slate-700 mb-2">Selecione as tarefas</label>
              <select 
                v-model="selectedTasks" 
                multiple 
                class="w-full border border-slate-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500 focus:outline-none min-h-[200px] mb-4 text-sm"
              >
                <option v-for="task in allTasks" :key="task.id" :value="task.id" class="p-2 border-b border-slate-50 last:border-0">
                  {{ task.title }}
                </option>
              </select>

              <button 
                @click="assignTasks" 
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 rounded-lg transition-colors shadow-sm"
              >
                Confirmar Atribuição
              </button>
            </div>
          </div>

          <!-- 📋 LISTA DE TAREFAS (Coluna Maior) -->
          <div class="lg:col-span-2">
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
              <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center">
                <h2 class="text-lg font-semibold text-slate-800">Tarefas em Andamento</h2>
              </div>

              <div class="divide-y divide-slate-100">
                <div v-for="task in tasks" :key="task.id" class="p-6 hover:bg-slate-50/50 transition-colors flex items-center justify-between">
                  <div class="flex-1">
                    <div class="flex items-center gap-3 mb-1">
                      <span class="text-slate-900 font-semibold tracking-tight">{{ task.title }}</span>
                      <span 
                        :class="task.status ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700'"
                        class="text-[10px] px-2 py-0.5 rounded font-bold uppercase tracking-wide"
                      >
                        {{ task.status ? 'Concluída' : 'Pendente' }}
                      </span>
                    </div>
                    <p class="text-sm text-slate-500 max-w-md">{{ task.description }}</p>
                  </div>

                  <button 
                    @click="removeTask(task.id)" 
                    class="ml-4 text-slate-400 hover:text-red-600 p-2 hover:bg-red-50 rounded-lg transition-all"
                    title="Remover Tarefa"
                  >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                  </button>
                </div>

                <div v-if="tasks.length === 0" class="p-12 text-center text-slate-400">
                  Nenhuma tarefa atribuída a esta pessoa.
                </div>
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>
</template>

<script>
import api from '../services/api';
import Sidebar from '../components/Sidebar.vue';
import Header from '../components/Header.vue';

export default {
  components: { Sidebar, Header },

  data() {
    return {
      person: {},
      tasks: [],
      allTasks: [],
      selectedTasks: []
    }
  },

  computed: {
    completedTasks() {
      return this.tasks.filter(t => t.status).length;
    },
    pendingTasks() {
      return this.tasks.filter(t => !t.status).length;
    }
  },

  async mounted() {
    this.loadData();
  },

  methods: {
    async loadData() {
      const id = this.$route.params.id;

      // 👤 pessoa
      const personRes = await api.get(`/people/${id}`);
      this.person = personRes.data;

      // 📋 tarefas da pessoa
      const tasksRes = await api.get(`/people/${id}/tasks`);
      this.tasks = tasksRes.data;

      // 📦 todas tarefas
      const allTasksRes = await api.get('/tasks');
      this.allTasks = allTasksRes.data;
    },

    async assignTasks() {
      const id = this.$route.params.id;

      await api.post(`/people/${id}/tasks`, {
        tasks: this.selectedTasks
      });

      this.loadData();
      this.selectedTasks = [];
    },

    async removeTask(taskId) {
      const id = this.$route.params.id;

      await api.delete(`/people/${id}/tasks/${taskId}`);

      this.tasks = this.tasks.filter(t => t.id !== taskId);
    }
  }
}
</script>