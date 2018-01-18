<template>
    <div class="modal-body">
        <div class="box-body">
        	<form-caracteristica 
                :caracteristica="caracteristica"  
                :nuevo="true"
                :errorsApi="errorsApi"
                @validadoNewCaracteristica="sendForm()">
            </form-caracteristica>
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
import FormCaracteristica from './Form.vue';

export default {
    data() {
        return {
            caracteristica:{
                nombre: null,
                adicional: 0
            },
            errorsApi: {}
        }
    },
    components: {
        FormCaracteristica
    },
    methods: {
        //envio de formulario de nueva Categoria
        sendForm: function() {
            console.log('enviar caracteristica')
            this.$http.post(
                'api/caracteristica', 
                {
                    nombre: this.caracteristica.nombre,
                    adicional: this.caracteristica.adicional
                })
                .then(response => {
                    this.$events.fire('reloadIndexCaracteristica')
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
                    this.$events.fire('validarFormCaracteristica')

        },
        resetForm() {
            this.caracteristica={
                nombre: null
            }
        },
         closeModal: function(){
            this.$events.fire('cerrar')
        }
    }
}
</script>
