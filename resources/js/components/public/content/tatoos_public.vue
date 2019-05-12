<template>
    <div class="row">
        <div class="col-12">
            <div class="card col-3" v-for="tatoo in tatoos">
                <div class="card-header">
                    <h2>{{ tatoo.name }}</h2>
                </div>
                <div class="card-body">
                    <div class="col-12">
                        <img :src="tatoo.url" alt="">
                    </div>
                    <div class="col-12">
                        {{ tatoo.price }}
                        <button class="btn btn-outline-primary" @click="showOrderModal(tatoo)">
                            Записаться
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <modal name="order" @before-open="loadMasters" :height="400" :scrollable="true">
            <div class="modal-header">
                Заказ на {{ tmp_order.name }}
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="">Выберите дату и время</label>
                    <datepicker v-model="order.note_date"
                                :monday-first="true"
                                :bootstrap-styling="true"
                                :language="ru">
                    </datepicker>
                    <vue-timepicker v-model="order.note_time" format="HH:mm"></vue-timepicker>
                </div>
                <div class="form-group">
                    <label for="">Выберите мастера:</label>
                    <v-select :options="masters" v-model="order.master" label="name">

                    </v-select>
                </div>
            </div>
            <div class="modal-footer">
                {{ tmp_order.price }}
                <button class="btn btn-outline-success" @click="makeOrder">
                    Записаться
                </button>
            </div>
        </modal>
    </div>
</template>

<script>
  import Datepicker from 'vuejs-datepicker';
  import VueTimepicker from 'vue2-timepicker'
  import {ru,en} from 'vuejs-datepicker/dist/locale';

  export default {
    name: "tatoos_public",
    components: { Datepicker, VueTimepicker },
    data() {
      return {
        tatoos: [],
        order: {
          master: '',
          tatoo: '',
          note_date: Date.now(),
          note_time: {
            'HH': '00',
            'mm': '00'
          }
        },
        masters: [],
        tmp_order: {},

        ru: ru
      }
    },
    computed: {},
    methods: {
      showOrderModal(tatoo) {
        this.tmp_order  = tatoo;
        this.$modal.show('order');
      },

      hideOrderModal() {
        this.$modal.hide('order');
      },

      async loadMasters() {
        const response = await axios.get('/tatoos/masters/2');
        if (response.status !== 200 || response.data.status === 'error') {
          this.$swal('Ошибка!', response.data.msg, 'error');
          return false;
        }
        this.masters = response.data.masters;
      },

      async makeOrder() {
        this.order.tatoo = this.tmp_order.id;
        const response = await axios.post('/orders/publish', { ...this.order });
        if (response.status !== 200 || response.data.status === 'error') {
          this.$swal('Ошибка!', response.data.msg, 'error');
          return false;
        }
        this.$swal('Успешно!', response.data.msg, 'success');
        this.hideOrderModal();
        return true;
      },

      async loadData() {
        const response = await axios.get('/tatoos');
        if (response.status !== 200 || response.data.status === 'error') {
          console.log('wtf', response.data);
          this.$swal('Ошибка!', response.data.msg, 'error');
          return false;
        }
        this.tatoos = response.data.tatoos.data;
      },
    },

    created() {
      this.loadData();
    }
  }
</script>

<style scoped>
    .col-12 {
        display: flex;
    }
</style>