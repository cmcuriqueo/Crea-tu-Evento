<template>
    <div class="modal-body">
        <div class="box-body">
        	<form-categoria 
                :categoria="categoria"  
                :nuevo= "true"
                :errorsApi="errorsApi"
                @validadoNewCategoria="sendForm()">
            </form-categoria>
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
    import FormCategoria from './Form.vue';

    export default {
        data() {
            return {
                categoria:{
                    nombre: '',
                    categoria_id: '',
                    tipo_proveedor: '',
                    subacategoriaNombre: ''
                },
                errorsApi: {}
            }
        },
        components: {
            FormCategoria
        },
        methods: {
            //envio de formulario de nueva Categoria
            sendForm: function() {
                this.$http.post(
                    'api/categoria', 
                    {
                        nombre: this.categoria.nombre,
                        categoria_id: this.categoria.categoria_id,
                        subacategoriaNombre: this.categoria.subacategoriaNombre,
                        tipo_proveedor: this.categoria.tipo_proveedor
                    })
                    .then(response => {
                        this.$events.fire('reloadIndexCategoria')
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
                        this.$events.fire('validarFormCategoria')

            },
            resetForm() {
                this.categoria={
                    nombre: null
                }
            },
             closeModal: function(){
                this.$events.fire('cerrar')
            }
        }
    }
</script>
