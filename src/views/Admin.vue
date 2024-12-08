<script setup>
import {ref, onMounted} from 'vue';
import {useUserStore} from '@/stores/user'; // Импортируем хранилище пользователя для получения токена

// Используем хранилище пользователя
const userStore = useUserStore();

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
    const response = await fetch('http://localhost:3000/students', {
      headers: {
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${userStore.token}` // Добавляем токен для авторизации
      }
    });
    students.value = await response.json();
  } catch (error) {
    console.error('Ошибка при загрузке студентов:', error);
  }
};

// Загрузка списка преподавателей
const loadTeachers = async () => {
  try {
    const response = await fetch('http://localhost:3000/teachers', {
      headers: {
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${userStore.token}` // Добавляем токен для авторизации
      }
    });
    teachers.value = await response.json();
  } catch (error) {
    console.error('Ошибка при загрузке преподавателей:', error);
  }
};

// Открытие формы для редактирования или добавления студента
const openStudentForm = (student = null) => {
  isModalOpen.value = true;
  modalTitle.value = student ? 'Редактировать студента' : 'Добавить студента';
  form.value = student ? {...student} : {id: null, name: '', group: ''};
};

// Открытие формы для редактирования или добавления преподавателя
const openTeacherForm = (teacher = null) => {
  isModalOpen.value = true;
  modalTitle.value = teacher ? 'Редактировать преподавателя' : 'Добавить преподавателя';
  form.value = teacher ? {...teacher} : {id: null, name: '', subject: ''};
};

// Закрытие модального окна
const closeModal = () => {
  isModalOpen.value = false;
  form.value = {id: null, name: '', group: '', subject: ''};
};

// Сохранение данных студента или преподавателя
const handleSubmit = async () => {
  try {
    const endpoint = showStudents.value
        ? 'http://localhost:3000/students'
        : 'http://localhost:3000/teachers';

    const method = form.value.id ? 'PUT' : 'POST';
    const response = await fetch(`${endpoint}/${form.value.id || ''}`, {
      method,
      headers: {
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${userStore.token}` // Добавляем токен для авторизации
      },
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
    await fetch(`http://localhost:3000/students/${id}`, {
      method: 'DELETE',
      headers: {
        'Authorization': `Bearer ${userStore.token}` // Добавляем токен для авторизации
      }
    });
    await loadStudents();
  } catch (error) {
    console.error('Ошибка при удалении студента:', error);
  }
};

// Удаление преподавателя
const deleteTeacher = async (id) => {
  try {
    await fetch(`http://localhost:3000/teachers/${id}`, {
      method: 'DELETE',
      headers: {
        'Authorization': `Bearer ${userStore.token}` // Добавляем токен для авторизации
      }
    });
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
