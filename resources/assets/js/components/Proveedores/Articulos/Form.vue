<template>
	<div class="row">
		<div class="col-xs-6">
			<div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('nombre')&&validarArticulo}">
				<input type="text" v-model="articulo.nombre" v-validate="'required'" name="nombre" class="form-control" placeholder="Nombre">
				<!-- validacion vee-validation -->
	            <span v-show="errors.has('nombre')&&validarArticulo" class="help-block">{{ errors.first('nombre') }}</span>
        	</div>
		</div>
		<div class="col-xs-6">
			<div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('stock')&&validarArticulo}">
				<input type="text" name="stock" v-model="articulo.stock" v-validate="'numeric'" class="form-control" placeholder="Stock">
				<!-- validacion vee-validation -->
	            <span v-show="errors.has('stock')&&validarArticulo" class="help-block">{{ errors.first('stock') }}</span>
        	</div>
		</div>
		<div class="col-xs-6">
			<div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('precio')&&validarArticulo}">
				<vue-numeric currency="$" class="form-control" separator="," v-bind:precision="2" v-model="articulo.precio"  v-validate="'required|min_value:1'" data-vv-name="precio"></vue-numeric>
				<!-- validacion vee-validation -->
	            <span v-show="errors.has('precio')&&validarArticulo" class="help-block">{{ errors.first('precio') }}</span>
	        </div>
		</div>
		<div class="col-xs-6">
			<div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('rubro')&&validarArticulo}">
				<select name="rubro" v-model="articulo.rubro_id" class="form-control" v-validate="'required'">
					<option value="" disabled>Selecione rubros</option>
					<option v-for="option in optionsRubros" :value="option.id">{{option.nombre}}</option>
				</select>
				<!-- validacion vee-validation --> 
	            <span v-show="errors.has('rubro')&&validarArticulo" class="help-block">{{ errors.first('rubro') }}</span>
        	</div>
		</div>
	</div>

</template>
<script>
	export default {
		props: {
			rubros: {
				required: true,
				type: Array
			},
			articulo:{
				required: true,
				type: Object
			}
		},
		data(){
			return {
				optionsRubros:[],
				validarArticulo: false
			}
		},
		beforeMount(){
			this.getRubros();
		},
		mounted(){
			this.$events.on("validarFormArticulo", () => this.validateSubmit())
		},
		methods:{
	        validateSubmit: function() {
            	this.$validator.validateAll().then((result) => {
                	if (result) {
	                    this.validarArticulo = false; 
	                    this.$emit('validoForm'); 
	                } else {
	                	this.validarArticulo = true;
	                }
	                return;
                }).catch(() => {
                });
	        },
			getRubros(){
				this.$http.get('api/rubro').then(response => {
					for(var rubro of response.data){
						for(var id of this.rubros){
							if(id == rubro.id && rubro.producto ){
								this.optionsRubros.push(rubro);
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