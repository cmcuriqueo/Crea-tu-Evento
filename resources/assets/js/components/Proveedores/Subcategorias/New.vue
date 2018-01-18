<template>
    <div class="modal-body">
        <div class="box-body">
        	<form-subcategoria 
                :subcategoria="subcategoria"  
                :nuevo="true"
                :errorsApi="errorsApi"
                @validadoNewSubcategoria="sendForm()">
            </form-subcategoria>
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
import FormSubcategoria from './Form.vue';

export default {
    data() {
        return {
            subcategoria:{
                nombre: null,
                categoria_id: ''
            },
            errorsApi: {}
        }
    },
    components: {
        FormSubcategoria
    },
    methods: {
        //envio de formulario de nueva
        sendForm: function() {
            this.$http.post(
                'api/subcategoria', 
                {
                    nombre: this.subcategoria.nombre,
                    categoria_id: this.subcategoria.categoria_id
                })
                .then(response => {
                    this.$events.fire('reloadIndexSubcategoria')
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
                    this.validarSubcategoria = true;
                    this.$events.fire('validarFormSubcategoria')

        },
        resetForm() {
            this.subcategoria={
                nombre: null
            }
        },
        closeModal: function(){
            this.$events.fire('cerrar')
        }
    }
}
</script>
