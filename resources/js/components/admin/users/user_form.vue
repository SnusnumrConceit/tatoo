<template>
    <div class="row">
        <div class="col-12">
            <div class="form-group col-4">
                <label for="">Фамилия</label>
                <input type="email"
                       v-model="user.last_name"
                       class="form-control">
            </div>
            <div class="form-group col-4">
                <label for="">Имя</label>
                <input type="email"
                       v-model="user.first_name"
                       class="form-control">
            </div>
            <div class="form-group col-4">
                <label for="">Email</label>
                <input type="email"
                       v-model="user.email"
                       class="form-control">
            </div>
            <div class="form-group col-4">
                <label for="">Пароль</label>
                <input type="password"
                       v-model="user.password"
                       class="form-control">
            </div>
            <div class="form-group col-4">
                <label for="">Дата рождения</label>
                <datepicker v-model="user.birthday"
                            :monday-first="true"
                            :bootstrap-styling="true"
                            :language="ru">
                </datepicker>
            </div>
            <div class="form-group col-4">
                <button class="btn btn-outline-success" v-if="$route.params.id" @click="save">
                    Сохранить
                </button>
                <button class="btn btn-outline-success" @click="save" v-else>
                    Добавить
                </button>
                <button class="btn btn-outline-secondary" @click="$router.push({ name: 'users' })">
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
    name: "user_form",
    components: {Datepicker},
    data() {
      return {
        user: {
          name: '',
          email: '',
          password: '',
          first_name: '',
          last_name: '',
          birthday: Date.now()
        },

        ru: ru,
        en: en,

        swal: {
          errors: [],
          message: ``
        },
      }
    },
    methods: {
      async save() {
        if (this.$route.params.id) {
          const response = await axios.post('/users/update/' + this.$route.params.id, this.user);
          if (response.status !== 200 || response.data.status === 'error') {
            this.swal.errors = (response.data.errors !== undefined) ? response.data.errors : {};
            this.swal.message = this.getSwalMessage();
            this.$swal({
              title: 'Ошибка!',
              html: response.data.msg + this.swal.message,
              type: 'error'
            });
            return false;
          }
          this.$swal('Успешно!', response.data.msg, 'success');
          this.$router.push({ name: 'users' });
          return true;
        } else {
          const response = await axios.post('/users/create', this.user);
          if (response.status !== 200 || response.data.status === 'error') {
            this.swal.errors = (response.data.errors !== undefined) ? response.data.errors : {};
            this.swal.message = this.getSwalMessage();
            this.$swal({
              title: 'Ошибка!',
              html: response.data.msg + this.swal.message,
              type: 'error'
            });
            return false;
          }
          this.$swal('Успешно!', response.data.msg, 'success');
          this.$router.push({ name: 'users' });
          return true;
        }
      },

      async loadData() {
        const response = await axios.get('/users/edit/' + this.$route.params.id);
        if (response.status !== 200 || response.data.status === 'error') {
          this.$swal('Ошибка!', response.data.msg, 'error');
          return false;
        }
        this.user = response.data.user;
        return true;
      },

      getSwalMessage() {
        return (Object.keys(this.swal.errors).length) ?
            `<div class="alert alert-danger m-t-20">
                        <ul class="p-l-20 p-r-20">
                            ${Object.values(this.swal.errors).map(err => `<li class="text-danger">${err[0]}</li>`)}
                        </ul>
                </div>`
            : '';
      }
    },
    created() {
      if (this.$route.params.id) {
        this.loadData();
      }
    }
  }
</script>

<style scoped>

</style>