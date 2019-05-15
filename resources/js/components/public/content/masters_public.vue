<template>
    <div class="row">
        <div class="col-12">
            <div class="card col-3" v-for="master in masters" @click="showMasterModal(master)">
                <div class="card-header">
                    <h2>{{ master.name }}</h2>
                </div>
                <div class="card-body">
                    <div class="col-12">
                        <img :src="master.url" alt="">
                    </div>
                </div>
            </div>
        </div>
        <modal name="master" @before-open="loadTatoos" :height="'auto'" :scrollable="true">
            <div class="modal-header">
                {{ master_info.name }}
            </div>
            <div class="modal-body">
                <div class="col-12">
                    <p>{{ master_info.description }}</p>
                </div>
                <div class="col-12" v-if="master_info.tatoos && master_info.tatoos.length > 1">
                    <h3>Татуировки</h3>
                </div>
                <div class="col-12">
                    <div class="card col-6" v-for="tatoo in master_info.tatoos">
                        <div class="card-header">
                            <h2>{{ tatoo.name }}</h2>
                        </div>
                        <div class="card-body">
                            <img :src="tatoo.url" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </modal>
    </div>
</template>

<script>

  export default {
    name: "masters_public",
    data() {
      return {
        masters: [],
        master_info: {
          tatoos: [],
        }
      }
    },
    computed: {},
    methods: {
      showMasterModal(master) {
        this.master_info  = { ...master, tatoos: [{}]};
        this.$modal.show('master');
      },

      hideMasterModal() {
        this.$modal.hide('order');
      },

      async loadTatoos() {
        const response = await axios.get(`/masters/${ this.master_info.id }/tatoos/`);
        if (response.status !== 200 || response.data.status === 'error') {
          this.$swal('Ошибка!', response.data.msg, 'error');
          return false;
        }
        this.$nextTick(() => {
            this.$set(this.master_info, 'tatoos', response.data.tatoos);
        });
      },

      async loadData() {
        const response = await axios.get('/masters');
        if (response.status !== 200 || response.data.status === 'error') {
          console.log('wtf', response.data);
          this.$swal('Ошибка!', response.data.msg, 'error');
          return false;
        }
        this.masters = response.data.employees.data;
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
