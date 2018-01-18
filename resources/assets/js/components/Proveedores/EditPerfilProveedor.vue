<template>
    <div>
        <div class="col-sm-4">
            <button type="button" class="btn-block" @click="showModificar = true">
                Modificar Informaci&oacute;n de Proveedor
            </button>
        </div>
        <!-- Modal Modificar-->
        <div id="modificar" class="modal" role="dialog" :style="{ display : showModificar  ? 'block' : 'none' }">
            <div class="modal-dialog modal-lg" v-if="loadedProveedor">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" @click="closeModal()">&times;</button>
                        <h4 class="modal-title">Modificar datos de Proveedor</h4>
                    </div>
                    <div class="modal-body">
                    	<form-proveedor 
                            :proveedor="proveedor" 
                            :validarProveedor="validarProveedor" 
                            :errorsApi="errorsApi"
                            @validadoEditProveedor="sendForm()" 
                            :editProveedor="true"
                            :nuevo="false">
                            
                        </form-proveedor>
                    </div>
                    <div class="modal-footer">
                        <div class="col-sm-12 clearfix" style="text-align:center;">
                            <button class="btn btn-default" @click="closeModal()">
                                <i class="glyphicon glyphicon-chevron-left"></i>
                                Atras
                            </button>
                            <button @click="validateBeforeSubmit()" type="button" class="btn btn-primary">
                                Guargar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import auth from '../../auth.js';
import FormProveedor from './Form.vue';

export default {
    props: {
        idProveedor: {
            type: Number,
            required: true
        }
    },
    data() {
        return {
            proveedores: { type: Object, default: null},//Peticion de datos
            proveedor: { type: Object, default: null},
            validarProveedor: false,
            errorsApi: {},
            error: false,
            loadedProveedor: false,
            showModificar: false
        }
    },
    components: {
        FormProveedor
    },
    beforeMount: function(){
        //selected data
        this.getProveedor();
    },
    methods: {
        //envio de formulario de modificación de informacion de usuario
        sendForm: function() {
            this.$http.patch(
                'api/proveedor/'+ this.proveedor.id+'/edit', 
                {
                    user_id: this.proveedor.user.id,
                    nombre: this.proveedor.nombre,
                    cuit: this.proveedor.cuit,
                    ingresos_brutos: this.proveedor.ingresos_brutos,
                    email: this.proveedor.email,
                    calle: this.proveedor.domicilio.calle,
                    numero: this.proveedor.domicilio.numero,
                    piso: this.proveedor.domicilio.piso,
                    telefono: this.proveedor.telefono,
                    localidad_id: this.proveedor.domicilio.localidad_id.value
                })
                .then(response => {

                    this.$toast.success({
                        title:'¡Cambios realizados!',
                        message:'Se han realizado correctamente los cambios. :D'
                    });
                    this.$events.fire('reloadIndexProveedor');
                    this.closeModal();
                }, response => {
                    this.validar= false;
                    this.validarProveedor= false;
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
            this.validarProveedor = true;
            this.$events.fire('validarFormProveedor')
        },
        getProveedor: function(){
            this.$http.get('api/proveedor/'+ this.idProveedor )
                .then(response => {
                this.proveedores = response.data.data
                   this.cargarProveedor()

                }, response => {
                    if(response.status === 404){
                        router.push('/404');
                    }
                })
        },
        cargarProveedor:function(){
            this.proveedor = this.proveedores;
            this.proveedor.telefono = this.proveedores.telefono.cod_area +'-'+ this.proveedores.telefono.numero;
            this.proveedor.user_id = {
               'value':this.proveedor.user_id,
               'label':this.proveedores.user.usuario.apellido+', '+this.proveedores.user.usuario.nombre+' ('+this.proveedores.user.email+')'
            },
            this.proveedor.domicilio.localidad_id = {
               'value':this.proveedores.domicilio.localidad.id,
               'label':this.proveedores.domicilio.localidad.nombre+' ('+this.proveedores.domicilio.localidad.provincia.nombre+')'
            }
            this.loadedProveedor = true;
        },
        closeModal: function(){
            this.showModificar = false;
        }

    }
}



        
</script>