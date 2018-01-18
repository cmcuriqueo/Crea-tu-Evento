<template>
    <div class="modal-body">
        <div class="box-body">
        	<form-calificacion 
                :rubros="reserva.rubros"
                :calificacion="calificacion"  
                :errorsApi="errorsApi"
                @validadoCalificacion="sendForm()">
            </form-calificacion>
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
import FormCalificacion from './Form.vue';

export default {
	props: {
		publicacion: {
			required: true
		}, 
		reserva: {
			required: true
		}
	},
    data() {
        return {
            calificacion: {
	            calidad: null,
	            calidad_precio: null,
	            profesionalidad: null,
	            respuesta: null,
	            recomendar: false,
	            comentario: ''
            },
            errorsApi: {}
        }
    },
    components: {
        FormCalificacion
    },
    methods: {
        //envio de formulario de nueva Categoria
        sendForm: function() {
            this.$http.post(
                'api/calificacion', 
                {
                	publicacion_id : this.publicacion.id,
                	reserva_id: this.reserva.id,
                    calidad: this.calificacion.calidad,
                    calidad_precio: this.calificacion.calidad_precio,
                    profesionalidad: this.calificacion.profesionalidad,
                    respuesta: this.calificacion.respuesta,
                    recomendar: this.calificacion.recomendar,
                    comentario: this.calificacion.comentario,
                })
                .then(response => {
                    this.$events.fire('reloadIndexCalificacion')
                    this.closeModal(),
                    this.errorsApi= {},
                    this.$toast.success({
                        title:'¡Cambios realizados!',
                        message:'Se han realizado correctamente los cambios. :D'
                    });
                    this.resetForm()
                    this.$events.fire('reloadIndexCalificacion');
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
            this.$events.fire('validarFormCalificacion')

        },
        resetForm() {
            this.calificacion = {
	            calidad: null,
	            calidad_precio: null,
	            profesionalidad: null,
	            respuesta: null,
	            recomendar: false,
	            comentario: ''
            }
        },
        closeModal: function(){
            this.$events.fire('cerrar')
        }
    }
}
</script>
