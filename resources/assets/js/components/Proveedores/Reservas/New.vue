<template>
    <div class="modal-body">
        <div class="box-body">
        	<form-reserva 
                :rubros="publicacion.prestacion.rubros"
                :domicilio="domicilio"
                :articulos="publicacion.articulos"
                :reserva="reserva"
                :tipo="'tipo'"
                :isEdit="true"
                :errorsApi="errorsApi"
                @validado="sendForm()">
            </form-reserva>
        </div>
        <div class="modal-footer" style="text-align:center;">
            <button class="btn btn-default" @click="closeModal()">
                <i class="glyphicon glyphicon-chevron-left"></i>
                Atras
            </button>
            <button @click="validateBeforeSubmit()" type="button" class="btn btn-primary">
                Reservar
            </button>
        </div>
    </div>
</template>
<script>
	import FormReserva from './Form'; 

	export default {
		props:{
			publicacion: {
				required: true,
				type: Object
			}
		},
		data(){
			return {
				errorsApi: [],
				tipo: 'reserva',
				reserva: {
					rubros: [],
					articulos: [],
					fecha: '',
					horario_id: '',
					estado: 'reservado',
					precio_total: 0,
					aplicar_decuento: false
				},
				domicilio: {
	                calle: null,
	                numero: null,
	                piso: null,
	                localidad_id: null
				}
			}
		},
		components: { FormReserva },
		methods: {
			validateBeforeSubmit: function() {                 
	            this.$events.fire('validarFormReserva')
	        },
	        sendForm: function(){
	        	if(this.domicilio.localidad_id != null){
	        		var localidad_id = this.domicilio.localidad_id.value;
	        	}
	        	var data = {
                	rubros: this.reserva.rubros,
                	articulos: this.reserva.articulos,
                	horario_id: this.reserva.horario_id,
                	estado: this.reserva.estado,
                	fecha: this.reserva.fecha,
                	precio_total: this.reserva.precio_total,
                    calle: this.domicilio.calle,
                    numero: this.domicilio.numero,
                    piso: this.domicilio.piso,
                    localidad_id: this.domicilio.localidad_id,
                    aplicar_decuento: reserva.aplicar_decuento
                }

	            this.$http.post(
	                'api/publicacion/'+this.publicacion.id+'/reserva', data)
	                .then(response => {
	                    this.errorsApi= {},
	                    this.$toast.success({
	                        title:'Reserva',
	                        message:'Se ha realizado correctamente su reserva'
	                    });
	                    this.closeModal()
	                }, response => {
	                    this.$toast.error({
	                        title:'¡Error!',
	                        message:'No se ha podido realizado correctamente su reserva'
	                    });
	                    if(response.status === 422)
	                    {
	                        this.errorsApi = response.body;
	                    }
	                })
	        },
			closeModal: function(){
            	this.$events.fire('cerrar')
        	}
		}
	}
</script>