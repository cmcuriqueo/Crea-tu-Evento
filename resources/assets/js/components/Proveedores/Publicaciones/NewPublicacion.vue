<template>
	<div class="default-content">
        <section class="content">
            <div class="row">
        		<div class="col-xs-12">
		        	<div class="box box-primary">
		        		<div class="box-header with-border">
		        			<h3 class="box-title">Crear Publicaci&oacute;n</h3>
		        		</div>
		        		<div class="box-body">
								<div class="col-sm-offset-2 col-sm-10" style="text-align:center">
									<el-steps :active="active" finish-status="success">
										<el-step title="Paso 1" description="Rubros"></el-step>
										<el-step title="Paso 2" description="Articulos y Horarios"></el-step>
										<el-step title="Paso 3" description="Publicacion"></el-step>
									</el-steps>
								</div>
								<br>
					            <template v-if="showFormRubro">
					                <form-rubro :rubro="prestacion" 
					                	:domicilio="domicilio" 
					                	:nuevo="nuevoRubro" 
					                	:errorsApi="errorsApi" 
					                	@validado="validoNextForm()"
					                	@validadoEdit="validoNextForm()">	
					                </form-rubro>
					            </template>

					        	<template v-if="showFormArticulo">
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
						        			:publicacionId="null" :nuevo="true" :horariosId="horarios" @update:horario="val => horariosArray = val">
						        		</form-horario>
					        		</div>
					        	</template>

					            <template v-if="showFormPublicacion">
						        	<form-publicacion 
						        		:publicacion="publicacion"
						        		:rubros_id="prestacion.rubros_id"
						        		:nuevo="true"
						        		:validarPublicacion="validarPublicacion"
						        		@validadoNewPublicacion="sendNewForm()"
						        		@update:validarPublicacion="val => validarPublicacion = val"
						        		:errorsApi="errorsApi">
						        	</form-publicacion>
						        </template>

				        </div>
				        <div class="box-footer">
				        	<div style="text-align:center;">

			                    <button v-if="!showFormRubro && showFormArticulo && !showFormPublicacion" 
			                    	class="btn btn-default" type="button" 
			                    	@click.stop="showFormRubro = !showFormRubro; 
			                    		showFormArticulo = !showFormArticulo; articulos=[]; active--">
			                    	Atras
			                    </button>
			                    <button v-if="!showFormRubro && !showFormArticulo && showFormPublicacion" 
			                    	class="btn btn-default" type="button" 
			                    	@click.stop="showFormArticulo = !showFormArticulo; showFormPublicacion = !showFormPublicacion; active--">
			                    	Atras
			                    </button>
			                    <button v-if="showFormRubro && !showFormArticulo && !showFormPublicacion"
			                    	class="btn btn-default" type="button" 
			                    	@click.stop="validarFormRubros()">
			                    	Siguiente
			                    </button>
			                    <button v-if="!showFormRubro && showFormArticulo && !showFormPublicacion"
			                    	class="btn btn-default" type="button" 
			                    	@click.stop="siguientePublicacion()">
			                    	Siguiente
			                    </button>
				        		<button v-if="showFormPublicacion" 
				        			class="btn btn-primary" @click="validateBeforeSubmit()">
				        			Crear Publicaci&oacute;n
				        		</button>
				        	</div>
				        </div>
		        	</div>
		        </div>
		    </div>
        </section>
    </div>
