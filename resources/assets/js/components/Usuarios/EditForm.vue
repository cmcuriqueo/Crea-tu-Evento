<template>
    <div>
        <div class="col-sm-4">
            <button type="button" class="btn-block" @click="showModificar = true">Modificar Informaci&oacute;n de perfil</button>
        </div>

        <!-- Modal Modificar-->
        <div id="modificar" class="modal" role="dialog" :style="{ display : showModificar  ? 'block' : 'none' }">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" @click="closeModal()">&times;</button>
                        <h4 class="modal-title">Modificar datos de Perfil</h4>
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal">

                            <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('nombre')&&validar}">
                                <label for="inputNombre" class="col-sm-2 control-label">Nombre</label>
                                <div class="col-sm-10">
                                    <input name="nombre"  v-validate:usuario.nombre="'required|min:4'" type="text" class="form-control" v-model="usuario.nombre" placeholder="Nombre">
                                    <!-- validacion vee-validation -->
                                    <span v-show="errors.has('nombre')&&validar" class="help-block">{{ errors.first('nombre') }}</span>
                                    <!-- validacion api-->
                                    <div class="text-red" v-if="errorsApi.nombre">
                                        <div v-for="msj in errorsApi.nombre">
                                            <p>{{ msj }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('apellido')&&validar}">
                                <label for="inputApellido" class="col-sm-2 control-label">Apellido</label>
                                <div class="col-sm-10">
                                    <input name="apellido"  v-validate:usuario.apellido="'required|min:4'" type="text" class="form-control" v-model="usuario.apellido" placeholder="Apellido">
                                    <!-- validacion vee-validation -->
                                    <span v-show="errors.has('apellido')&&validar" class="help-block">{{ errors.first('apellido') }}</span>
                                    <!-- validacion api-->
                                    <div class="text-red" v-if="errorsApi.apellido">
                                        <div v-for="msj in errorsApi.apellido">
                                            <p>{{ msj }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('localidad')&&validar}">
                                <label class="col-sm-2 control-label">Localidad</label>
                                <div class="col-sm-10">
                                    <v-select 
                                        :debounce="250"
                                        v-validate="'required'" 
                                        data-vv-name="localidad"
                                        v-model="localidadSelect"
                                        :on-search="getOptions" 
                                        :options="localidades"
                                        placeholder="Seleccione una localidad">
                                    </v-select>
                                    <!-- validacion vee-validation -->
                                    <span v-show="errors.has('localidad')&&validar" class="help-block">{{ errors.first('localidad') }}</span>
                                    <!-- validacion api-->
                                    <div class="text-red" v-if="errorsApi.localidad_id">
                                        <div v-for="msj in errorsApi.localidad_id">
                                            <p>{{ msj }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('sexo')&&validar}">
                                <label for="inputSexo" class="col-sm-2 control-label">Sexo</label>
                                <div class="col-sm-10">
                                    <input name="sexo"  v-validate:usuario.sexo="'required'" type="radio" v-model="usuario.sexo" value="M" :checked="{'false': true, 'true': usuario.sexo == 'M'}"/>Masculino<br/>
                                    <input name="sexo" type="radio" v-model="usuario.sexo" value="F" :checked="{'false': true, 'true': usuario.sexo == 'F'}">Femenino<br/>
                                    <!-- validacion vee-validation -->
                                    <span v-show="errors.has('sexo')&&validar" class="help-block">{{ errors.first('sexo') }}</span>
                                    <!-- validacion api-->
                                    <div class="text-red" v-if="errorsApi.sexo">
                                        <div v-for="msj in errorsApi.sexo">
                                            <p>{{ msj }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('facha')&&validar}">
                                <label for="inputNombre" class="col-sm-2 control-label">Fecha de Nacimiento</label>
                                <div class="col-sm-10"><br/>

                                    <input 
                                        v-model="usuario.fecha_nac" 
                                        type="date" 
                                        v-validate="'required'"
                                        name="facha"
                                        v-validate:usuario.fecha_nac="'required'"
                                        :min="disabled.to"
                                        :max="disabled.from"
                                        >
                                    <!-- validacion vee-validation -->
                                    <span v-show="errors.has('facha')&&validar" class="help-block">{{ errors.first('facha') }}</span>
                                    <!-- validacion api-->
                                    <div class="text-red" v-if="errorsApi.fecha_nac">
                                        <div v-for="msj in errorsApi.fecha_nac">
                                            <p>{{ msj }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                          
                                <div class="col-sm-offset-2 col-sm-10">
                                  
                                    <button @click="validateBeforeSubmit()" type="button" class="btn btn-primary">
                                        Guargar
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div> 
    </div>
</template>

<script>
import auth from '../../auth.js';
import vSelect from "vue-select";

export default {
    data() {
        return {
            validar: false,
            showModificar: false,
            usuario: { nombre: '', apellido: '', sexo: '', fecha_nac: ''},
            error: false,
            fecha: null,
            disabled: { to: '1920-01-01', from: null },
            localidades: [],
            localidadSelect: null,
            errorsApi: []
        }
    },
    beforeMount: function(){
        //selected data
        this.getUserPerfil();
        this.setDefaultLocalidad();
    },
    mounted: function(){
        //rangos maximos de fechas
        this.fecha = new Date();
        this.disabled.from = this.fecha.getFullYear()+'-'+this.fecha.getMonth()+'-'+this.fecha.getDate();

    },
    components: {
        vSelect
    },
    methods: {
        //envio de formulario de modificación de informacion de usuario
        sendForm: function() {
                
            this.$http.post(
                'api/usuario/'+this.$route.params.userId,
                {
                    _method: 'PATCH',
                    nombre:  this.usuario.nombre,
                    apellido:  this.usuario.apellido,
                    fecha_nac:  this.usuario.fecha_nac,
                    localidad_id:  this.localidadSelect.value,
                    sexo:  this.usuario.sexo
                })
                .then(response => {
                    //recarga de informacion de perfil
                    this.showModificar = false;
                    this.$toast.success({
                        title:'¡Cambios realizados!',
                        message:'Se han realizado correctamente los cambios. :D'
                    });
                    this.$events.fire('reloadComponentPerfil');
                }, response => {
                    this.validar= false;
                    this.$toast.error({
                        title:'¡Error!',
                        message:'No se han podido guardar los cambios. :('
                    });
                    if(response.status === 401|| response.status === 422 )
                    {
                        this.errorsApi = response.body;
                    }

                })

        },
        closeModal: function(){
            this.errorsApi = [];
            this.getUserPerfil();
            if (auth.user.profile.usuario.localidad_id != null){
                this.setDefaultLocalidad();
            }
            this.validar = false;
            this.showModificar = false;
        },
        //form validation
        validateBeforeSubmit: function() {
            this.$validator.validateAll().then((result) => {
                if (result){
                    this.sendForm();
                } else {
                    this.validar = true;
                }
                return;
            }).catch(() => {
                
            });
        },
        //obtiene lista de localidades segun correponda
        getOptions: function(search, loading) {
            loading(true)
            this.$http.get('api/localidades/?q='+ search
                ).then(response => {
                    this.localidades = response.data.data
                    loading(false)
                })
        },
        setDefaultLocalidad: function(){
            if(auth.user.profile.usuario.localidad_id != null)
                this.localidadSelect = {
                   'value':auth.user.profile.usuario.localidad_id,
                   'label':auth.user.profile.usuario.localidad.nombre+' ('+auth.user.profile.usuario.localidad.provincia.nombre+')'
                }
        },
        getUserPerfil: function(){
            this.$http.get('api/usuario/'+ this.$route.params.userId )
                .then(response => {
                    this.usuario = response.data.data
                }, response => {
                    if(response.status === 404){
                        router.push('/404');
                    }
                })
        }
    }
}
</script>