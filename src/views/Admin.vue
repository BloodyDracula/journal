<script setup>
import { ref, onMounted } from 'vue';

// Управление отображением студентов и преподавателей
const showStudents = ref(true);
const showTeachers = ref(false);

// Данные студентов и преподавателей
const students = ref([]);
const teachers = ref([]);

// Управление модальным окном
const isModalOpen = ref(false);
const modalTitle = ref('');
const form = ref({
  id: null,
  name: '',
  group: '',
  subject: '',
});

// Загрузка списка студентов
const loadStudents = async () => {
  try {
    const response = await fetch('/api/admin/students');
    students.value = await response.json();
  } catch (error) {
    console.error('Ошибка при загрузке студентов:', error);
  }
};

// Загрузка списка преподавателей
const loadTeachers = async () => {
  try {
    const response = await fetch('/api/admin/teachers');
    teachers.value = await response.json();
  } catch (error) {
    console.error('Ошибка при загрузке преподавателей:', error);
  }
};

// Открытие формы для редактирования или добавления студента
const openStudentForm = (student = null) => {
  isModalOpen.value = true;
  modalTitle.value = student ? 'Редактировать студента' : 'Добавить студента';
  form.value = student ? { ...student } : { id: null, name: '', group: '' };
};

// Открытие формы для редактирования или добавления преподавателя
const openTeacherForm = (teacher = null) => {
  isModalOpen.value = true;
  modalTitle.value = teacher ? 'Редактировать преподавателя' : 'Добавить преподавателя';
  form.value = teacher ? { ...teacher } : { id: null, name: '', subject: '' };
};

// Закрытие модального окна
const closeModal = () => {
  isModalOpen.value = false;
  form.value = { id: null, name: '', group: '', subject: '' };
};

// Сохранение данных студента или преподавателя
const handleSubmit = async () => {
  try {
    const endpoint = showStudents.value
        ? '/api/admin/students'
        : '/api/admin/teachers';

    const method = form.value.id ? 'PUT' : 'POST';
    const response = await fetch(endpoint, {
      method,
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(form.value),
    });

    if (response.ok) {
      closeModal();
      if (showStudents.value) {
        await loadStudents();
      } else {
        await loadTeachers();
      }
    }
  } catch (error) {
    console.error('Ошибка при сохранении:', error);
  }
};

// Удаление студента
const deleteStudent = async (id) => {
  try {
    await fetch(`/api/admin/students/${id}`, { method: 'DELETE' });
    await loadStudents();
  } catch (error) {
    console.error('Ошибка при удалении студента:', error);
  }
};

// Удаление преподавателя
const deleteTeacher = async (id) => {
  try {
    await fetch(`/api/admin/teachers/${id}`, { method: 'DELETE' });
    await loadTeachers();
  } catch (error) {
    console.error('Ошибка при удалении преподавателя:', error);
  }
};

// Загрузка данных при монтировании
onMounted(() => {
  loadStudents();
  loadTeachers();
});
</script>

<template>
  <div class="admin-page">
    <div>
      <button @click="showStudents = true; showTeachers = false">Студенты</button>
      <button @click="showStudents = false; showTeachers = true">Преподаватели</button>
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
            <button @click="openStudentForm(student)">Редактировать</button>
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
            <button @click="openTeacherForm(teacher)">Редактировать</button>
            <button @click="deleteTeacher(teacher.id)">Удалить</button>
          </td>
        </tr>
        </tbody>
      </table>
    </div>

    <div v-if="isModalOpen" class="modal">
      <h3>{{ modalTitle }}</h3>
      <form @submit.prevent="handleSubmit">
        <div>
          <label>Имя:</label>
          <input v-model="form.name" />
        </div>
        <div v-if="showStudents">
          <label>Группа:</label>
          <input v-model="form.group" />
        </div>
        <div v-if="showTeachers">
          <label>Предмет:</label>
          <input v-model="form.subject" />
        </div>
        <button type="submit">Сохранить</button>
        <button type="button" @click="closeModal">Отмена</button>
      </form>
    </div>
  </div>
</template>

<style>
/* Добавьте стили для оформления */
</style>
