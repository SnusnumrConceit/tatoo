<template>
    <div class="row">
        <div class="col-12">
            <div class="form-group col-4">
                <label for="">Имя</label>
                <input type="email"
                       v-model="tatoo.name"
                       class="form-control">
            </div>
            <div class="form-group col-4">
                <label for="">Фотография</label>
                <input type="email"
                       v-model="tatoo.url"
                       class="form-control">
            </div>
            <div class="form-group col-4">
                <label for="">Дата рождения</label>
                <datepicker v-model="tatoo.birthday"
                            :monday-first="true"
                            :bootstrap-styling="true"
                            :language="ru">
                </datepicker>
            </div>
            <div class="form-group col-4">
                <label for="">Описание</label>
                <textarea v-model="tatoo.description"
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
                <button class="btn btn-outline-secondary" @click="$router.push({ name: 'tatoos' })">
                    Отмена
                </button>
            </div>
        </div>
    </div>
</template>

<script>
  export default {
    name: "tatoo_form",
    data() {
      return {
        tatoo: {
          name: '',
          description: '',
          url: '',
          birthday: Date.now()
        }
      }
    },
    methods: {
      async save() {
        if (this.$route.params.id) {
          const response = await axios.post('/tatoos/update/' + this.$route.params.id, this.tatoo);
          if (response.status !== 200 || response.data.status === 'error') {
            this.$swal('Ошибка!', response.data.msg, 'error');
            return false;
          }
          this.$swal('Успешно!', response.data.msg, 'success');
          this.$router.push({ name: 'tatoos' });
          return true;
        } else {
          const response = await axios.post('/tatoos/create', this.tatoo);
          if (response.status !== 200 || response.data.status === 'error') {
            this.$swal('Ошибка!', response.data.msg, 'error');
            return false;
          }
          this.$swal('Успешно!', response.data.msg, 'success');
          this.$router.push({ name: 'tatoos' });
          return true;
        }
      },

      async loadData() {
        const response = await axios.get('/tatoos/edit/' + this.$route.params.id);
        if (response.status !== 200 || response.data.status === 'error') {
          this.$swal('Error!', response.data.msg, 'error');
          return false;
        }
        this.tatoo = response.data.tatoo;
        return true;
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