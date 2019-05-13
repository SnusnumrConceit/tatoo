<template>
    <div class="row">
        <div class="col-12">
            <div class="form-group col-4">
                <label for="">Клиент</label>
                <select class="form-control" v-model="order.user_id">
                    <option :value="client.id"
                            v-for="client in clients"
                            :key="client.id">
                        {{ client.name }}
                    </option>
                </select>
            </div>
            <div class="form-group col-4">
                <label for="">Таутировка</label>
                <select class="form-control" v-model.number="order.tatoo_id" @change="loadMasters">
                    <option :value="tatoo.id"
                            v-for="tatoo in tatoos"
                            :key="tatoo.id">
                        {{ tatoo.name }}
                    </option>
                </select>
            </div>
            <div class="form-group col-4">
                <label for="">Мастер</label>
                <v-select :options="masters" label="name" v-model="order.master"></v-select>
            </div>
            <div class="form-group col-4">
                <label for="">Дата и время записи</label>
                <datepicker v-model="order.note_date"
                            :monday-first="true"
                            :bootstrap-styling="true"
                            :language="ru">
                </datepicker>
                <vue-timepicker v-model="order.note_time" format="HH:mm"></vue-timepicker>
            </div>
            <div class="form-group col-4">
                <label for="">Статус</label>
                <select name="" id="" class="form-control" v-model="order.status">
                    <option value="1">Отказано</option>
                    <option value="2">Предзаказ</option>
                    <option value="3">Завершён</option>
                </select>
            </div>
            <div class="form-group col-4">
                <button class="btn btn-outline-success" v-if="$route.params.id" @click="save">
                    Сохранить
                </button>
                <button class="btn btn-outline-success" @click="save" v-else>
                    Добавить
                </button>
                <button class="btn btn-outline-secondary" @click="$router.push({ name: 'orders' })">
                    Отмена
                </button>
            </div>
        </div>
    </div>
</template>

<script>
  import Datepicker from 'vuejs-datepicker';
  import VueTimepicker from 'vue2-timepicker'
  import {ru,en} from 'vuejs-datepicker/dist/locale';

  export default {
    name: "order_form",
    components: {Datepicker, VueTimepicker},
    data() {
      return {
        order: {
          user_id: '',
          tatoo_id: '',
          note_date: Date.now(),
          note_time: {
            HH: '00',
            mm: '00'
          },
          status: '',
          master: {}
        },

        clients: [],
        tatoos: [],

        ru: ru,
        en: en,

        swal: {
          errors: [],
          message: ``
        },

        masters: []
      }
    },
    methods: {
      async save() {
        this.order.master = this.order.master.id;
        if (this.$route.params.id) {
          const response = await axios.post('/orders/update/' + this.$route.params.id, this.order);
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
          this.$router.push({ name: 'orders' });
          return true;
        } else {
          const response = await axios.post('/orders/create', this.order);
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
          this.$router.push({ name: 'orders' });
          return true;
        }
      },

      async loadData() {
        const response = await axios.get('/orders/edit/' + this.$route.params.id);
        if (response.status !== 200 || response.data.status === 'error') {
          this.$swal('Ошибка!', response.data.msg, 'error');
          return false;
        }
        this.order = response.data.order;
          if (this.$route.params.id) {
              this.loadMasters();
          }
        return true;
      },

      async loadExtendsData() {
        const response = await axios.get('/orders/extends');
        if (response.status !== 200 || response.data.status === 'error') {
          this.$swal('Ошибка!', response.data.msg, 'error');
          return false;
        }
        this.clients = response.data.clients;
        this.tatoos = response.data.tatoos;
        return true;
      },

      async loadMasters() {
        const response = await axios.get(`/tatoos/${this.order.tatoo_id}/masters/`);
        if (response.status !== 200 || response.data.status === 'error') {
          this.$swal('Ошибка!', response.data.msg, 'error');
          return false;
        }
        this.masters = response.data.masters;
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
      this.loadExtendsData();
    },
  }
</script>

<style scoped>

</style>
