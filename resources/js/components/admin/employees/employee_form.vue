<template>
    <div class="row">
        <div class="col-12">
            <div class="form-group col-4">
                <label for="">Имя</label>
                <input type="email"
                       v-model="employee.name"
                       class="form-control">
            </div>
            <div class="form-group col-4">
                <label for="">Фотография</label>
                <button class="btn btn-outline-primary"
                        @click="toggleCropper"
                        v-if="! crop.tmp.length && ! employee.url.length">
                    Загрузить
                </button>
                <cropper v-model="crop.show"
                         field="img"
                         :params="{'channel': 'master'}"
                         url="/image/upload"
                         lang-type="ru"
                         :headers="{'X-CSRF-TOKEN': 'wtC3am56kRpK1Dd8AZ3UgWAsjZXVFrP35b6WcOtK'}"
                         @crop-success="cropSuccess"
                         @crop-upload-success="cropUploadSuccess"
                         @crop-upload-fail="cropUploadFail"
                ></cropper>
                <img :src="crop.tmp" alt="">
                <button class="btn btn-outline-danger"
                        @click="removeImage"
                        v-if="crop.tmp.length">
                    Удалить
                </button>
            </div>
            <div class="form-group col-4">
                <label for="">Должность</label>
                <select class="form-control" v-model="employee.appointment_id">
                    <option :value="appointment.id"
                            v-for="appointment in appointments"
                            :key="appointment.id">
                        {{ appointment.name }}
                    </option>
                </select>
            </div>
            <div class="form-group col-4">
                <label for="">Дата рождения</label>
                <datepicker v-model="employee.birthday"
                            :monday-first="true"
                            :bootstrap-styling="true"
                            :language="ru">
                </datepicker>
            </div>
            <div class="form-group col-4">
                <label for="">Описание</label>
                <textarea v-model="employee.description"
                          class="form-control">
                </textarea>
            </div>
            <div class="form-group col-4">
                <div class="master-tatoo row p-b-15" v-for="(tatoo, index) in employee.tatoos">
                    <div class="col-10">
                        <v-select v-model="employee.tatoos[index]" label="name" :options="tatoos">
                            <span slot="no-options">Не найдено ни одной татуировки</span>
                        </v-select>
                    </div>
                    <div class="col">
                        <i class="fa fa-trash text-danger" @click="employee.tatoos.splice(index, 1)"></i>
                    </div>
                </div>
            </div>
            <div class="form-group col-4">
                <button class="btn btn-outline-primary" @click="employee.tatoos.push({})">
                    Добавить татуировки <i class="far fa-plus-square"></i>
                </button>
            </div>
            <div class="form-group col-4">
                <button class="btn btn-outline-success" v-if="$route.params.id" @click="save">
                    Сохранить
                </button>
                <button class="btn btn-outline-success" @click="save" v-else>
                    Добавить
                </button>
                <button class="btn btn-outline-secondary" @click="$router.push({ name: 'employees' })">
                    Отмена
                </button>
            </div>
        </div>
    </div>
</template>

<script>
  import Datepicker from 'vuejs-datepicker';
  import {ru,en} from 'vuejs-datepicker/dist/locale';
  import myUpload from 'vue-image-crop-upload';

  export default {
    name: "employee_form",
    components: {Datepicker, 'cropper': myUpload },
    data() {
      return {
        employee: {
          name: '',
          description: '',
          url: '',
          birthday: Date.now(),
          appointment_id: '',
          tatoos: [
            {}
          ]
        },

        appointments: [],

        crop: {
          show: false,
          destination: '',
          tmp: ''
        },

        tatoos: [],

        spinner:false,

        ru: ru,
        en: en,

        swal: {
          errors: [],
          message: ``
        },
      }
    },
    computed: {
      selectOptions() {
        return this.tatoos.map(t => ({label: t.name, value: t.id}));
      }
    },
    methods: {
      toggleCropper() {
        this.crop.show = !this.crop.show;
      },

      cropSuccess(imgDataUrl, field) {
        console.log(imgDataUrl);
      },

      cropUploadSuccess(response, field) {
        if (response.status === 'error') {
          this.$swal('Ошибка!', response.msg, 'error');
          return false;
        }
        this.employee.url = response.tmp;
        this.crop.tmp = response.tmp.replace('public', 'storage');
        this.crop.destination = response.tmp.replace('tmp', 'store');
      },

      cropUploadFail(status, field) {
        console.log(status);
      },

      async removeImage() {
        const response = await axios.post('/image/remove', { 'destination': this.employee.url });
        if (response.status !== 200 || response.data.status === 'error') {
          this.$swal('Ошибка!', response.data.msg, 'error');
          return false;
        }
        this.employee.url = '';
        this.crop.tmp = '';
      },

      async save() {
        if (this.$route.params.id) {
          const response = await axios.post('/employees/update/' + this.$route.params.id, { ...this.employee, destination: this.crop.destination});
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
          this.$router.push({ name: 'employees' });
          return true;
        } else {
          const response = await axios.post('/employees/create', { ...this.employee, destination: this.crop.destination});
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
          this.$router.push({ name: 'employees' });
          return true;
        }
      },

      async loadData() {
        const response = await axios.get('/employees/edit/' + this.$route.params.id);
        if (response.status !== 200 || response.data.status === 'error') {
          this.$swal('Ошибка!', response.data.msg, 'error');
          return false;
        }
        this.employee = response.data.employee;
        this.crop.tmp = this.employee.url.replace('public', 'storage');
        return true;
      },

      async loadExtendsData() {
        const response = await axios.get('/employees/extends');
        if (response.status !== 200 || response.data.status === 'error') {
          this.$swal('Ошибка!', response.data.msg, 'error');
          return false;
        }
        this.appointments = response.data.appointments;
        this.tatoos = response.data.tatoos;
        return true;
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
      this.loadExtendsData();
      if (this.$route.params.id) {
        this.loadData();
      }
    }
  }
</script>

<style lang="scss">
    @import "../../../../../node_modules/vue-select/src/scss/vue-select.scss";
</style>