<template>
	<div class="default-content">
		<section class="content">
			<div class="row">
				<div class="col-md-9">
					<div class="box box-success" v-if="presupuesto != null">
						<div class="box-header with-border">
							<i class="fa fa-credit-card margin-r-5"></i>
							<h3 class="box-title">Presupuesto</h3>
							<div class="pull-right">
								{{formatDate(presupuesto.fecha)}} 
								<template v-if="presupuesto.hora_inicio!=null">Desde {{formatTime(presupuesto.hora_inicio)}}hs a {{formatTime(presupuesto.hora_finalizacion)}}hs</template>
							</div>
						</div>
						<!-- /.box-header -->
						<div class="box-body">
							<div class="col-md-12">
								<h4 style="text-align:center">
									<template v-if="presupuesto.publicacion.estado">
										<router-link :to="/publicacion/ + presupuesto.publicacion.id">
											<u>{{ presupuesto.publicacion.titulo }}</u>
										</router-link>
									</template>
									<template v-else>
										<el-tooltip content="Esta publicación fue eliminada" placement="bottom" effect="light">
											<u>{{ presupuesto.publicacion.titulo }}</u>
										</el-tooltip>
									</template>
								</h4>
							</div>
							<div class="col-md-12" v-if="presupuesto.rubros.length > 0">
								<h5><label>Servicios y/o Productos</label></h5>
								<div v-for="rubro in presupuesto.rubros" v-if="rubro.servicio || rubro.producto" class="col-sm-3 col-xs-6">
									<li>{{rubro.nombre}}</li>
								</div>
							</div>
							<div class="col-md-12" v-if="presupuesto.articulos.length > 0">
								<hr>
								<h5><label>Articulos</label></h5>
								<div v-for="articulo in presupuesto.articulos" class="col-sm-3 col-xs-6">
									<li>{{articulo.nombre}} <template v-if="articulo.pivot.cantidad > 0"> x {{articulo.pivot.cantidad}}</template></li>
								</div>
							</div>

							<template v-if="presupuesto.presupuestado == true">
								<div class="col-md-12">
									<hr>
									<small class="label bg-green">
										Conste total: {{ formatMoney(presupuesto.precio_total) }} 
										<template v-if="showAplicarDescuento">
											<template v-if="presupuesto.aplicar_decuento">con descuento</template>
											<template v-else>sin descuento</template>
										</template>
									</small>
								</div>
							</template> 
							<template v-if="showConflict == true">
								<div class="col-md-12">
									<hr>
									<div class="callout callout-warning">
										¡Vaya! Ya hay una reserva para el horario elejido.
									</div>
								</div>
							</template>

							<div class="col-sm-12">
								<template v-if="role.USUARIO == auth.user.profile.roles_id">
									<template v-if="presupuesto.presupuestado == 0 && isAfterNow(presupuesto.fecha) && presupuesto.estado == 'presupuesto'">
										<hr>
										<div class="callout callout-success">
											<h4><i class="icon fa fa-info"></i>    Estado</h4>
											<p>En espera de respuesta del proveedor.</p>
							      </div>
									</template>
									<template v-if="presupuesto.presupuestado == 0 && !isAfterNow(presupuesto.fecha) && presupuesto.estado == 'presupuesto'">
										<div class="callout callout-warning">
											<h4><i class="icon fa fa-warning"></i>   Atenci&oacute;n</h4>
											<p>La fecha elegida para su evento en este presupuesto debe de ser actualizada.</p>
							      </div>
									</template>
								</template>
								<template v-if="role.PROVEEDOR == auth.user.profile.roles_id">
									<template v-if="!isAfterNow(presupuesto.fecha) && presupuesto.estado != 'confirmado'">
										<hr>
										<div class="callout callout-warning">
											<h4><i class="icon fa fa-warning"></i>   Atenci&oacute;n</h4>
											<p>La fecha elegida para este evento debe de ser actualizada por el adquiriente.</p>
							      		</div>
									</template>
									<template v-if="presupuesto.presupuestado == true && isAfterNow(presupuesto.fecha) && presupuesto.estado == 'presupuesto'">
										<hr>
										<div class="callout callout-success">
											<h4><i class="icon fa fa-info"></i>   Estado</h4>
											<p>En espera de respuesta del adquiriente.</p>
							      		</div>
									</template>
								</template>
								<template v-if="presupuesto.estado == 'confirmado' && !isAfterNow(presupuesto.fecha)">
									<hr>
									<div class="callout callout-success">
										<h4><i class="icon fa fa-check"></i>   Estado</h4>
										<p>Adquisición confirmada!.</p>										
									</div>
								</template>
								<template v-if="presupuesto.estado == 'confirmado' && isAfterNow(presupuesto.fecha)">
									<hr>
									<div class="callout callout-success">
										<h4><i class="icon fa fa-check"></i>   Estado</h4>
										<p>Adquisición concretada!.</p>										
									</div>
								</template>
								<template v-if="presupuesto.estado == 'cancelado'">
									<hr>
									<div class="callout callout-success">
										<h4><i class="icon fa fa-ban"></i></i>   Estado</h4>
										<p>Solicitud cancelada!.</p>										
									</div>
								</template>
							</div>
						</div>
						<div class="box-footer clearfix"  v-if="presupuesto.estado != 'cancelado' && presupuesto.estado != 'confirmado'">
							<div style="text-align: center;">
								<!-- boton de modificacion de proveedor-->
			            		<button class="btn btn-sm btn-primary btn-flat" 
			            			v-if="role.PROVEEDOR == auth.user.profile.roles_id && presupuesto.estado != 'reservado' && presupuesto.estado != 'cancelado' && presupuesto.estado != 'confirmado' && isAfterNow(presupuesto.fecha)"
			            			@click.prevent="getPublicacion('presupuesto')" :disabled="!presupuesto.publicacion.estado">
			            			Modificar Presupuesto
			            		</button>

								<!-- boton de modificacion de usuario-->
			            		<button class="btn btn-sm btn-default btn-fla" 
			            			v-if="role.USUARIO == auth.user.profile.roles_id &&presupuesto.estado == 'presupuesto'"
			            			@click.prevent="getPublicacion('modificar')" :disabled="!presupuesto.publicacion.estado">
			            			Modificar
			            		</button>
								<!-- boton de reserva de usuario-->
			            		<button class="btn btn-sm btn-primary btn-fla" 
			            			v-if="role.USUARIO == auth.user.profile.roles_id && presupuesto.estado == 'presupuesto' &&presupuesto.presupuestado && isAfterNow(presupuesto.fecha)"
			            			@click.prevent="changeEstadoPresupuesto('reservado')" :disabled="!presupuesto.publicacion.estado">
			            			Reservar
			            		</button>
								<!-- boton de confirmacion reserva de usuario-->
			            		<button class="btn btn-sm btn-primary btn-fla" 
			            			v-if="role.USUARIO == auth.user.profile.roles_id && presupuesto.estado == 'reservado' && isAfterNow(presupuesto.fecha)"
			            			@click.prevent="changeEstadoPresupuesto('confirmado')" :disabled="!presupuesto.publicacion.estado">
			            			Confirmar Reserva
			            		</button>
								<!-- boton de cancelar reserva o presupuesto de usuario-->
			            		<button class="btn btn-sm btn-danger btn-fla" 
			            			v-if="role.USUARIO == auth.user.profile.roles_id  && presupuesto.estado == 'reservado'"
			            			@click.prevent="changeEstadoPresupuesto('cancelado')" :disabled="!presupuesto.publicacion.estado">
			            			Cancelar
			            		</button>
			            	</div>
			            </div>
					</div>
				</div>

				<div class="col-md-3" v-if="presupuesto != null">
					<div class="box box-success collapsed-box">
						<div class="box-header with-border">
							<h3 v-if="role.USUARIO == auth.user.profile.roles_id" class="box-title">Proveedor</h3>
							<h3 v-if="role.PROVEEDOR == auth.user.profile.roles_id" class="box-title">Adquiriente</h3>
							<div class="box-tools pull-right">
								<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
								</button>
							</div>
							<!-- /.box-tools -->
						</div>
						<!-- /.box-header -->
						<div class="box-body" style="display: none;">
							<template v-if="role.USUARIO == auth.user.profile.roles_id">
								<img class="profile-user-img img-responsive" 
								:src="'/storage/avatars/' + presupuesto.publicacion.proveedor.user.usuario.avatar" alt="avatar">
								<div class="col-sm-12" style="margin-top: 65px">
									<label class="col-sm-12">Nombre </label><div class="col-sm-12">{{ presupuesto.publicacion.proveedor.nombre}}</div>
									<label class="col-sm-12">Email </label><div class="col-sm-12">{{ presupuesto.publicacion.proveedor.email}}</div>
									<label class="col-sm-12">Ubicaci&oacute;n </label>
									<div class="col-sm-12">
										{{ presupuesto.publicacion.proveedor.domicilio.localidad.nombre }} - {{presupuesto.publicacion.proveedor.domicilio.localidad.provincia.nombre}}
									</div>
									<label class="col-sm-6">Telefono </label>
									<div class="col-sm-6">
										{{ presupuesto.publicacion.proveedor.telefono.cod_area}}{{ presupuesto.publicacion.proveedor.telefono.numero}}
									</div>
								</div>
							</template>
							<template v-if="role.PROVEEDOR == auth.user.profile.roles_id">
								<img class="profile-user-img img-responsive" 
								:src="'/storage/avatars/' + presupuesto.user.usuario.avatar" alt="avatar">
								<div class="text-center" style="margin-top: 50px">
									<label class="col-sm-12">Nombre </label>
									<div class="col-sm-12">{{ presupuesto.user.usuario.nombre}} {{presupuesto.user.usuario.apellido}}</div>
									<label class="col-sm-12">Email </label><div class="col-sm-12">{{ presupuesto.user.email}}</div>
									<label class="col-sm-12">Ubicaci&oacute;n </label>
									<div class="col-sm-12" v-if="presupuesto.user.usuario.localidad_id != null">
										{{ presupuesto.user.usuario.localidad.nombre }} - {{presupuesto.user.usuario.localidad.provincia.nombre}}
									</div>
								</div>
							</template>
						</div>
						<!-- /.box-body -->
					</div>
					<!-- /.box -->
				</div>

				<div class="col-sm-12" v-if="presupuesto != null && mensajes.length > 0">
					<div class="box box-success direct-chat direct-chat-success">
						<div class="box-header with-border">
							<i class="fa fa-comments-o margin-r-5"></i>
							<h3 class="box-title">Mensajes</h3>
						</div>
						<!-- /.box-header -->
						<div class="box-body">
							<!-- Conversations are loaded here -->
							<div class="direct-chat-messages" style="height: 350px;padding: 20px;">
								<div v-for="mensaje in mensajes">
									<!-- Message. Default to the left -->
									<div v-if="mensaje.from_user_id != auth.user.profile.id" class="direct-chat-msg">
										<div class="direct-chat-info clearfix">
											<span class="direct-chat-name pull-left">{{ getNameFromUser }}</span>
										</div>
										<div class="direct-chat-text">
											{{ mensaje.mensaje }}
											<div class="direct-chat-info clearfix">
												<span class="direct-chat-timestamp pull-right" style="color:black">
													{{ formatDateTime(mensaje.created_at) }} hs
												</span>
											</div>
										</div>
									</div>

									<!-- Message to the right -->
									<div v-else class="direct-chat-msg right">
										<div class="direct-chat-text">
											{{ mensaje.mensaje }}
											<div class="direct-chat-info clearfix">
												<span class="direct-chat-timestamp pull-right" style="color:black">
													{{ formatDateTime(mensaje.created_at) }} hs
												</span>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!-- /.box-body -->
						<div class="box-footer">
							<form action="#" method="post">
							<div class="input-group">
								<input type="text" name="message" v-model="newMensaje" v-validate="'required'" placeholder="Mensaje" class="form-control" :disabled="presupuesto.estado == 'cancelado' || (presupuesto.estado == 'confirmado' && !isAfterNow(presupuesto.fecha))">
								<span class="input-group-btn">
									<button @click.prevent="validateBeforeSend" :disabled="presupuesto.estado == 'cancelado' || (presupuesto.estado == 'confirmado' && !isAfterNow(presupuesto.fecha)) || !presupuesto.publicacion.estado" type="button" class="btn btn-success btn-flat">Enviar</button>
								</span>
							</div>
							</form>
						</div>
					    <!-- /.box-footer-->
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
			</div>
		</section>

	    <!-- modal presupuesto para proveedor-->
	    <div v-if="showPresupuesto && role.PROVEEDOR == auth.user.profile.roles_id" id="presupuesto" class="modal" role="dialog" :style="{ display : showPresupuesto  ? 'block' : 'none' }">
	        <div class="modal-dialog modal-lg">
	            <div class="modal-content">
	                <div class="modal-header">
	                    <button type="button" class="close" @click="closeModal()">&times;</button>
	                    <h4 class="modal-title">Presupuesto - {{ publicacion.titulo }}</h4>
	                </div>
	                <form-presupuesto 
	                	:estado="presupuesto" 
	                	:publicacion="publicacion" 
	                	:reserva_id="presupuesto.id">
	                </form-presupuesto>
	            </div>
	        </div>
	    </div>

	    <!-- modal modificar presupuesto para usuario-->
	    <div v-if="showModificarPresupuesto && role.USUARIO == auth.user.profile.roles_id" id="modificar" class="modal" role="dialog" :style="{ display : showModificarPresupuesto  ? 'block' : 'none' }">
	        <div class="modal-dialog modal-lg">
	            <div class="modal-content">
	                <div class="modal-header">
	                    <button type="button" class="close" @click="closeModal()">&times;</button>
	                    <h4 class="modal-title">Presupuesto - {{ publicacion.titulo }}</h4>
	                </div>
	                <edit-presupuesto 
	                	:estado="presupuesto" 
	                	:publicacion="publicacion" 
	                	:presupuestoId="presupuesto.id">
	                </edit-presupuesto>
	            </div>
	        </div>
	    </div>

	</div>
