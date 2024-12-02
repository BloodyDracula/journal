<template>
  <div class="teacher-page">
    <h1>Добро пожаловать, {{ teacherName }}</h1>
    <h2>Ваши группы:</h2>
    <ul v-if="groups.length > 0">
      <li v-for="group in groups" :key="group.id" @click="selectGroup(group)">
        {{ group.name }}
      </li>
    </ul>
    <p v-else>У вас пока нет закреплённых групп.</p>

    <div v-if="selectedGroup">
      <h2>Студенты группы: {{ selectedGroup.name }}</h2>
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
            <button @click="updateGrade(student)">Поставить оценку</button>
            <button @click="updateAttendance(student)">Отметить посещаемость</button>
          </td>
        </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
import { useUserStore } from '@/stores/user';
import {onMounted, ref} from "vue";

export default {
  setup() {
    const userStore = useUserStore();

    // Данные преподавателя
    const teacherName = userStore.user.name;

    // Группы, закреплённые за преподавателем
    const groups = ref([]);
    const selectedGroup = ref(null);

    // Загрузка групп преподавателя
    const loadGroups = async () => {
      // Пример API-запроса для получения групп
      const response = await fetch(`/api/teacher/${userStore.user.id}/groups`);
      groups.value = await response.json();
    };

    // Выбор группы
    const selectGroup = (group) => {
      selectedGroup.value = group;
      // Подгрузка студентов группы
      fetch(`/api/groups/${group.id}/students`)
          .then((res) => res.json())
          .then((students) => {
            selectedGroup.value.students = students;
          });
    };

    // Обновление оценки
    const updateGrade = (student) => {
      const newGrade = prompt(`Введите новую оценку для ${student.name}:`, student.grade || '');
      if (newGrade) {
        // Пример API-запроса для обновления оценки
        fetch(`/api/students/${student.id}/grade`, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ grade: newGrade }),
        }).then(() => {
          student.grade = newGrade; // Локальное обновление данных
        });
      }
    };

    // Обновление посещаемости
    const updateAttendance = (student) => {
      const status = confirm(`Студент ${student.name} присутствовал?`) ? 'Присутствовал' : 'Отсутствовал';
      // Пример API-запроса для обновления посещаемости
      fetch(`/api/students/${student.id}/attendance`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ status }),
      }).then(() => {
        student.attendance = status; // Локальное обновление данных
      });
    };

    // Загрузка данных при монтировании
    onMounted(loadGroups);

    return { teacherName, groups, selectedGroup, selectGroup, updateGrade, updateAttendance };
  },
};
</script>

<style>
.teacher-page {
  max-width: 800px;
  margin: 0 auto;
}

ul {
  list-style: none;
  padding: 0;
}

li {
  cursor: pointer;
  margin: 5px 0;
  padding: 10px;
  background-color: #f0f0f0;
  border: 1px solid #ccc;
}

li:hover {
  background-color: #e0e0e0;
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
}
</style>
