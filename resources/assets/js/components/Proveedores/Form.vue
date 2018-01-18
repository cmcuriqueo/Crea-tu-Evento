<template>
    <div><br/>
        <form role="form">
        <div class="col-sm-12">
            <div class="col-sm-6">
                <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('usuarios')&&validarProveedor}" v-if="!editProveedor">
                    <div class="col-sm-12">
                        <label class="control-label">Usuario</label><br>
                        <v-select 
                            :debounce="250"
                            v-validate="'required'" 
                            v-model="proveedor.user_id"
                            name= "user_id"
                            data-vv-name="usuarios"
                            :on-search="getOptionsUsuario" 
                            :options="usuarios"
                            placeholder="Seleccione un usuario">
                        </v-select>
                        <!-- validacion vee-validation -->
                        <span v-show="errors.has('usuarios')&&validarProveedor" class="help-block">{{ errors.first('usuarios') }}</span>
                        <!-- validacion api-->
                        <div class="text-red" v-if="errorsApi.user_id">
                            <div v-for="msj in errorsApi.user_id">
                                <p>{{ msj }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                    
                <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('nombre')&&validarProveedor}">
                    <div class="col-sm-12">
                        <label for="inputNombre" class="control-label">Nombre Razón social</label><br>
                        <input name="nombre"  v-validate:proveedor.nombre="'required|min:4'" type="text" class="form-control" v-model="proveedor.nombre" placeholder="Nombre">
                        <!-- validacion vee-validation -->
                        <span v-show="errors.has('nombre')&&validarProveedor" class="help-block">{{ errors.first('nombre') }}</span>
                        <!-- validacion api-->
                        <div class="text-red" v-if="errorsApi.nombre">
                            <div v-for="msj in errorsApi.nombre">
                                <p>{{ msj }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('cuit')&&validarProveedor}">
                    <div class="col-sm-12">
                        <label for="inputCuit" class="control-label">N° de Cuit</label><br>
                         <input name="cuit" v-validate="'required|min:9|max:11'" type="number" v-model="proveedor.cuit" value="cuit" class="form-control">
                        <!-- validacion vee-validation -->
                        <span v-show="errors.has('cuit')&&validarProveedor" class="help-block">{{ errors.first('cuit') }}</span>
                        <!-- validacion api-->
                        <div class="text-red" v-if="errorsApi.cuit">
                            <div v-for="msj in errorsApi.cuit">
                                <p>{{ msj }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('email')&&validarProveedor}">
                    <div class="col-sm-12">
                        <label for="inputEmail" class="control-label">Email</label><br>
                        <input name="email"  v-validate="'required|email'" type="email" class="form-control" v-model="proveedor.email" placeholder="Email">

                        <!-- validacion vee-validation -->
                        <span v-show="errors.has('email')&&validarProveedor" class="help-block">{{ errors.first('email') }}</span>
                        <!-- validacion api-->
                        <div class="text-red" v-if="errorsApi.email">
                            <div v-for="msj in errorsApi.email">
                                <p>{{ msj }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('adjunto')&&validarProveedor}" v-if="!editProveedor">
                    <div class="col-sm-12">
                        <label for="inputDni" class="control-label"><i class="fa fa-file-image-o"></i> Adjunto (opcional)</label>
                            
                        <br>

                        <input v-if="nuevo"
                            type="file" 
                            v-validate.reject="'ext:jpg,png,jpeg,pdf'" 
                            @change="onFileChange"
                            name="adjunto">

                        <input v-else
                            type="file" 
                            v-validate.reject="'ext:jpg,png,jpeg,pdf'" 
                            @change="onFileChange"
                            name="adjunto">
                        <!-- validacion vee-validation -->
                        <span v-show="errors.has('adjunto')&&validarProveedor" class="help-block">{{ errors.first('adjunto') }}</span>

                    </div>                    
                </div>
            </div>

            <div class="col-sm-6">
                <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('telefono')&&validarProveedor}">
                    <div class="col-sm-12">
                        <label for="inputTelefono" class="control-label">Telefono de contacto</label><br>
                        <input 
                            name="telefono" 
                            v-validate="rulesTelefono"
                            type="text-red" 
                            v-model="proveedor.telefono" 
                            class="form-control"
                            placeholder="#####-########">

                        <!-- validacion vee-validation -->
                        <span v-show="errors.has('telefono')&&validarProveedor" class="help-block">
                                {{ errors.first('telefono') }}
                        </span>
                        <!-- validacion api-->
                        <div class="text-red" v-if="errorsApi.telefono">
                            <div v-for="msj in errorsApi.telefono">
                                <p>{{ msj }}</p>
                            </div>
                        </div>
                    </div>                    
                </div>
                <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('ingresos_brutos')&&validarProveedor}">
                    <div class="col-sm-12">
                        <label for="inputIngresosBrutos" class="control-label">N° Ingresos Brutos</label><br>
                        <input 
                            name="ingresos_brutos" 
                            v-validate="'required'" 
                            type="number" 
                            v-model="proveedor.ingresos_brutos" 
                            class="form-control" 
                            value="ingresos_brutos"
                            min="1">

                        <!-- validacion vee-validation -->
                        <span v-show="errors.has('ingresos_brutos')&&validarProveedor" class="help-block">
                                {{ errors.first('ingresos_brutos') }}
                        </span>
                        <!-- validacion api-->
                        <div class="text-red" v-if="errorsApi.ingresos_brutos">
                            <div v-for="msj in errorsApi.ingresos_brutos">
                                <p>{{ msj }}</p>
                            </div>
                        </div>
                    </div>                    
                </div>
                <div>
                    <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('localidad')&&validarProveedor}">
                        <div class="col-sm-12">
                            <label class="control-label">Localidad</label><br>
                            <v-select
                                :debounce="250" 
                                :on-search="getOptionsLocalidad" 
                                :options="localidades"
                                data-vv-name="localidad"
                                v-model="proveedor.domicilio.localidad_id" 
                                v-validate="'required'" 
                                placeholder="Seleccione una localidad">
                            </v-select>
                           
                            <!-- validacion vee-validation -->
                            <span v-show="errors.has('localidad')&&validarProveedor" class="help-block">{{ errors.first('localidad') }}</span>
                            <!-- validacion api-->
                            <div class="text-red" v-if="errorsApi.localidad_id">
                                <div v-for="msj in errorsApi.localidad_id">
                                    <p>{{ msj }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('calle')&&validarProveedor}">
                        <div class="col-sm-12">
                            <label for="inputCalle" class="control-label">Direccion </label><br>
                            <input name="calle"  v-validate="'required|min:4'" type="text" class="form-control" v-model="proveedor.domicilio.calle" placeholder="calle">
                            <!-- validacion vee-validation -->
                            <span v-show="errors.has('calle')&&validarProveedor" class="help-block">{{ errors.first('calle') }}</span>
                            <!-- validacion api-->
                            <div class="text-red" v-if="errorsApi.calle">
                                <div v-for="msj in errorsApi.calle">
                                    <p>{{ msj }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has(('numero')||('piso'))&&validarProveedor}">
                        <div class="col-sm-12 col-md-6">
                                <label for="inputNro" class="control-label">N° </label><br>
                                <input 
                                    name="numero" 
                                    v-validate="'required'" 
                                    type="number" v-model="proveedor.domicilio.numero" 
                                    value="numero" 
                                    class="form-control">

                                <!-- validacion vee-validation -->
                                <span v-show="errors.has('numero')&&validarProveedor" class="help-block">{{ errors.first('numero') }}</span>
                                <!-- validacion api-->
                                <div class="text-red">
                                    <div v-if="errorsApi.numero" v-for="msj in errorsApi.numero">
                                        <p>{{ msj }}</p>
                                    </div>
                                </div>
                        </div>

                        <div class="col-sm-12 col-md-6">
                            
                            <label for="inputPiso" class="control-label">Dpto. </label><br>
                            <input name="piso" type="number" v-model="proveedor.domicilio.piso" value="piso" class="form-control">

                            <!-- validacion vee-validation -->
                            <span v-show="errors.has('piso')&&validarProveedor" class="help-block">{{ errors.first('piso') }}</span>
                            <div class="text-red">
                                <div v-if="errorsApi.piso" v-for="msj in errorsApi.piso">
                                    <p>{{ msj }}</p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
     </form>
