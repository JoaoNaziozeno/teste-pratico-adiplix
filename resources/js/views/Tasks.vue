<template>
  <div class="flex min-h-screen bg-[#f9f9ff] text-[#191b23] font-['Inter']">

    <Sidebar />

    <div class="ml-64 min-h-screen flex flex-col flex-1">
      <Header />

      <main class="p-6 flex-1 bg-background">
        <div class="max-w-7xl mx-auto">
          
          <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-6">
            <div>
              <h2 class="text-3xl font-bold text-on-surface">Gerenciar Tarefas</h2>
              <p class="text-base text-on-surface-variant">Gerencie as tarefas e vincule  as pessoas.</p>
            </div>
            
            <BaseButton @click="showModal = true" icon="add">
              Adicionar Tarefa
            </BaseButton>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-blue-100 p-4 rounded-xl shadow-sm border border-gray-100">
              <p class="text-[10px] text-[#424754] uppercase font-bold mb-2">Total de tarefas</p>
              <span class="text-2xl font-bold">{{ tasks.length }}</span>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
          <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
              <thead>
                <tr class="border-b border-gray-100 bg-gray-50/50">
                  <th class="px-6 py-4 text-xs font-bold text-[#424754] uppercase tracking-wider">ID</th>
                  <th class="px-6 py-4 text-xs font-bold text-[#424754] uppercase tracking-wider">Status</th>
                  <th class="px-6 py-4 text-xs font-bold text-[#424754] uppercase tracking-wider">Título</th>
                  <th class="px-6 py-4 text-xs font-bold text-[#424754] uppercase tracking-wider">Descrição</th>
                  <th class="px-6 py-4 text-xs font-bold text-[#424754] uppercase tracking-wider text-right">Ações</th>
                </tr>
              </thead>
              
              <tbody class="divide-y divide-gray-50">
                <tr v-for="task in tasks" :key="task.id" class="hover:bg-gray-50 transition-colors group">
                  <td class="px-6 py-4 text-sm text-[#424754]">{{ task.id }}</td>
                  
                  <td class="px-6 py-4">
                    <div class="flex items-center gap-2">
                      <div 
                        :class="task.status ? 'bg-[#00a86b]' : 'bg-amber-500'" 
                        class="w-2 h-2 rounded-full"
                      ></div>
                      <span class="text-sm text-[#424754]">{{ task.status ? 'Concluída' : 'Pendente' }}</span>
                    </div>
                  </td>

                  <td class="px-6 py-4">
                    <span class="font-semibold text-sm text-[#191b23]">{{ task.title }}</span>
                  </td>

                  <td class="px-6 py-4 text-sm text-[#424754] max-w-xs truncate">
                    {{ task.description }}
                  </td>

                  <td class="px-6 py-4 text-right">
                    <div class="flex items-center justify-end gap-2">
                      <button @click="editTask(task)" class="p-2 text-[#424754] hover:text-[#0058be] hover:bg-blue-50 rounded-lg transition-all">
                        <span class="material-symbols-outlined text-[20px]">edit</span>
                      </button>
                        
                      <button @click="viewTaskDetails(task.id)" class="p-2 text-[#424754] hover:text-[#0058be] hover:bg-blue-50 rounded-lg transition-all" title="Ver Detalhes da Tarefa">
                        <span class="material-symbols-outlined text-sm">visibility</span>
                      </button>
                      
                      <button @click="deleteTask(task.id)" class="p-2 text-[#424754] hover:text-[#ba1a1a] hover:bg-red-50 rounded-lg transition-all">
                        <span class="material-symbols-outlined text-[20px]">delete</span>
                      </button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
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
          <BaseButton @click="saveTask" icon="check">
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

      // Método unificado para Salvar (Novo ou Edição)
      async saveTask() {
        if (!this.title) {
          alert("Por favor, preencha o título.");
          return;
        }

        // LIMPEZA DOS IDS:
        // Se o select devolver objetos, extraímos apenas o ID. 
        // Se devolver apenas o ID, mantemos o valor.
        const cleanPeopleIds = this.selectedPeople.map(p => {
          return typeof p === 'object' ? p.id : p;
        });

        const payload = {
          title: this.title,
          description: this.description,
          status: this.status ? 1 : 0,
          people_ids: cleanPeopleIds // Enviamos o array de números limpo
        };

        try {
          let response;

          if (this.editingId) {
            // EDIÇÃO: Certifique-se que o editingId não está nulo
            response = await api.put(`/tasks/${this.editingId}`, payload);
            console.log("Resposta da Edição:", response.data);
          } else {
            // CRIAÇÃO
            response = await api.post('/tasks', payload);
            console.log("Resposta da Criação:", response.data);
          }

          // Se chegou aqui, deu certo
          alert(this.editingId ? "Tarefa atualizada!" : "Tarefa criada!");
          
          await this.getTasks(); // Recarrega a tabela principal
          this.closeModal();     // Fecha o modal e limpa os campos
          
        } catch (error) {
          // ANALISE O ERRO NO CONSOLE (F12)
          console.error("Erro detalhado:", error.response ? error.response.data : error);
          
          const msgErro = error.response?.data?.message || "Erro desconhecido no servidor.";
          alert("Falha ao salvar: " + msgErro);
        }
      },

      async deleteTask(id, force = false) {
        // Define a mensagem com base na tentativa (normal ou forçada)
        const confirmMsg = force 
          ? "Esta tarefa possui pessoas vinculadas. Deseja desvincular todos e apagar a tarefa assim mesmo?"
          : "Tem certeza que deseja excluir esta tarefa?";

        if (!confirm(confirmMsg)) return;

        try {
          // Se force for true, adicionamos o parâmetro na URL para o Laravel aceitar
          const url = force ? `/tasks/${id}?force=true` : `/tasks/${id}`;
          
          await api.delete(url);

          // Se deu certo, remove da lista na tela
          this.tasks = this.tasks.filter(t => t.id !== id);
          
          if (force) {
            alert("Tarefa e vínculos removidos com sucesso.");
          }
        } catch (error) {
          // Verifica se o erro é o status 422 (atenção) enviado pelo seu Controller
          if (error.response && error.response.status === 422 && error.response.data.requires_confirmation) {
            // Chama a função novamente para a "segunda confirmação"
            this.deleteTask(id, true);
          } else {
            console.error(error);
            alert("Erro ao excluir tarefa.");
          }
        }
      },

      // Preenche o modal para edição
      editTask(task) {
        this.editingId = task.id;
        this.title = task.title;
        this.description = task.description;
        this.status = !!task.status; // Garante que seja booleano
        
        // Se o seu backend já retorna a relação 'people' no GET /tasks
        if (task.people) {
          this.selectedPeople = task.people.map(p => p.id);
        } else {
          this.selectedPeople = [];
        }
        
        this.showModal = true;
      },

      closeModal() {
        this.showModal = false;
        this.editingId = null;
        this.title = '';
        this.description = '';
        this.status = false;
        this.selectedPeople = [];
      },

      viewPerson(id) {
        // Redireciona para a página da pessoa (ajuste a rota conforme seu projeto)
        this.$router.push(`/people/${id}`);
      },

      viewTaskDetails(id) {
        // Isso garante que você vá para /tasks/1, /tasks/2, etc.
        this.$router.push(`/tasks/${id}`);
      },

    }
  }

</script>

<style scoped>
.material-symbols-outlined {
  font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
}
</style>