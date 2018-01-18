<template>
    <div class="login-box">
        <div class="login-logo">
            <a href="#"><b>Crea tu Evento</b></a>
        </div>
        <!-- /.login-logo -->
        <div class="login-box-body">
            <p class="login-box-msg">Restablecer Contraseña</p>
            <div class="callout callout-danger" v-if="error">
                <p>Estas credenciales no coinciden con nuestros registros.</p>
            </div>

            <form  @submit.prevent="validateBeforeSubmit">

                <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('email')}">
                    <input name="email" v-model="email" type="email" v-validate="'required|email'" class="form-control" placeholder="Email">
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                    <!-- validacion vee-validation -->
                    <span v-show="errors.has('email')" class="help-block">{{ errors.first('email') }}</span>
                    <!-- validacion api-->
                    <div class="text-red" v-if="errorsApi.email">
                        <p>{{ errorsApi.email }}</p>
                    </div>
                </div>

                <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('password')}">
                    <input name="password" v-model="password" type="password" v-validate:password="'required|min:6'" class="form-control" placeholder="Ingresar Contraseña">
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    <!-- validacion vee-validation -->
                    <span v-show="errors.has('password')" class="help-block">{{ errors.first('password') }}</span>
                    
                    <!-- validacion api-->
                    <div class="help-block" v-if="errorsApi.password">
                        <div v-for="msj in errorsApi.password">
                            <p>{{ msj }}.</p>
                        </div>
                    </div>
                </div>

                <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('confirmation')}">
                    <input name="confirmation" v-model="password_confirmation" type="password" v-validate:confirmation="'required|confirmed:password'" class="form-control" placeholder="Confirmar contraseña">
                    <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
                    <!-- validacion vee-validation -->
                    <span v-show="errors.has('confirmation')" class="help-block">{{ errors.first('confirmation') }}</span>
                    
                    <!-- validacion api-->
                    <div class="help-block" v-if="errorsApi.password_confirmation">
                        <div v-for="msj in errorsApi.password_confirmation">
                            <p>{{ msj }}.</p>
                        </div>
                    </div>
                </div>

                <div class="form-group has-feedback text-center row">
                    <button type="submit" class="btn btn-primary btn-flat">
                        Restablecer Contraseña
                    </button>
                </div>
                
            </form>
        </div>
    </div>
</template>

<script>
import auth from '../../../auth.js';
import router from '../../../routes.js';

export default {
    data() {
        return {
            email: null,
            token: null,
            password: null,
            password_confirmation: null,
            error: null,
            errorsApi: [],
            auth: auth
        }
    },
    methods: {
        //clear errorsApi
        clearErrors: function(){
            this.error = false,
            this.errorsApi = []
        },
        //send form
        sendForm: function(){
            this.$http.post(
                'api/password/reset',
                {
                    email: this.email,
                    password: this.password,
                    password_confirmation: this.password_confirmation,
                    token: this.$route.params.token

                }
            ).then(response => {
                auth.user.authenticated = true
                auth.user.profile = response.data.data


                router.push({
                    name: 'home'
                })
            }, response => {
                if(response.status === 404) {
                    this.error = true
                } else {
                    this.errorsApi = response.body
                }
            })
        },
        //form validation
        validateBeforeSubmit: function(e) {
            this.clearErrors();
            this.$validator.validateAll().then((result) => {
                if (result) {
                    this.sendForm();
                    return;
                }
            }).catch(() => {
                // failed
            });

        }
    }
}    
</script>