</div>
</template>



<script>
import auth from '../../auth.js';
import route from '../../routes.js';
import vSelect from "vue-select";

export default {
    props: {
        editProveedor: {
            default: false
        },
        proveedor: {
            type: Object,
            required: true
        },
        nuevo: {
            type: Boolean,
            required: true
        },
        errorsApi: {
            type: Object,
            required: true
        }
    },
    data() {
        return {
            usuarios:[],
            localidades: [],
            validar: false,
            validarProveedor: false,
                rulesTelefono: {
                  required: true,
                  regex: /[0-9]{3,4}-[0-9]{6,8}/i
                }
        }
    },
    components: {
        vSelect
    },
    mounted() {
        this.$events.$on('validarFormProveedor', () =>this.validateBeforeSubmit());
    },
    beforeDestroy() {
        this.$events.$off('validarFormProveedor')
    },
    methods: {
       
        validateBeforeSubmit: function() {
            this.$validator.validateAll().then((result) => {
                if (result) {
                    this.validarProveedor = false;
                    if (this.nuevo){
                        this.$emit('validadoProveedor'); 
                    }else{
                        this.$emit('validadoEditProveedor');  
                    }
                } else {
                    this.validarProveedor = true;
                }
                return;
            }).catch(() => {
                
            });
        },
        //obtiene lista de usuarios segun requiera
        getOptionsUsuario: function(search, loading) {
            loading(true)
            this.$http.get('api/busqueda/usuarios/?q='+ search
                ).then(response => {
                    this.usuarios = response.data
                    loading(false)
                })
        },
        //obtiene lista de usuarios segun requiera
        getOptionsLocalidad: function(search, loading) {
            loading(true)
            this.$http.get('api/localidades/?q='+ search
                ).then(response => {
                    this.localidades = response.data.data
                    loading(false)
                })
        },

        onFileChange(e) {
            var files = e.target.files || e.dataTransfer.files;
            if (!files.length)
                return;
            this.createImage(files[0]);
        },
        createImage(file) {
            var adjunto = new Image();
            var reader = new FileReader();

            reader.onload = (e) => {
                this.proveedor.adjunto = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    }
}
</script>