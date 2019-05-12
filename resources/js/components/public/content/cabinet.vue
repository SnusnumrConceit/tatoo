<template>
    <div>
        <div class="modal-header">
            <h2>{{ user_info.full_name }}</h2>
            <button class="close"
                    @click="$root.$emit('hide', 'cabinet')">
                &times;
            </button>
        </div>
        <div class="modal-body">
            <div class="orders-container" v-if="user_info.orders.length !== 0">
                <table class="table table-borderless">
                    <thead class="thead-dark">
                    <th>Татуировка</th>
                    <th>Стоимость</th>
                    <th>Дата записи</th>
                    <th>Статус</th>
                    </thead>
                    <tbody>
                    <tr class="" v-for="order in user_info.orders.data" :key="order.id">
                        <td class="">
                            {{ order.tatoo }}
                        </td>
                        <td>
                            {{ order.price }}
                        </td>
                        <td>
                            {{ order.note_date }}
                        </td>
                        <td>
                            <span class="text-danger" v-if="order.status_type === 0">
                                        <i>{{ order.status }}</i>
                                    </span>
                            <span class="text-info" v-else-if="order.status_type === 1">
                                        <i>{{ order.status }}</i>
                                    </span>
                            <span class="text-success" v-else-if="order.status_type === 2">
                                        <i>{{ order.status }}</i>
                                    </span>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <paginate v-model="pagination.page"
                          v-if="pagination.last_page > 1"
                          :page-count="pagination.last_page"
                          :container-class="'pagination'"
                          :page-class="'page-item'"
                          :page-link-class="'page-link'"
                          :prev-text="'Пред.'"
                          :prev-class="'page-item'"
                          :prev-link-class="'page-link'"
                          :next-text="'След.'"
                          :next-class="'page-item'"
                          :next-link-class="'page-link'"
                          :click-handler="switchPage"></paginate>
            </div>
            <div class="alert alert-info" v-else>
                Вы не совершили ни одного заказа
            </div>
        </div>
    </div>
</template>

<script>
  import {mapGetters} from 'vuex';

  export default {
    name: "cabinet",

    props: {
      id: ''
    },

    data() {
      return {
        user_info: {
          orders: []
        },

        pagination: {
          page: 1,
          last_page: 1
        }
      }
    },

    methods: {
      switchPage(page) {
        this.page =  page;
        this.loadData();
      },

      async loadData() {
        const response = await axios.get(`/users/info`, { params: { page: this.page}});
        if (response.status !== 200 || response.data.status === 'error') {
          this.$swal('Ошибка!', response.data.msg, 'error');
          return false;
        }
        this.user_info = response.data.user_info;
        this.pagination.last_page = response.data.user_info.orders.last_page;
      }
    },

    computed: {
      ...mapGetters('Auth', {
        'user': 'getUser'
      })
    },

    created() {
      this.loadData();
    }
  }
</script>

<style scoped>

</style>