<template>
	<div class="modal-body">
		<div class="box-body">
        	<form-presupuesto 
                :rubros="publicacion.prestacion.rubros"
                :domicilio="domicilio"
                :articulos="publicacion.articulos"
                :presupuesto="presupuesto"
                :publicacionId="publicacion.id"
                :tipo="'tipo'"
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
			}
		},
		data(){
			return {
				tipo: 'presupuesto',
				presupuesto: {
					rubros: [],
					articulos: [],
					fecha: '',
					horario_id: '',
					estado: 'presupuesto',
					comentario: ''
				},
				domicilio: {
	                calle: null,
	                numero: null,
	                piso: null,
	                localidad_id: null
				},
				errorsApi: []
			}
		},
		components: { FormPresupuesto },
		methods: {
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
	            this.$http.post(
	                'api/publicacion/'+this.publicacion.id+'/presupuesto', data)
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