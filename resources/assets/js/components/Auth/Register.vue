<template>
    <div class="register-box container row" style="margin: 0% auto;">
        <router-link to="/">
            <div class="login-logo">
                <a href="#"><b>Crea tu Evento</b></a>
            </div>
        </router-link>
        <div class="register-box-body">
            <h3 class="login-box-msg">Registrar una nueva cuenta</h3><br>
            <div class="help-block" v-if="error">
                <p class="text-red">Error al registrar</p> 
            </div>
            <form @submit.prevent="validateBeforeSubmit()">

                 <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('nombre')&&validar}">
                    <input name="nombre" v-model="usuario.nombre" v-validate="'required|min:4|max:55'" type="text" class="form-control" placeholder="Ingresar nombre">
                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                    <!-- validacion vee-validation -->
                    <span  v-show="errors.has('nombre')&&validar" class="help-block">{{ errors.first('nombre') }}</span>
                    
                    <div class="text-red" v-if="errorsApi.nombre">
                        <div v-for="msj in errorsApi.nombre">
                            <p>{{ msj }}</p>
                        </div>
                    </div>
                 </div>

                <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('apellido')&&validar}">
                    <input name="apellido" v-model="usuario.apellido" v-validate="'required|min:4|max:55'" type="text" class="form-control" placeholder="Ingresar apellido">
                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                    <!-- validacion vee-validation -->
                    <span   v-show="errors.has('apellido') && validar" class="help-block">{{ errors.first('apellido') }}</span>
                    <div class="text-red" v-if="errorsApi.apellido">
                        <div v-for="msj in errorsApi.apellido">
                            <p>{{ msj }}</p>
                        </div>
                    </div>
                 </div>
                
  

                <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('email')&&validar}">
                    <input name="email" v-model="email" v-validate="'required|email'" type="email" class="form-control" placeholder="Ingresar Email">
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                    <!-- validacion vee-validation -->
                    <span v-show="errors.has('email')&&validar" class="help-block">{{ errors.first('email') }}</span>
                    
                    <!-- validacion api-->
                    <div class="text-red" v-if="errorsApi.email">
                        <div v-for="msj in errorsApi.email">
                            <p>{{ msj }}</p>
                        </div>
                    </div>
                </div>

                <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('password')&&validar}">
                    <input name="password" v-model="password" type="password" v-validate="'required|min:6'" class="form-control" placeholder="Ingresar Contraseña">
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

                <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('confirmation')&&validar}">
                    <input name="confirmation" v-model="password_confirmation" type="password" v-validate="'required|confirmed:password'" class="form-control" placeholder="Confirmar contraseña">
                    <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
                    <!-- validacion vee-validation -->
                    <span v-show="errors.has('confirmation')&&validar" class="help-block">{{ errors.first('confirmation') }}</span>
                    
                    <!-- validacion api-->
                    <div class="text-red" v-if="errorsApi.password_confirmation">
                        <div v-for="msj in errorsApi.password_confirmation">
                            <p>{{ msj }}</p>
                        </div>
                    </div>
                </div>

                
                 <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('fecha')&&validar}">

                    <el-date-picker style="width: 100%"
                        v-model="usuario.fecha_nac"
                        name="fecha"
                        data-vv-name="fecha"
                        v-validate="'required'"
                        type="date"
                        :picker-options="pickerOptions"
                        placeholder="Seleccione su fecha de nacimiento">
                    </el-date-picker>

                    <span class="glyphicon glyphicon-calendar form-control-feedback"></span>
                    <!-- validacion vee-validation -->
                    <span v-show="errors.has('fecha')&&validar" class="help-block">{{ errors.first('fecha') }}</span>
                    <!-- validacion api-->
                    <div class="text-red" v-if="errorsApi.fecha_nac">
                        <div v-for="msj in errorsApi.fecha_nac">
                            <p>{{ msj }}</p>
                        </div>
                    </div>
                 
                 </div>

                 <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('localidad')&&validar}">
                      <el-select style="width: 100%"
                        v-model="usuario.localidad_id"
                        filterable
                        remote
                        reserve-keyword
                        placeholder="Localidad"
                        :remote-method="getOptions"
                        v-validate="'required'" 
                        data-vv-name="localidad"
                        :loading="loading">
                        <el-option
                          v-for="item in localidades"
                          :key="item.place_id"
                          :label="item.formatted_address"
                          :value="item.place_id">
                        </el-option>
                      </el-select>
                      <span class="glyphicon glyphicon glyphicon-map-marker form-control-feedback"></span>
                    <!-- vee-validate-->
                    <span v-show="errors.has('localidad')&&validar" class="help-block">{{ errors.first('localidad') }}</span>
                    <!-- validacion api-->
                    <div class="text-red" v-if="errorsApi.localidad_id">
                        <div v-for="msj in errorsApi.localidad_id">
                            <p>{{ msj }}</p>
                        </div>
                    </div>
                </div>

                <div class="form-group has-feedback">
                    <vue-google-autocomplete
                        id="map"
                        ref="address"
                        classname="form-control"
                        placeholder="Start typing"
                        enable-geolocatio1n="true"
                        v-on:placechanged="getAddressData">
                    </vue-google-autocomplete>
                </div>

                <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('sexo')&&validar}">
                    <label for="inputSexo" class="col-sm-2 control-label">Sexo</label>
                    <div class="col-sm-10">
                        <input name="sexo" v-validate="'required'" type="radio" v-model="usuario.sexo" value="M">Masculino<br>
                        <input name="sexo" type="radio" v-model="usuario.sexo" value="F">Femenino
                    </div>
                    <!-- validacion vee-validation -->
                    <span v-show="errors.has('sexo')&&validar" class="help-block">{{ errors.first('sexo') }}</span>

                    <!-- validacion api-->
                    <div class="text-red" v-if="errorsApi.sexo">
                        <div v-for="msj in errorsApi.sexo">
                            <p>{{ msj }}</p>
                        </div>
                    </div>

                </div>
                <div class="form-group has-feedback text-center row">
                    <p>
                        Al crear una cuenta acepta el 
                            <router-link tag="a" to="/terminos-condiciones" target="_blank">Contrato de servicios de Eventos.</router-link>
                    </p>
                    <br>
                    <!-- /.col -->
                    <div class="col-xs-12">
                        <button type="submit" class="btn btn-primary btn-flat">Crear cuenta</button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>
            <div class="text-center" style="margin-top: 0px">
                <p>- O -</p>
                <a href="#" @click="redirection()" class="btn btn-block btn-social btn-google btn-flat"><i class="fa fa-google-plus"></i> Registrarse con
                Google+</a>
            </div>
            ¿Ya tienes una cuenta?<router-link tag="a" to="/login" class="text-center"> Iniciar sesión</router-link>
      </div>
      <!-- /.form-box -->
    </div>
    <!-- /.register-box -->

