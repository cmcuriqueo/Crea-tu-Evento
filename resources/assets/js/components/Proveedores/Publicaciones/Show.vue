<template>
	<div class="default-content">

		<section v-if= "publicacion != null " class="content">

			<br>
		    <!-- Default box -->
		    <div class="box box-primary">
		    	<div class="box-header" v-if="auth.user.authenticated">
				    <div class="col-sm-12" v-if="
				    	auth.user.profile.roles_id == role.PROVEEDOR && 
				    	publicacion.proveedor.user_id == auth.user.profile.id" >
						<button class="btn btn-primary pull-left" @click="goToNewPublicacion()">Nueva Publicaci&oacute;n</button>

			        	<div class="pull-right">

			        		<button class="btn btn-primary" @click="modificar(publicacion.id)">
			        			<i class="glyphicon glyphicon-pencil"></i>
			        			Editar Publicaci&oacute;n</button>
			        		<button class="btn" v-bind:class="btnStyle(publicacion.estado)" @click="baja(publicacion.id)">
			        			<i class="glyphicon glyphicon-trash"></i>
			        			{{ publicacion.estado == 1 ? 'Dar de baja' : 'Dar de alta'}}
			        		</button>

			        	</div>
					</div>
		    	</div>
		        <div class="box-body">
		        	<div class="col-sm-12">
				        <section>
						  	<div class="col-md-offset-0.5 col-sm-7">
						  		
				          		<carousel 
				          			:perPage="1" 
				          			:perPageCustom="[[480, 1], [768, 1]]" 
				          			:autoplay="false" 
				          			:autoplayTimeout="15000" 
				          			ref="carousel">
				          			<slide v-for="item in publicacion.fotos" :key="item.nombre">
				          				<img :src="'/storage/proveedores/publicaciones/'+item.nombre" class="img-responsive" style="max-height: 350px;    position: absolute;top: 50%;left: 50%;transform: translateX(-50%) translateY(-50%); min-height:100%">

				          			</slide>
				          		</carousel>

						    </div>
							<div class="col-sm-5">
								<div class="col-sm-12">
									<h3 class="text-uppercase">{{publicacion.titulo}}
				                        <div v-if="publicacion.proveedor.user_id != auth.user.profile.id" class="pull-right">
	                                       <div style="cursor: pointer" v-if="verificar_favorite()">
		                                        <div @click="favorite_icon(publicacion.id)">
		                                            <i class="fa fa-fw fa-heart"></i>
		                                        </div>
		                                    </div>

		                                    <div style="cursor: pointer" v-else>
		                                        <div @click="favorite_icon(publicacion.id)">
		                                            <i class="fa fa-fw fa-heart-o"></i>
		                                        </div>
		                                    </div>
				                        </div>
			                        </h3>

			                        <p>
							      			<strong>Categoria: </strong> {{publicacion.subcategoria.categoria.nombre}}


							      		<div style="float: right;">
											<el-popover 
												ref="popover" 
												placement="bottom" 
												title="Telefono" 
												width="200" 
												trigger="click" 
												:content="publicacion.proveedor.telefono.cod_area+'-'+publicacion.proveedor.telefono.numero">
											</el-popover>
											<el-button v-popover:popover><i class="fa fa-phone"></i></el-button>
							      		</div>
							      	</p>
						      		<p>
						      			<strong>Fecha de publicaci&oacute;n:</strong> {{formatData(publicacion.created_at)}}
						      		</p>

						      		<p>
						      			<strong>Proveedor:</strong> {{publicacion.proveedor.nombre}}
						      		</p>

							       	<p>
					                	<strong>Email:</strong> {{publicacion.proveedor.email}}
						          	</p>
						          	<p v-if="publicacion.prestacion.habilitacion != null">
						          		Ha indicado que cuenta con habilitacion comercial.
						          	</p>
						          	<hr>
						          	<template v-if="publicacion.prestacion.domicilio_id != null">
							          	<p >
							          		<i class="fa fa-map-marker" aria-hidden="true"></i> <strong>Ubicacion</strong>
							          	</p>
							          	<p>
							          		<strong>Ciudad </strong>{{ publicacion.prestacion.domicilio.localidad.nombre }} -
							          		{{ publicacion.prestacion.domicilio.localidad.provincia.nombre }}
							          	</p>
							          	<p>
											<strong>Calle </strong>{{ publicacion.prestacion.domicilio.calle}}<strong> Nro </strong>{{ publicacion.prestacion.domicilio.numero}}
										</p>
							        </template>
						          	<template v-else>
							          	<p>
							          		<i class="fa fa-map-marker" aria-hidden="true"></i> <strong>Ubicacion</strong>
							          	</p>
							          	<p>
							          		<strong>Ciudad </strong>{{ publicacion.proveedor.domicilio.localidad.nombre }} -
							          		{{ publicacion.proveedor.domicilio.localidad.provincia.nombre }}
							          	</p>
							          	<p>
											<strong>Calle </strong>{{ publicacion.proveedor.domicilio.calle }}<strong> Nro </strong>{{ publicacion.proveedor.domicilio.numero}}
										</p>
						          	</template>
						          	
						          	<hr v-if="publicacion.calificacion != null">

	                                <div class="col-sm-12 col-xs-12" v-if="publicacion.calificacion != null">

	                                	<el-rate
											v-model="publicacion.calificacion"
											disabled
											show-text
											text-color="#ff9900"
											text-template="{value} puntos.">
										</el-rate>

	                                </div> 
							        <div class="col-sm-12 col-xs-12">
							        	<hr>

							        	<div class="col-sm-11 col-sm-offset-1">

									        <button type="button" class="btn btn-block btn-success" @click.prevent="showPresupuestoModal()"
									        	v-if="!auth.user.authenticated ||
							                	(auth.user.authenticated && !(auth.user.profile.roles_id != role.USUARIO))">
									        	<i class="fa fa-calendar-check-o" aria-hidden="true"> Solicitar Presupuesto</i> 	
									        </button>

							        	</div>
							        </div>
						        </div>
					        </div>

					    </section>
					</div>
		        </div>
		    </div>
		    <!-- /.box -->
			<div class="nav-tabs-custom">
				<ul class="nav nav-tabs">
					<li class="active"><a href="#informacion" @click.prevent data-toggle="tab">
						Informaci&oacute;n</a>
					</li>
					<li><a href="#opiniones" @click.prevent data-toggle="tab" v-if="publicacion.calificaciones.length > 0">
						Opiniones</a>
					</li>
					<li v-if="auth.user.authenticated && ((auth.user.profile.roles_id == role.PROVEEDOR && 
				    	publicacion.proveedor.user_id == auth.user.profile.id) || auth.user.profile.roles_id == role.ADMINISTRADOR || auth.user.profile.roles_id == role.SUPERVISOR)"><a href="#proveedor" @click.prevent data-toggle="tab">Eventos y Estadisticas</a>
				    </li>
				</ul>
				<div class="tab-content">

             		<div class="tab-pane active" id="informacion">
	             		<div class="box-body">
							<div v-if="publicacion.caracteristicas.length != 0" class="col-sm-12">
								<h4><u>Caracteristicas</u></h4>
								<ul>
									<div v-for="item in publicacion.caracteristicas">
										<div class="col-sm-4">
											<li>
												<div class="col-sm-6">{{ strUpper(item.nombre) }}</div>
												<div class="col-sm-6" 
													v-if="item.adicional && item.pivot.informacion != null"> 
													{{ strUpper(item.pivot.informacion) }}
												</div>
											</li>
											
										</div>
									</div>
								</ul>
								<br><br><hr>
							</div>

					        <div v-if="publicacion.oferta != null && publicacion.oferta.length!=0" class="col-sm-12">
					          	<h4><u>Oferta</u></h4>
					          	{{publicacion.oferta}}
					          	<p v-if=" publicacion.fecha_finalizacion != '0000-00-00' && publicacion.fecha_finalizacion != null ">
				          			Fecha Finalizacion: {{ publicacion.fecha_finalizacion }}
				         		</p>
				         		<p v-else>
				         			Oferta permanente
				         		</p>
				         		<br><hr>
					        </div>
					        <div v-if="publicacion.articulos.length > 0" class="col-sm-12">
					        	<h4><u>Servicios y Productos</u></h4>
					        	<div class="col-sm-12" v-for="(rubro, index) in publicacion.prestacion.rubros" v-if=" showRubroInfo(rubro.id)">
					        		<h5><b>{{ strUpper(rubro.nombre) }}</b></h5>
					        		{{ printArticles(rubro.id)}}
					        		<hr v-if="(index+1) == publicacion.prestacion.rubros.length">
					        	</div>
					        </div>
					        <div class="col-sm-12">
				               	<div class="col-sm-12" v-html='publicacion.descripcion'></div>
				            </div>
				        </div>
             		</div>

             		<div class="tab-pane" id="opiniones" name="opiniones">
             			<div class="row">
             				<div class="col-sm-12">
             					<div v-for="(calificacion, index) in publicacion.calificaciones" v-if="index <= quantityCalificaciones">
								    <div class="col-sm-12">
								    	<hr v-if="index > 0">
								    	<div class="pull-right" v-if="calificacion.reportado == false && auth.user.authenticated && (auth.user.profile.roles_id == role.PROVEEDOR)">
											<el-dropdown @command="handleCommand">
												<span class="el-dropdown-link">
													<i class="el-icon-arrow-down el-icon--right"></i>
												</span>
												<el-dropdown-menu slot="dropdown">
													<el-dropdown-item v-bind:command="calificacion">Reportar</el-dropdown-item>
												</el-dropdown-menu>
											</el-dropdown>
								    	</div>
								    	<div class="pull-right" v-if="auth.user.authenticated && (auth.user.profile.roles_id == role.SUPERVISOR || auth.user.profile.roles_id == role.ADMINISTRADOR)">
											<el-dropdown @command="handleCommandDelete">
												<span class="el-dropdown-link">
													<i class="el-icon-arrow-down el-icon--right"></i>
												</span>
												<el-dropdown-menu slot="dropdown">
													<el-dropdown-item v-bind:command="calificacion">Eliminar</el-dropdown-item>
												</el-dropdown-menu>
											</el-dropdown>
								    	</div>
								    	<div v-if="calificacion.reportado == true">
											<span class="pull-right-container">
												<small class="label pull-right bg-red">reportado</small>
											</span>
								    	</div>
								    	<div class="col-sm-12">
											<div class="user-block">
												<img class="img-circle img-bordered-sm" :src="'/storage/avatars/' + calificacion.reserva.user.usuario.avatar" alt="avatar">
												<span class="username">
													{{ calificacion.reserva.user.usuario.nombre }}
													{{ calificacion.reserva.user.usuario.apellido }}
												</span>
												<span class="description">{{ formatDataNow(calificacion.created_at) }}</span>
											</div>
										</div>
								        <div class="col-sm-6">
								            <label>Calidad: </label> 
								            <el-rate
												v-model="calificacion.calidad"
												disabled
												show-score
												text-color="#ff9900"
												score-template="{value} puntos">
											</el-rate>
								        </div>
								        <div class="col-sm-6">
								            <label>Relacion Precio Calidad: </label>
								            <el-rate
												v-model="calificacion.calidad_precio"
												disabled
												show-score
												text-color="#ff9900"
												score-template="{value} puntos">
											</el-rate>
								        </div>
								        <div class="col-sm-6">
								            <label>Profesionalidad: </label>
								            <el-rate
												v-model="calificacion.profesionalidad"
												disabled
												show-score
												text-color="#ff9900"
												score-template="{value} puntos">
											</el-rate>
								        </div>
								        <div class="col-sm-6">
								            <label>Respuesta: </label>
								            <el-rate
												v-model="calificacion.respuesta"
												disabled
												show-score
												text-color="#ff9900"
												score-template="{value} puntos">
											</el-rate>
								        </div>
								        <div class="col-sm-12">
								            <label>Comentario: </label><br>
								            <p>{{calificacion.comentario}}</p>
								        </div>
								    </div>
								    <div class="col-sm-12">
										<div v-if="index == quantityCalificaciones">
					                        <button class="btn 23btn-default btn-block" @click="verMas">Ver Mas</button>
					                    </div>
								    </div>
             					</div>
             				</div>
             			</div>
             		</div>

             		<div v-if="auth.user.authenticated && ((auth.user.profile.roles_id == role.PROVEEDOR && 
				    	publicacion.proveedor.user_id == auth.user.profile.id) || auth.user.profile.roles_id == role.ADMINISTRADOR || auth.user.profile.roles_id == role.SUPERVISOR)" class="tab-pane" id="proveedor">
				    	<div class="box-body">
				    		<div class="row">
					    		<div class="col-sm-12">
					    			<index-reservas :publicacionId="publicacion.id"></index-reservas><br>
					    		</div>
								<div class="col-sm-12" v-if="loadedData">
									<div class="col-md-offset-1 col-md-5">
										<div class="info-box bg-aqua">
											<span class="info-box-icon"><i class="fa fa-heart-o" style="color: white;"></i></span>
											<div class="info-box-content">
												<span class="info-box-text">Favoritos</span>
												<span class="info-box-number">{{publicacion.favoritos.length}}</span>
												<div class="progress">
													<div class="progress-bar" v-bind:style="'width: '+publicacion.favoritos.length+'%'"></div>
												</div>
												<span class="progress-description">
													{{publicacion.favoritos.length}} tienen en sus favoritos.
												</span>
											</div>
											<!-- /.info-box-content -->
										</div>
										<!-- /.info-box -->
									</div>
									<div class="col-md-5">
										<div class="info-box bg-yellow">
											<span class="info-box-icon"><i class="fa fa-calendar"></i></span>
											<div class="info-box-content">
												<span class="info-box-text">Eventos</span>
												<span class="info-box-number">{{ reservasCount }}</span>
												<div class="progress">
													<div class="progress-bar" v-bind:style="'width: '+reservaAumento+'%'"></div>
												</div>
												<span class="progress-description">
													{{reservaAumento}}% Incremento en el ultimo mes.
												</span>
											</div>
											<!-- /.info-box-content -->
										</div>
										<!-- /.info-box -->
									</div>
									<div class="col-md-4">
										<!-- small box -->
										<div class="small-box bg-aqua">
											<div class="inner">
												<h3>{{reservasNuevas}}</h3>
												<p>Nuevas reservas</p>
											</div>
											<div class="icon">
												<i class="fa fa-shopping-cart"></i>
											</div>
										</div>
									</div>
									<div class="col-md-4">
										<!-- small box -->
										<div class="small-box bg-red">
											<div class="inner">
												<h3>{{ vistas }}</h3>

												<p>Vistas</p>
											</div>
											<div class="icon">
												<i class="ion ion-pie-graph"></i>
											</div>
										</div>
									</div>
									<div class="col-md-4">
										<!-- small box -->
										<div class="small-box bg-green">
											<div class="inner">
												<h3>{{reservasCanceladas}}<sup style="font-size: 20px">%</sup></h3>

												<p>Reservas canceladas</p>
											</div>
											<div class="icon">
												<i class="ion ion-stats-bars"></i>
											</div>
										</div>
									</div>   
									<div class="col-md-12">
										<line-chart style="height: 200px;" 
											:data="dataCharts" 
											:options="{responsive: true, maintainAspectRatio: false}"></line-chart>	
									</div> 
								</div>
							</div>
						</div>
             		</div>
             	</div>
            </div>

            <div class="box box-default" v-if="publicacacionesProveedor.length > 0 && (!auth.user.authenticated || (auth.user.authenticated && auth.user.profile.roles_id != role.PROVEEDOR))">
            	<div class="box-header with-border">
            		<h3 class="box-title">Más publicaciones del proveedor</h3>
            	</div>
            	<div class="box-body">
					<div class="col-sm-3 col-md-3" v-for="(publi, index) in publicacacionesProveedor">
						<router-link 
							tag="a" 
							:to="'/publicacion/'+ publi.id">
							<div class="thumbnail">
								<div style="min-height:200px;">
									<img v-for="(foto, index) in publi.fotos" v-if="index == 0" :src="'/storage/proveedores/publicaciones/'+foto.nombre" alt="foto" style="max-height: 200px;" class="img-responsive">
								</div>
								<div class="caption">
									<p>{{ formatMoney(publi.precio) }}</p>
									<h3 style="text-align:center;">{{ publi.titulo }}</h3>
									
								</div>
							</div>
						</router-link>
					</div>
            	</div>
            </div>
            <div class="box box-default" v-if="publicacacionesSugeridas.length > 0 && (!auth.user.authenticated || (auth.user.authenticated && auth.user.profile.roles_id != role.PROVEEDOR))">
            	<div class="box-header with-border">
            		<h3 class="box-title">Publicaciones Sugeridas</h3>
            	</div>
            	<div class="box-body">
					<div class="col-sm-3 col-md-3" v-for="(publi, index) in publicacacionesSugeridas">
						<router-link 
							tag="a" 
							:to="'/publicacion/'+ publi.id">
							<div class="thumbnail">
								<div style="min-height:200px;">
									<img v-for="(foto, index) in publi.fotos" v-if="index == 0" :src="'/storage/proveedores/publicaciones/'+foto.nombre" alt="foto" style="max-height: 200px;" class="img-responsive">
								</div>
								<div class="caption">
									<p>{{ formatMoney(publi.precio) }}</p>
									<h3 style="text-align:center;">{{ publi.titulo }}</h3>
									
								</div>
							</div>
						</router-link>
					</div>
            	</div>
            </div>
	        <div <div class="box box-default">
	        	<div class="box-header with-border">
	        		<i class="fa fa-fw fa-star-o margin-r-5" style="color: rgb(247, 186, 42);"></i><h3 class="box-title">Opiniones</h3>
	        	</div>
	        	<div class="box-body" v-if="publicacion.calificaciones.length > 0">
	        		<div class="col-sm-3" style="text-align:center;">
		        			<h2>{{publicacion.calificacion}}</h2>
		        			<p>
                            	<el-rate
									v-model="publicacion.calificacion"
									disabled
									text-color="#ff9900">
								</el-rate>
							</p>
							<p style="color:#ff9900"><i class="fa fa-users" aria-hidden="true"></i> {{publicacion.calificaciones.length}} en total.</p>

	        		</div>
	        		<div class="col-md-9">
						<!-- Post -->
						<div class="post clearfix">
							<div class="user-block">
								<img class="img-circle img-bordered-sm" :src="'/storage/avatars/' + publicacion.calificaciones[publicacion.calificaciones.length-1].reserva.user.usuario.avatar" alt="avatar">
								<span class="username">
									{{ publicacion.calificaciones[publicacion.calificaciones.length-1].reserva.user.usuario.nombre }}
									{{ publicacion.calificaciones[publicacion.calificaciones.length-1].reserva.user.usuario.apellido }}
								</span>
								<span class="description">{{ formatDataNow(publicacion.calificaciones[publicacion.calificaciones.length-1].created_at) }}</span>
							</div>
							<!-- /.user-block -->
							<p>
                            	<el-rate
									v-model="publicacion.calificaciones[publicacion.calificaciones.length-1].puntuacion_total"
									disabled
									show-text
									text-color="#ff9900"
									text-template="{value} puntos.">
								</el-rate>
							</p>
							<p>
								{{ publicacion.calificaciones[publicacion.calificaciones.length-1].comentario }}
							</p>

						</div>
						<!-- /.post -->
	        		</div>
	        	</div>
	        	<div class="box-body" v-else>
	        		<div class="text-center">
	        			Esta publicaci&oacute;n no tiene ninguna calificaci&oacute;n aun.
	        		</div>
	        	</div>
		        <!-- /.box-body -->
		        <div  class="box-footer">
		          	<div style="text-align:center;">
		          		<div class="col-sm-12">
		          			<button @click="goBack()" class="btn btn-default">
		                        <i class="glyphicon glyphicon-chevron-left"></i> Atras
		                    </button>
			            </div>
		          	</div>
		        </div>
		        <!-- /.box-footer-->
	        </div>
	    </section>

	    <!-- modal presupuesto -->
	    <div v-if="auth.user.authenticated && showPresupuesto" class="modal" role="dialog" :style="{ display : showPresupuesto  ? 'block' : 'none' }">
	        <div class="modal-dialog modal-lg">
	            <div class="modal-content">
	                <div class="modal-header">
	                    <button type="button" class="close" @click="closeModal()">&times;</button>
	                    <h4 class="modal-title">Solicitar presupuesto a {{ publicacion.proveedor.nombre }}</h4>
	                </div>
	                <new-presupuesto :publicacion="publicacion"></new-presupuesto>
	            </div>
	        </div>
	    </div>

	</div>
