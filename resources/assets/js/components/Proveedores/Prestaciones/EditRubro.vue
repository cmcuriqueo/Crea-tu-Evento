<template>
    <div>
        <div class="box-body">  
            <div v-if="showForm && getRubros" class="modal-body">

        	<form-rubro 
                :rubro="rubro"
                :domicilio="domicilio" 
                :nuevo="nuevo"
                :rubrosRegistrados="rubrosRegistrados"
                @validadoEdit="sendFormEdit()"
                :errorsApi="errorsApi" >
            </form-rubro>
            </div>

        </div>
        <div v-if="!showForm" class="overlay">
            <i class="fa fa-refresh fa-spin"></i>
        </div>
        <div class="modal-footer">
            <div  style="text-align:center;">
                <button class="btn btn-default" @click="atras()">
                    <i class="glyphicon glyphicon-chevron-left"></i>
                    Atras
                </button>
                <button @click="validateBeforeSubmit()" type="button" class="btn btn-primary">
                    Guargar
                </button>
            </div>
        </div>
    </div>   
</template>

<script>
import auth from '../../../auth.js';
import FormRubro from './FormRubro.vue';

export default {
    props: {
            idRubro: {
                type: Number,
                required: true
            },
    },
    data() {
        return {
            data: { type: Object, default: null},//Peticion de datos
            domicilio: { type: Object, default: null}, 
            rubro:{
                rubro_id: '',
                comercio: false,
                habilitacion: null,
                fecha_habilitacion: null
            },
            validarRubro: false,
            validarDomicilio: false,
            errorsApi: {},
            error: false,
            fecha: null,
            nuevo: false,
            showForm: false,
            getRubros: false,
            rubrosRegistrados: [],
            params: {}
        }
    },
    components: {
        FormRubro
    },
    beforeMount: function(){
        //selected data
        this.getRubro();
        this.getRubrosRegistrados();
        
    },
    
    methods: {
        //envio de formulario de modificación 
        sendFormEdit: function() {

            if (this.rubro.comercio){
                    this.params= {
                            rubro_id: this.rubro.rubro_id,
                            habilitacion: this.rubro.habilitacion,
                            fecha_habilitacion: this.rubro.fecha_habilitacion,
                            calle: this.domicilio.calle,
                            numero: this.domicilio.numero,
                            piso: this.domicilio.piso,
                            localidad_id: this.domicilio.localidad_id.value,
                            comercio: this.rubro.comercio
                    }
            } else {
                this.params= {
                        rubro_id: this.rubro.rubro_id,
                        comercio: this.rubro.comercio
                }
            }
            this.$http.patch(
                'api/proveedor/rubro/'+ this.idRubro, this.params
                ).then(response => {
                    this.$emit('reload')
                    this.atras();
                    this.errorsApi= {},
                    this.$toast.success({
                        title:'¡Cambios realizados!',
                        message:'Se han realizado correctamente los cambios. :D'
                    });
                }, response => {
                    this.validar= false;
                    this.validarRubro= false;
                    this.$toast.error({
                        title:'¡Error!',
                        message:'No se han podido guardar los cambios. :('
                    });
                    if(response.status === 422)
                    {
                        this.errorsApi = response.body;
                    }
                })
        },

        //form validation
        validateBeforeSubmit: function() {                 
                    this.validarRubro = true;
                    this.$events.fire('validarForm')
        },
        getRubro: function(){
            this.$http.get('api/proveedor/rubro/' + this.idRubro)
                .then(response => {
                    this.data = response.data.rubro
                    this.cargarRubro()
                   
                }, response => {
                    if(response.status === 404){
                        router.push('/404');
                    }
                })
        },
        cargarRubro:function(){
            this.domicilio = this.data.domicilio,
            this.rubro = this.data
            if(this.domicilio != null){
                this.domicilio.localidad_id = {
                   'value':this.domicilio.localidad_id,
                   'label':this.domicilio.localidad.nombre+' ('+this.domicilio.localidad.provincia.nombre+')'
                }
                this.rubro.comercio = true;
            } else {
                this.rubro.comercio = false;
            }
            this.showForm = true;

        },
        atras: function(){
            this.$events.fire('cerrar');
            this.$events.fire('reloadComponentPerfil');
        },
        getRubrosRegistrados: function() {
            this.$http.get('api/proveedor/'+auth.user.profile.id+'/rubro/'
                ).then(response => {
                    this.rubrosRegistrados = response.data.rubros
                    this.getRubros = true;
                });
        }
    }
} 
</script>