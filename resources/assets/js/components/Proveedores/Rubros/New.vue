<template>
    <div class="modal-body">
        <div class="box-body">
        	<form-rubro 
                :rubro="rubro"  
                :nuevo="true"
                :errorsApi="errorsApi"
                @validadoNewRubro="sendForm()">
            </form-rubro>
        </div>
        <div class="modal-footer" style="text-align:center;">
            <button class="btn btn-default" @click="closeModal()">
                <i class="glyphicon glyphicon-chevron-left"></i>
                Atras
            </button>
            <button @click="validateBeforeSubmit()" type="button" class="btn btn-primary">
                Guargar
            </button>
        </div>
    </div>
</template>

<script>
import auth from '../../../auth.js';
import FormRubro from './Form';

export default {
    data() {
        return {
            rubro:{
                nombre: null,
                servicio: false,
                salon: false,
                producto: false
            },
            errorsApi: {}
        }
    },
    components: {
        FormRubro
    },
    methods: {
        //envio de formulario de nueva Categoria
        sendForm: function() {

            this.$http.post(
                'api/rubro', 
                {
                    nombre: this.rubro.nombre,
                    servicio: this.rubro.servicio,
                    salon: this.rubro.salon,
                    producto: this.rubro.producto,
                    caracteristicas: this.rubro.caracteristicas
                })
                .then(response => {
                    this.$events.fire('reloadIndexRubro')
                    this.closeModal(),
                    this.errorsApi= {},
                    this.$toast.success({
                        title:'¡Cambios realizados!',
                        message:'Se han realizado correctamente los cambios. :D'
                    });
                    this.resetForm()
                }, response => {
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
            this.$events.fire('validarFormRubro')
        },
        resetForm() {
            this.rubro={
                nombre: null
            }
        },
        closeModal: function(){
            this.$events.fire('cerrar')
        }
    }
}
</script>
