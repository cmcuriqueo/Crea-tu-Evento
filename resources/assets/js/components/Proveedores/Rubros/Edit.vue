<template>
    <div>
        <div v-if="showForm" class="modal-body">
        	<form-rubro
                :rubro="rubro" 
                :nuevo="false"
                @validadoEditRubro="sendFormEdit()"  
                :errorsApi="errorsApi" >
            </form-rubro>
        </div>
        <div class="modal-footer">
            <div class="col-sm-12" style="text-align:center;">
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
import FormRubro from './Form.vue';

export default {
    props: {
            idRubro: {
                type: Number,
                required: true
            },
    },
    data() {
        return {
            datos: { type: Object, default: null},//Peticion de datos
            rubro: { type: Object, default: null}, 
            errorsApi: {},
            showForm: false
        }
    },
    components: {
        FormRubro
    },
    beforeMount: function(){
        //selected data
        this.getRubro();
    },
    methods: {
        //envio de formulario de modificación
        sendFormEdit: function() {
            this.$http.post(
                'api/rubro/'+ this.rubro.id, 
                {
                    _method: 'PATCH',
                    nombre: this.rubro.nombre,
                    salon: this.rubro.salon,
                    servicio: this.rubro.servicio,
                    producto: this.rubro.producto,
                    caracteristicas : this.rubro.caracteristicas
                })
                .then(response => {
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
                    this.$events.fire('validarFormRubro')
        },
        getRubro: function(){
            this.$http.get('api/rubro/'+ this.idRubro)
                .then(response => {
                    this.datos = response.data.data
                    this.cargarRubro()

                }, response => {
                    this.$toast.error({
                        title:'¡Error!',
                        message:'No se han podido cargar las opciones. :('
                    });
                    this.closeModal();
                })
        },
        cargarRubro: function(){
            this.rubro = this.datos
            this.showForm = true;
        },

        closeModal: function(){
            this.$events.fire('cerrar');
        }

    }
}



        
</script>