</template>

<script>
    import auth from '../../auth.js';
    import router from '../../routes.js';
    import vSelect from "vue-select";
    import { Validator } from 'vee-validate';
    import VueGoogleAutocomplete from 'vue-google-autocomplete'

    export default {
        
        data() {
            return {
                validar: false,
                name: null,
                email: null,
                password: null,
                password_confirmation: null,
                error: false,
                errorsApi: [],
                localidades: [],
                usuario: {
                    localidad_id: null,
                    nombre: null,
                    apellido: null,
                    sexo: null,
                    fecha_nac: null,
                    login: true
                },
                address: '',
                loading: false,
                pickerOptions: {
                    disabledDate: function(date){
                        const today = new Date();
                        today.setFullYear(today.getFullYear() - 18);
                        return date > today;
                    }
                }
            }
        },
        components: {vSelect, VueGoogleAutocomplete},
        mounted() {
            this.$refs.address.focus();
        },
        methods: {
            /**
            * When the location found
            * @param {Object} addressData Data of the found location
            * @param {Object} placeResultData PlaceResult object
            * @param {String} id Input container ID
            */
            getAddressData: function (addressData, placeResultData, id) {
                this.address = addressData;
            },
            //clear errorsApi
            clearErrors: function(){
                this.error = false,
                this.errorsApi = []     
            },
            //send form
            register: function(){
                this.$http.post(
                    'api/register',
                    {
                        name: this.usuario.nombre,
                        email: this.email,
                        password: this.password,
                        password_confirmation: this.password_confirmation,
                        remember: false,
                        nombre: this.usuario.nombre,
                        apellido: this.usuario.apellido,
                        sexo: this.usuario.sexo,
                        fecha_nac: this.usuario.fecha_nac,
                        localidad_id: this.usuario.localidad_id.value,
                        login: true
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
                        this.error = true //error de 
                        this.errorsApi = response.body//lista de errores
                        this.validar = false
                })
            },
            validateBeforeSubmit: function() {
                this.clearErrors();
                this.$validator.validateAll().then((result) => {
                    if (result) {
                        this.register();

                    } else
                    {
                        this.validar = true;
                    }
                    return;
                }).catch(() => {

                });

            },
            getOptions: function(query) {
                this. loading = true;
                this.$http.get('api/localidades/?q='+ query
                    ).then(response => {
                        this.localidades = response.data.results;
                        this.loading = false;
                    }, response => {this.loading = false;})
                
            },
            redirection: function(){
                 window.location.href = "http://localhost:8000/redirect";
            }
        }
    }
</script>


