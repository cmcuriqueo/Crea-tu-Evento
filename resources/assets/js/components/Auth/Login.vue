<template>
    <div class="login-box" style="margin: 0% auto; height: 680px;">
        <router-link to="/">
            <div class="login-logo">
                <a href="#"><b>Crea tu Evento</b></a>
            </div>
        </router-link>
        <!-- /.login-logo -->
        <div class="login-box-body">
            <div v-if="deactivated" class="callout callout-danger">
                <h4>Cuenta desactivada! :(</h4>
                <p>Podras volver a activarla cuando desees introduciendo tu correo
                 y contraseña.</p>
            </div>
            <div v-if="internalServerError" class="callout callout-danger">
                <h4>¡Vaya! Algo salió mal. :(</h4>
                <p>Hemos tenido un problema en el servidor. Intentelo nuevamente mas tarde.</p>
            </div>
            <div v-if="block" class="callout callout-danger">
                <h4>¡Vaya!</h4>
                <p>Su cuenta se encuentra temporalmente bloqueada.</p>
            </div>
            <div v-if="error">
                <p class="login-box-msg text-red">Estas credenciales no coinciden con nuestros registros.</p>
            </div>
            <form @submit.prevent="validateBeforeSubmit">

                <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('email') && validar}">
                    <input name="email" v-model="email" type="email" v-validate="'required|email'" class="form-control" placeholder="Email">
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                    <!-- validacion vee-validation -->
                    <span v-show="errors.has('email') && validar" class="help-block">{{ errors.first('email') }}</span>
                    <!-- validacion api-->
                    <div class="text-red" v-if="errorsApi.email">
                        <div v-for="msj in errorsApi.email">
                            <p>{{ msj }}</p>
                        </div>
                    </div>
                </div>

                <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('password') && validar}">
                    <input name="password" v-model="password" v-validate="'required|min:4'" type="password" class="form-control" placeholder="Password">
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    <!-- validacion vee-validation -->
                    <span v-show="errors.has('password')&&validar" class="help-block">{{ errors.first('password') }}</span>
                    <!-- validacion api-->
                    <div class="text-red" v-if="errorsApi.password">
                        <div v-for="msj in errorsApi.password">
                            <p>{{ msj }}</p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-7 col-xs-offset-1">
                        <div class="checkbox icheck">
                            <label>
                                <input v-model="remember" type="checkbox"> Mantener la sesión iniciada
                            </label>
                        </div>
                    </div>
                    <div class="col-xs-4">
                        <button type="submit" class="btn btn-primary btn-block btn-flat">Iniciar</button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>
            <div class="social-auth-links text-center" style="margin-top: 0px;">
              <p>- O -</p>
              <a href="#" @click="redirection()" class="btn btn-block btn-social btn-google btn-flat"><i class="fa fa-google-plus"></i> Inicia sesion con
                Google+</a>
            </div>
            <router-link tag="a" to="/password-reset">¿Se olvido la contraseña?</router-link><br>
            <router-link tag="a" to="/registrar" class="text-center">
                    Registrar una nueva cuenta
            </router-link>
        </div>
        <!-- /.login-box-body -->
    </div>
    <!-- /.login-box -->
</template>

<script>
import auth from '../../auth.js';
import router from '../../routes.js';
//import ImageInput from '../Usuarios/ImageInput.vue';

export default {

    data() {
        return {
            validar: false,
            email: null,
            password: null,
            remember: false,
            error: false,
            errorsApi: [],
            deactivated: false,
            internalServerError: false,
            block: false
        }
    },
    beforeMount() {
        this.deactivated = this.$route.query.deactivated
    },
    //components: {
    //    ImageInput
    //},
    methods: {
        //clear errorsApi
        clearErrors: function(){
            this.error = false,
            this.errorsApi = []
        },
        //send form
        sendForm: function(){
            this.$http.post(
                'api/login',
                {
                    email: this.email,
                    password: this.password,
                    remember: this.remember
                }
            ).then(response => {
                localStorage.setItem('id_token', response.data.meta.token)
                Vue.http.headers.common['Authorization'] = 'Bearer ' + localStorage.getItem('id_token')
                auth.user.authenticated = true
                auth.user.profile = response.data.data

                router.push({
                    name: 'home'
                })
                
            }, response => {
                this.validar = false;
                if(response.status === 401) {
                    this.error = true
                } else if (response.status === 500) {
                    this.internalServerError = true;
                } else if(response.status === 403) {
                    this.block = true;
                } else {
                    this.errorsApi = response.body
                }
            })
        },
        //form validation
        validateBeforeSubmit: function() {
            this.clearErrors();
            //mensaje para desactivar cuenta
            this.deactivated = false;
            //validacion despues de el evento enviar
            this.validar = true;
            //elimino errors si los hubo anteriormente
            this.internalServerError = false;
            this.block = false;
            this.$validator.validateAll().then((result) => {
                if (result) {
                    this.sendForm();
                    return;
                }
            }).catch(() => {
                // failed
            });

        },
        redirection: function(){
             window.location.href = "http://localhost:8000/redirect";
        }
    }

}
</script>
