<template>
<div>
    <div v-if="ShowAdvertenciaSalon" class="col-md-6 col-md-offset-3">
        <div class="alert alert-warning alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <p><i class="icon fa fa-warning"></i> Puede agregar solo un rubro de salones.</p>
        </div>
    </div>
    <form role="form">

        <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('rubros')&&validarRubro}">
            <div class="col-sm-12">
                <label for="inputRubro" class="control-label">Rubros</label><br>
                <v-select 
                    placeholder="Rubros"
                    data-vv-name="rubros"
                    v-validate="'required'" 
                    v-model="rubrosSelect" 
                    :on-change="callbackSelect"  
                    :options="optionsRubros"
                    multiple>
                </v-select>

                <!-- validacion vee-validation -->
                <span v-show="errors.has('rubros')&&validarRubro" class="help-block">{{ errors.first('rubros') }}</span>
                <!-- validacion api-->
                <div class="text-red" v-if="errorsApi.rubros_id">
                    <div v-for="msj in errorsApi.rubros_id">
                        <p>{{ msj }}</p>
                    </div>
                </div>
            </div>
        </div></br>
        <!--Datos de Habilitación y dirección del comercio-->
        <div v-if="rubro.rubros_id.length > 0 && salon == false" class="col-sm-12">
            <label class="control-label">Cuenta con Comercio de atención. </label> <br>  
            <input type="checkbox" id="checkbox" v-model="comercio" @click="$forceUpdate()" style="text-align:center;">
            <label for="checkbox">{{ comercio == true ? "Si" : "No" }}</label>
        </div>
        <div v-if="(((comercio== true)||salon == true)&&rubro.rubros_id.length > 0)">
            <div>
                <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('habilitacion')&&validarRubro}">
                    <div class="col-sm-6">
                        <label for="inputHabilitacion" class="control-label">Habilitación</label><br>
                         <input name="habilitacion" v-validate="'numeric|required'" type="text" v-model="rubro.habilitacion" value="habilitacion" class="form-control" placeholder="Numero de habilitacion">
                        <!-- validacion vee-validation -->
                        <span v-show="errors.has('habilitacion')&&validarRubro" class="help-block">{{ errors.first('habilitacion') }}</span>
                        <!-- validacion api-->
                        <div class="text-red" v-if="errorsApi.habilitacion">
                            <div v-for="msj in errorsApi.habilitacion">
                                <p>{{ msj }}</p>
                            </div>
                        </div>
                    </div>
                </div>
              
                <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('fecha')&&validarRubro}">
                    <div class="col-sm-6">
                        <label for="inputNombre" class="control-label">Fecha de habilitación</label><br>
                        <input 
                            v-if="rubro.habilitacion != null && rubro.habilitacion != ''"
                            v-model="rubro.fecha_habilitacion" 
                            type="date"
                            v-validate="'required'"
                            value="fecha"
                            name="fecha"
                            class="form-control">
                        <input 
                            v-else
                            v-model="rubro.fecha_habilitacion" 
                            type="date"
                            value="fecha"
                            name="fecha"
                            class="form-control">
                        <!-- validacion vee-validation -->
                        <span v-show="errors.has('fecha')&&validarRubro" class="help-block">{{ errors.first('fecha') }}</span>
                        <!-- validacion api-->
                        <div class="text-red" v-if="errorsApi.fecha_habilitacion">
                            <div v-for="msj in errorsApi.fecha_habilitacion">
                                <p>{{ msj }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="rubro.rubros_id.length > 0 &&(comercio == true || salon == true )&&nuevo" class="col-sm-12">
            <label class="control-label">El domicilio es igual al registrado como proveedor? </label> <br>  
            <input type="checkbox" id="checkbox" v-model="rubro.comercio" @click="$forceUpdate()" style="text-align:center;">
            <label for="checkbox">{{ rubro.comercio  == true ? "No" : "Si" }}</label>
        </div>
        <div v-if="(((comercio == true &&rubro.comercio  == true)||(salon == true&&rubro.comercio  == true))&&rubro.rubros_id.length > 0)">
            <div>
                <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('localidad')&&validarRubro}">
                    <div class="col-sm-12">
                        <label class="control-label">Localidad</label><br>
                        <v-select
                            :debounce="250" 
                            :on-search="getOptions" 
                            :options="localidades"
                            data-vv-name="localidad"
                            v-validate="'required'"
                            v-model="domicilio.localidad_id" 
                            placeholder="Seleccione una localidad">
                        </v-select>
                       
                        <!-- validacion vee-validation -->
                        <span v-show="errors.has('localidad')&&validarRubro" class="help-block">{{ errors.first('localidad') }}</span>
                        <!-- validacion api-->
                        <div class="text-red" v-if="errorsApi.localidad_id">
                            <div v-for="msj in errorsApi.localidad_id">
                                <p>{{ msj }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('calle')&&validarRubro}">
                    <div class="col-sm-8">
                        <label for="inputCalle" class="control-label">Direccion </label><br>
                        <input name="calle"  v-validate:domicilio.calle="'required|min:4'" type="text" class="form-control" v-model="domicilio.calle" placeholder="Calle">
                        <!-- validacion vee-validation -->
                        <span v-show="errors.has('calle')&&validarRubro" class="help-block">{{ errors.first('calle') }}</span>
                        <!-- validacion api-->
                        <div class="text-red" v-if="errorsApi.calle">
                            <div v-for="msj in errorsApi.calle">
                                <p>{{ msj }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has(('numero')||('piso'))&&validarRubro}">
                    <div class="col-sm-2 col-md-2 col-xs-6">
                            <label for="inputNro" class="control-label col-sm-12">N° </label><br>
                            <input 
                                name="numero" 
                                v-validate="'numeric'" 
                                type="text" v-model="domicilio.numero" 
                                value="numero" 
                                class="form-control"
                                placeholder="Numero">

                            <!-- validacion vee-validation -->
                            <span v-show="errors.has('numero')&&validarRubro" class="help-block">{{ errors.first('numero') }}</span>
                            <!-- validacion api-->
                            <div class="text-red">
                                <div v-if="errorsApi.numero" v-for="msj in errorsApi.numero">
                                    <p>{{ msj }}</p>
                                </div>
                            </div>
                    </div>

                    <div class="col-sm-2 col-md-2 col-xs-6">
                        
                        <label for="inputPiso" class="control-label">Dpto. </label><br>
                        <input name="piso" type="text" v-model="domicilio.piso" placeholder="Piso" class="form-control col-sm-12">

                        <!-- validacion vee-validation -->
                        <span v-show="errors.has('piso')&&validarRubro" class="help-block">{{ errors.first('piso') }}</span>
                        <div class="text-red">
                            <div v-if="errorsApi.piso" v-for="msj in errorsApi.piso">
                                <p>{{ msj }}</p>
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
import auth from '../../../auth.js';
import vSelect from "vue-select";

