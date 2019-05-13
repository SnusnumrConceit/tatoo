<template>
    <div class="container-fluid p-t-30">
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-md-4">
                        <h2 class="title-1 m-b-25">Персонал</h2>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-outline-success" @click="$router.push({ name: 'employee_form' })">
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
                    <div class="col-md-2">
                        <button class="btn btn-outline-primary" @click="exportData()">
                            Export
                        </button>
                    </div>
                </div>
                <div class="table-responsive table--no-card m-b-40" v-if="employees.length">
                    <table class="table table-borderless table-striped table-earning">
                        <thead>
                            <th @click="setFilter('name')" class="text-left">
                                Имя
                                <i class="fa fa-sort-amount-up" v-if="filter.name === 'name' && filter.type === 'DESC'"></i>
                                <i class="fa fa-sort-amount-down" v-else-if="filter.name === 'name' && filter.type === 'ASC'"></i>
                            </th>
                            <th @click="setFilter('appointment')" class="text-left">
                                Должность
                                <i class="fa fa-sort-amount-up" v-if="filter.name === 'appointment' && filter.type === 'DESC'"></i>
                                <i class="fa fa-sort-amount-down" v-else-if="filter.name === 'appointment' && filter.type === 'ASC'"></i>
                            </th>
                            <th @click="setFilter('birthday')" class="text-left">
                                Дата рождения
                                <i class="fa fa-sort-amount-up" v-if="filter.name === 'birthday' && filter.type === 'DESC'"></i>
                                <i class="fa fa-sort-amount-down" v-else-if="filter.name === 'birthday' && filter.type === 'ASC'"></i>
                            </th>
                            <th @click="setFilter('created_at')" class="text-left">
                                Дата заступления
                                <i class="fa fa-sort-amount-up" v-if="filter.name === 'created_at' && filter.type === 'DESC'"></i>
                                <i class="fa fa-sort-amount-down" v-else-if="filter.name === 'created_at' && filter.type === 'ASC'"></i>
                            </th>
                            <th></th>
                        </thead>
                        <tbody v-for="(employee, index) in employees" :key="employee.id">
                            <td @click="showModal(employee.id)">{{ employee.name }}</td>
                            <td>{{ employee.appointment }} </td>
                            <td>{{ employee.birthday }} </td>
                            <td>{{ employee.created_at }} </td>
                            <td>
                                <i class="fa fa-cog text-success" @click="$router.push({path: '/admin/employees/' + employee.id})"></i>
                                <i class="fa fa-trash text-danger" @click="remove(index, employee.id)"></i>
                            </td>
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
                    По запросу {{ search.keyword }} не найдено ни одного работника
                </div>
                <modal name="master_info">
                    <div class="modal-header">
                        <h2>
                            {{ employee_info.name}}
                        </h2>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-3">
                                <img :src="employee_info.url" alt="">
                            </div>
                            <div class="col-9">
                                <div class="col-12">
                                    <h3>Должность:</h3>
                                    <p>{{ employee_info.appointment }}</p>
                                </div>
                                <div class="col-12">
                                    <h3>Описание</h3>
                                    {{ employee_info.description}}
                                </div>
                            </div>
                        </div>
                        <div class="row" v-if="employee_info.tatoos.length">
                            <div class="col-12" v-for="tatoo in tatoos">
                                {{ tatoo.name }}
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
    name: "employees",
    directives: {debounce},
    data() {
      return {
        employees: [],

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

        employee_info:  {
          tatoos: []
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
        this.$modal.show('master_info');
      },

      async remove(index, id) {
        const response = await axios.post('/employees/remove/' + id);
        if (response.status !== 200 || response.data.status === 'error') {
          this.$swal('Ошибка!', response.data.msg, 'error');
          return false;
        } else {
          this.$swal('Успешно!', response.data.msg, 'success');
          this.employees.splice(index, 1);
        }
      },

      async searchData() {
        const response = await axios.get('/employees/search', { params: {
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
          this.employees = response.data.employees.data;
          this.pagination.last_page = response.data.employees.last_page;
        }
      },

      async loadData() {
        const response = await axios.get('/employees', { params: { page: this.pagination.page} });
        if (response.status !== 200 || !response.data.status === 'error') {
          console.log(response.data.msg);
        } else {
          this.employees = response.data.employees.data;
          this.pagination.last_page = response.data.employees.last_page;
        }
      },

      async loadInfo(id) {
        const response = await axios.get('/employees/info/' + id);
        if (response.status !== 200 || !response.data.status === 'error') {
          this.$swal('Ошибка!', response.data.msg, 'error');
          return false;
        } else {
          this.employee_info = response.data.employee;
        }
      },

      exportData() {
        axios({
          url: '/employees/export',
          method: 'GET',
          responseType: 'blob'
        }).then((response) => {
          let blob = new Blob([response.data]);
          let link = document.createElement('a')
          link.href = window.URL.createObjectURL(blob)
          link.download = 'employees.xlsx'
          link.click()
        }).catch((err) => {
          console.log(err)
        });
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
