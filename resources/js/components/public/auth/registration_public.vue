<template>
    <div>
        <div class="modal-header">
            <h2>Регистрация</h2>
            <button class="close"
                    @click="$root.$emit('hide', 'registration')">
                &times;
            </button>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label for="">Фамилия</label>
                <input class="form-control" v-model="registration.last_name">
            </div>
            <div class="form-group">
                <label for="">Имя</label>
                <input class="form-control" v-model="registration.first_name">
            </div>
            <div class="form-group">
                <label for="">Дата рождения</label>
                <datepicker v-model="registration.birthday"
                            :language="ru"
                            :monday-first="true"
                            :required="true"
                            :bootstrap-styling="true">
                </datepicker>
            </div>
            <div class="form-group">
                <label for="">Email</label>
                <input class="form-control" v-model="registration.email">
            </div>
            <div class="form-group">
                <label for="">Пароль</label>
                <input class="form-control"
                       type="password"
                       v-model="registration.password">
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-outline-primary" @click="registrate">
                Зарегистрироваться
            </button>
        </div>
    </div>
</template>

<script>
  import {mapActions} from 'vuex';
  import Datepicker from 'vuejs-datepicker';
  import { ru } from 'vuejs-datepicker/dist/locale/';

  export default {
    name: "registration_public",
    components: { Datepicker },
    data() {
      return {
        registration: {
          email: '',
          last_name: '',
          first_name: '',
          birthdate: new Date(),
          password: '',
        },

        errors: [],

        swal: {
          errors: [],
          message: ``,
        },

        ru: ru
      }
    },
    methods: {
      async registrate() {
        const response = await axios.post('/registration', this.registration);
        if (response.status !== 200 || response.data.status === 'error') {
          this.swal.errors = (response.data.errors !== undefined) ? response.data.errors : {};
          this.swal.message = this.getSwalMessage();
          console.log(this.swal.errors.length);
          this.$swal({
            title: 'Ошибка!',
            html: response.data.msg + this.swal.message,
            type: 'error'
          });
          return false;
        } else {
          this.fillStorage(response.data);
          this.$modal.hide('registration');
          this.registration = {};
        }
      },

      ...mapActions('Auth', {
        'setUser': 'setUser'
      }),

      fillStorage(data) {
        localStorage.setItem('user', JSON.stringify(data.user));
        this.setUser(data.user);
        localStorage.setItem('token', data.token);
        localStorage.setItem('csrf_token', data.user.csrf_token);
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
    }
  }
</script>

<style scoped>

</style>