</template>
<script>
	import auth from './../../auth';
	import Role from './../../config';
	import FormPresupuesto from './../Proveedores/Reservas/Edit';
	import EditPresupuesto from './../Proveedores/Presupuestos/Edit';
	import moment from 'moment';
	import accounting from 'accounting-js';
	export default {
		data(){
			return {
				mensajes: [],
				publicacacionesSugeridas: [],
				presupuesto: null,
				publicacion: {},
				showPresupuesto: false,
				showModificarPresupuesto: false,
				newMensaje: '',
				auth: auth,
				showConflict: false,
				validar: false,
				listPath: [
					{route: '/', name: 'Inicio'}, 
					{route: '/mensajes', name: 'Mensajes'}],
				role: Role
			}
		},
		beforeMount(){
			this.getMenensajes()
		},
		mounted() {
			this.$events.on('cerrar', () => this.closeModal());
		},
		beforeDestroy() {
			this.$events.$off('cerrar')
    	},
		components: {
			FormPresupuesto, EditPresupuesto
		},
		methods: {
			getMenensajes(){
	            this.$http.get('api/mensaje/'+this.$route.params.mensajeId )
	                .then(response => {
	                	this.presupuesto = response.data.presupuesto;
	                	this.mensajes = response.data.mensajes;
	                	this.publicacacionesSugeridas = response.data.publicacacionesSugeridas;
						this.listPath= [
							{route: '/', name: 'Inicio'}, 
							{route: '/mensajes', name: 'Mensajes'}];
	                	this.listPath.push({route: 'mensaje/'+this.$route.params.mensajeId, name: this.presupuesto.publicacion.titulo });
						this.$events.fire('changePath', this.listPath, this.presupuesto.publicacion.titulo );
	                }, response => {
	                    if(response.status === 404){
	                        router.push('/404');
	                    }
	                    if(response.status === 500){
	                        router.push('/500');
	                    }
	                    this.$toast.error({
	                        title:'¡Error!',
	                        message:'No se ha podido cargar los mensajes.'
	                    });
	                })
			},
			getPublicacion: function(accion){

	            this.$http.get('api/publicacion/'+this.presupuesto.publicacion.id )
	                .then(response => {
	                    this.publicacion = response.data.publicacion
	                }, response => {
	                    this.$toast.error({
	                        title:'¡Error!',
	                        message:'No se ha podido cargar los datos.'
	                    });
	                })
	            if(accion == 'modificar')
	            	this.showModificarPresupuesto = true;
	            else
	            	this.showPresupuesto = true;
			},
			formatTime (value) {
                return (value == null)
                    ? ''
                    : moment(value, 'hh:mm:ss').format('hh:mm')
            },
			formatDate (value) {
                return (value == null)
                    ? ''
                    : moment(value, 'YYYY-MM-DD').format('D MMM YYYY')
            },
            formatDateTime(value){
                return (value == null)
                    ? ''
                    : moment(value, 'YYYY-MM-DD hh:mm:ss').format('D MMM YYYY hh:mm')
            },
			validateBeforeSend(){
            	this.$validator.validateAll().then((result) => {
                	if (result) {
	                	this.validar = false;
	           			this.sendNewMensaje();
	           		} else {
	           			this.validar = true;
	           		}
					return;
                }).catch(() => {
                    
                });
			},
			sendNewMensaje(){
				var ultimo_mensaje_id = (this.mensajes.length - 1 );
				this.$http.post('api/mensaje/', {
						mensaje: this.newMensaje,
						prev_message_id: this.mensajes[ultimo_mensaje_id].id
					}).then(response => {
						this.getMenensajes();
						this.newMensaje = ''
					}, response => {
	                    this.$toast.error({
	                        title:'¡Error!',
	                        message:'No se ha podido cargar los mensajes.'
	                    });
					})
			},
			changeEstadoPresupuesto(estado){
				this.$http.patch('api/reserva/'+this.presupuesto.id+'/estado', {
						estado: estado
					}).then(response => {
						this.presupuesto.estado = estado;
	                    this.$toast.success({
	                        title:'¡Cambios realizados!',
	                        message:'Se ha realizado correctamente la operación'
	                    });					
                	}, response => {
                		if(response.status == 409){
                			this.showConflict = true;
                		}
	                    this.$toast.error({
	                        title:'¡Error!',
	                        message:'No se ha podido realizar correctamente la operación'
	                    });
					})
			},
			closeModal(){
				this.showPresupuesto = false;
				this.showModificarPresupuesto = false;
				this.getMenensajes();
			},
            formatMoney(value){
            	return (value == null)
                    ? ''
            		: accounting.formatMoney(value, "$", 2, ".", ",");
            },
            isAfterNow(value){
            	return moment(value, 'YYYY-MM-DD').isAfter(moment({}));
            }
		},
		computed: {
			getNameFromUser(){
				if(auth.user.profile.roles_id == this.role.USUARIO)
				{
					return this.presupuesto.publicacion.proveedor.nombre;
				} else {
					for(var mensaje of this.mensajes){
						if(mensaje.to_user_id != auth.user.profile.id){
							return mensaje.to_user.usuario.nombre + ' ' +mensaje.to_user.usuario.apellido
						} else{
							return mensaje.from_user.usuario.nombre + ' ' +mensaje.from_user.usuario.apellido
						}
					}
				}
			},
	    	showAplicarDescuento(){
                return this.presupuesto.aplicar_decuento;
	    	}
		}
	}
</script>
<style>
	.direct-chat-text {
	    margin: 5px 40% 0 0px;
	}
	.right .direct-chat-text {
	    margin-right: 0px;
	    margin-left: 40%;
	}
</style>