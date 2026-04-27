<template>
  <div>
    <h2>Tarefas</h2>

    <form @submit.prevent="createTask">
      <div>
        <input v-model="form.title" placeholder="Título" />
      </div>

      <div>
        <input v-model="form.description" placeholder="Descrição" />
      </div>

      <div>
        <label>
          <input type="checkbox" v-model="form.status" />
          Concluída
        </label>
      </div>

      <button type="submit">Criar Tarefa</button>
    </form>

    <hr />

    <ul>
      <li v-for="task in tasks" :key="task.id">
        {{ task.title }} -
        {{ task.status ? '✔ Concluída' : '⏳ Pendente' }}

        <input
          type="checkbox"
          :checked="task.status"
          @change="toggleStatus(task)"
        />

        <button @click="deleteTask(task.id)">Excluir</button>
      </li>
    </ul>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      tasks: [],
      form: {
        title: '',
        description: '',
        status: false
      }
    }
  },

  mounted() {
    this.getTasks();
  },

  methods: {
    async getTasks() {
      const res = await axios.get('/api/tasks');
      this.tasks = res.data;
    },

    async createTask() {
      await axios.post('/api/tasks', this.form);
      this.form = { title: '', description: '', status: false };
      this.getTasks();
    },

    async toggleStatus(task) {
      await axios.put(`/api/tasks/${task.id}`, {
        status: !task.status
      });
      this.getTasks();
    },

    async deleteTask(id) {
      await axios.delete(`/api/tasks/${id}`);
      this.getTasks();
    }
  }
}
</script>