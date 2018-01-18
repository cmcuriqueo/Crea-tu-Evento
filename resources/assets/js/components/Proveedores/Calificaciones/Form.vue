<template>
	<div>
		<form role="form">
			<div class="col-sm-6">
            	<div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('calidad')&&validarCalificacion}">
                	<div class="col-sm-12">
                    	<label  class="control-label">Calidad <template v-if="isSalon">del Sal&oacute;n</template>
                    		<template v-else-if="isServicio">del Servicio</template>
                    		<template v-else="isProducto">de los Productos</template>
                    	</label>

                    	<el-rate
							v-model="calificacion.calidad"
							:texts="['malo', 'decepcionado', 'normal', 'bueno', 'genial']"
							name="calidad"
							data-vv-name="calidad"
							v-validate="'required|min_value:1|max_value:5'"
							@change="changeRate"
							show-text>
						</el-rate>
						<span v-show="errors.has('calidad')&&validarCalificacion" class="help-block">
							{{ errors.first('calidad') }}
						</span>
                    </div>
                </div>
			</div>
			<div class="col-sm-6">
            	<div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('calidad_precio')&&validarCalificacion}">
                	<div class="col-sm-12">
                    	<label  class="control-label">Relaci&oacute;n Precio-Calidad <template v-if="isSalon">del Sal&oacute;n</template>
                    		<template v-else-if="isServicio">del Servicio</template>
                    		<template v-else="isProducto">de los Productos</template></label>
                    	</label>
                    	<el-rate
							v-model="calificacion.calidad_precio"
							:texts="['malo', 'decepcionado', 'normal', 'bueno', 'genial']"
							name="calidad_precio"
							data-vv-name="calidad_precio"
							v-validate="'required|min_value:1|max_value:5'"
							@change="changeRate"
							show-text>
						</el-rate>
						<span v-show="errors.has('calidad_precio')&&validarCalificacion" class="help-block">
							{{ errors.first('calidad_precio') }}
						</span>
                    </div>
                </div>
			</div>
			<div class="col-sm-6">
            	<div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('profesionalidad')&&validarCalificacion}">
                	<div class="col-sm-12">
                    	<label  class="control-label">Profesionalidad del Preveedor <template v-if="isSalon">del Sal&oacute;n</template>
                    		<template v-else-if="isServicio">del Servicio</template>
                    		<template v-else="isProducto">de los Productos</template></label>
                    	</label>
                    	<el-rate
							v-model="calificacion.profesionalidad"
							:texts="['malo', 'decepcionado', 'normal', 'bueno', 'genial']"
							name="profesionalidad"
							data-vv-name="profesionalidad"
							v-validate="'required|min_value:1|max_value:5'"
							@change="changeRate"
							show-text>
						</el-rate>
						<span v-show="errors.has('profesionalidad')&&validarCalificacion" class="help-block">
							{{ errors.first('profesionalidad') }}
						</span>
                    </div>
                </div>
			</div>
			<div class="col-sm-6">
            	<div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('respuesta')&&validarCalificacion}">
                	<div class="col-sm-12">
                    	<label  class="control-label">Respuesta del Proveedor</label>
                    	<el-rate
							v-model="calificacion.respuesta"
							:texts="['malo', 'decepcionado', 'normal', 'bueno', 'genial']"
							name="respuesta"
							data-vv-name="respuesta"
							v-validate="'required|min_value:1|max_value:5'"
							@change="changeRate"
							show-text>
						</el-rate>
						<span v-show="errors.has('respuesta')&&validarCalificacion" class="help-block">
							{{ errors.first('respuesta') }}
						</span>
                    </div>
                </div>
			</div>
			<div class="col-sm-12">
            	<div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('tipo')&&validarCalificacion}">
                	<div class="col-sm-12">
                    	<label  class="control-label">¿Recomendarias a este proveedor?</label><br>
                    	<el-checkbox v-model="calificacion.recomendar">
                    		{{calificacion.recomendar ? 'Si' : 'No'}}
                    	</el-checkbox>
                    </div>
                </div>
			</div>
			<div class="col-sm-12">
				<div class="col-sm-12">
					<div class="alert alert-warning alert-dismissible">
	                	<i class="icon fa fa-warning"></i>
	                	Por favor, sé fiel a los hechos y evita comentarios inapropiados.
	              	</div>
              	</div>
              	<div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('comentario') && validarCalificacion}">
					<div class="col-sm-12">
						<textarea class="form-control" 
							name="comentario"
							v-model="calificacion.comentario"
							v-validate="'required|min:10|max:100'" placeholder="Resume tu experiencia.">
						</textarea>	
						<span v-show="errors.has('comentario')&&validarCalificacion" class="help-block">
							{{ errors.first('comentario') }}
						</span>					
					</div>              		
              	</div>
				
			</div>
		</form>
	</div>
</template>
<script>
	export default {
		props: {
			calificacion: {
				type: Object,
				required: true
			},
			isEdit: {
				required: false,
				default: false
			},
            errorsApi: {
                type: Object,
                required: true
            },
           	rubros: {
           		required: false
           	}
		},
		data() {
			return {
				validarCalificacion: false
			}
		},
	    mounted() {
	        this.$events.$on('validarFormCalificacion', () =>this.validateBeforeSubmit());
	    },
		beforeDestroy() {
		    this.$events.$off('validarFormCalificacion')

		},
		methods: {
			validateBeforeSubmit(){
            	this.$validator.validateAll().then((result) => {
                	if (result) {
	                    this.validarCalificacion = false; 
	                    this.$emit('validadoCalificacion')
	                } else {
	                	this.validarCalificacion = true;
	                }
	                return;
                }).catch(() => {
                    
                });
			},
			changeRate(){
				this.calificacion.puntuacion_total = 
					((this.calificacion.calidad + this.calificacion.calidad_precio + this.calificacion.profesionalidad + this.calificacion.respuesta) / 4);
			}
		},
	    computed: {
	        isSalon(){
	            for (let rubro of this.rubros) {
	            	if(rubro.salon){
	            		return true;
	            	}
	            }
	            return false;
	        }, 
	        isServicio(){
	            for (let rubro of this.rubros) {
	            	if(rubro.servicio){
	            		return true;
	            	}
	            }
	            return false;
	        },
	        isProducto(){
	            for (let rubro of this.rubros) {
	            	if(rubro.producto){
	            		return true;
	            	}
	            }
	            return false;
	        }
	    }
	}
</script>