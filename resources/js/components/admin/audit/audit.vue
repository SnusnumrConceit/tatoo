<template>
    <div class="container-fluid p-t-30">
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-md-4">
                        <h2 class="title-1 m-b-25">Журнал аудита</h2>
                    </div>
                    <!--<button class="btn btn-outline-success" @click="$router.push({ path: '/admin/audits/create' })">-->
                    <!--Добавить-->
                    <!--</button>-->
                    <!--<div class="col-md-4 players__search">-->
                    <!--<div class="input-group">-->
                    <!--&lt;!&ndash;<div class="input-group-btn">&ndash;&gt;-->
                    <!--&lt;!&ndash;<button class="btn btn-secondary" :disabled="search.processing" @click="searchWrap">&ndash;&gt;-->
                    <!--&lt;!&ndash;<i class="fa fa-search"></i>&ndash;&gt;-->
                    <!--&lt;!&ndash;</button>&ndash;&gt;-->
                    <!--&lt;!&ndash;</div>&ndash;&gt;-->
                    <!--<input type="text"-->
                    <!--class="form-control"-->
                    <!--style="font-size: 14px;"-->
                    <!--v-model.lazy="search.keyword"-->
                    <!--v-debounce="300"-->
                    <!--placeholder="Поиск...">-->
                    <!--<button v-if="isSearch && search.keyword.length" @click="resetSearch">-->
                    <!--<i class="fa fa-times"></i>-->
                    <!--</button>-->
                    <!--</div>-->
                </div>
            </div>
            <div class="table-responsive table--no-card m-b-40" v-if="audits.length">
                <table class="table table-borderless table-striped table-earning">
                    <thead>
                    <th>
                        Событие
                        <!--<i class="fa fa-sort-amount-up" v-if="filter.name === 'name' && filter.type === 'DESC'"></i>-->
                        <!--<i class="fa fa-sort-amount-down" v-else-if="filter.name === 'name' && filter.type === 'ASC'"></i>-->
                    </th>
                    <th>
                        Совершивший действие
                    </th>
                    <th>
                        Статус
                    </th>
                    <th>
                        Дата и время
                    </th>
                    </thead>
                    <tbody v-for="(audit, index) in audits" :key="audit.id">
                    <td>{{ audit.event }}</td>
                    <td>{{ checkEmpty(audit.user, 'user') }}</td>
                    <td>{{ audit.status }}</td>
                    <td>{{ audit.created_at }}</td>
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
                Не найдено ни одной записи в журнале
            </div>
        </div>
    </div>
    </div>
</template>

<script>
  export default {
    name: "audit",
    data() {
      return {
        audits: [],

        pagination: {
          last_page: 1,
          page: 1
        }
      }
    },
    methods: {
      checkEmpty(data, type) {
        switch (type) {
          case 'user': return (data !== null) ? `${data.first_name} ${data.last_name}` : '';
        }
      },

      switchPage($page) {
        this.pagination.page = page;
        this.loadData();
      },

      async loadData() {
        const response = await axios.get('/audits', { params: { page: this.pagination.page }});
        if (response.status !== 200 || response.data.status === 'error') {
          this.$swal('Ошибка', response.msg, 'error');
          return false;
        }
        this.audits = response.data.audits.data;
        this.pagination.last_page = response.data.audits.last_page;
      }
    },

    created() {
      this.loadData();
    }
  }
</script>

<style scoped>

</style>