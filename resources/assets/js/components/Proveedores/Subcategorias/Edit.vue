<template>
    <div>
        <div class="modal-body">
        	<form-subcategoria v-if="showNow"
                :subcategoria="subcategoria" 
                :nuevo="false"
                @validadoEditSubcategoria="sendFormEdit()"  
                :errorsApi="errorsApi" >
            </form-subcategoria>
        </div>
        <div class="modal-footer">
            <div class="col-sm-12 box-footer clearfix" style="text-align:center;">
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
import FormSubcategoria from './Form.vue';

export default {
    props: {
            idSubcategoria: {
                type: Number,
                required: true
            },
    },
    data() {
        return {
            data: { type: Object, default: null},//Peticion de datos
            subcategoria: { type: Object, default: null}, 
            errorsApi: {},
            error: false,
            showNow: false
        }
    },
    components: {
        FormSubcategoria
    },
    beforeMount: function(){
        //selected data
        this.getSubcategoria();
    },
    methods: {
        //envio de formulario de modificación
        sendFormEdit: function() {
            this.$http.post(
                'api/subcategoria/'+ this.subcategoria.id, 
                {
                    _method: 'PATCH',
                    nombre: this.subcategoria.nombre,
                    categoria_id: this.subcategoria.categoria_id
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
                    this.validarCategoria= false;
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
        getSubcategoria: function(){
            this.$http.get('api/subcategoria/'+ this.idSubcategoria)
                .then(response => {
                    this.data = response.data.data
                    this.cargarSubcategoria()

                }, response => {
                    if(response.status === 404){
                        router.push('/404');
                    }
                })
        },
        cargarSubcategoria: function(){
            this.subcategoria = this.data;
            this.showNow = true
        },
        closeModal: function(){
            this.$events.fire('cerrar');
        }

    }
}



        
</script>