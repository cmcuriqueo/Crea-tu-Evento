<template>
	<div class="modal-body">
		<div class="box-body">
        	<form-presupuesto v-if="loaded"
                :rubros="publicacion.prestacion.rubros"
                :domicilio="domicilio"
                :articulos="publicacion.articulos"
                :presupuesto="presupuesto"
                :tipo="'tipo'"
                :publicacionId="publicacion.id"
                :isEdit="true"
                :errorsApi="errorsApi"
                @validado="sendForm()">
            </form-presupuesto>
		</div>
		<div class="modal-footer" style="text-align:center;">
            <button class="btn btn-default" @click="closeModal()" ref="buttonAtras">
                <i class="glyphicon glyphicon-chevron-left"></i>
                Atras
            </button>
            <button @click="validateBeforeSubmit()" type="button" class="btn btn-primary" ref="button">
                Solicitar
            </button>
		</div>
	</div>
</template>
<script>

	import FormPresupuesto from './Form';

	export default {
		props: {
			publicacion: {
				required: true,
				type: Object
			},
			presupuestoId: {
				required: true
			}
		},
		data(){
			return {
				tipo: 'presupuesto',
				presupuesto: {},
				loaded: false,
				domicilio: {},
				errorsApi: []
			}
		},
		beforeMount(){
			this.getPresupuesto();
		},
		components: { FormPresupuesto },
		methods: {
			getPresupuesto(){
				this.loaded = false;
				this.$http.get('api/reserva/'+ this.presupuestoId).then(response => {
					this.presupuesto = response.data;
					this.domicilio = response.data.domicilio;
					this.loaded = true;
				}, response => {
					this.loaded = false;
                    this.$toast.error({
                        title:'¡Error!',
                        message:'No se ha podido cargar correctamente la información'
                    });
                    this.closeModal();
				})
			},
			validateBeforeSubmit: function() {                 
	            this.$events.fire('validarFormPresupuesto')
	        },
	        sendForm: function(){
	        	if(this.domicilio.localidad_id != null){
	        		var localidad_id = this.domicilio.localidad_id.value;
	        	}
	        	var data = {
                	rubros: this.presupuesto.rubros,
                	articulos: this.presupuesto.articulos,
                	horario_id: this.presupuesto.horario_id,
                	estado: this.presupuesto.estado,
                	fecha: this.presupuesto.fecha,
                	comentario: this.presupuesto.comentario,
                    calle: this.domicilio.calle,
                    numero: this.domicilio.numero,
                    piso: this.domicilio.piso,
                    localidad_id: localidad_id,
                }
                this.$refs.button.disabled = true;
                this.$refs.buttonAtras.disabled = true;
	            this.$http.patch(
	                'api/presupuesto/'+ this.presupuestoId, data)
	                .then(response => {
	                    this.closeModal(),
	                    this.errorsApi= {},
	                    this.$toast.success({
	                        title:'Solicitud de Presupuesto',
	                        message:'Se ha realizado correctamente su solicitud'
	                    });
	                    this.$refs.button.disabled = false;
	                    this.$refs.buttonAtras.disabled = false;
	                }, response => {
	                    this.$toast.error({
	                        title:'¡Error!',
	                        message:'No se ha podido realizado correctamente su solicitud'
	                    });
	                    if(response.status === 422)
	                    {
	                        this.errorsApi = response.body;
	                    }
	                    this.$refs.button.disabled = false;
	                    this.$refs.buttonAtras.disabled = false;
	                })
	        },
			closeModal: function(){
            	this.$events.fire('cerrar')
        	}
		}
	}
</script>