<template>
	<div>
		<form role="form">
			<input name="tomorrow" :value="fecha" type="hidden">
			<template>
				<div class="col-sm-4">
		            <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('fecha')&&validarPresupuesto}">
		                <div class="col-sm-12">
		                    <label for="fecha" class="control-label">Fecha del evento </label><br>
		                    <el-date-picker
						    	v-model="presupuesto.fecha"
						    	name="fecha"
						    	data-vv-name="fecha"
						    	v-validate="'required|date_format:YYYY-MM-DD|after:tomorrow'"
						    	@change="changeDate"
						    	type="date"
						    	:picker-options="pickerOptions"
						    	placeholder="Seleccione un dia">
						    </el-date-picker>
                    		<span v-show="errors.has('fecha:date_format')&&validarPresupuesto" class="help-block">
                    			{{ errors.first('fecha:date_format') }}
                    		</span>
                    		<span v-show="errors.has('fecha:required')&&validarPresupuesto" class="help-block">
                    			{{ errors.first('fecha:required') }}
                    		</span>
                    		<span v-show="errors.has('fecha:after')&&validarPresupuesto" class="help-block">
                    			El campo fecha del evento debe de ser despues de {{ fecha }}
                    		</span>
		                </div>
		            </div>
		        </div>

		        <template v-if="showEstadoReserva && requiredDate">
		        	<div v-if="opcionesReservas.length > 0" class="col-sm-8">
		        		<div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('horario')&&validarPresupuesto}">
			        		<div class="col-sm-12">
			        			<label for="horario" class="control-label">Horarios disponibles </label><br>
				        		<el-select v-model="presupuesto.horario_id" placeholder="Seleccione un horario" 
				        			name="horario" v-validate="'required'" style="width:100%;">
									<el-option
										v-for="item in opcionesReservas"
										:key="item.id"
										:label="'Desde ' + item.hora_inicio + ' hs Hasta ' + item.hora_finalizacion+ ' hs'+ ' - Coste: '+formatMoney(item.precio)"
										:value="item.id">
									</el-option>
				        		</el-select>
				        		<span v-show="errors.has('horario')" class="help-block">
				        			{{ errors.first('horario') }}
				        		</span>
				        	</div>
				        </div>
		        	</div>
		        	<div v-else class="col-sm-8">
		        		<div class="col-sm-12" style="padding-top: 13px;">
		        			<div class="alert alert-warning alert-dismissible">
		        				No se han encontrado horarios disponibles este d&iacute;a.
		        			</div>
		        		</div>	        		
		        	</div>
		        </template>
		        <template v-if="showNoDisponible && requiredDate">
		        	<div class="col-sm-8">
		        		<div class="col-sm-12" style="padding-top: 13px;">
		        			<div class="alert alert-warning alert-dismissible">
		        				No se han encontrado horarios disponibles este d&iacute;a.
		        			</div>
		        		</div>	        		
		        	</div>
		        </template>
		    </template>

	        <template v-if="isRequiredDomicilio">
				<div class="col-sm-12">
					<hr>
	                <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('localidad')&&validarPresupuesto}">
	                    <div class="col-sm-12">
	                        <label class="control-label">Localidad</label><br>
	                        <v-select
	                            :debounce="250" 
	                            :on-search="getOptions" 
	                            :options="localidades"
	                            data-vv-name="localidad"
	                            v-validate="'required'"
	                            v-model="domicilio.localidad_id" 
	                            placeholder="Seleccione una localidad">
	                        </v-select>
	                       
	                        <!-- validacion vee-validation -->
	                        <span v-show="errors.has('localidad')&&validarPresupuesto" class="help-block">
	                        	{{ errors.first('localidad') }}
	                        </span>
	                    </div>
	                </div>

	                <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('calle')&&validarPresupuesto}">
	                    <div class="col-sm-8">
	                        <label for="inputCalle" class="control-label">Direccion </label><br>
	                        <input name="calle"  v-validate:domicilio.calle="'required|min:4'" type="text" class="form-control" v-model="domicilio.calle" placeholder="Calle">
	                        <!-- validacion vee-validation -->
	                        <span v-show="errors.has('calle')&&validarPresupuesto" class="help-block">{{ errors.first('calle') }}</span>
	                    </div>
	                </div>
	                
	                <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has(('numero')||('piso'))&&validarPresupuesto}">
	                    <div class="col-sm-2 col-xs-6">
	                            <label for="inputNro" class="control-label">N° </label><br>
	                            <input 
	                                name="numero" 
	                                v-validate="'numeric'" 
	                                type="text" v-model="domicilio.numero" 
	                                value="numero" 
	                                class="form-control"
	                                placeholder="Numero">

	                            <!-- validacion vee-validation -->
	                            <span v-show="errors.has('numero')&&validarPresupuesto" class="help-block">
	                            	{{ errors.first('numero') }}
	                            </span>
	                    </div>

	                    <div class="col-sm-2 col-xs-6">
	                        
	                        <label for="inputPiso" class="control-label">Dpto. </label><br>
	                        <input name="piso" type="text" v-model="domicilio.piso" placeholder="Piso" class="form-control col-sm-12">

	                        <!-- validacion vee-validation -->
	                        <span v-show="errors.has('piso')&&validarPresupuesto" class="help-block">{{ errors.first('piso') }}</span>

	                    </div>
		            </div>
		        </div>
	        </template>
			<div class="col-sm-12">
				<hr>
	            <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('rubros')&&validarPresupuesto}">
	                <div class="col-sm-12">
	                    <label for="rubros" class="control-label">{{ nameRubro }}</label><br>
						<el-select v-model="presupuesto.rubros" multiple placeholder="Seleccione" data-vv-name="rubros" v-validate="'required'" style="width:100%;" @change="callbackSelectRubros">
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
	        <div class="col-sm-12" v-if="opcionesArticulos.length > 0 || noOpcionesArticulos.length > 0">
	        	<hr>
	        	<div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('productos')&&validarPresupuesto}">
	        		<div class="col-sm-12">
	        			<label for="productos">Productos</label><br>	        			
	        			<div v-for="opcion in opcionesArticulos" v-bind:key="opcion.id">

		        			<div class="col-sm-4 col-xs-12">
		        				<div class="col-sm-2 col-xs-2" >
		        					<input type="checkbox"
		        					v-validate:articulosArray="'required'" checked
		        					:value="opcion.id" @click="setOrSpliceArticulo(opcion.id)" name="productos">
								</div>
								<div class="col-sm-10 col-xs-10">
		        					{{  opcion.nombre }}
		        				</div>
		        			</div>
		        		</div>
	        			<div v-for="opcion in noOpcionesArticulos" v-bind:key="opcion.id">

		        			<div class="col-sm-4 col-xs-12">
		        				<div class="col-sm-2 col-xs-2" >
		        					<input type="checkbox"
		        					v-validate:articulosArray="'required'" 
		        					:value="opcion.id" @click="setOrSpliceArticulo(opcion.id)" name="productos">
								</div>
								<div class="col-sm-10 col-xs-10">
		        					{{  opcion.nombre }}
		        				</div>
		        			</div>
		        		</div>
	        		</div>
	        		<div class="col-sm-12">
	        			<!-- validacion vee-validation -->
                    	<span v-show="errors.has('productos') && validarPresupuesto" class="help-block">
                    		El campo productos es requerido
                    	</span>
	        		</div>
	        	</div>
	        </div>
	        <div class="col-sm-12">
	        	<hr>
	        	<div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('comentario')&&validarPresupuesto}">
	        		<div class="col-sm-12">
	        			<label for="comentario">Comentario</label><br>	
	        			<textarea class="form-control" data-vv-name="comentario" v-validate="'required|min:10|max:400'" v-model="presupuesto.comentario" placeholder="Ingrese aqui un comentario.">
	        				
	        			</textarea>
						<span v-show="errors.has('comentario') && validarPresupuesto" class="help-block">{{ errors.first('comentario') }}</span>
	        		</div>
	        	</div>
	        </div>
		</form>
	</div>
