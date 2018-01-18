<template>
	<div>
		<form role="form">
			<template>
				<div class="col-sm-4">
		            <div class="form-group has-feedback">
		                <div class="col-sm-12">
		                    <label for="fecha" class="control-label">Fecha del evento </label><br>
		                    <el-date-picker
						    	v-model="reserva.fecha"
						    	name="fecha"
						    	disabled
						    	type="date"
						    	:picker-options="pickerOptions"
						    	placeholder="Seleccione un dia">
						    </el-date-picker>
		                </div>
		            </div>
		        </div>

	        	<div v-if="reserva.hora_inicio != null" class="col-sm-8">
	        		<div class="col-sm-12">
						<div class="callout">
							<label for="horario" class="control-label">Horarios  </label><br>

							Desde {{reserva.hora_inicio }}hs hasta {{reserva.hora_finalizacion}}hs
						</div>
		        	</div>
	        	</div>
		    </template>

			<div class="col-sm-12">
				<hr>
	            <div class="form-group has-feedback">
	                <div class="direct-chat-messages col-sm-12" style="height: 180px;">
	                    <label for="rubros" class="control-label">{{ nameRubro }}</label><br>
						<el-select v-model="reserva.rubros" multiple placeholder="Seleccione" name="rubros" style="width:100%;" disabled @change="callbackSelectRubros">
							<el-option
								v-for="item in opcionesRubros"
								:key="item.value"
								:label="item.label"
								:value="item.value">
							</el-option>
						</el-select>
	                </div>
	            </div>
	        </div>
	        <div class="col-sm-12" v-if="opcionesArticulos.length > 0">
	        	<hr>
	        	<div class="form-group has-feedback">
	        		<div class="col-sm-12">
	        			<label for="articulos">Articulos</label><br>
	        			<div class="col-sm-12">
	        				<div class="col-sm-4">
	        					producto
	        				</div>
	        				<div class="col-sm-2">
	        					precio
	        				</div>
	        				<div class="col-sm-6">
	        					cantidad
	        				</div>
	        			</div>
	        			<hr>
	        			<div v-for="opcion in opcionesArticulos">

		        			<div class="col-sm-12">
								<div class="col-sm-4">
		        					{{  opcion.nombre }}
		        				</div>
		        				<div class="col-sm-2">
		        					$ {{ opcion.precio }}
		        				</div>
								<div class="col-sm-6">
									<div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('articulo' + opcion.id)&&validarReserva}">

			        					<input class="form-control" type="number" min="1" 
			        						:value="cantidadValue(opcion.id)"
			        						:name="'articulo' + opcion.id"
			        						@change="setCantidad(opcion.id, $event)">
                    					
			        				</div>
		        				</div>
		        			</div>
		        		</div>
	        		</div>
	        	</div>
	        </div>
	       <div class="col-sm-12">
	            <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('precio')&&validarReserva}">
	                <div class="col-sm-4">
	                    <label for="precio" class="control-label">Precio</label><br>
	                    <div class="input-group">
	                        <vue-numeric currency="$" class="form-control" separator="," v-bind:precision="2" v-model="reserva.precio_total" v-validate="'required|min_value:1'" data-vv-name="precio"></vue-numeric>
	                    </div>
	                    <!-- validacion vee-validation -->
	                    <span v-show="errors.has('precio')&&validarReserva" class="help-block">
	                    	 El campo precio es requerido.
                    	</span>             	
	                </div>
	                <div class="col-sm-8" style="padding-top: 30px;" v-if="showAplicarDescuento">
						<template>
						  <!-- `checked` should be true or false -->
						  <el-checkbox v-model="reserva.aplicar_decuento" @change="aplicarDescuento">Aplicar Descuento</el-checkbox>
						</template>
	                </div>
	            </div>
	       	</div>
		</form>
	</div>