</template>
<style type="text/css">
	.fa-heart-o {
		color: #3c8dbc;
	}
	.fa-heart {
		color: #3c8dbc;
	}
</style>
<script>
	import route from './../../../routes.js'
	import auth from './../../../auth.js'
	import Role from '../../../config.js'
	import { Carousel, Slide } from 'vue-carousel';
	import moment from 'moment';
	import NewPresupuesto from './../Presupuestos/New';
	import accounting from 'accounting-js';
	import LineChart from './LineChart.js';

	Vue.component('index-reservas', require('./../Reservas/Index'));
	export default {
		
		data() {
			return {
				publicacion: null,
				puntos: 2.5,
				productoId: null,
				auth: auth,
				role: Role,
				showPresupuesto: false,
				publicacacionesProveedor: [],
				publicacacionesSugeridas: [],
				loadedData: false,
				quantityCalificaciones: 9,
			}
		},
		mounted(){
			this.$events.on('cerrar', () => this.closeModal());
		    this.$nextTick(function() {
		      this.getPublicacion();
		    })
		},
		components: {
        	Carousel, Slide, NewPresupuesto, LineChart
    	},
		methods: {
			getPublicacion: function(){
	            this.$http.get('api/publicacion/'+this.$route.params.publicacionId )
	                .then(response => {
	                    this.publicacion = response.data.publicacion
	                    this.publicacacionesProveedor = response.data.publicacionesProveedor
	                    this.publicacacionesSugeridas = response.data.publicacacionesSugeridas
						var listPath = [
								{route: '/', name: 'Inicio'}, 
								{
									route: '/?with_category='+ this.publicacion.subcategoria.categoria.id, 
									name: this.publicacion.subcategoria.categoria.nombre
								},
								{
									route: '/?with_subcategory='+ this.publicacion.subcategoria.id, 
									name: this.publicacion.subcategoria.nombre},

								{
									route: '/publicacion/'+this.$route.params.publicacionId, 
									name: this.publicacion.titulo
								}]
						this.$events.fire('changePath', listPath, this.publicacion.titulo);
						this.loadedData = true;
	                }, response => {
	                    if(response.status === 404){
	                        router.push('/404');
	                    }
	                    if(response.status === 500){
	                        router.push('/500');
	                    }
	                    this.$toast.error({
	                        title:'¡Error!',
	                        message:'No se ha podido cargar la publicacion. :('
	                    });
	                })
			},
			showPresupuestoModal(){
				if(auth.user.authenticated){
					this.showPresupuesto = true
				}
				else
				{
					route.push('/login');
				}
			},
			verificar_favorite(){
				if (auth.user.authenticated){
					if (this.publicacion.favoritos != null){
		                for (var i = 0; i < this.publicacion.favoritos.length; i++) {
		                    if (this.publicacion.favoritos[i].user_id == auth.user.profile.id){
		                        return true
		                    }
		                }
		            }
		         }
                return false
            },
			favorite_icon(id){
				if (auth.user.authenticated){
                this.$http.post(
                    'api/favoritos/', 
                    {
                        user_id: auth.user.profile.id,
                        publicacion_id : id
                    }).then(response => {
                            this.publicacion.favoritos = response.data.favoritos
                             this.$events.$emit('changeFavorite', eventData => this.getFavourite());
                             this.getPublicacion();
                    }, response => {
                        this.$toast.error({
                            title:'¡Error!',
                            message:'No se ha podido completar la accion. :('
                        });
                    }) 
                 }else{
                    var listPath = [
                        {route: '/login', name: 'Login'}
                    ]
                    this.$events.fire('changePath', listPath, 'Login');
                    route.push('/login');
                 }  
            },
			modificar(id){
				route.push('/publicacion/'+id+'/edit');
			},
			baja(id){
	            this.$http.delete('api/publicacion/'+id )
	                .then(response => {
	                    this.$toast.success({
	                        title:'Acción realizada!',
	                        message:'La acción se han realizado correctamente. :D'
	                    });
	                    this.goBack()
	                }, response => {
	                    this.$toast.error({
	                        title:'¡Error!',
	                        message:'No se han podido realizar la acción. :('
	                    });

	                })
			},
			goToNewPublicacion(){
				route.push('/publicacion/new');
			},
			goBack: function(){
	            route.go(-1)
	        },
            formatData: function(value){

                return (value == null)
                    ? ''
                    : moment(value, 'YYYY-MM-DD').format('D MMM YYYY');
            },
			btnStyle: function(estado){
				if(estado == 1) return ' btn-danger';
				else return ' btn-success';
			},
			showRubroInfo(rubro_id){
				for(var articulo of this.publicacion.articulos){
					if(articulo.rubro_id == rubro_id){
						return true;
					}
				}
				return false;
			},
			printArticles(rubro_id){
				var list = '';
				var primero = true;
				for(var articulo of this.publicacion.articulos){
					if(articulo.rubro_id == rubro_id){
						if(primero){
							list = this.strUpper(articulo.nombre)
						} else {
							list = list + ', ' + articulo.nombre;
						}
						primero = false;
					}
				}
				return list+'.';
			},
			closeModal: function(){
                this.showPresupuesto = false;
            },
	        strUpper: function(str){
	                var res = str.substring(1, str.length);
	                var res  = res.toLowerCase();
	                var res1 = str.substring(0, 1);
	                var res1 = res1.toUpperCase();
	                return  res1+''+res;
	        },
            formatMoney(value){
            	return (value == null)
                    ? ''
            		: accounting.formatMoney(value, "$", 2, ",", ".");
            },
            getUltimaCalificacion(){
            	var len = this.publicacion.calificaciones.length;
            	this.ultimaCalificacion = this.publicacion.calificaciones[len-1];
            },
            formatDataNow: function(value){
                moment.locale('es');
                return (value == null)
                    ? ''
                    : moment(value, 'YYYY-MM-DD').fromNow();
            },
			verMas: function(){
                if((this.quantityCalificaciones + 10) > this.publicacion.calificaciones.length )
                {
                    this.quantityCalificaciones = this.quantityCalificaciones + 10;
                } else {
                    this.quantityCalificaciones = this.publicacion.calificaciones.length;
                }
            },
			handleCommand(command) {
	            this.$http.patch('api/calificacion/'+command.id+'/reportar' )
	                .then(response => {
	                    this.$toast.success({
	                        title:'Acción realizada!',
	                        message:'Se ha reportado esta calificacion'
	                    });
	                    command.reportado = true;
	                }, response => {
	                    this.$toast.error({
	                        title:'¡Error!',
	                        message:'No se han podido realizar la acción.'
	                    });

	                })
			},
			handleCommandDelete(command) {
	            this.$http.delete('api/calificacion/'+command.id )
	                .then(response => {
	                    this.$toast.success({
	                        title:'Acción realizada!',
	                        message:'Se ha reportado esta calificacion'
	                    });
	                    for (var i = 0; i < this.publicacion.calificaciones.length; i++) 
	                    {
	                    	if(this.publicacion.calificaciones[i].id == command.id)
	                    	{
	                    		this.publicacion.calificaciones.splice(i,1);
	                    		break;
	                    	}
	                    }
	                    return;
	                }, response => {
	                    this.$toast.error({
	                        title:'¡Error!',
	                        message:'No se han podido realizar la acción.'
	                    });

	                })
			}
		},
		computed: {
			reservasCount(){
				var count = 0;
				for (var reserva of this.publicacion.reservas) {
					if(reserva.estado == 'confirmado')
						count++;
				}
				if(count == 0)
					return 0;
				return count;
			},
			reservaAumento(){
				var count = 0;
				var countTomorrow = 0;
				for (var reserva of this.publicacion.reservas) {
					if(reserva.estado == 'confirmado'){
						count++;
						if(moment().subtract(1, 'months').isAfter(moment(reserva.fecha, 'YYYY-MM-DD'))){
							countTomorrow++;
						}
					}
				}
				if(count == 0)
					return 0;
				return parseInt((countTomorrow*100)/count);
			},
			reservasNuevas(){
				var count = 0;
				for (var reserva of this.publicacion.reservas) {
					if(reserva.estado == 'reserva' && moment(reserva.fecha, 'YYYY-MM-DD').isAfter(moment()))
						count++;
				}
				return count;
			},
			reservasCanceladas(){
				var count = 0;
				var countCancelados = 0;
				for (var reserva of this.publicacion.reservas) {
					if(reserva.estado == 'cancelado')
						countCancelados++;
					count++;
				}
				if(count == 0)
					return 0;
				return parseInt((countCancelados*100)/count);
			},
			dataCharts(){
		
				var labels = [];
				var label = 'Visualizaciones de esta publicación';
				var backgroundColor = '#3c8dbc';
				var data = [];

				var hoy = moment({});
				var mesesCount = [ 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ];

				var meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 
					'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

				for (var visualizacion of this.publicacion.vistas) {
					if( moment(visualizacion.created_at, 'YYYY-MM-DD').year() == hoy.year()){
						switch(moment(visualizacion.created_at, 'YYYY-MM-DD').month()+1) {
						    case 1:
						        mesesCount[0] = mesesCount[0] + 1;
						        break;
						    case 2:
						        mesesCount[1] = mesesCount[1] + 1;
						        break;
						    case 3:
						        mesesCount[2] = mesesCount[2] + 1;
						        break;
						    case 4:
						        mesesCount[3] = mesesCount[3] + 1;
						        break;
						    case 5:
						        mesesCount[4] = mesesCount[4] + 1;
						        break;
						    case 6:
						        mesesCount[5] = mesesCount[5] + 1;
						        break;
						    case 7:
						        mesesCount[6] = mesesCount[6] + 1;
						        break;
						    case 8:
						        mesesCount[7] = mesesCount[7] + 1;
						        break;
						    case 9:
						        mesesCount[8] = mesesCount[8] + 1;
						        break;
						    case 10:
						        mesesCount[9] = mesesCount[9] + 1;
						        break;
						    case 11:
						        mesesCount[10] = mesesCount[10] + 1;
						        break;
						    case 12:
						        mesesCount[11] = mesesCount[11] + 1;
						        break;
						}
					}
				}
				for (var i = 0; i <= hoy.month(); i++) {
					labels.push(meses[i]);
					data.push(mesesCount[i]);
				}
				return  { labels: labels, datasets: [{label:label, backgroundColor:backgroundColor, data:data}] }
			},
			vistas(){
				return this.publicacion.vistas.length;
			}
		},
		watch: {
	        '$route.params.publicacionId' (){
	            this.getPublicacion();
	        }
	    }
	}
</script>
<style>
  .el-dropdown-link {
    cursor: pointer;
  }
</style>