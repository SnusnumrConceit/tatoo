<template>
    <div class="row">
        <div class="col-12">
            <div class="form-group col-4">
                <label for="">Название</label>
                <input type="text"
                       v-model="appointment.name"
                       class="form-control">
            </div>
            <div class="form-group col-4">
                <button class="btn btn-outline-success" v-if="$route.params.id" @click="save">
                    Сохранить
                </button>
                <button class="btn btn-outline-success" @click="save" v-else>
                    Добавить
                </button>
                <button class="btn btn-outline-secondary" @click="$router.push({ name: 'appointments' })">
                    Отмена
                </button>
            </div>
        </div>
    </div>
</template>

<script>
  export default {
    name: "appointment_form",
    data() {
      return {
        appointment: {
          name: '',
        },
      }
    },
    methods: {
      async save() {
        if (this.$route.params.id) {
          const response = await axios.post('/appointments/update/' + this.$route.params.id, this.appointment);
          if (response.status !== 200 || response.data.status === 'error') {
            this.$swal('Ошибка!', response.data.msg, 'error');
            return false;
          }
          this.$swal('Успешно!', response.data.msg, 'success');
          this.$router.push({ name: 'appointments' });
          return true;
        } else {
          const response = await axios.post('/appointments/create', this.appointment);
          if (response.status !== 200 || response.data.status === 'error') {
            this.$swal('Ошибка!', response.data.msg, 'error');
            return false;
          }
          this.$swal('Успешно!', response.data.msg, 'success');
          this.$router.push({ name: 'appointments' });
          return true;
        }
      },

      async loadData() {
        const response = await axios.get('/appointments/edit/' + this.$route.params.id);
        if (response.status !== 200 || response.data.status === 'error') {
          this.$swal('Error!', response.data.msg, 'error');
          return false;
        }
        this.appointment = response.data.appointment;
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