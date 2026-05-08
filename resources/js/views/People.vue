<template>
  <div class="flex min-h-screen bg-[#f9f9ff] text-[#191b23] font-['Inter']">

    <Sidebar />

    <div class="flex-1 ml-64">
  
    <Header />

      <main class="p-6 max-w-[1280px] mx-auto">
        <div class="flex items-end justify-between mb-8">
          <div>
            <h2 class="text-[30px] font-bold leading-tight">Gerenciar Pessoas</h2>
            <p class="text-[#424754] mt-1">Gerencie as pessoas vinculando às em suas tarefas.</p>
          </div>
          
          <BaseButton @click="showModal = true" icon="add">
            Adicionar Pessoa
          </BaseButton>
        </div>

        <div v-if="showModal || editingId" class="fixed inset-0 z-[60] flex items-center justify-center p-6 bg-black/40 backdrop-blur-sm">
          <div class="bg-white w-full max-w-md rounded-xl shadow-xl border border-gray-200 overflow-hidden">
            
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
              <h3 class="text-xl font-bold text-blue-600">
                {{ editingId ? 'Editar Pessoa' : 'Cadastrar Pessoa' }}
              </h3>
              <button @click="closeModal" class="text-gray-400 hover:text-gray-600 transition-colors">
                <span class="material-symbols-outlined">close</span>
              </button>
            </div>
            
            <!-- Body -->
            <div class="p-6 space-y-4">
              <BaseInput 
                label="Nome Completo" 
                v-model="name" 
                placeholder="Ex: João Naziozeno" 
              />

              <BaseInput 
                label="E-mail" 
                v-model="email" 
                type="email"
                placeholder="naziozeno@email.com" 
              />

              <!-- Agora vinculamos Tarefas à Pessoa -->
              <BaseMultiSelect 
                label="Atribuir Tarefas"
                :options="tasks" 
                v-model="selectedTasks"
                placeholder="Pesquisar tarefas..."
              />
            </div>

            <!-- Footer -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-end gap-3">
              <button @click="closeModal" class="px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-100 rounded-lg transition-all">
                Cancelar
              </button>
              <BaseButton @click="savePerson" icon="person_add">
                {{ editingId ? 'Salvar Alterações' : 'Cadastrar Pessoa' }}
              </BaseButton>
            </div>
          </div>
        </div>

        <!-- Stats Overview -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
          <div class="bg-blue-100 p-4 rounded-xl shadow-sm border border-gray-100">
            <p class="text-[10px] text-[#424754] uppercase font-bold mb-2">Total de Pessoas</p>
            <span class="text-2xl font-bold">{{ people.length }}</span>
          </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
          <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
              <thead>
                <tr class="border-b border-gray-100 bg-gray-50/50">
                  <th class="px-6 py-4 text-xs font-bold text-[#424754] uppercase">ID</th>
                  <th class="px-6 py-4 text-xs font-bold text-[#424754] uppercase">Name</th>
                  <th class="px-6 py-4 text-xs font-bold text-[#424754] uppercase">Email</th>
                  <th class="px-6 py-4 text-xs font-bold text-[#424754] uppercase text-right">Ações</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-50">
                <tr v-for="p in people" :key="p.id" class="hover:bg-gray-50 transition-colors group">
                  <td class="px-6 py-4 text-sm text-[#424754]">{{ p.id }}</td>
                  <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                      <div class="w-8 h-8 rounded-full bg-[#d8e2ff] flex items-center justify-center text-[#0058be] font-bold text-xs">
                        {{ p.name.substring(0,2).toUpperCase() }}
                      </div>
                      <span class="font-semibold text-sm">{{ p.name }}</span>
                    </div>
                  </td>
                  <td class="px-6 py-4 text-sm text-[#424754]">{{ p.email }}</td>
                  <td class="px-6 py-4 text-right">
                    <div class="flex items-center justify-end gap-2">
                      <button @click="editPerson(p)" class="p-2 text-on-surface-variant hover:text-primary hover:bg-blue-50 rounded-lg transition-all" title="Editar">
                        <span class="material-symbols-outlined text-xl">edit</span>
                      </button>

                      <router-link :to="`/people/${p.id}`" class="p-2 text-[#424754] hover:text-[#0058be] hover:bg-blue-50 rounded-lg transition-all" title="Ver Pessoa">
                        <span class="material-symbols-outlined text-sm">visibility</span>
                      </router-link>

                      <button @click="deletePerson(p.id)" class="p-2 text-[#424754] hover:text-[#ba1a1a] hover:bg-red-50 rounded-lg transition-all" title="Excluir">
                        <span class="material-symbols-outlined text-sm">delete</span>
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
      people: [],
      tasks: [],
      selectedTasks: [],
      name: '',
      email: '',
      showModal: false,
      editingId: null // Garantindo que o editingId esteja declarado
    }
  },

  async mounted() {
    this.loadPeople();
    this.loadTasks();
  },

  methods: {
    async loadPeople() {
      const res = await api.get('/people');
      this.people = res.data;
    },

    async loadTasks() {
      try {
        const res = await api.get('/tasks');
        // Prepara as opções para o BaseMultiSelect
        this.tasks = res.data.map(t => ({ 
          id: t.id, 
          name: t.title 
        }));
      } catch (error) {
        console.error("Erro ao carregar tarefas:", error);
      }
    },

    async savePerson() {
      if (!this.name || !this.email) {
        alert("Nome e E-mail são obrigatórios");
        return;
      }

      // LIMPEZA DOS IDs (Igual fizemos em Tarefas)
      const cleanTaskIds = this.selectedTasks.map(t => {
        return typeof t === 'object' ? t.id : t;
      });

      const payload = {
        name: this.name,
        email: this.email,
        task_ids: cleanTaskIds // Enviando como 'task_ids' para o Controller
      };

      try {
        if (this.editingId) {
          await api.put(`/people/${this.editingId}`, payload);
          alert("Pessoa atualizada com sucesso!");
        } else {
          await api.post('/people', payload);
          alert("Pessoa cadastrada com sucesso!");
        }

        this.closeModal();
        this.loadPeople();
      } catch (error) {
        console.error("Erro ao salvar pessoa:", error.response?.data);
        alert("Erro ao salvar: " + (error.response?.data?.message || "Verifique os dados."));
      }
    },

    // Lógica de exclusão com dupla confirmação (Solução 1)
    async deletePerson(id, force = false) {
      const confirmMsg = force 
        ? "Esta pessoa possui tarefas vinculadas. Deseja desvincular tudo e apagar o registro assim mesmo?"
        : "Tem certeza que deseja excluir esta pessoa?";

      if (!confirm(confirmMsg)) return;

      try {
        const url = force ? `/people/${id}?force=true` : `/people/${id}`;
        await api.delete(url);
        
        this.loadPeople();
        if (force) alert("Pessoa e vínculos removidos.");
      } catch (error) {
        // Se o Laravel retornar 422 e pedir confirmação
        if (error.response && error.response.status === 422 && error.response.data.requires_confirmation) {
          this.deletePerson(id, true);
        } else {
          alert("Erro ao excluir pessoa.");
        }
      }
    },

    editPerson(person) {
      this.editingId = person.id;
      this.name = person.name;
      this.email = person.email;
      this.selectedTasks = person.tasks ? person.tasks.map(t => t.id) : [];
      this.showModal = true;
    },

    closeModal() {
      this.showModal = false;
      this.editingId = null;
      this.name = '';
      this.email = '';
      this.selectedTasks = [];
    }
  }
}
</script>