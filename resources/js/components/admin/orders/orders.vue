<template>
    <div class="container-fluid p-t-30">
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-md-4">
                        <h2 class="title-1 m-b-25">Заказы</h2>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-outline-success" @click="$router.push({ path: '/orders/create' })">
                            Добавить
                        </button>
                    </div>
                    <div class="col-md-4 players__search">
                        <div class="input-group">
                            <!--<div class="input-group-btn">-->
                            <!--<button class="btn btn-secondary" :disabled="search.processing" @click="searchWrap">-->
                            <!--<i class="fa fa-search"></i>-->
                            <!--</button>-->
                            <!--</div>-->
                            <input type="text"
                                   class="form-control"
                                   style="font-size: 14px;"
                                   v-model.lazy="search.keyword"
                                   v-debounce="300"
                                   placeholder="Поиск...">
                            <button v-if="isSearch && search.keyword.length" @click="resetSearch">
                                <i class="fa fa-times clear-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="table-responsive table--no-card m-b-40" v-if="orders.length">
                    <table class="table table-borderless table-striped table-earning">
                        <thead>
                        <th @click="setFilter('name')" class="text-left">
                            Клиент
                            <i class="fa fa-sort-amount-up" v-if="filter.name === 'name' && filter.type === 'DESC'"></i>
                            <i class="fa fa-sort-amount-down" v-else-if="filter.name === 'name' && filter.type === 'ASC'"></i>
                        </th>
                        <th @click="setFilter('tatoo')" class="text-left">
                            Тату
                            <i class="fa fa-sort-amount-up" v-if="filter.name === 'tatoo' && filter.type === 'DESC'"></i>
                            <i class="fa fa-sort-amount-down" v-else-if="filter.name === 'tatoo' && filter.type === 'ASC'"></i>
                        </th>
                        <th @click="setFilter('price')" class="text-left">
                            Цена
                            <i class="fa fa-sort-amount-up" v-if="filter.name === 'price' && filter.type === 'DESC'"></i>
                            <i class="fa fa-sort-amount-down" v-else-if="filter.name === 'price' && filter.type === 'ASC'"></i>
                        </th>
                        <th @click="setFilter('note_date')" class="text-left">
                            На какое время
                            <i class="fa fa-sort-amount-up" v-if="filter.name === 'note_date' && filter.type === 'DESC'"></i>
                            <i class="fa fa-sort-amount-down" v-else-if="filter.name === 'note_date' && filter.type === 'ASC'"></i>
                        </th>
                        <th @click="setFilter('status')" class="text-left">
                            Статус
                            <i class="fa fa-sort-amount-up" v-if="filter.name === 'status' && filter.type === 'DESC'"></i>
                            <i class="fa fa-sort-amount-down" v-else-if="filter.name === 'status' && filter.type === 'ASC'"></i>
                        </th>
                        <th @click="setFilter('created_at')" class="text-left">
                            Время заказа
                            <i class="fa fa-sort-amount-up" v-if="filter.name === 'created_at' && filter.type === 'DESC'"></i>
                            <i class="fa fa-sort-amount-down" v-else-if="filter.name === 'created_at' && filter.type === 'ASC'"></i>
                        </th>
                        <th></th>
                        </thead>
                        <tbody>
                            <tr v-for="(order, index) in orders" :key="order.id">
                                <td @click="showModal(order.id)">{{ order.customer }}</td>
                                <td>{{ order.tatoo }} </td>
                                <td>{{ order.price }}</td>
                                <td>{{ order.note_date }} </td>
                                <td>{{ order.status_type }}</td>
                                <td>{{ order.created_at }} </td>
                                <td>
                                    <i class="fa fa-cog text-success" @click="$router.push({path: '/orders/' + order.id})"></i>
                                    <i class="fa fa-trash text-danger" @click="remove(index, order.id)"></i>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <paginate v-model="pagination.page"
                              v-if="pagination.last_page"
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
                    По запросу {{ search.keyword }} не найдено ни одного пользователя
                </div>
                <modal name="order_info">
                    <div class="modal-header" v-if="order_info.customer.length">
                        <h2>
                            {{ order_info.customer}} - {{ order_info.tatoo.name }}
                        </h2>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-3">
                                <img :src="order_info.url" alt="">
                            </div>
                            <div class="col-9">
                                <div class="col-12">
                                    <h3>Цена:</h3>
                                    <p>{{ order_info.price }}</p>
                                </div>
                                <div class="col-12">
                                    <h3>Описание</h3>
                                    {{ order_info.status}}
                                </div>
                            </div>
                        </div>
                    </div>
                </modal>
            </div>
        </div>
    </div>