</template>
<script> 
	import FormPublicacion from './Form';
	import route from './../../../routes.js';
    import FormRubro from './../Prestaciones/FormRubro';
    import FormArticulo from './../Articulos/New';
    import FormHorario from './../Horarios/New';
   
    import AddArticulo from './AddArticulo';

	export default {
		data() {
			return {
				publicacion: {
	                    titulo: '',
	                    descripcion: '',
	                    subcategoria_id: '',
	                    precio: 0,
	                    oferta: false,
	                    descuento: null,
	                    fecha_finalizacion: null,
	                    fotos: [],
	                    caracteristicas: []
				},
				validarPublicacion: false,
				errorsApi:[],
				id: null,
				active: 0,
				listPath : [{route: '/', name: 'Inicio'}, {route: '/publicacion/new', name: 'Nueva Publicación'}],
                showFormRubro: true,
                showFormPublicacion: false,
                showFormArticulo: false,
                showFormHorario: false,
                nuevoRubro: true,
                articulos: [],
                horarios: [],
                horariosArray: [],
                articulosArray: [],
                domicilio: {
                    calle: null,
                    numero: null,
                    piso: null,
                    localidad_id: null
                },
                prestacion: {
                    rubros_id: [],
                    comercio: false,
                    habilitacion: null,
                    fecha_habilitacion: null,
                }
			}
		},
		mounted(){
			this.resetForm();
			this.$events.fire('changePath', this.listPath, 'Nueva Publicación');
			//this.$events.on("validadoFormPublicacion", () => this.sendNewForm());
			this.$events.on("agregarIdH", (id) => this.agregarIdH(id));
			this.$events.on('deleteId', (id) => this.deleteId(id));
		},
		components: {
			FormPublicacion, FormRubro, FormArticulo, AddArticulo, FormHorario
		},
		methods: {
			validateBeforeSubmit: function() {
	            this.validarPublicacion = true;
	            this.$events.fire('validarFormPublicacion');
	        },
	        validarFormRubros(){
	        	this.$events.fire('validarForm');
	        },
	        validoNextForm(){
	        	this.showFormPublicacion = false;
	        	this.showFormRubro = false; 
	        	this.showFormArticulo = true;
	        	this.nuevoRubro = false;
	        	this.active++;
	        },
	        sendNewForm(){
	        	if ( this.publicacion.fecha_finalizacion == '0000-00-00' || this.publicacion.fecha_finalizacion == '' ){
	        		this.publicacion.fecha_finalizacion = null
	        	}
	        	var localidad_id = null;
	        	if(this.domicilio.localidad_id != null){
	        		localidad_id = this.domicilio.localidad_id.value;
	        	}
	            this.$http.post(
	                'api/publicacion/', 
	                {
	                    titulo: this.publicacion.titulo,
	                    descripcion: this.publicacion.descripcion,
	                    subcategoria_id: this.publicacion.subcategoria_id,
	                    precio: this.publicacion.precio,
	                    oferta: this.publicacion.oferta,
	                    descuento: this.publicacion.descuento,
	                    fecha_finalizacion: this.publicacion.fecha_finalizacion,
	                    fotos: this.publicacion.fotos,
	                    caracteristicas: this.publicacion.caracteristicas,
	                    horariosId: this.horarios,

	                    comercio: this.prestacion.comercio,
	                    fecha_habilitacion: this.prestacion.fecha_habilitacion,
	                    habilitacion: this.prestacion.habilitacion,
	                    rubros_id: this.prestacion.rubros_id,
	                    servicio: this.servicio,
	                    
	                    calle: this.domicilio.calle,
						localidad_id: localidad_id,
						numero: this.domicilio.numero,
						piso: this.domicilio.piso,

	                    articulos: this.articulos
	                })
	                .then(response => {
	                    this.$toast.success({
	                        title:'¡Publicación Creada!',
	                        message:'Se creado correctamente su publicación. :D'
	                    });
	                    this.$events.fire('changePath', this.listPath, 'Ver Publicacion');
	                    this.id = response.data.id;
						route.push('/publicacion/'+ this.id);
	                }, response => {
	                    this.validarPublicacion= false;
	                    this.$toast.error({
	                        title:'¡Error!',
	                        message:'No se han podido crear su publicación.'
	                    });
	                    if(response.status === 422)
	                    {
	                        this.errorsApi = response.body;
	                    }
	                })
	        },
	        agregarIdH(id){
				this.horarios.push(id)
			},
			siguientePublicacion(){
				this.calcularPrecio();
				this.showFormArticulo = !this.showFormArticulo;
				this.showFormPublicacion = !this.showFormPublicacion; 
				this.active++;
			},
			deleteId(id){
				for (var i = 0; i < this.horarios.length; i++) {
					if (this.horarios[i] == id){
						this.horarios.splice(i,1)
                    }
				}
			},
	        resetForm(){
	        	this.publicacion = {
	        		'titulo':'',
	        		'descripcion': '',
	        		'oferta': '',
	        		'fotos': null,
	        		'fecha_finalizacion': null
	        	}
	        },
	        goBack: function(){
	            route.go(-1)
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