<template>
    <div class="login-box layout-boxed" style="margin: 0% auto; height: 680px;">
        <br><br><br>
        <div class="login-logo">
            <a href="#"><b>Crea tu Evento</b></a>
        </div>
        <!-- /.login-logo -->
        <div class="login-box-body">
            <div class="callout callout-success" v-if="showMessage">
                <p>Se ha enviado a su correo electronico un enlace para cambiar su contraseña</p>
            </div>
            <p class="login-box-msg">Restablecer Contraseña</p>


            <form  @submit.prevent="validateBeforeSubmit">

                <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('email')}">
                    <input name="email" v-model="email" type="email" v-validate="'required|email'" class="form-control" placeholder="Email">
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                    <!-- validacion vee-validation -->
                    <span v-show="errors.has('email')" class="help-block">{{ errors.first('email') }}</span>
                    <!-- validacion api-->
                    <div class="text-red" v-if="errorsApi.email">
                        <div v-for="msj in errorsApi.email">
                            <p>{{ msj }}.</p>
                        </div>
                    </div>
                </div>

                <div class="form-group has-feedback text-center row" style="margin-top: 0px">
                    <button type="submit" class="btn btn-primary btn-flat">
                        Restablecer Contraseña
                    </button>
                </div>

            </form>
             ¿Recuerdas tu contraseña?<router-link tag="a" to="/login"> Iniciar sesi&oacute;n</router-link><br>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            email: null,
            showMessage: false,
            errorsApi: []
        }
    },
    methods: {
        //clear errorsApi
        clearErrors: function(){
            this.errorsApi = []
        },
        //send form
        sendForm: function(){
            this.$http.post(
                'api/password/email',
                {
                    email: this.email,
                }
            ).then(response => {

                this.showMessage = true;
            
            }, response => {
                if(response.status === 422) {
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