<template>
	<div v-if="showForm">
		<div class="box-header">
		<button class="btn btn-primary btn-sm pull-right" @click="showModal = !showModal">Agregar Articulo</button>
		</div>

        <div v-if="showModal" class="modal" role="dialog" :style="{ display : showModal  ? 'block' : 'none' }">
            <div class="modal-dialog">
            <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" @click="closeModal()">&times;</button>
                        <h4 class="modal-title">Agregar Articulo</h4>
                    </div>
					<div class="modal-body">
			        	<div class="box-body">
							<form-articulo 
								:rubros="rubros" 
								:articulo="newArticulo" 
								@validoForm="sendArticulo">
							</form-articulo>
						</div>
					</div>
					<div class="modal-footer">
						<button @click.stop="validateBeforeSubmit" class="btn btn-info pull-right">Agregar</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
	import FormArticulo from './Form'
	export default {
		props: {
			rubros:{
				type: Array,
				required: true
			}
		},
		data(){
			return {
				showModal: false,
				newArticulo:{
					nombre: '',
					stock: null,
					precio: 0,
					rubro_id: ''
				},
				showForm: false
			}
		},
		components:{
			FormArticulo
		},
		beforeMount: function(){
           this.eventShow();
        },
		mounted() {
			this.$events.on('showForm', () => this.showForm = true);
		},
		methods:{
			validateBeforeSubmit(){
				this.$events.fire('validarFormArticulo')
			},
			sendArticulo(){
				this.$http.post('api/articulo',{
					nombre: this.newArticulo.nombre,
					stock: this.newArticulo.stock,
					precio: this.newArticulo.precio,
					rubro_id: this.newArticulo.rubro_id
				}).then(response => {
					this.closeModal();
                    this.$toast.success({
                        title:'¡Cambios realizados!',
                        message:'Se han realizado correctamente los cambios.'
                    });
					this.$events.fire('reloadIndexArticulo'); 
				}, response => {
                    this.$toast.error({
                        title:'¡Error!',
                        message:'No se han podido guardar los cambios.'
                    });
				});
			},
			closeModal(){
				this.showModal = !this.showModal;
				this.newArticulo = {
					nombre: '',
					stock: null,
					precio: 0,
					rubro_id: ''
				}
			},
			eventShow(){
				this.$http.get('api/rubro').then(response => {
					for(var rubro of response.data){
						for(var id of this.rubros){
							if(id == rubro.id && rubro.producto ){
								this.$events.emit('showForm');
							}
							if(id == rubro.id && (rubro.salon||rubro.servicio)){
								this.$events.emit('showFormH');
							}
						}
					}
				}, response => {
					console.log('Error en rubros');
				});
			}
		}
	}
</script>