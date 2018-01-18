<template>

	<div class="row">
		<div v-if="nuevo">
			<div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('dia')&&validarHorario}" style=" text-align: center;" data-toggle="buttons">
				 <label v-for="item in dias" class="btn bg-purple margin" @click="AddDia(item.id)">
				    <input type="checkbox" autocomplete="off"> {{item.nombre}}
				  </label>
				<!-- validacion vee-validation -->
	            <span v-show="errors.has('dia')&&validarHorario" class="help-block">{{ errors.first('dia') }}</span>
        	</div>
		</div>
		<div v-else>
			<div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('dia')&&validarHorario}" style=" text-align: center;" data-toggle="buttons">
				     <h4>{{horario.dia.toUpperCase()}}</h4>
				<!-- validacion vee-validation -->
	            <span v-show="errors.has('dia')&&validarHorario" class="help-block">{{ errors.first('dia') }}</span>
        	</div>
		</div>
		<div class="col-sm-3 col-sm-offset-1">
			<label>Hora Inicio</label><br>
			<div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('desde')&&validarHorario}">
				<el-time-select placeholder="Desde" style="width: 100%;" v-model="horario.hora_inicio" v-validate="'required'":picker-options="{
			      start: '01:00',
			      step: '01:00',
			      end: '23:00'
			    }">
		  		</el-time-select>
		  		<span v-show="errors.has('desde')&&validarHorario" class="help-block">{{ errors.first('desde') }}</span>
		  	</div>
		</div>
		<div class="col-sm-3">
		<label>Hora Fin</label><br>
			<div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('hasta')&&validarHorario}">
			    <el-time-select placeholder="Hasta"  style="width: 100%;" v-model="horario.hora_fin" v-validate="'required'" :picker-options="{
			      start: '02:00',
			      step: '01:00',
			      end: '24:00',
			      minTime: horario.hora_inicio
			    }"></el-time-select>
			    <span v-show="errors.has('hasta')&&validarHorario" class="help-block">{{ errors.first('hasta') }}</span>
			</div>
		</div>
		<div class="col-sm-4 col-xs-4">
			<label>Precio</label><br>
			<div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('precio')&&validarHorario}">
				<vue-numeric currency="$" separator="," v-bind:precision="2" v-model="horario.precio" v-validate="'required|min_value:1'" data-vv-name="precio" class="form-control"></vue-numeric>
				<!-- validacion vee-validation -->
	            <span v-show="errors.has('precio')&&validarHorario" class="help-block">{{ errors.first('precio') }}</span>
	        </div>
		</div>
	</div>

</template>

<script>
	import ElTimeSelect from 'element-ui/lib/time-select.js'

	export default {
		props: {
			horario:{
				required: true,
				type: Object
			},
			nuevo:{
				required: true	
			}
		},
		data(){
			return {
				optionsRubros:[],
				validarHorario: false,
                dias: [],
                showFormH: false,
                diasSelect: [],
			}
		},
		beforeMount(){
			this.cargarDias();
		},
		mounted(){
			this.$events.on("validarFormHorario", () => this.validateSubmit())
			this.$events.on('showFormH', () => this.showFormH = true);
		},
		components: {ElTimeSelect},
		methods:{
	        validateSubmit: function() {
	            this.$validator.validateAll().then((result) => {
	                if (result) {
	                    this.validarHorario = false; 
	                    this.horario.dias = this.diasSelect
	                    if (this.horario.dias.length == 0&&this.nuevo){
	                    	this.validarHorario = true; 
		                    	this.$toast.error({
		                        title:'¡Error!',
		                        message:'No selecciono ningún día. :('
	                    	});
	                    }
	                    if (this.horario.hora_fin < this.horario.hora_inicio){
	                    	this.validarHorario = true; 
	                    	this.$toast.error({
		                        title:'¡Error!',
		                        message:'El horario de finalizado es menor al de inicio. :('
	                    	});
	                    }else{
	                    	if (this.horario.hora_fin == null||(this.horario.hora_inicio == null)){
	                    	this.validarHorario = true; 
	                    	this.$toast.error({
		                        title:'¡Error!',
		                        message:'Debe seleccionar un horario de inicio y de finalización. '
		                    	});
		                    }
	                    	if (this.horario.hora_fin == this.horario.hora_inicio){
	                    	this.validarHorario = true; 
	                    	this.$toast.error({
		                        title:'¡Error!',
		                        message:'El horario de finalizado es igual al de inicio. :('
		                    	});
		                    }

		                }
	                    if (this.validarHorario == false) {
	                    	this.$emit('validoFormHorario'); 
	                    }
	                } else {
	                	this.validarHorario = true;
	                }
	                return;
                }).catch(() => {
                    
                });
	        },
	        AddDia(dia){
	        	for (var i = 0; i < this.diasSelect.length; i++) {
	        		if (dia == this.diasSelect[i]){
	        			this.diasSelect.splice(i, 1)
	        			return
	        		}
	        	}
   	        	this.diasSelect.push(dia)
	        },
	        cargarDias(){
	        	this.dias.push({id: 'domingo',nombre: 'Do'},
	        					{id:'lunes',nombre:'Lu'},
	        					{id:'martes',nombre:'Ma'},
	        					{id:'miercoles',nombre:'Mi'},
	        					{id:'jueves',nombre:'Ju'},
	        					{id:'viernes',nombre:'Vi'},
	        					{id:'sabado',nombre:'Sa'})
	        }
		}
	}
</script>