<template>
  <div class="admin-page">
    <h1>Администраторская панель</h1>
    <div class="controls">
      <button @click="showStudents = true; showTeachers = false">Управление студентами</button>
      <button @click="showTeachers = true; showStudents = false">Управление преподавателями</button>
    </div>

    <div v-if="showStudents">
      <h2>Список студентов</h2>
      <button @click="openStudentForm()">Добавить студента</button>
      <table>
        <thead>
        <tr>
          <th>Имя</th>
          <th>Группа</th>
          <th>Действия</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="student in students" :key="student.id">
          <td>{{ student.name }}</td>
          <td>{{ student.group }}</td>
          <td>
            <button @click="editStudent(student)">Редактировать</button>
            <button @click="deleteStudent(student.id)">Удалить</button>
          </td>
        </tr>
        </tbody>
      </table>
    </div>

    <div v-if="showTeachers">
      <h2>Список преподавателей</h2>
      <button @click="openTeacherForm()">Добавить преподавателя</button>
      <table>
        <thead>
        <tr>
          <th>Имя</th>
          <th>Предмет</th>
          <th>Действия</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="teacher in teachers" :key="teacher.id">
          <td>{{ teacher.name }}</td>
          <td>{{ teacher.subject }}</td>
          <td>
            <button @click="editTeacher(teacher)">Редактировать</button>
            <button @click="deleteTeacher(teacher.id)">Удалить</button>
          </td>
        </tr>
        </tbody>
      </table>
    </div>

    <!-- Модальные формы -->
    <div v-if="isModalOpen" class="modal">
      <div class="modal-content">
        <h2>{{ modalTitle }}</h2>
        <form @submit.prevent="handleSubmit">
          <label for="name">Имя:</label>
          <input v-model="form.name" type="text" required />

          <div v-if="showStudents">
            <label for="group">Группа:</label>
            <input v-model="form.group" type="text" required />
          </div>

          <div v-if="showTeachers">
            <label for="subject">Предмет:</label>
            <input v-model="form.subject" type="text" required />
          </div>

          <button type="submit">Сохранить</button>
          <button type="button" @click="closeModal">Отмена</button>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      showStudents: true,
      showTeachers: false,
      students: [],
      teachers: [],
      isModalOpen: false,
      modalTitle: '',
      form: {
        id: null,
        name: '',
        group: '',
        subject: '',
      },
    };
  },
  methods: {
    async loadStudents() {
      try {
        const response = await fetch('/api/admin/students');
        this.students = await response.json();
      } catch (error) {
        console.error('Ошибка при загрузке студентов:', error);
      }
    },
    async loadTeachers() {
      try {
        const response = await fetch('/api/admin/teachers');
        this.teachers = await response.json();
      } catch (error) {
        console.error('Ошибка при загрузке преподавателей:', error);
      }
    },
    openStudentForm(student = null) {
      this.isModalOpen = true;
      this.modalTitle = student ? 'Редактировать студента' : 'Добавить студента';
      this.form = student ? { ...student } : { id: null, name: '', group: '' };
    },
    openTeacherForm(teacher = null) {
      this.isModalOpen = true;
      this.modalTitle = teacher ? 'Редактировать преподавателя' : 'Добавить преподавателя';
      this.form = teacher ? { ...teacher } : { id: null, name: '', subject: '' };
    },
    closeModal() {
      this.isModalOpen = false;
      this.form = { id: null, name: '', group: '', subject: '' };
    },
    async handleSubmit() {
      try {
        const endpoint = this.showStudents
            ? '/api/admin/students'
            : '/api/admin/teachers';

        const method = this.form.id ? 'PUT' : 'POST';
        const response = await fetch(endpoint, {
          method,
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(this.form),
        });

        if (response.ok) {
          this.closeModal();
          if (this.showStudents) {
            await this.loadStudents();
          } else {
            await this.loadTeachers();
          }
        }
      } catch (error) {
        console.error('Ошибка при сохранении:', error);
      }
    },
    async deleteStudent(id) {
      try {
        await fetch(`/api/admin/students/${id}`, { method: 'DELETE' });
        await this.loadStudents();
      } catch (error) {
        console.error('Ошибка при удалении студента:', error);
      }
    },
    async deleteTeacher(id) {
      try {
        await fetch(`/api/admin/teachers/${id}`, { method: 'DELETE' });
        await this.loadTeachers();
      } catch (error) {
        console.error('Ошибка при удалении преподавателя:', error);
      }
    },
  },
  mounted() {
    this.loadStudents();
    this.loadTeachers();
  },
};
</script>

<style>
.admin-page {
  max-width: 800px;
  margin: 0 auto;
  text-align: center;
}

table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 20px;
}

th, td {
  border: 1px solid #ccc;
  padding: 10px;
  text-align: center;
}

.controls button {
  margin: 10px;
}

.modal {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
}

.modal-content {
  background: #fff;
  padding: 20px;
  border-radius: 8px;
  text-align: left;
}
</style>
