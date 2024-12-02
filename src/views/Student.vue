<template>
  <div class="student-page">
    <h1>Добро пожаловать, {{ studentName }}</h1>
    <h2>Информация о вашей группе: {{ group.name }}</h2>

    <table v-if="group.students.length > 0">
      <thead>
      <tr>
        <th>Имя</th>
        <th>Оценка</th>
        <th>Посещаемость</th>
      </tr>
      </thead>
      <tbody>
      <tr v-for="student in group.students" :key="student.id">
        <td>{{ student.name }}</td>
        <td>{{ student.grade || 'Не выставлено' }}</td>
        <td>{{ student.attendance || 'Не отмечено' }}</td>
      </tr>
      </tbody>
    </table>
    <p v-else>Список студентов пуст.</p>
  </div>
</template>

<script>
import { useUserStore } from '@/stores/user';
import {onMounted, ref} from "vue";

export default {
  setup() {
    const userStore = useUserStore();

    // Имя студента
    const studentName = userStore.user.name;

    // Данные группы студента
    const group = ref({
      name: '',
      students: [],
    });

    // Загрузка информации о группе
    const loadGroup = async () => {
      try {
        // Пример API-запроса для получения информации о группе студента
        const response = await fetch(`/api/student/${userStore.user.id}/group`);
        const data = await response.json();
        group.value.name = data.name;
        group.value.students = data.students;
      } catch (error) {
        console.error('Ошибка при загрузке данных группы:', error);
      }
    };

    // Загрузка данных при монтировании
    onMounted(loadGroup);

    return { studentName, group };
  },
};
</script>

<style>
.student-page {
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
</style>
