<template>
    <div>
        <a class="btn btn-primary btn-block" @click="toggleShow">Cambiar Foto</a>
        <my-upload
            field="avatar"
            v-model="show"
            @crop-upload-success="cropUploadSuccess"
            :width="300"
            :url="url"
            :params="params"
            :height="300"
            :headers="headers">
        </my-upload>
    </div>

</template>

<script>
import 'babel-polyfill';
import myUpload from './../Plugins/vue-image-crop-upload/upload-2.vue';
import auth from './../../auth.js'

export default {
        data() {
            return {
                url: 'api/user/'+this.$route.params.userId+'/perfil/avatar/',
                show: false,
                headers: {
                    smail: '*_~',
                    'X-CSRF-TOKEN': Laravel.csrfToken
                },
                params: {
                    name: 'avatar'
                }
            }
        },
        components: {
            'my-upload': myUpload
        },
        methods: {
            toggleShow() {
                this.show = !this.show;
            },
            /**
             * upload success
             *
             * [param] jsonData   server api return data, already json encode
             * [param] field
             */
            cropUploadSuccess(jsonData, field){
                auth.user.profile.usuario.avatar = jsonData.data;
                this.$emit('avatarUpdate');

            }
        }
    }
</script> 
