<template>
  <div class="flex min-h-screen bg-[#f9f9ff] text-[#191b23] font-['Inter']">
    <!-- SideNavBar -->
    <nav class="fixed inset-y-0 left-0 w-64 flex flex-col p-4 z-50 bg-gray-50 border-r border-gray-200">
      <div class="mb-8 px-2 flex items-center gap-3">
        <div class="w-8 h-8 rounded bg-[#0058be] flex items-center justify-center">
          <span class="material-symbols-outlined text-white text-lg">corporate_fare</span>
        </div>
        <div>
          <h1 class="text-lg font-black text-blue-600 leading-none">TaskFlow</h1>
          <p class="text-[10px] uppercase tracking-widest text-[#424754] font-bold">Management Suite</p>
        </div>
      </div>
      <div class="flex-1 space-y-1">
        <router-link to="/people" class="flex items-center gap-3 px-4 py-2 text-blue-600 font-bold bg-blue-50 rounded-lg">
          <span class="material-symbols-outlined">group</span>
          <span class="text-sm">People</span>
        </router-link>
        <router-link to="/tasks" class="flex items-center gap-3 px-4 py-2 text-gray-600 hover:text-gray-900 hover:bg-gray-200/50 rounded-lg transition-all">
          <span class="material-symbols-outlined">assignment</span>
          <span class="text-sm">Tasks</span>
        </router-link>
      </div>
    </nav>

    <!-- Content Area -->
    <div class="flex-1 ml-64">
      <!-- TopAppBar -->
      <header class="h-16 flex items-center justify-between px-6 bg-white border-b border-gray-200 shadow-sm">
        <div class="relative max-w-md w-full">
          <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[#424754] text-sm">search</span>
          <input class="w-full pl-10 pr-4 py-2 bg-[#f2f3fd] border border-[#c2c6d6] rounded-lg text-sm outline-none focus:ring-2 focus:ring-[#0058be]" placeholder="Search people..." type="text"/>
        </div>
        <div class="flex items-center gap-4">
          <div class="h-8 w-px bg-gray-200 mx-2"></div>
          <div class="w-8 h-8 rounded-full bg-[#adc6ff] flex items-center justify-center text-[#0058be] font-bold text-xs">AD</div>
        </div>
      </header>

      <!-- Main Content -->
      <main class="p-6 max-w-[1280px] mx-auto">
        <!-- Page Header -->
        <div class="flex items-end justify-between mb-8">
          <div>
            <nav class="flex items-center gap-2 mb-2 text-[#424754] text-xs">
              <span>Directory</span>
              <span class="material-symbols-outlined text-[10px]">chevron_right</span>
              <span class="text-[#0058be] font-bold">People</span>
            </nav>
            <h2 class="text-[30px] font-bold leading-tight">Team Directory</h2>
            <p class="text-[#424754] mt-1">Manage organization members and their access.</p>
          </div>
          
          <!-- Botão para abrir o formulário (pode ser um modal ou toggle) -->
          <button @click="showForm = !showForm" class="flex items-center gap-2 bg-[#0058be] text-white px-6 py-2.5 rounded-lg text-sm font-medium shadow-sm hover:opacity-90 transition-all">
            <span class="material-symbols-outlined text-sm">{{ showForm ? 'close' : 'add' }}</span>
            {{ showForm ? 'Cancelar' : 'Add Person' }}
          </button>
        </div>

        <!-- Form Inline (Estilo simplificado do Modal do modelo) -->
        <div v-if="showForm" class="mb-8 bg-white p-6 rounded-xl border border-gray-200 shadow-sm space-y-4">
          <div class="grid grid-cols-2 gap-4">
            <div class="space-y-1.5">
              <label class="text-xs font-medium text-[#424754]">Nome</label>
              <input v-model="name" class="w-full px-3 py-2 border border-[#c2c6d6] rounded-lg outline-none focus:border-[#0058be]" type="text"/>
            </div>
            <div class="space-y-1.5">
              <label class="text-xs font-medium text-[#424754]">E-mail</label>
              <input v-model="email" class="w-full px-3 py-2 border border-[#c2c6d6] rounded-lg outline-none focus:border-[#0058be]" type="email"/>
            </div>
          </div>
          <button @click="createPerson" class="bg-[#0058be] text-white px-6 py-2 rounded-lg text-sm font-bold hover:opacity-90">Salvar Cadastro</button>
        </div>

        <!-- Stats Overview -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
          <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
            <p class="text-[10px] text-[#424754] uppercase font-bold mb-2">Total People</p>
            <span class="text-2xl font-bold">{{ people.length }}</span>
          </div>
        </div>

        <!-- Table Container -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
          <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
              <thead>
                <tr class="border-b border-gray-100 bg-gray-50/50">
                  <th class="px-6 py-4 text-xs font-bold text-[#424754] uppercase">ID</th>
                  <th class="px-6 py-4 text-xs font-bold text-[#424754] uppercase">Name</th>
                  <th class="px-6 py-4 text-xs font-bold text-[#424754] uppercase">Email</th>
                  <th class="px-6 py-4 text-xs font-bold text-[#424754] uppercase text-right">Actions</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-50">
                <tr v-for="p in people" :key="p.id" class="hover:bg-gray-50 transition-colors group">
                  <td class="px-6 py-4 text-sm text-[#424754]">#{{ p.id }}</td>
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
                      <router-link :to="`/people/${p.id}`" class="p-2 text-[#424754] hover:text-[#0058be] hover:bg-blue-50 rounded-lg transition-all" title="Ver Perfil">
                        <span class="material-symbols-outlined text-sm">visibility</span>
                      </router-link>
                      <button @click="deletePerson(p.id)" class="p-2 text-[#424754] hover:text-[#ba1a1a] hover:bg-red-50 rounded-lg transition-all" title="Delete">
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

export default {
  data() {
    return {
      people: [],
      name: '',
      email: '',
      showForm: false // Controla a exibição do formulário de cadastro
    }
  },

  async mounted() {
    this.loadPeople();
  },

  methods: {
    async loadPeople() {
      const res = await api.get('/people');
      this.people = res.data;
    },

    async createPerson() {
      if (!this.name || !this.email) return alert("Preencha todos os campos");
      
      const res = await api.post('/people', {
        name: this.name,
        email: this.email
      });
      
      this.people.push(res.data);
      this.name = '';
      this.email = '';
      this.showForm = false; // Fecha o formulário após criar
    },

    async deletePerson(id) {
      if (confirm("Deseja realmente excluir esta pessoa?")) {
        await api.delete(`/people/${id}`);
        this.people = this.people.filter(p => p.id !== id);
      }
    }
  }
}
</script>

<style scoped>
/* Importação dos ícones e fontes caso não estejam no app.blade.php */
@import url('https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined');
</style>