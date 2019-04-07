<template>
    <div class="row">
        <div class="col-12">
            <div class="form-group col-4">
                <label for="">Имя</label>
                <input type="email"
                       v-model="employee.name"
                       class="form-control">
            </div>
            <div class="form-group col-4">
                <label for="">Фотография</label>
                <input type="email"
                       v-model="employee.url"
                       class="form-control">
            </div>
            <div class="form-group col-4">
                <label for="">Должность</label>
                <select class="form-control" v-model="employee.appointment">
                    <option :value="appointment.id"
                            v-for="appointment in appointments"
                            :key="appointment.id">
                        {{ appointment.name }}
                    </option>
                </select>
            </div>
            <div class="form-group col-4">
                <label for="">Дата рождения</label>
                <datepicker v-model="employee.birthday"
                            :monday-first="true"
                            :bootstrap-styling="true"
                            :language="ru">
                </datepicker>
            </div>
            <div class="form-group col-4">
                <label for="">Описание</label>
                <textarea v-model="employee.description"
                          class="form-control">
                </textarea>
            </div>
            <div class="form-group col-4">
                <button class="btn btn-outline-success" v-if="$route.params.id" @click="save">
                    Сохранить
                </button>
                <button class="btn btn-outline-success" @click="save" v-else>
                    Добавить
                </button>
                <button class="btn btn-outline-secondary" @click="$router.push({ name: 'employees' })">
                    Отмена
                </button>
            </div>
        </div>
    </div>
</template>

<script>
  import Datepicker from 'vuejs-datepicker';
  import {ru,en} from 'vuejs-datepicker/dist/locale';

  export default {
    name: "employee_form",
    components: {Datepicker},
    data() {
      return {
        employee: {
          name: '',
          description: '',
          url: '',
          birthday: Date.now(),
          appointment: ''
        },

        appointments: [],

        ru: ru,
        en: en
      }
    },
    methods: {
      async save() {
        if (this.$route.params.id) {
          const response = await axios.post('/employees/update/' + this.$route.params.id, this.employee);
          if (response.status !== 200 || response.data.status === 'error') {
            this.$swal('Ошибка!', response.data.msg, 'error');
            return false;
          }
          this.$swal('Успешно!', response.data.msg, 'success');
          this.$router.push({ name: 'employees' });
          return true;
        } else {
          const response = await axios.post('/employees/create', this.employee);
          if (response.status !== 200 || response.data.status === 'error') {
            this.$swal('Ошибка!', response.data.msg, 'error');
            return false;
          }
          this.$swal('Успешно!', response.data.msg, 'success');
          this.$router.push({ name: 'employees' });
          return true;
        }
      },

      async loadData() {
        const response = await axios.get('/employees/edit/' + this.$route.params.id);
        if (response.status !== 200 || response.data.status === 'error') {
          this.$swal('Ошибка!', response.data.msg, 'error');
          return false;
        }
        this.employee = response.data.employee;
        return true;
      },

      async loadExtendsData() {
        const response = await axios.get('/employees/extends');
        if (response.status !== 200 || response.data.status === 'error') {
          this.$swal('Ошибка!', response.data.msg, 'error');
          return false;
        }
        this.appointments = response.data.appointments;
        return true;
      }

    },
    created() {
      this.loadExtendsData();
      if (this.$route.params.id) {
        this.loadData();
      }
    }
  }
</script>

<style scoped>

</style>