<template>
  <div class="bg-background text-on-background min-h-screen flex">
    <!-- Reutilizando seus componentes existentes -->
    <Sidebar />

    <div class="ml-64 min-h-screen flex flex-col flex-1">
      <Header />

      <!-- Page Content -->
      <main class="mt-16 p-6 flex-1 bg-background">
        <div class="max-w-7xl mx-auto">
          
          <!-- Page Header Actions -->
          <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-6">
            <div>
              <h2 class="text-3xl font-bold text-on-surface">Gerenciamento de Tarefas</h2>
              <p class="text-base text-on-surface-variant">Monitore e organize o fluxo de trabalho da sua organização.</p>
            </div>
            
            <!-- Botão para abrir formulário/modal -->
            <button 
              @click="showModal = true"
              class="inline-flex items-center gap-2 bg-primary text-white px-6 py-2.5 rounded-lg font-medium hover:opacity-90 active:scale-95 transition-all shadow-sm"
            >
              <span class="material-symbols-outlined">add</span>
              Nova Tarefa
            </button>
          </div>

          <!-- Main Data Table Container -->
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

    <!-- Modal Form (Substituindo o form antigo) -->
    <div v-if="showModal || editingId" class="fixed inset-0 z-[60] flex items-center justify-center p-6 bg-black/40 backdrop-blur-sm">
      <div class="bg-white w-full max-w-md rounded-xl shadow-xl border border-outline-variant overflow-hidden">
        <div class="px-6 py-4 border-b border-outline-variant flex items-center justify-between">
          <h3 class="text-xl font-bold text-on-surface">{{ editingId ? 'Editar Tarefa' : 'Criar Nova Tarefa' }}</h3>
          <button @click="closeModal" class="text-on-surface-variant hover:text-on-surface">
            <span class="material-symbols-outlined">close</span>
          </button>
        </div>
        
        <div class="p-6 space-y-4">
          <div class="space-y-1.5">
            <label class="text-sm font-medium text-on-surface">Título da Tarefa</label>
            <input 
              v-model="title"
              class="w-full px-4 py-2 border border-outline-variant rounded-lg text-sm focus:ring-2 focus:ring-primary outline-none" 
              placeholder="Ex: Reunião de Planejamento" 
              type="text"
            />
          </div>
          <div class="space-y-1.5">
            <label class="text-sm font-medium text-on-surface">Descrição</label>
            <textarea 
              v-model="description"
              class="w-full px-4 py-2 border border-outline-variant rounded-lg text-sm focus:ring-2 focus:ring-primary outline-none" 
              placeholder="Detalhes da tarefa..." 
              rows="3"
            ></textarea>
          </div>
          <div class="flex items-center gap-2">
            <input type="checkbox" v-model="status" id="status" class="rounded border-outline-variant text-primary focus:ring-primary" />
            <label for="status" class="text-sm font-medium text-on-surface">Marcar como concluída</label>
          </div>
        </div>

        <div class="px-6 py-4 bg-gray-50 border-t border-outline-variant flex items-center justify-end gap-3">
          <button @click="closeModal" class="px-4 py-2 text-on-surface-variant font-medium hover:bg-gray-100 rounded-lg transition-all">Cancelar</button>
          <button @click="createTask" class="px-6 py-2 bg-primary text-white font-medium rounded-lg hover:opacity-90 transition-all shadow-sm">
            {{ editingId ? 'Salvar Alterações' : 'Criar Tarefa' }}
          </button>
        </div>
      </div>
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
      tasks: [],
      title: '',
      description: '',
      status: false,
      editingId: null,
      showModal: false // Controle de visibilidade do modal
    }
  },

  async mounted() {
    this.getTasks();
  },

  methods: {
    async getTasks() {
      const res = await api.get('/tasks');
      this.tasks = res.data;
    },

    async createTask() {
      const payload = {
        title: this.title,
        description: this.description,
        status: this.status
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
/* Importando ícones caso não estejam no global */
.material-symbols-outlined {
  font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
}
</style>