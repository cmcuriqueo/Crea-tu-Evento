<template>
<div>
    <form role="form">
        <div class="col-sm-12">
            <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('nombre')&&validarCaracteristica}">
                <div class="col-sm-12">
                    <label for="inputCaracteristica" class="control-label">Nombre</label><br>
                       <input name="nombre" v-validate="'required|min:4|max:40'" type="text" v-model="caracteristica.nombre" 
                        class="form-control" placeholder="Nombre">
                            <!-- validacion vee-validation -->
                    <span v-show="errors.has('nombre')&&validarCaracteristica" class="help-block">{{ errors.first('nombre') }}</span>
                    <!-- validacion api-->
                    <div class="text-red" v-if="errorsApi.nombre">
                        <div v-for="msj in errorsApi.nombre">
                            <p>{{ msj }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6">
            <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('adicional')&&validarCaracteristica}">
                <div class="col-sm-12">
                    <label class="control-label">Informacion Adicional</label><br>

                    <input type="checkbox" id="checkbox" v-model="caracteristica.adicional" @click="$forceUpdate()" style="text-align:center;">
                    <label for="checkbox">{{ caracteristica.adicional == true ? "Si" : "No" }}</label>

                    <!-- validacion api-->
                    <div class="text-red" v-if="errorsApi.adicional">
                        <div v-for="msj in errorsApi.adicional">
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
import auth from '../../../auth.js';
import vSelect from "vue-select";

export default {
    props: {
            caracteristica: {
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
            validarCaracteristica: false
        }
    },
    components: {
        vSelect
    },
    mounted() {
        this.$events.$on('validarFormCaracteristica', () => this.validateBeforeSubmit());
    },
    methods: {
        //form validation
        validateBeforeSubmit: function() {
            this.$validator.validateAll().then((result) => {
                if (result) {
                    this.validarCaracteristica = false; 
                    if (this.nuevo){
                        this.$emit('validadoNewCaracteristica')
                    }else{
                        this.$emit('validadoEditCaracteristica')
                    }
                } else {
                    this.validarCaracteristica = true;
                }
                return;
            }).catch(() => {
                
            });
        }
    }
}
</script>