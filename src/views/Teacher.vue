<script setup>
import {onMounted, ref} from 'vue';
import {useUserStore} from '@/stores/user';

// Получение данных преподавателя из Pinia-хранилища
const userStore = useUserStore();
const teacherName = userStore.user.name;

// Список групп и выбранная группа
const groups = ref([]);
const selectedGroup = ref(null);

// Загрузка групп преподавателя
const loadGroups = async () => {
  try {
    const response = await fetch(`/api/teacher/${userStore.user.id}/groups`);
    groups.value = await response.json();
  } catch (error) {
    console.error('Ошибка при загрузке групп:', error);
  }
};

// Выбор группы
const selectGroup = async (group) => {
  selectedGroup.value = { ...group, students: [] };
  try {
    const response = await fetch(`/api/groups/${group.id}/students`);
    selectedGroup.value.students = await response.json();
  } catch (error) {
    console.error('Ошибка при загрузке студентов группы:', error);
  }
};

// Обновление оценки
const updateGrade = async (student) => {
  const newGrade = prompt(`Введите новую оценку для ${student.name}:`, student.grade || '');
  if (newGrade) {
    try {
      await fetch(`/api/students/${student.id}/grade`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ grade: newGrade }),
      });
      student.grade = newGrade; // Локальное обновление данных
    } catch (error) {
      console.error('Ошибка при обновлении оценки:', error);
    }
  }
};

// Обновление посещаемости
const updateAttendance = async (student) => {
  const status = confirm(`Студент ${student.name} присутствовал?`) ? 'Присутствовал' : 'Отсутствовал';
  try {
    await fetch(`/api/students/${student.id}/attendance`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ status }),
    });
    student.attendance = status; // Локальное обновление данных
  } catch (error) {
    console.error('Ошибка при обновлении посещаемости:', error);
  }
};

// Загрузка данных при монтировании
onMounted(loadGroups);
</script>

<template>
  <div class="teacher-page">
    <h1>Добро пожаловать, {{ teacherName }}</h1>

    <h2>Ваши группы:</h2>
    <ul>
      <li v-for="group in groups" :key="group.id" @click="selectGroup(group)">
        {{ group.name }}
      </li>
    </ul>

    <div v-if="selectedGroup">
      <h2>Группа: {{ selectedGroup.name }}</h2>
      <table>
        <thead>
        <tr>
          <th>Имя</th>
          <th>Оценка</th>
          <th>Посещаемость</th>
          <th>Действия</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="student in selectedGroup.students" :key="student.id">
          <td>{{ student.name }}</td>
          <td>{{ student.grade || 'Не выставлено' }}</td>
          <td>{{ student.attendance || 'Не отмечено' }}</td>
          <td>
            <button @click="updateGrade(student)">Изменить оценку</button>
            <button @click="updateAttendance(student)">Отметить посещаемость</button>
          </td>
        </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<style>
.teacher-page {
  max-width: 900px;
  margin: 0 auto;
  padding: 20px;
}

ul {
  list-style: none;
  padding: 0;
}

ul li {
  cursor: pointer;
  margin: 5px 0;
  padding: 5px 10px;
  background: #f0f0f0;
  border-radius: 4px;
}

ul li:hover {
  background: #e0e0e0;
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

button {
  margin: 0 5px;
  padding: 5px 10px;
  background: #007bff;
  color: #fff;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

button:hover {
  background: #0056b3;
}
</style>
