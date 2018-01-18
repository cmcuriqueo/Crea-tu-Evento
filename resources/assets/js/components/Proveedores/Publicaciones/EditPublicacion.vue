<template>
	<div class="default-content">
        <div class="content">
        	<div class="box box-primary">
        		<div class="box-header with-border">
        			<h3 class="box-title">Editar Publicaci&oacute;n</h3>
        		</div>
        		<div class="box-body">
		            <template v-if="showFormPrestacion && showForm">
		                <form-prestacion :rubro="prestacion" 
		                	:domicilio="domicilio" 
		                	:nuevo="false" 
		                	:errorsApi="errorsApi" 
		                	@validado="validoNextForm()"
		                	@validadoEdit="validoNextForm()">	
		                </form-prestacion>
 
		            </template>

		        	<template v-if="showFormArticulo && showForm">
						<div class="col-sm-12">
							<form-articulo
								:rubros="prestacion.rubros_id">
							</form-articulo>
						</div>
						<div class="col-sm-12"> 
							<add-articulo :rubros="prestacion.rubros_id" :articulosSelect="articulos" @update:articulos="val => articulosArray = val">
							</add-articulo>
						</div>
						<div class="col-sm-12">
							<form-horario
								:publicacionId="publicacion.id"
								:nuevo="false"
								:horariosId="null"
								@update:horario="val => horariosArray = val">
							</form-horario>
						</div>
		        	</template>

		        	<form-publicacion v-if="showForm && showFormPublicacion"
		        		:publicacion="publicacion" 
		        		:nuevo="false"
		        		:rubros_id="prestacion.rubros_id"
		        		:validarPublicacion="validarPublicacion"
		        		@validadoEditPublicacion="sendEditForm()"
		        		@update:validarPublicacion="val => validarPublicacion = val"
		        		:errorsApi="errorsApi">
		        	</form-publicacion>
		        </div>
                <div v-if="!showForm" class="overlay">
                    <i class="fa fa-refresh fa-spin"></i>
                </div>
		        <div class="box-footer">
		        	<div style="text-align:center;">

			            <button v-if="showFormPrestacion && !showFormArticulo && !showFormPublicacion" @click="goBack()" class="btn btn-default">
	                        <i class="glyphicon glyphicon-chevron-left"></i>
	                        Atras
	                    </button>

	                    <button v-if="!showFormPrestacion && showFormArticulo && !showFormPublicacion" 
	                    	class="btn btn-default" type="button" 
	                    	@click.stop="showFormPrestacion = !showFormPrestacion; showFormArticulo = !showFormArticulo; articulos=[]">
	                    	Atras
	                    </button>
	                    <button v-if="!showFormPrestacion && !showFormArticulo && showFormPublicacion" 
	                    	class="btn btn-default" type="button" 
	                    	@click.stop="showFormArticulo = !showFormArticulo; showFormPublicacion = !showFormPublicacion">
	                    	Atras
	                    </button>
	                    <button v-if="showFormPrestacion && !showFormArticulo && !showFormPublicacion"
	                    	class="btn btn-default" type="button" 
	                    	@click.stop="validarFormRubros()">
	                    	Siguiente
	                    </button>
	                    <button v-if="!showFormPrestacion && showFormArticulo && !showFormPublicacion"
	                    	class="btn btn-default" type="button" 
	                    	@click.stop="siguientePublicacion()">
	                    	Siguiente
	                    </button>

		        		<button v-if="!showFormPrestacion && !showFormArticulo && showFormPublicacion" class="btn btn-primary" @click="validateBeforeSubmit()">Guardar</button>
		        	</div>
		        </div>
        	</div>
        </div>
    </div>
