<template>
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Авторизация</div>

                <div class="card-body">

                    <div class="form-group row">
                        <label for="email" class="col-md-4 col-form-label text-md-right">Email</label>

                        <div class="col-md-6">
                            <input type="text"
                                   class="form-control"
                                   v-model="user.email">

                            <span class="invalid-feedback" role="alert" v-if="errors.email.status">
                                    <strong>{{ errors.email.message }}</strong>
                            </span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="email" class="col-md-4 col-form-label text-md-right">Пароль</label>

                        <div class="col-md-6">
                            <input type="password"
                                   class="form-control"
                                   v-model="user.password">

                            <span class="invalid-feedback" role="alert" v-if="errors.password.status">
                                        <strong>{{ errors.password.message }}</strong>
                                </span>
                        </div>
                    </div>

                    <div class="form-group row mb-0">
                        <div class="col-md-8 offset-md-4">
                            <button class="btn btn-outline-primary"
                                    @click="login()">
                                Войти
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

  import { mapActions } from 'vuex';
  export default {
    name: "login",

    data() {
      return {
        user: {
          email: '',
          password: ''
        },
        errors: {
          email: {
            status: false,
            message: ''
          },
          password: {
            status: false,
            message: ''
          }
        },
        swal: {
          errors: [],
          message: ``,
        }
      }
    },

    methods: {
      validate() {

      },

      ...mapActions('Auth', {
        setUser: 'setUser'
      }),

      async login() {
        const response = await axios.post('/login', this.user);
        if (response.status !== 200 || response.data.status === 'error') {
          this.errors = (response.data.errors !== undefined) ? response.data.errors : {};
          this.swal.message = this.getSwalMessage();
          this.$swal({
            title: 'Ошибка!',
            html: response.data.msg + this.swal.message,
            type: 'error'
          });
        }
        if (response.data.user.role !== 'admin') {
          this.$swal('Ошибка!', 'Неверные данные', 'error');
          return false;
        }
        localStorage.setItem('token', response.data.token);
        localStorage.setItem('user', JSON.stringify(response.data.user));
        localStorage.setItem('csrf_token', response.data.user.csrf_token);
        this.setUser(response.data.user);
        this.$router.push('/admin/tatoos');
      },

      getSwalMessage() {
        return (this.swal.errors.length) ?
            `<div class="alert alert-danger m-t-20">
                        <ul class="p-l-20 p-r-20">
                            ${Object.values(this.swal.errors).map(err => `<li class="text-danger">${err[0]}</li>`)}
                        </ul>
                </div>`
            : '';
      }
    },

    // watch: {
    //     'user.email': function () {
    //         let email_len = this.user.email.length;
    //         if (!email_len) {
    //             this.errors.email.status = false;
    //             this.errors.message = 'Вы не ввели email';
    //         }
    //         if (!email_len < 10 && !email_len > 0) {
    //             this.errors.email.status = false;
    //             this.errors.message = 'Длина email не может быть менее 10 символов';
    //         }
    //         //регулярка
    //     },
    //
    //     'user.password': function () {
    //         let pass_len = this.user.password.length;
    //         if (!pass_len) {
    //             this.errors.password.status = false;
    //             this.errors.message = 'Вы не ввели пароль';
    //         }
    //         if (!pass_len < 6 && !email_len > 0) {
    //             this.errors.password.status = false;
    //             this.errors.message = 'Длина пароля не может быть менее 6 символов';
    //         }
    //         //регулярка
    //     }
    // }
  }
</script>

<style scoped>

</style>
