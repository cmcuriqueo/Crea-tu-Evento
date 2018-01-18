<template>
    <div class="default-content">
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Registrar un rubro</h3>
                        </div>
                        <div class="box-body">
                        	<form-rubro v-if="getRubros"
                                :rubro="rubro" 
                                :domicilio= "domicilio"
                                :rubrosRegistrados="rubrosRegistrados"
                                :nuevo= "true"
                                :errorsApi="errorsApi"
                                @validado="sendForm()">
                            </form-rubro>
                        </div>
                        <div v-if="!getRubros" class="overlay">
                            <i class="fa fa-refresh fa-spin"></i>
                        </div>
                        <div class="box-footer">
                            <div style="text-align:center;">
                                <button @click="validateBeforeSubmit()" type="button" class="btn btn-primary">
                                    Guargar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>

<script>
import auth from '../../../auth.js';
import route from '../../../routes.js';
import FormRubro from './FormRubro.vue';

export default {
    data() {
        return {
            domicilio: {
                calle: null,
                numero: null,
                piso: null,
                localidad_id: null
            },
            rubro:{
                rubro_id: '',
                comercio: false,
                habilitacion: null,
                fecha_habilitacion: null
            },
            showNew: false,
            validarRubro: false,
            errorsApi: [],
            error: false,
            estado: true,
            getRubros: false,
            rubrosRegistrados: [],
            params: {},
            listPath : [{route: '/', name: 'Inicio'}, {route: '/rubro/new', name: 'Registrar Rubro'}]
        }
    },
    beforeMount: function(){
        this.getRubrosRegistrados(); 
    },
    mounted(){
        this.$events.fire('changePath', this.listPath, 'Registrar Rubro');
    },
    components: {
        FormRubro
    },
    methods: {
        //envio de formulario de modificación de informacion de usuario
        sendForm: function() {

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
                        comercio: this.rubro.comercio,
                        habilitacion: this.rubro.habilitacion,
                        fecha_habilitacion: this.rubro.fecha_habilitacion
                }
            }
            this.$http.post(
                'api/proveedor/rubro/', this.params
                )
                .then(response => {
                    this.$toast.success({
                         title:'¡Detalle de Rubro Creada!',
                         message:'Se creado correctamente el Detalle de Rubro. :D'
                    });
                    this.getRubros = false;
                    this.resetForm();
                    this.errorsApi= [];
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
        resetForm() {
            this.rubro={
                tipo_rubro: null,
                rubro_id: '',
                comercio: true,
                habilitacion: null,
                fecha_habilitacion: null
            },
            this.domicilio= {
                calle: null,
                numero: null,
                piso: null,
                localidad_id: null
            }
            this.getRubrosRegistrados();
        },
        getRubrosRegistrados: function() {
            this.$http.get('api/proveedor/'+auth.user.profile.id+'/rubro/'
                ).then(response => {
                    this.rubrosRegistrados = response.data.rubros
                    this.getRubros = true;
                });
        },
    }
}
</script>
