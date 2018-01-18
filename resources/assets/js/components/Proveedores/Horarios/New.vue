<template>
	<div v-if="showFormH">
	    <div class="box-header">
	            <button class="btn btn-primary pull-right btn-sm"
	            @click="showNewHorario = true">
	            Agregar Horario
	            </button>
	    </div>
		<div v-if="showNewHorario" class="modal" role="dialog" :style="{ display : showNewHorario  ? 'block' : 'none' }">
	        <div class="modal-dialog">
	        <!-- Modal content-->
	            <div class="modal-content">
	                <div class="modal-header">
	                    <button type="button" class="close" @click="closeModal()">&times;</button>
	                    <h4 class="modal-title">Agregar Horario</h4>
	                </div>
	                <div v-if= "horarioNo =! []" >
	                	<h6>No se pudo Cargar El horario del {{horarioNo.dia}} </h6>
	                </div>
	                <div class="modal-body">
	        			<div class="box-body">
							<form-horario :horario="newHorario" :nuevo="true" @validoFormHorario="sendHorario">
							</form-horario>
						</div>
				        <div class="modal-footer" style="text-align:center;">
				            <button class="btn btn-default" @click="closeModal()">
				                <i class="glyphicon glyphicon-chevron-left"></i>
				                Atras
				            </button>
				            <button @click="validateBeforeSubmit()" type="button" class="btn btn-primary">
				                Agregar
				            </button>
				        </div>
					</div>
					<!-- /.box-body -->
				</div>
			</div>
		</div>
		<index-horario 
    		:publicacionId="publicacionId" :horariosId="horariosId" @update:horario="retornar">
		</index-horario>
	</div>
</template>
<script>
	import FormHorario from './Form'
	import IndexHorario from './../Horarios/Index';
	export default {
		props: {
			nuevo: {
				type: Boolean
			},
			horariosId:{
				type: Array
			},
			publicacionId:{
				type: Number
			}
		},
		data(){
			return {
				newHorario:{
					hora_inicio: '',
					hora_fin: '',
					dias: {
							id:'',
							nombre: ''
						},
					precio: 0,
					publicacion_id: ''
				},
				horarioNo: [],
				showFormH: false,
				showNewHorario: false
			}
		},
		components:{
			FormHorario, IndexHorario
		},
		mounted() {
			this.$events.on('showFormH', () => this.showFormH = true);
		},
		methods:{
			validateBeforeSubmit(){
				this.$events.fire('validarFormHorario')
			},
			sendHorario(){
					this.$http.post('api/horario',{
						hora_inicio: this.newHorario.hora_inicio,
						hora_fin: this.newHorario.hora_fin,
						dia: this.newHorario.dias,
						precio: this.newHorario.precio,
						publicacion_id: this.publicacionId,
						horariosId: this.horariosId
					}).then(response => {
						this.newHorario = {
							hora_inicio: '',
							hora_fin: '',
							dias: {},
							precio: 0,
							publicacion_id: ''
						}
						if ((response.data.idHorario)&&(this.nuevo)){
							for (var i = 0; i < response.data.idHorario.length; i++) {
								if (response.data.idHorario[i] != null){
									this.$events.fire('agregarIdH',response.data.idHorario[i])
								}
							}
						}
						if (response.data.horarioNo){
							this.horarioNo = response.data.horarioNo
							for(var horario of this.horarioNo){
								this.$toast.error({
			                        title:'¡Error!',
			                        message:'No se ha podido guardar el horario del "'+horario.dia+'" ya que existe un horario cargado en ese horario. :('
			                    })
							}
						}else{
							this.$toast.success({
	                        title:'¡Cambios realizados!',
	                        message:'Se han realizsado correctamente los cambios.'
                   			 });
						}
						this.closeModal();
					}, response => {
						if (response.data.horarioNo){
							this.horarioNo = response.data.horarioNo
							for(var horario of this.horarioNo){
								this.$toast.error({
				                        title:'¡Error!',
				                        message:'No se ha podido guardar el  horario del "'+horario.dia+'" ya que existe un horario cargado en ese horario. :('
				                    })
							}
						}
						if(response.status === 404){
							this.$toast.error({
			                        title:'¡Error!',
			                        message:'No se ha podido guardar el horario. :('
			                    })
						}
						this.closeModal();
					});
					this.closeModal();
			},
			closeModal(){
				this.showNewHorario= false;
				this.$events.fire('close');

			},
			retornar(val){
				this.$emit('update:horario', val)
			}
		}
	}
</script>