</template>
<script>
	import auth from './../../../auth.js'
	import moment from 'moment';
	export default {
		props: {
			rubros: {
				required: true,
				type: Array
			},
			articulos: {
				required: true,
				type: Array
			},
			tipo: {
				required: true,
				type: String
			},
			reserva: {
				required: true,
				type: Object
			},
			isEdit: {
				required: false,
				default: false
			}
		},
		data(){
			return {
				validarReserva: false,
				localidades: [],
				opcionesRubros: [],
				opcionesArticulos: [],
				opcionesReservas: [],
		        pickerOptions: {
					disabledDate: function(date){
						const tomorrow = new Date();
						tomorrow.setDate(tomorrow.getDate() + 1);
						return date < tomorrow;
					}
				}
			}
		},
	    beforeMount(){
	    	this.loadOpcions();
	    },
	    mounted(){
	    	this.$events.on('validarFormReserva', () => this.validateBeforeSubmit());
	    },
	    beforeDestroy() {
		    this.$events.$off('validarFormReserva');
		},
	    methods: {
	    	validateBeforeSubmit: function() {
	            this.$validator.validateAll().then((result) => {
	                if (result){
			                this.validarReserva = false; 
			                this.$emit('validado');
			        } else {
						this.validarReserva = true;
			        }
			        return;
			    }).catch(() => {
		        	
		      	});
		    },
	    	callbackSelectRubros: function(val) {
	    		this.opcionesArticulos = [];
	    		var selectableItems = [];
	    		for(var articulo of this.articulos){
	    			for(var rubro of val){
	    				if(articulo.rubro_id == rubro){
	    					selectableItems.push(articulo.id);
	    					this.opcionesArticulos.push(
	    							{id: articulo.id, nombre: articulo.nombre, stock: articulo.stock, precio: articulo.precio});
	    					break;
	    				}
	    			}
	    		}

	    		for(var i = 0; i < this.reserva.articulos.length; i++){
	    			var esta = false;
	    			for(var articulo_id of selectableItems){
	    				if(this.reserva.articulos[i].articulo_id == articulo_id){
	    					esta = true;
			    		}
	    			}
	    			if(!esta){
	    				this.reserva.articulos.splice(i, 1);
	    			}
	    		}
	    	},
	    	setCantidad(articulo_id, e){
	    		for (var i = 0; i < this.reserva.articulos.length; i++) {
	    			if(articulo_id == this.reserva.articulos[i].articulo_id){
	    				this.reserva.articulos[i].cantidad = e.srcElement.value;
	    				break;
	    			}
	    		}
	    		this.calularCoste();
	    	},
	    	cantidadValue(articulo_id){
	    		for (var i = 0; i < this.reserva.articulos.length; i++) {
	    			if(articulo_id == this.reserva.articulos[i].articulo_id){
	    				 return this.reserva.articulos[i].cantidad;
	    			}
	    		}
	    		return 0;
	    	},
	    	loadOpcions(){
	    		//var rubrosArticulos = [];
	    		console.log();
	    		for(var rubro of this.rubros){

		    			var option = {value: rubro.id, label: rubro.nombre};

		    			//if(!rubro.servicio && !rubro.salon && rubro.producto)
		    			//	rubrosArticulos.push(rubro.id);

		    			this.opcionesRubros.push(option);

	    		}
	    		var articulos = [];
    			for(var articulo of this.reserva.articulos){
    				articulos.push({articulo_id: articulo.id, cantidad: articulo.pivot.cantidad});
    				this.opcionesArticulos.push({
    						id: articulo.id,
    						nombre: articulo.nombre,
    						precio: articulo.precio,
    						stock: articulo.stock,
    						cantidad: articulo.pivot.cantidad
    						});
    			}
    			this.reserva.articulos = articulos;
	    	},
	    	calularCoste(){
	    		var precie = 0.00;
	    		for (var reservado of this.reserva.articulos) {

	    			for (var articulo of this.articulos) {
		    			if(reservado.articulo_id == articulo.id){
		    				precie = precie + (articulo.precio * reservado.cantidad);
		    				break;
		    			}
	    			}
	    		}
	    		if(this.reserva.horario_id != null){
	    			precie = precie + this.reserva.horario.precio;
	    		}
	    		if(!this.reserva.aplicar_decuento){
	    			this.reserva.precio_total = precie;
	    		}
	    		else {
	    			console.log((this.reserva.publicacion.descuento * precie) / 100);
	    			this.reserva.precio_total = precie - ((this.reserva.publicacion.descuento * precie) / 100);
	    		}
	    	},
	    	aplicarDescuento(){
	    		this.calularCoste();
	    	}
	    },
	    computed:{
	    	valueMoney(){
	    		return this.reserva.precio_total;
	    	},
	    	nameRubro(){
	    		for(var rubro of this.rubros){
	    			if(rubro.servicio)
	    				return 'Servicios';
	    		}
	    		return 'Productos';
	    	},
	    	requiredDate(){
	    		for(var rubro of this.rubros){
	    			if(rubro.servicio || rubro.salon)
	    				return true;
	    		}
	    		return false;
	    	},
	    	showAplicarDescuento(){
                if(this.reserva.publicacion.descuento != null){
                    if(this.reserva.publicacion.fecha_finalizacion != null){
                        if(moment(this.reserva.publicacion.fecha_finalizacion, 'YYYY-MM-DD').isAfter(moment({}))){
                            return true;
                        }
                    } else {
                        return true;
                    }
                }
                return false;
	    	}
	    }
	}
</script>