export default {
    props: {
            domicilio: {
                type: Object,
                required: true
            },
            rubro: {
                type: Object,
                required: true
            },
            nuevo: {
                type: Boolean,
                required: true
            },
            errorsApi: {
                required: true
            }
    },
    data() {
        return {
            localidades: [],
            validarRubro: false,
            optionsRubros: [],
            rubrosSelect: [],
            estaCargado: true,
            salon: false,
            ShowAdvertenciaSalon: false,
            comercio: true,
        }
    },
    components: {
        vSelect
    },
    beforeMount() {

        this.getOptionsRubros();
    },
    created: function() {
    },
    mounted() {
        this.$events.$on('validarForm', () => this.validateBeforeSubmit());     
    },
    methods: {
        //form validation
        validateBeforeSubmit: function() {
            this.$validator.validateAll().then((result) => {
                if (result){
                     this.validarRubro = false; 
                    if (this.nuevo){
                        this.$emit('validado')
                    }else{

                        this.$emit('validadoEdit')
                    }
                }else{
                     this.validarRubro = true;
                }
                return;
            })
        },
        //obtiene lista de localidades 
        getOptions: function(search, loading) {
            loading(true)
            this.$http.get('api/localidades/?q='+ search
                ).then(response => {
                    this.localidades = response.data.data
                    loading(false)
                })
        },

        getOptionsRubros: function() {
            this.$http.get('api/rubros/'
                ).then(response => {
                    let options = response.data
                    for (let rubros of options){
                        this.optionsRubros.push({ label: rubros.nombre,
                             value: rubros.id, 
                             salon: rubros.salon, 
                             servicio: rubros.servicio,
                             producto: rubros.producto })
                        if(!this.nuevo){
                            for (var id of this.rubro.rubros_id) {
                                if(id == rubros.id)
                                this.rubrosSelect.push({ label: rubros.nombre, 
                                    value: rubros.id, 
                                    salon: rubros.salon, 
                                    servicio: rubros.servicio,
                                    producto: rubros.producto });
                            }
                        }
                    }
                    this.estaCargado = !this.estaCargado;
                })

        },
        callbackSelect(val){
            if(!this.estaCargado)
            this.rubro.rubros_id=[];
            this.salon= false;
            for (var i = 0; i < val.length; i++) {
                if(val[i].salon){
                    if (this.salon){
                    this.ShowAdvertenciaSalon = true;
                    val.splice(i, 1)
                    setTimeout(() => this.ShowAdvertenciaSalon = false, 6000);
                    }else{
                        this.salon= true
                        this.rubro.comercio = true
                        this.rubro.rubros_id.push(val[i].value)
                    }
                }else{
                    this.rubro.rubros_id.push(val[i].value)
                }
            }
        }
    }
}

</script>
