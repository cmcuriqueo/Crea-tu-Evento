<template>
    <div>
        <div class="modal-body">
        	<form-localidad v-if="showNow"
                :localidad="localidad" 
                :nuevo="false"
                @validadoEditLocalidad="sendFormEdit()"  
                :errorsApi="errorsApi" >
            </form-localidad>
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
import auth from '../../auth.js';
import FormLocalidad from './Form.vue';

export default {
    props: {
            idLocalidad: {
                type: Number,
                required: true
            },
    },
    data() {
        return {
            data: { type: Object, default: null},//Peticion de datos
            localidad: { type: Object, default: null}, 
            errorsApi: {},
            error: false,
            showNow: false
        }
    },
    components: {
        FormLocalidad
    },
    beforeMount: function(){
        //selected data
        this.getLocalidad();
    },
    methods: {
        //envio de formulario de modificación
        sendFormEdit: function() {
            this.$http.post(
                'api/localidad/'+ this.localidad.id+'/edit', 
                {
                    _method: 'PATCH',
                    nombre: this.localidad.nombre,
                    provincia_id: this.localidad.provincia_id
                })
                .then(response => {
                    this.$events.fire('reloadIndexLocalidad')
                    this.closeModal();
                    this.errorsApi= {},
                    this.$toast.success({
                        title:'¡Cambios realizados!',
                        message:'Se han realizado correctamente los cambios. :D'
                    });
                }, response => {
                    this.validarLocalidad= false;
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
                    this.validarLocalidad = true;
                    this.$events.fire('validarFormLocalidad')
        },
        getLocalidad: function(){
            this.$http.get('api/localidades/'+ this.idLocalidad)
                .then(response => {
                    this.data = response.data.localidad
                    this.cargarLocalidad()

                }, response => {
                    if(response.status === 404){
                        router.push('/404');
                    }
                })
        },
        cargarLocalidad: function(){
            this.localidad = this.data;
            this.showNow = true
        },
        closeModal: function(){
            this.$events.fire('cerrar');
        }

    }
}



        
</script>