</template>
<script>
	import FormPublicacion from './Form';
	import router from './../../../routes.js'
    import FormPrestacion from './../Prestaciones/FormRubro';
    import FormArticulo from './../Articulos/New';
    import FormHorario from './../Horarios/New';
    import AddArticulo from './AddArticulo';


	export default {
		data() {
			return {
				publicacion: {
					type: Object
				},
				prestacion: {
					type: Object
				},
				domicilio: {
					type: Object
				},
				articulos: [],
				validarPublicacion: false,
				errorsApi: [],
				showForm: false,
				showFormPrestacion: true,
				showFormArticulo: false,
				showFormPublicacion: false,
				id: null,
				horariosArray: [],
				articulosArray: []
			}
		},
		beforeMount: function(){
			this.getPublicacion();
		},
		components: {
			FormPublicacion, FormPrestacion, FormArticulo, AddArticulo, FormHorario
		},
		methods: {
			validateBeforeSubmit: function() {                 
	            this.validarPublicacion = true;
	            this.$events.fire('validarFormPublicacion')
	        },
	        sendEditForm(){
	        	var fotosIds= []
	        	for (var i = 0; i < this.publicacion.fotos.length; i++) {
	        		fotosIds.push(this.publicacion.fotos[i].id);
	        	}
	        	if ((this.publicacion.fecha_finalizacion == '0000-00-00')||(this.publicacion.fecha_finalizacion == '')){
	        		this.publicacion.fecha_finalizacion = null
	        	}
	        	var localidad_id = null;
	        	if(this.domicilio.localidad_id != null){
	        		localidad_id = this.domicilio.localidad_id.value;
	        	}
	            this.$http.patch('api/publicacion/'+this.$route.params.publicacionId,
	                {
	                    titulo: this.publicacion.titulo,
	                    descripcion: this.publicacion.descripcion,
	                    subcategoria_id: this.publicacion.subcategoria_id,
	                    oferta: this.publicacion.oferta,
	                    descuento: this.publicacion.descuento,
	                    precio: this.publicacion.precio,
	                    fecha_finalizacion: this.publicacion.fecha_finalizacion,
	                    fotos: fotosIds,
	                    fotosUpdate: this.publicacion.fotosUpdate,
	                    caracteristicas: this.publicacion.caracteristicas,
	                    articulos: this.articulos,
	                    
	                    comercio: this.prestacion.comercio,
	                    fecha_habilitacion: this.prestacion.fecha_habilitacion,
	                    habilitacion: this.prestacion.habilitacion,
	                    rubros_id: this.prestacion.rubros_id,
	                    cant_horas: this.prestacion.cant_horas,
	                    precio_servicio: this.prestacion.precio,

	                    calle: this.domicilio.calle,
						localidad_id: localidad_id,
						numero: this.domicilio.numero,
						piso: this.domicilio.piso,

	                })
	                .then(response => {
	                    this.$toast.success({
	                        title:'¡Publiacion Editada!',
	                        message:'Se edito correctamente su publicación. :D'
	                    });
	                    this.$events.fire('changePath', this.listPath, 'Ver Publicacion');
	                    this.id =  response.data.id ; 
						router.push('/publicacion/'+ this.id);
	                }, response => {
	                    this.validarPublicacion= false;
	                    this.$toast.error({
	                        title:'¡Error!',
	                        message:'No se ha podido editar su publicación. :('
	                    });
	                    if(response.status === 422)
	                    {
	                        this.errorsApi = response.body;
	                    }
	                })
	        },
	        validarFormRubros(){
	        	this.$events.fire('validarForm');
	        },
	        validoNextForm(){
	        	this.showFormPublicacion = false;
	        	this.showFormPrestacion = false; 
	        	this.showFormArticulo = true;
	        },
	        siguientePublicacion(){
				this.calcularPrecio();
				this.showFormArticulo = !this.showFormArticulo;
				this.showFormPublicacion = !this.showFormPublicacion;
			},
	        getPublicacion: function(){
	        	this.$http.get('api/publicacion/'+this.$route.params.publicacionId)
	        	.then(response =>{
		        		this.publicacion = response.data.publicacion
		        		this.loadDefaultData();
		        		this.showForm = true;
		        	}, response => {
	                    if(response.status === 404){
	                        router.go(-1)
		                    this.$toast.error({
		                        title:'¡Error!',
		                        message:'No se ha cargado su publicación. :('
		                    });
	                    }
		        	});
	        },
	        loadDefaultData(){
	        	this.domicilio = this.publicacion.prestacion.domicilio;
	        	this.prestacion = this.publicacion.prestacion;
	        	this.prestacion.rubros_id = [];
	            if(this.domicilio != null){
	                this.domicilio.localidad_id = {
	                   'value':this.domicilio.localidad_id,
	                   'label':this.domicilio.localidad.nombre+' ('+this.domicilio.localidad.provincia.nombre+')'
	                }
	                this.prestacion.comercio = true;
	            } else {
	                this.prestacion.comercio = false;
	            }

	        	for (var articulo of this.publicacion.articulos) {
					this.articulos.push(articulo.id);
	        	}
	        	for(var rubro of this.publicacion.prestacion.rubros){
	        		this.prestacion.rubros_id.push(rubro.id);
	        	}
	        },
	        goBack: function(){
	            router.go(-1)
	        },
	        calcularPrecio: function(){
	        	this.publicacion.precio = 0;
        		for(var id of this.articulos){
    	        	for(var articulo of this.articulosArray){
    	        		if(id == articulo.id){
    	        			this.publicacion.precio = this.publicacion.precio + articulo.precio;
    	        		}
	        		}
        		}
        		if(this.horariosArray.length > 0){
	        		var menorCosto = this.horariosArray[0].precio;
	        		for (var i = 1; i < this.horariosArray.length; i++) {
	        			if(this.horariosArray[i].precio < menorCosto)
	        				menorCosto = this.horariosArray[i].precio;
	        		}
	        		this.publicacion.precio = this.publicacion.precio + menorCosto;
	        	}
	        }
		}
	}
</script>