<template>
<div>
    <form role="form">
        <div class="col-sm-12">
            <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('nombre')&&validarLocalidad}">
                <div class="col-sm-12">
                    <label for="inputSubcategoria" class="control-label">Nombre</label><br>
                       <input name="nombre" v-validate="'required|min:4|max:22'" type="text" v-model="localidad.nombre" 
                        class="form-control" placeholder="Nombre">
                            <!-- validacion vee-validation -->
                    <span v-show="errors.has('nombre')&&validarLocalidad" class="help-block">{{ errors.first('nombre') }}</span>
                    <!-- validacion api-->
                    <div class="text-red" v-if="errorsApi.nombre">
                        <div v-for="msj in errorsApi.nombre">
                            <p>{{ msj }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12">
            <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('provincia')&&validarLocalidad}">
                <div class="col-sm-12">
                    <label class="control-label">Provincia</label><br>

                    <select 
                        name="provincia"
                        v-model="localidad.provincia_id" 
                        class="form-control" 
                        v-validate="'required'"
                        placeholder='Seleccione una provincia'>
                        <option disabled value="">Seleccione un Provincia</option>
                            <option v-for="option in provincias" v-bind:value="option.value">
                                {{option.label}}
                            </option>
                    </select>
                    
                    <!-- validacion vee-validation -->
                    <span v-show="errors.has('provincia')&&validarLocalidad" class="help-block">{{ errors.first('provincia') }}</span>
                    <!-- validacion api-->
                    <div class="text-red" v-if="errorsApi.provincia">
                        <div v-for="msj in errorsApi.provincia">
                            <p>{{ msj }}</p>
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
import vSelect from "vue-select";

export default {
    props: {
        localidad: {
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
            validarLocalidad: false,
            provincia_id: '',
            provincias: []
        }
    },
    beforeMount(){
        this.getOptionsProvincias();
    },
    mounted() {
        this.$events.$on('validarFormLocalidad', () => this.validateBeforeSubmit());
    },
    components: {
        vSelect
    },
    methods: {
        //form validation
        validateBeforeSubmit: function() {
            this.$validator.validateAll().then((result) => {
                if (result) {
                    this.validarLocalidad = false;
                    if (this.nuevo){
                        this.$emit('validadoNewLocalidad')
                    }else{
                        this.$emit('validadoEditLocalidad')
                    }
                    return;
                }
            }).catch(() => {
                this.validarLocalidad= true;
            });
        },
        changeProvincia: function(){
            this.localidad.provincia_id = this.provincia_id;
        },
        //obtiene lista de categorias 
        getOptionsProvincias: function() {
            this.$http.get('api/provincias/'
                ).then(response => {
                    this.provincias = response.data.data
                })
        }
    }
}
</script>