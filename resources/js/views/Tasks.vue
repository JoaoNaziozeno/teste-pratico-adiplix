<template>
  <div class="flex min-h-screen bg-[#f9f9ff] text-[#191b23] font-['Inter']">

    <Sidebar />

    <div class="ml-64 min-h-screen flex flex-col flex-1">
      <Header />

      <main class="mt-16 p-6 flex-1 bg-background">
        <div class="max-w-7xl mx-auto">
          
          <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-6">
            <div>
              <h2 class="text-3xl font-bold text-on-surface">Gerenciar Tarefas</h2>
              <p class="text-base text-on-surface-variant">Gerencie suas tarefas e pessoas.</p>
            </div>
            
            <BaseButton @click="showModal = true" icon="add">
              Adicionar Tarefa
            </BaseButton>
          </div>

          <div class="bg-white border border-outline-variant rounded-xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
              <table class="w-full text-left border-collapse">
                <thead>
                  <tr class="bg-gray-50 border-b border-outline-variant">
                    <th class="px-6 py-4 text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Título</th>
                    <th class="px-6 py-4 text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Descrição</th>
                    <th class="px-6 py-4 text-xs font-semibold text-on-surface-variant uppercase tracking-wider text-right">Ações</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant">
                  <tr v-for="task in tasks" :key="task.id" class="hover:bg-gray-50 transition-colors group">
                    <td class="px-6 py-5">
                      <div class="flex items-center gap-3">
                        <div 
                          :class="task.status ? 'bg-secondary' : 'bg-tertiary-container'" 
                          class="w-2.5 h-2.5 rounded-full"
                        ></div>
                        <span class="text-sm font-medium">{{ task.status ? 'Concluída' : 'Pendente' }}</span>
                      </div>
                    </td>
                    <td class="px-6 py-5">
                      <span class="text-sm font-semibold text-on-surface">{{ task.title }}</span>
                    </td>
                    <td class="px-6 py-5 text-sm text-on-surface-variant max-w-xs truncate">
                      {{ task.description }}
                    </td>
                    <td class="px-6 py-5">
                      <div class="flex items-center justify-end gap-2">
                        <button 
                          @click="editTask(task)"
                          class="p-2 text-on-surface-variant hover:text-primary hover:bg-blue-50 rounded-lg transition-all" 
                          title="Editar"
                        >
                          <span class="material-symbols-outlined text-xl">edit</span>
                        </button>
                        <button 
                          @click="deleteTask(task.id)"
                          class="p-2 text-on-surface-variant hover:text-error hover:bg-red-50 rounded-lg transition-all" 
                          title="Excluir"
                        >
                          <span class="material-symbols-outlined text-xl">delete</span>
                        </button>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </main>
    </div>
    
    <div v-if="showModal || editingId" class="fixed inset-0 z-[60] flex items-center justify-center p-6 bg-black/40 backdrop-blur-sm">
      <div class="bg-white w-full max-w-md rounded-xl shadow-xl border border-gray-200 overflow-hidden">
        
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
          <h3 class="text-xl font-bold text-blue-600">{{ editingId ? 'Editar Tarefa' : 'Criar Nova Tarefa' }}</h3>
          <button @click="closeModal" class="text-gray-400 hover:text-gray-600 transition-colors">
            <span class="material-symbols-outlined">close</span>
          </button>
        </div>
        
        <div class="p-6 space-y-4">
          <BaseInput 
            label="Título da Tarefa" 
            v-model="title" 
            placeholder="Ex: Reunião de Planejamento" 
          />

          <BaseInput 
            label="Descrição" 
            v-model="description" 
            type="textarea" 
            placeholder="Detalhes da tarefa..." 
            rows="3" 
          />

          <BaseMultiSelect 
            label="Vincular Pessoas"
            :options="people"
            v-model="selectedPeople"
            placeholder="Selecione os responsáveis"
          />

          <div class="flex items-center gap-2 py-2">
            <input type="checkbox" v-model="status" id="status" class="w-4 h-4 rounded border-gray-300 text-[#0058be] focus:ring-[#0058be]" />
            <label for="status" class="text-sm font-medium text-[#424754]">Marcar como concluída</label>
          </div>
        </div>

        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-end gap-3">
          <button @click="closeModal" class="px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-100 rounded-lg transition-all">
            Cancelar
          </button>
          <BaseButton @click="createTask" icon="check">
            {{ editingId ? 'Salvar Alterações' : 'Criar Tarefa' }}
          </BaseButton>
        </div>
      </div>
    </div>

  </div>
</template>

<script>
import api from '../services/api';
import Sidebar from '../components/Sidebar.vue';
import Header from '../components/Header.vue';
import BaseButton from '../components/BaseButton.vue';
import BaseInput from '../components/BaseInput.vue';
import BaseMultiSelect from '../components/BaseMultiSelect.vue';

export default {
  components: { Sidebar, Header, BaseButton, BaseInput, BaseMultiSelect },

  data() {
    return {
      tasks: [],
      title: '',
      description: '',
      status: false,
      editingId: null,
      showModal: false,
      people:[],
      selectedPeople: []
    }
  },

  async mounted() {
    this.getTasks();
    this.loadPeople();
  },

  methods: {
    async getTasks() {
      const res = await api.get('/tasks');
      this.tasks = res.data;
    },

    async loadPeople() {
      const res = await api.get('/people');
      this.people = res.data;
    },

    async createTask() {
      const payload = {
        title: this.title,
        description: this.description,
        status: this.status,
      };

      if (this.editingId) {
        await api.put(`/tasks/${this.editingId}`, payload);
      } else {
        await api.post('/tasks', payload);
      }

      this.closeModal();
      this.getTasks();
    },

    editTask(task) {
      this.title = task.title;
      this.description = task.description;
      this.status = task.status;
      this.editingId = task.id;
      this.showModal = true;
      this.selectedPeople = task.user ? task.user.map(u => u.id) : [];
    },

    async deleteTask(id) {
      if(confirm("Deseja realmente excluir esta tarefa?")) {
        await api.delete(`/tasks/${id}`);
        this.tasks = this.tasks.filter(t => t.id !== id);
      }
    },

    closeModal() {
      this.showModal = false;
      this.editingId = null;
      this.resetForm();
    },

    resetForm() {
      this.title = '';
      this.description = '';
      this.status = false;
    }
  }
}
</script>

<style scoped>
.material-symbols-outlined {
  font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
}
</style>