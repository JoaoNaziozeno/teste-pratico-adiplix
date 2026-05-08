<template>
  <div class="flex min-h-screen bg-[#F8FAFC] text-[#1E293B] font-['Inter']">
    <Sidebar />

    <div class="flex-1 ml-64">
      <Header />

      <main class="p-8 max-w-6xl mx-auto">
        
        <div class="flex items-center justify-between mb-8">
          <div>
            <div class="flex items-center gap-3 mb-1">
              <h1 class="text-2xl font-bold tracking-tight text-slate-900">{{ task.title }}</h1>
              <span 
                :class="task.status ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700'"
                class="text-[10px] px-2 py-0.5 rounded font-bold uppercase tracking-wide border border-current opacity-70"
              >
                {{ task.status ? 'Concluída' : 'Em Andamento' }}
              </span>
            </div>
            <p class="text-slate-500">{{ task.description }}</p>
          </div>
        </div>

        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
          <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
            <p class="text-sm font-medium text-slate-500 uppercase tracking-wider">Total de Responsáveis</p>
            <p class="text-3xl font-bold mt-2">{{ people.length }}</p>
          </div>
          <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm border-l-4 border-l-blue-500">
            <p class="text-sm font-medium text-slate-500 uppercase tracking-wider">Departamento Principal</p>
            <p class="text-xl font-bold mt-2 text-blue-600">Desenvolvimento</p> 
          </div>
          <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm border-l-4 border-l-purple-500">
            <p class="text-sm font-medium text-slate-500 uppercase tracking-wider">Prioridade</p>
            <p class="text-xl font-bold mt-2 text-purple-600 italic">Alta</p>
          </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
          <div class="lg:col-span-1">
            <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm sticky top-24">
              <h2 class="text-lg font-semibold mb-4">Adicionar Responsável</h2>
              
              <label class="block text-sm font-medium text-slate-700 mb-2">Selecione a pessoa</label>
              <select 
                v-model="selectedPeople" 
                multiple 
                class="w-full border border-slate-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500 focus:outline-none min-h-[200px] mb-4 text-sm"
              >
                <option v-for="person in allPeople" :key="person.id" :value="person.id" class="p-2 border-b border-slate-50 last:border-0">
                  {{ person.name }}
                </option>
              </select>

              <button 
                @click="assignPeople" 
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 rounded-lg transition-colors shadow-sm"
              >
                Vincular à Tarefa
              </button>
            </div>
          </div>

          <div class="lg:col-span-2">
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
              <div class="px-6 py-4 border-b border-slate-100">
                <h2 class="text-lg font-semibold text-slate-800">Equipe Designada</h2>
              </div>

              <div class="divide-y divide-slate-100">
                <div v-for="p in people" :key="p.id" class="p-6 hover:bg-slate-50/50 transition-colors flex items-center justify-between">
                  <div class="flex items-center gap-4">
                    <div class="h-10 w-10 rounded-full bg-slate-200 flex items-center justify-center text-slate-600 font-bold text-xs">
                      {{ p.name.charAt(0) }}
                    </div>
                    <div>
                      <div class="text-slate-900 font-semibold tracking-tight">{{ p.name }}</div>
                      <p class="text-sm text-slate-500">{{ p.email }}</p>
                    </div>
                  </div>

                  <button 
                    @click="removePerson(p.id)" 
                    class="text-slate-400 hover:text-red-600 p-2 hover:bg-red-50 rounded-lg transition-all"
                    title="Remover da Tarefa"
                  >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                  </button>
                </div>

                <div v-if="people.length === 0" class="p-12 text-center text-slate-400 text-sm italic">
                  Nenhuma pessoa vinculada a esta tarefa ainda.
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
      task: {},          
      people: [],        
      allPeople: [],     
      selectedPeople: [] 
    }
  },

  async mounted() {
    await this.loadData();
  },

  methods: {
      async loadData() {
      const id = this.$route.params.id;
      try {
        const taskRes = await api.get(`/tasks/${id}`);
        
        // Se o Laravel usar Resource, os dados vem dentro de .data.data
        // Se for retorno simples, vem em .data
        this.task = taskRes.data.data || taskRes.data;

        const peopleRes = await api.get(`/tasks/${id}/people`);
        this.people = peopleRes.data;

        const allPeopleRes = await api.get('/people');
        this.allPeople = allPeopleRes.data;
      } catch (error) {
        console.error("Erro ao carregar:", error);
      }
    },

    async assignPeople() {
      const id = this.$route.params.id;
      if (this.selectedPeople.length === 0) return;

      try {
        // Envia people_ids como array para o seu TaskController@attachPeople
        await api.post(`/tasks/${id}/people`, {
          people_ids: this.selectedPeople 
        });

        this.selectedPeople = []; 
        await this.loadData(); // Recarrega a lista para atualizar a tela
      } catch (error) {
        console.error("Erro ao vincular:", error);
        alert("Erro ao vincular pessoas.");
      }
    },

    async removePerson(personId) {
      const taskId = this.$route.params.id;

      if (confirm("Deseja remover esta pessoa desta tarefa?")) {
        try {
          // Como sua rota de DELETE não tem o ID da pessoa na URL, enviamos via 'data'
          await api.delete(`/tasks/${taskId}/people`, {
            data: { 
              people_ids: [personId] 
            }
          });
          
          // Atualização reativa na interface
          this.people = this.people.filter(p => p.id !== personId);
        } catch (error) {
          console.error("Erro ao remover:", error);
          alert("Não foi possível desvincular.");
        }
      }
    }
  }
}
</script>