<template>
	<div>
	    <div class="modal-body">
	        <div class="box-body">
	        	<form-reserva v-if="loadedReserva"
	                :rubros="publicacion.prestacion.rubros"
	                :articulos="publicacion.articulos"
	                :reserva="reserva"
	                :tipo="'tipo'"
	                :errorsApi="errorsApi"
	                @validado="sendForm()">
	            </form-reserva>
	        </div>
		    <div class="box-footer" style="text-align:center;">
		        <button class="btn btn-default" @click="closeModal()">
		            <i class="glyphicon glyphicon-chevron-left"></i>
		            Atras
		        </button>
		        <button @click="validateBeforeSubmit()" type="button" class="btn btn-primary">
		            Enviar Presupuesto
		        </button>
		    </div>
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
			},
			estado:{
				required: true
			},
			reserva_id: {
				required: true
			}
		},
		data(){
			return {
				errorsApi: [],
				tipo: 'reserva',
				reserva: {},
				loadedReserva: false
			}
		},
		beforeMount(){
			this.getReserva();
		},
		components: { FormReserva },
		methods: {
			validateBeforeSubmit: function() {                 
	            this.$events.fire('validarFormReserva')
	        },
	        getReserva(){
	        	this.$http.get('/api/reserva/'+this.reserva_id)
	        	.then(response => {
	        		this.reserva = response.data;
	        		var rubros = [];
	        		for(var rubro of this.reserva.rubros){
	        			rubros.push(rubro.id)
	        		}
	        		this.reserva.rubros = rubros;
	        		this.loadedReserva = true;
	        	}, response => {
                    this.$toast.error({
                        title:'¡Error!',
                        message:'No se ha podido cargar correctamente'
                    });
                    this.closeModal();
	        	})
	        },
	        sendForm: function(){

	        	var data = {
                	articulos: this.reserva.articulos,
                	precio_total: this.reserva.precio_total,
                	aplicar_decuento: this.reserva.aplicar_decuento
                }

	            this.$http.patch(
	                'api/presupuesto/'+this.reserva.id, data)
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