<template>
    <div>
        <div class="modal-body">
        	<form-categoria 
                :categoria="categoria" 
                :nuevo="false"
                @validadoEditCategoria="sendFormEdit()"  
                :errorsApi="errorsApi" >
            </form-categoria>
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
</template>

<script>
import auth from '../../../auth.js';
import FormCategoria from './Form.vue';

export default {
    props: {
            idCategoria: {
                type: Number,
                required: true
            },
    },
    data() {
        return {
            categorias: { type: Object, default: null},//Peticion de datos
            categoria: { type: Object, default: null}, 
            errorsApi: {}
        }
    },
    components: {
        FormCategoria
    },
    beforeMount: function(){
        //selected data
        this.getCategoria();
        
    },
    methods: {
        //envio de formulario de modificación de informacion de usuario
        sendFormEdit: function() {
            this.$http.post(
                'api/categoria/'+ this.categoria.id, 
                {
                    _method: 'PATCH',
                    nombre: this.categoria.nombre,
                    tipo_proveedor: this.categoria.tipo_proveedor
                })
                .then(response => {
                    this.$events.fire('reloadIndexCategoria')
                    this.$events.fire('reloadIndexSubcategoria')
                    this.$events.fire('reloadIndexRubro')
                    this.closeModal();
                    this.errorsApi= {},
                    this.$toast.success({
                        title:'¡Cambios realizados!',
                        message:'Se han realizado correctamente los cambios. :D'
                    });
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
        getCategoria: function(){
            this.$http.get('api/categoria/'+ this.idCategoria)
                .then(response => {
                    this.categorias = response.data.data
                    this.cargarCategoria()

                }, response => {
                    if(response.status === 404){
                        router.push('/404');
                    }
                })
        },
        cargarCategoria: function(){
            this.categoria = this.categorias
        },

        closeModal: function(){
            this.$events.fire('cerrar');
        }

    }
}



        
</script>