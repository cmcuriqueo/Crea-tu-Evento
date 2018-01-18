<template>
<div>
    <form role="form">
        <div class="col-sm-12">
            <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('nombre')&&validarRubro}">
                <div class="col-sm-12">
                    <label for="inputRubro" class="control-label">Nombre</label><br>
                       <input name="nombre" v-validate="'required|min:4|max:22'" type="text" v-model="rubro.nombre" 
                        class="form-control" placeholder="Nombre">
                            <!-- validacion vee-validation -->
                    <span v-show="errors.has('nombre')&&validarRubro" class="help-block">{{ errors.first('nombre') }}</span>
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
            <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('tipos')&&validarRubro}">

                <div class="col-sm-12">
                    <label for="inputTipo" class="control-label">Rubro de :</label><br>
                    <input type="checkbox" name="tipos" value="producto" v-model="rubro.producto" v-validate="'required'">
                    <label for="producto">Producto</label>
                    <input type="checkbox" name="tipos" value="salon" v-model="rubro.salon" v-validate="'required'">
                    <label for="salon">Salon</label>
                    <input type="checkbox" name="tipos" value="servicio" v-model="rubro.servicio" v-validate="'required'">
                    <label for="servicio">Servicio</label><br>

                    <span v-show="errors.has('tipos')&&validarRubro" class="help-block">{{ errors.first('tipos') }}</span>
                    <!-- validacion api-->
                    <div class="text-red" v-if="errorsApi.tipos">
                        <div v-for="msj in errorsApi.tipos">
                            <p>{{ msj }}</p>
                        </div>
                    </div>
                </div>

            </div> 
        </div>
        <div class="col-sm-12">
            <div class="col-sm-12">
                <label for="inputCaracteristica" class="control-label">Caracteristicas: </label><br>
                <div class="direct-chat-messages" style="height: 180px;">
                    <div class="direct-chat-msg">
                        <div v-for="caracteristica in caracteristicas">
                            <div class="col-sm-6">
                              <input type="checkbox" @click="selected(caracteristica, $event)" checked> {{caracteristica.nombre}}
                            </div>
                        </div>
                        <div v-for="caracteristica in caracteristicas_no">
                            <div class="col-sm-6">
                              <input type="checkbox" @click="selected(caracteristica, $event)"> {{caracteristica.nombre}}
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
            rubro: {
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
            validarRubro: false,
            options_caracteristicas: [],
            caracteristicas: [],
            caracteristicas_no: []
        }
    },
    beforeMount(){
        this.getCaracteristicas();
    },
    components: {
        vSelect
    },
    mounted() {
        this.$events.$on('validarFormRubro', () => this.validateBeforeSubmit());
    },
    created(){
        this.agregarOptions();
    },
    methods: {
        //form validation
        validateBeforeSubmit: function() {
            this.$validator.validateAll().then((result) => {
                if (result){
                    this.validarRubro = false; 
                    if (this.nuevo){
                        this.$emit('validadoNewRubro')
                    }else{
                        this.rubro.caracteristicas = this.options_caracteristicas
                        this.$emit('validadoEditRubro')

                    }
                } else {
                    this.validarRubro = true;
                }
                return;
            }).catch(() => {
                
            });
        },
        getCaracteristicas: function(){
            this.$http.get('api/caracteristica')
                .then(response => {
                    this.caracteristicas = response.data,
                    this.noselected(this.caracteristicas,this.rubro.caracteristicas)
                }, response => {
                    this.$toast.error({
                        title:'¡Error!',
                        message:'No se han podido cargar las caracteristicas.'
                    });
                    this.closeModal();
                })
        },
        selected(caracteristica,e){
            if (e.toElement.checked){
                    this.options_caracteristicas.push(caracteristica.id)
            }else{
                for (var i = 0; i < this.options_caracteristicas.length; i++)
                {
                    if (this.options_caracteristicas[i] == caracteristica.id){
                        this.options_caracteristicas.splice(i, 1)
                    }
                }
            }
        },
        noselected: function(data1, data2){
            var option = [];
            if(!this.nuevo)
            {
                for (var i = 0; i < data1.length; i++) {
                    var igual=false;
                     for (var j = 0; j < data2.length & !igual; j++) {
                         if(data1[i]['id'] == data2[j]['id'] && 
                            data1[i]['nombre'] == data2[j]['nombre']) 
                                 igual=true;
                     }
                    if(!igual)
                        option.push(data1[i]);
                }
                this.caracteristicas_no = option;
                this.caracteristicas = data2;
            }else{
                for (var i = 0; i < data1.length; i++) {
                        option.push(data1[i]);
                }
                this.caracteristicas_no = option;
                this.caracteristicas = [];
            }           

        },
        agregarOptions: function(){
            if (this.rubro.caracteristicas){ 
                for (var i = 0; i < this.rubro.caracteristicas.length; i++) { 
                    this.options_caracteristicas.push(this.rubro.caracteristicas[i].id)
                }
            }
        }
    }
}
</script>