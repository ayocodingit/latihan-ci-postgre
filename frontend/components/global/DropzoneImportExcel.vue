<template>
  <vue-dropzone ref="myFile" id="register_file" :options="dropzoneOptions" v-on:vdropzone-sending="previewFile"
    :useCustomSlot=true>
    <div class="col-lg-12">
      <div class="alert alert-info rounded-lg col-md-6 mx-auto py-2" v-if="alertInfo">
        <em class="fa fa-info-circle text-blue" />
        <small> {{ alertInfo }} </small>
      </div>
      <div class="text-muted">
        {{ this.$t("label.dropzone-import-info") }}
      </div>
      <br>
      <button class="btn btn-default"><em class='fa fa-cloud-upload' /> Choose File</button>
    </div>
  </vue-dropzone>
</template>

<script>
  import vue2Dropzone from 'vue2-dropzone'
  import 'vue2-dropzone/dist/vue2Dropzone.min.css'
  export default {
    name: 'DropzoneImportExcel',
    props: {
      previewFile: Function,
      url: {
        type: String,
        default: 'https://httpbin.org/post'
      },
      thumbnailWidth: {
        type: Number,
        default: 200
      },
      addRemoveLinks: {
        type: Boolean,
        default: true
      },
      acceptedFiles: {
        type: String,
        default: '.xlsx,.xls"'
      },
      maxFiles: {
        type: String,
        default: '1'
      },
      maxFilesize: {
        type: Number,
        default: 10
      },
      alertInfo: {
        type: String,
        default: ''
      }
    },
    components: {
      vueDropzone: vue2Dropzone,
    },
    data() {
      return {
        isLoading: false,
        dropzoneOptions: {
          url: this.url,
          thumbnailWidth: this.thumbnailWidth,
          addRemoveLinks: this.addRemoveLinks,
          acceptedFiles: this.acceptedFiles,
          maxFiles: this.maxFiles,
          maxFilesize: this.maxFilesize
        }
      }
    },
    methods: {
      removeAllFiles() {
        this.dropzoneOptions.removeType = "client"
        this.$refs.myFile.removeAllFiles()
        this.dropzoneOptions.removeType = "server"
      },
    },
    created() {
      this.$bus.$on('reset-dropzone', this.removeAllFiles)
    },
  }
</script>