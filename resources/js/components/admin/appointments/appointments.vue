<template>
    <div class="container-fluid p-t-30">
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-md-4">
                        <h2 class="title-1 m-b-25">Должности</h2>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-outline-success" @click="$router.push({ path: '/appointments/create' })">
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
                    <div class="md-2">
                        <button class="btn btn-outline-primary" @click="exportData">
                            Export
                        </button>
                    </div>
                </div>
                <div class="table-responsive table--no-card m-b-40" v-if="appointments.length">
                    <table class="table table-borderless table-striped table-earning">
                        <thead>
                            <th @click="setFilter('name')" class="text-left">
                                Наименование
                                <i class="fa fa-sort-amount-up" v-if="filter.name === 'name' && filter.type === 'DESC'"></i>
                                <i class="fa fa-sort-amount-down" v-else-if="filter.name === 'name' && filter.type === 'ASC'"></i>
                            </th>
                            <th></th>
                        </thead>
                        <tbody v-for="(appointment, index) in appointments" :key="appointment.id">
                            <td>{{ appointment.name }}</td>
                            <td>
                                <i class="fa fa-cog text-success" @click="$router.push({path: '/appointments/' + appointment.id})"></i>
                                <i class="fa fa-trash text-danger" @click="remove(index, appointment.id)"></i>
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
                    По запросу {{ search.keyword }} не найдено ни одной должности
                </div>
            </div>
        </div>
    </div>
</template>

<script>
  import debounce from 'v-debounce';
    
  export default {
    directives: {debounce},
    data() {
      return {
        appointments: [],

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

      async remove(index, id) {
        const response = await axios.post('/appointments/remove/' + id);
        if (response.status !== 200 || response.data.status === 'error') {
          this.$swal('Ошибка!', response.data.msg, 'error');
          return false;
        } else {
          this.$swal('Успешно!', response.data.msg, 'success');
          this.appointments.splice(index, 1);
        }
      },

      async searchData() {
        const response = await axios.get('/appointments/search', { params: {
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
          this.appointments = response.data.appointments.data;
          this.pagination.last_page = response.data.appointments.last_page;
        }
      },

      async loadData() {
        const response = await axios.get('/appointments', { params: { page: this.pagination.page} });
        if (response.status !== 200 || !response.data.status === 'error') {
          console.log(response.data.msg);
        } else {
          this.appointments = response.data.appointments.data;
          this.pagination.last_page = response.data.appointments.last_page;
        }
      },

      async exportData() {
        // const response = await axios.get('/appointments/export');
        axios({
          url: '/appointments/export',
          responseType: 'blob',
          method: 'GET'
        })
        .then((response) => {
          let blob = new Blob([response.data]);
          let link = document.createElement('a')
          link.href = window.URL.createObjectURL(blob)
          link.download = 'appointments.xlsx'
          link.click()
        })
        .catch((err) => {
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