</template>

<script>
  import debounce from 'v-debounce';

  export default {
    name: "orders",
    directives: {debounce},
    data() {
      return {
        orders: [],

        pagination: {
          page: 1,
          last_page: 1
        },

        search: {
          keyword: '',
          isSearch: false,
          processing: false, //для дисабли кнопки
        },

        filter: {
          name: '',
          type: ''
        },

        order_info: {
          customer: {},
          tatoo: {}
        }
      }
    },


    computed: {
      isSearch() {
        return this.search.isSearch;
      }
    },

    methods: {
      switchPage(page) {
        this.pagination.page = page;
        if (!this.search.isSearch) {
          this.loadData();
        } else {
          this.searchData();
        }
        return true;
      },

      setFilter(name) {
        switch (this.filter.type) {
          case '':
            this.filter.type = 'DESC';
            break;
          case 'DESC':
            this.filter.type = 'ASC';
            break;
          case 'ASC':
            this.filter.type = 'DESC';
            break;
          default:
            break;
        }
        this.filter.name = name;
        this.search.isSearch = true;
        this.switchPage(1);
      },

      resetSearch() {
        this.search.isSearch = false;
        this.search.keyword = '';
        this.switchPage(1);
        return true;
      },

      searchWrap() {
        this.search.isSearch = true;
        this.search.processing = true;
        this.switchPage(1);
      },

      showModal(id) {
        this.loadInfo(id);
        this.$modal.show('order_info');
      },

      async remove(index, id) {
        const response = await axios.post('/orders/remove/' + id);
        if (response.status !== 200 || response.data.status === 'error') {
          this.$swal('Ошибка!', response.data.msg, 'error');
          return false;
        } else {
          this.$swal('Успешно!', response.data.msg, 'success');
          this.orders.splice(index, 1);
        }
      },

      async searchData() {
        const response = await axios.get('/orders/search', { params: {
            keyword: this.search.keyword,
            filter: {...this.filter},
            page: this.pagination.page
          }});
        if (response.status !== 200) {
          this.$swal('Ошибка', response.data.msg, 'error');
          this.search.processing = false;
          return false;
        } else {
          this.search.processing = false;
          this.orders = response.data.orders.data;
          this.pagination.last_page = response.data.orders.last_page;
        }
      },

      async loadInfo(id) {
        const response = await axios.get('/orders/info/' + id);
        if (response.status !== 200 || !response.data.status === 'error') {
          this.$swal('Ошибка!', response.data.msg, 'error');
          return false;
        } else {
          this.order_info = response.data.order;
        }
      },

      async loadData() {
        const response = await axios.get('/orders', { params: { page: this.pagination.page} });
        if (response.status !== 200 || !response.data.status === 'error') {
          console.log(response.data.msg);
        } else {
          this.orders = response.data.orders.data;
          this.pagination.last_page = response.data.orders.last_page;
        }
      }
    },

    watch: {
      'search.keyword': function (after, before) {
        if (after === before) {
          return false;
        }
        if (!after.length) {
          this.resetSearch();
          return true;
        }
        this.searchWrap();
      },
    },

    created() {
      this.loadData();
    },
  }
</script>

<style scoped>
    .clear-search {
        color: #a7a7a7;
        position: absolute;
        right: 10px;
        bottom: 10px;
    }
</style>