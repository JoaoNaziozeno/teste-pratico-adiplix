<template>
  <Sidebar />
  <Header />

  <main class="pl-64 pt-20 p-6">

    <!-- 👤 DADOS DA PESSOA -->
    <div class="mb-6">
      <h1 class="text-3xl font-bold">{{ person.name }}</h1>
      <p class="text-gray-600">{{ person.email }}</p>
    </div>

    <!-- 📊 RESUMO -->
    <div class="grid grid-cols-3 gap-4 mb-6">
      <div class="bg-gray-100 p-4 rounded">
        Total: {{ tasks.length }}
      </div>
      <div class="bg-green-100 p-4 rounded">
        Concluídas: {{ completedTasks }}
      </div>
      <div class="bg-red-100 p-4 rounded">
        Pendentes: {{ pendingTasks }}
      </div>
    </div>

    <!-- ➕ ATRIBUIR TAREFAS -->
    <div class="mb-6">
      <h2 class="text-xl font-bold mb-2">Atribuir tarefas</h2>

      <select v-model="selectedTasks" multiple class="border p-2 w-full mb-2">
        <option v-for="task in allTasks" :key="task.id" :value="task.id">
          {{ task.title }}
        </option>
      </select>

      <button @click="assignTasks" class="bg-blue-500 text-white px-4 py-2 rounded">
        Salvar
      </button>
    </div>

    <!-- 📋 LISTA DE TAREFAS -->
    <div>
      <h2 class="text-xl font-bold mb-2">Tarefas da pessoa</h2>

      <div v-for="task in tasks" :key="task.id" class="border p-3 mb-2 flex justify-between">

        <div>
          <strong>{{ task.title }}</strong>
          <p class="text-sm text-gray-600">{{ task.description }}</p>

          <span v-if="task.status" class="text-green-600">✔ Concluída</span>
          <span v-else class="text-yellow-600">⏳ Pendente</span>
        </div>

        <button 
          @click="removeTask(task.id)" 
          class="bg-red-500 text-white px-3 rounded"
        >
          Remover
        </button>

      </div>
    </div>

  </main>
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