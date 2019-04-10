<template>
    <div class="row">
        <div class="col-12">
            <div class="form-group col-4">
                <label for="">Название</label>
                <input type="text"
                       v-model="tatoo.name"
                       class="form-control">
            </div>
            <div class="form-group col-4">
                <label for="">Фотография</label>
                <button class="btn btn-outline-primary"
                        @click="toggleCropper"
                        v-if="! crop.tmp.length && ! tatoo.url.length">
                    Загрузить
                </button>
                <cropper v-model="crop.show"
                         field="img"
                         :params="{'channel': 'tatoo'}"
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
                <!--<input type="email"-->
                       <!--v-model="tatoo.url"-->
                       <!--class="form-control">-->
            </div>
            <div class="form-group col-4">
                <label for="">Стоимость</label>
                <input type="text"
                       v-model.number="tatoo.price"
                       class="form-control">
            </div>
            <div class="form-group col-4">
                <label for="">Описание</label>
                <textarea v-model="tatoo.description"
                          class="form-control">
                </textarea>
            </div>
            <div class="form-group col-4">
                <button class="btn btn-outline-success" v-if="$route.params.id" @click="save">
                    Сохранить
                </button>
                <button class="btn btn-outline-success" @click="save" v-else>
                    Добавить
                </button>
                <button class="btn btn-outline-secondary" @click="$router.push({ name: 'tatoos' })">
                    Отмена
                </button>
            </div>
        </div>
    </div>
</template>

<script>
  import myUpload from 'vue-image-crop-upload';

  export default {
    name: "tatoo_form",
    components: {
     'cropper': myUpload
    },
    data() {
      return {
        tatoo: {
          name: '',
          description: '',
          url: '',
          price: ''
        },
        crop: {
          show: false,
          destination: '',
          tmp: ''
        }
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
        this.tatoo.url = response.tmp;
        this.crop.tmp = response.tmp.replace('public', 'storage');
        this.crop.destination = response.tmp.replace('tmp', 'store');
      },

      cropUploadFail(status, field) {
        console.log(status);
      },

      async removeImage() {
        const response = await axios.post('/image/remove', { 'destination': this.tatoo.url });
        if (response.status !== 200 || response.data.status === 'error') {
          this.$swal('Ошибка!', response.data.msg, 'error');
          return false;
        }
        this.tatoo.url = '';
        this.crop.tmp = '';
      },

      async save() {
        if (this.$route.params.id) {
          const response = await axios.post('/tatoos/update/' + this.$route.params.id, { ...this.tatoo, destination: this.crop.destination});
          if (response.status !== 200 || response.data.status === 'error') {
            this.$swal('Ошибка!', response.data.msg, 'error');
            return false;
          }
          this.$swal('Успешно!', response.data.msg, 'success');
          this.$router.push({ name: 'tatoos' });
          return true;
        } else {
          const response = await axios.post('/tatoos/create', { ...this.tatoo, destination: this.crop.destination});
          if (response.status !== 200 || response.data.status === 'error') {
            this.$swal('Ошибка!', response.data.msg, 'error');
            return false;
          }
          this.$swal('Успешно!', response.data.msg, 'success');
          this.$router.push({ name: 'tatoos' });
          return true;
        }
      },

      async loadData() {
        const response = await axios.get('/tatoos/edit/' + this.$route.params.id);
        if (response.status !== 200 || response.data.status === 'error') {
          this.$swal('Ошибка!', response.data.msg, 'error');
          return false;
        }
        this.tatoo = response.data.tatoo;
        this.crop.tmp = this.tatoo.url.replace('public', 'storage');
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