</template>
<script>
	import auth from './../../../auth.js'
	import vSelect from "vue-select";
	import moment from 'moment';
	import accounting from 'accounting-js';


	export default {
		props: {
			/* Rubros disponibe para seleccion */
			rubros: {
				required: true,
				type: Array
			},
			/* articulos disponibles para seleccion*/
			articulos: {
				required: true,
				type: Array
			},
			tipo: {
				required: true,
				type: String
			},
			/* objeto Presupuesto a modificar*/
			presupuesto: {
				required: true,
				type: Object
			},
			/* indica si se trata de formulario de edion o moificacion*/
			isEdit:{
				required: false,
				default: false
			},
			domicilio: {
				dafault: {
	                calle: null,
	                numero: null,
	                piso: null,
	                localidad_id: null
				},
				required: true
			},
			publicacionId: {
				required: true
			}
		},
		data(){
			return {
				validarPresupuesto: false,
				localidades: [],
				opcionesRubros: [],
				opcionesArticulos: [],
				noOpcionesArticulos: [],
				showNoDisponible: false,
				showEstadoReserva: false,
				opcionesReservas: [],
				fecha: moment({}).add(1, 'day').format('YYYY-MM-DD'),
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
	    	this.$events.on('validarFormPresupuesto', () => this.validateBeforeSubmit());
	    },
	    beforeDestroy() {
		    this.$events.$off('validarFormPresupuesto');
		},
		components: {
			vSelect
		},
	    methods: {
	    	validateBeforeSubmit: function() {
                this.$validator.validateAll().then((result) => {
                    if (result) {
                    	if(this.showNoDisponible == false){
		                    this.validarPresupuesto = false; 
		                    this.$emit('validado');
		                }
	                } else {
	                	this.validarPresupuesto = true;
	                }
	                return;
                }).catch(() => {
                    
                });
		    },
		    getHorarios(){
		    	var fecha = moment(this.presupuesto.fecha, 'YYYY-MM-DD').format('YYYY-MM-DD');
		    	var urlApi = 'api/publicacion/'+this.publicacionId+'/horario/'+fecha;
		    	if(this.isEdit)
		    		urlApi = urlApi + '?except_this_reserva='+this.presupuesto.id;
		    	this.$http.get(urlApi)
		    		.then(response => {
		    			this.showStatus(response.data);
		    		}, response => {

		    		});
		    },
	    	callbackSelectRubros: function(val) {
	    		this.noOpcionesArticulos = [];
	    		this.opcionesArticulos = [];
	    		var selectableItems = [];
	    		for(var articulo of this.articulos){
	    			for(var rubro of val){
	    				if(articulo.rubro_id == rubro){
	    					selectableItems.push(articulo.id);
	    					this.noOpcionesArticulos.push(
	    							{id: articulo.id, nombre: articulo.nombre, stock: articulo.stock, precio: articulo.precio});
	    					break;
	    				}
	    			}
	    		}

	    		for(var i = 0; i < this.presupuesto.articulos.length; i++){
	    			var esta = false;
	    			for(var articulo_id of selectableItems){
	    				if(this.presupuesto.articulos[i].articulo_id == articulo_id){
	    					esta = true;
			    		}
	    			}
	    			if(!esta){
	    				this.presupuesto.articulos.splice(i, 1);
	    			}
	    		}

	    		if(this.isRequiredSalon && !this.selectRubroSalon){
		    		for(var rubro of this.rubros){
		    			if( rubro.salon )
		    			{
							this.presupuesto.rubros.push(rubro.id);
		    				break;
		    			}
		    		}
		    	}
	    	},
	    	getOptions: function(search, loading) {
	            loading(true)
	            this.$http.get('api/localidades/?q='+ search
	                ).then(response => {
	                    this.localidades = response.data.data
	                    loading(false)
	                })
        	},
	    	setOrSpliceArticulo(articulo_id){
	    		var esta = false;
	    		for (var i = 0; i < this.presupuesto.articulos.length; i++) {
	    			if(this.presupuesto.articulos[i].articulo_id == articulo_id){
	    				esta = true;
	    				this.presupuesto.articulos.splice(i, 1);
	    				break
	    			}
	    		}
	    		if(!esta){
	    			this.presupuesto.articulos.push({articulo_id: articulo_id, cantidad: 0});
	    		} 
	    	},
	    	changeDate(val){
	    		this.showEstadoReserva = false;
	    		this.presupuesto.horario_id = null;
	    		if(this.requiredDate && this.presupuesto.fecha != null && this.presupuesto.fecha != '')
	    			this.getHorarios();
	    	},
	    	showStatus(horarios){
	    		this.opcionesReservas = [];
	    		this.showEstadoReserva = false;
	    		if(Array.isArray(horarios)){ 
		    		for(var horario of horarios)
		    		{
		    			if(horario.estado == 'disponible')
				    		this.opcionesReservas.push(
				    			{
				    				id: horario.id,
				    				hora_inicio: horario.hora_inicio,
				    				hora_finalizacion: horario.hora_fin,
				    				precio: horario.precio
					    		});
		    		}
		    		this.showEstadoReserva = true;
		    	}
		    	else if (horarios.estado != 'disponible') 
		    		this.showNoDisponible = true;
		    	else
		    		this.showNoDisponible = false;
	    	},
	    	loadOpcions(){

	    		for(var rubro of this.rubros){

	    			var option = {value: rubro.id, label: rubro.nombre};

    				if(!this.isEdit)
    				{
    					this.presupuesto.rubros.push(rubro.id);
    				}

	    			this.opcionesRubros.push(option);
	    		}
	    		if(!this.isEdit){
	    			for (var articulo of this.articulos) {
		    			this.presupuesto.articulos.push(
							{
								articulo_id: articulo.id, 
								cantidad: 0
	    					});
		    			this.opcionesArticulos.push(
	    							{id: articulo.id, nombre: articulo.nombre, stock: articulo.stock, precio: articulo.precio});
	    			}
	    		}

	    		if(this.isEdit){
	    			if(this.requiredDate)
	    			{
	    				this.getHorarios();
	    			}

	    			if(this.domicilio.localidad_id != null)
	    			{
		    			//seteo datos del lugar del evento
		    			this.domicilio.localidad_id = {
		 						'value':this.domicilio.localidad_id,
	               				'label':this.domicilio.localidad.nombre+' ('+this.domicilio.localidad.provincia.nombre+')'
		    				}
		    		}

	    			//seteo valores para seleccionados de Articulos
	    			var articulos = [];
		    		for (var articulo of this.presupuesto.articulos) {

		    			articulos.push(
		    					{
		    						articulo_id: articulo.id, 
		    						cantidad: articulo.pivot.cantidad
		    					});
		    		}
		    		this.presupuesto.articulos = articulos;

		    		//seteo valores para seleccionados de Rubros
	    			var rubros = [];
		    		for (var i = 0; i < this.presupuesto.rubros.length; i++) {
		    			rubros.push( this.presupuesto.rubros[i].id );
		    		}
		    		this.presupuesto.rubros = rubros;

		    		//seteo opciones para select Articulos
		    		this.noSelected(this.articulos,this.presupuesto.articulos);

	    		}
	    	},
		    noSelected(data1, data2){
	            let option = [];
	            let option2 = [];
	            if(!this.nuevo)
	            {
	                for (let i = 0; i < data1.length; i++) {
	                    let igual=false;
	                     for (let j = 0; j < data2.length & !igual; j++) {
	                         if(data1[i].id == data2[j].articulo_id) 
	                                 igual=true;
	                     }
	                    if(!igual)
	                        option.push(data1[i]);
	                    else
	                    	option2.push(data1[i])
	                }
	                this.noOpcionesArticulos = option;
	                this.opcionesArticulos = option2;
					
					for (var i = 0; i < this.noOpcionesArticulos.length; i++) {
	                	var esta = false;
	                	for (var rubro of this.presupuesto.rubros) {
	                		if(this.noOpcionesArticulos[i].rubro_id == rubro){
	                			esta = true; break;
	                		}
	                	}
	                	if(!esta){
                			this.noOpcionesArticulos.splice(i,1);
                			i--;
	                	}
					}
	            }
		    },
            formatMoney(value){
        		return (value == null)
                    ? ''
            		: accounting.formatMoney(value, "$", 2, ".", ",");
        	},
	    	isChecked(articulo_id, e){

	    		if(!this.isEdit)
	    		{
	    			return false;
	    		}
	    		else
	    		{
	    			for (var i = 0; i < this.presupuesto.articulos.length; i++) {
	    				if(this.presupuesto.articulos[i].articulo_id = articulo_id)
	    					return true;
	    			}
	    			return false;
	    		}
	    	},
	    	dateTomorrow(){
	    		var duration = moment.duration({'days' : 1});
				var tomorrow = moment().add(duration);
				return  tomorrow.format('YYYY-MM-DD');
			}
	    },
	    computed:{
	    	articulosArray(){
	    		return this.presupuesto.articulos;
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
	    	isRequiredDomicilio(){
	    		for(var rubro of this.rubros){
	    			if( rubro.salon )
	    				return false;
	    		}
	    		return true;
	    	},
	    	isRequiredSalon(){
	    		for(var rubro of this.rubros){
	    			if( rubro.salon )
	    				return true;
	    		}
	    		return false;
	    	},
	    	selectRubroSalon(){
	    		var rubro_id = null;
	    		for(var rubro of this.rubros){
	    			if( rubro.salon ){
	    				rubro_id = rubro.id;
	    				break;
	    			}
	    		}
	    		for(var rubro of this.presupuesto.rubros){
	    			if(rubro == rubro_id)
	    				return true
	    		}
	    		return false;
	    	}
	    }
	}
</script>