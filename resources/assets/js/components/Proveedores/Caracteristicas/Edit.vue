<template>
    <div>
        <div v-if="showForm" class="modal-body">
        	<form-caracteristica
                :caracteristica="caracteristica" 
                :nuevo="false"
                @validadoEditCaracteristica="sendFormEdit()"  
                :errorsApi="errorsApi" >
            </form-caracteristica>
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
import FormCaracteristica from './Form.vue';

export default {
    props: {
            idCaracteristica: {
                type: Number,
                required: true
            },
    },
    data() {
        return {
            //datos: { type: Object, default: null},//Peticion de datos
            caracteristica: { type: Object, default: null}, 
            errorsApi: {},
            showForm: false
        }
    },
    components: {
        FormCaracteristica
    },
    beforeMount: function(){
        //selected data
        this.getCaracteristica();
        
    },
    methods: {
        //envio de formulario de modificación
        sendFormEdit: function() {
            this.$http.post(
                'api/caracteristica/'+ this.caracteristica.id, 
                {
                    _method: 'PATCH',
                    nombre: this.caracteristica.nombre,
                    adicional : this.caracteristica.adicional
                })
                .then(response => {
                    this.$events.fire('reloadIndexCaracteristica')
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
                    this.$events.fire('validarFormCaracteristica')
        },
        getCaracteristica: function(){
            this.$http.get('api/caracteristica/'+ this.idCaracteristica)
                .then(response => {
                    this.caracteristica = response.data.caracteristica
                    this.showForm = true;

                }, response => {
                    this.$toast.error({
                        title:'¡Error!',
                        message:'No se han podido cargar las opciones. :('
                    });
                    this.closeModal();
                })
        },
        cargarCaracteristica: function(){
            this.caracteristica = this.datos
            this.showForm = true;
        },

        closeModal: function(){
            this.$events.fire('cerrar');
        }

    }
}



        
</script>