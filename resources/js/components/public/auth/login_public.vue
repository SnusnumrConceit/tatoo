<template>
    <div>
        <div class="modal-header">
            <h2>Авторизация</h2>
            <button class="close"
                    @click="$root.$emit('hide', 'login')">
                &times;
            </button>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label for="">Email</label>
                <input class="form-control" v-model="login.email">
            </div>
            <div class="form-group">
                <label for="">Пароль</label>
                <input class="form-control" type="password" v-model="login.password">
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-outline-primary" @click="authorize">
                Войти
            </button>
        </div>
    </div>
</template>

<script>
  import {mapActions} from 'vuex';

  export default {
    name: "login_public",
    data() {
      return {
        login: {
          email: '',
          password: ''
        },

        swal: {
          errors: [],
          message: ``
        }
      }
    },

    methods: {
      async authorize() {
        const response = await axios.post('/login', this.login);
        if (response.status !== 200 || response.data.status === 'error') {
          this.swal.errors = (response.data.errors !== undefined) ? response.data.errors : {};
          this.swal.message = this.getSwalMessage();
          this.$swal({
            title: 'Ошибка!',
            html: response.data.msg + this.swal.message,
            type: 'error'
          });
        } else {
          this.fillStorage(response.data);
          this.$modal.hide('login');
          this.login = {};
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