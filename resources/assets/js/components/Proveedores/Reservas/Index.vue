<template>
	<div class="row">
		<template v-if="!with_box">
			<vue-event-calendar v-if="loaded"
				:events="dataEvents">				
		      <template slot-scope="props">
		        <div v-for="(event, index) in props.showEvents" class="event-item">

		          	<h3 class="title"> 
		          		<router-link :to="/publicacion/ + event.publicacion.id">
		          			{{event.publicacion.titulo}}
						</router-link>
					</h3>
			        <p v-if="event.hora_inicio != null">Desde {{ formatTime(event.hora_inicio) }}hs hasta {{ formatTime(event.hora_finalizacion) }} hs</p>
			        <p>Total: {{ formatMoney(event.precio_total) }}</p>
			        <p v-if="auth.user.profile.roles_id == role.PROVEEDOR"> 
			        	Reservado por: {{event.user.usuario.nombre}} {{event.user.usuario.apellido}}
			        </p>
			        <p v-else>
		          		Proveedor: {{event.publicacion.proveedor.nombre}}
		            </p>
		            <el-collapse v-model="activeNames">
						<el-collapse-item title="Detalles" :name="event.id" @change="handleChange">

							<div v-if="event.rubros.length > 0">
								<h5><label>Servicios y/o Productos</label></h5>
								<div v-for="rubro in event.rubros" v-if="rubro.servicio || rubro.producto">
									<li>{{rubro.nombre}}</li>
								</div>
							</div>
							<div v-if="event.articulos.length > 0">
								<hr>
								<h5><label>Articulos</label></h5>
								<div v-for="articulo in event.articulos">
									<li>{{articulo.nombre}} <template v-if="articulo.pivot.cantidad > 0"> x {{articulo.pivot.cantidad}}</template></li>
								</div>
							</div>
						</el-collapse-item>
					</el-collapse>
		        </div>
		      </template>
			</vue-event-calendar>
			<div class="col-md-12" v-if="!loaded">
			    <div class="text-center">
			        No se econtraron eventos!.
			    </div>
			</div>
		</template>
		<template v-if="with_box">
			<div class="default-content">
				<section class="content">
					<div class="row">
						<div class="col-md-12">
							<div class="box box-primary">
								<div class="box-header with-border">
									<h3 class="box-title">Reservas</h3>
								</div>
								<div class="box-body">
									<vue-event-calendar v-if="loaded" 
										:events="dataEvents">				
								      <template slot-scope="props">
								        <div v-for="(event, index) in props.showEvents" class="event-item">

								          	<h3 class="title"> 
								          		<router-link :to="/publicacion/ + event.publicacion.id">
								          			{{event.publicacion.titulo}}
												</router-link>
											</h3>
									        <p v-if="event.hora_inicio != null">Desde {{ formatTime(event.hora_inicio) }}hs hasta {{ formatTime(event.hora_finalizacion) }} hs</p>
									        <p>Total: {{ formatMoney(event.precio_total) }}</p>
									        <p v-if="auth.user.profile.roles_id == role.PROVEEDOR"> 
									        	Reservado por: {{event.user.usuario.nombre}} {{event.user.usuario.apellido}}
									        </p>
									        <p v-else>
								          		Proveedor: {{event.publicacion.proveedor.nombre}}
								            </p>
								            <el-collapse v-model="activeNames" @change="handleChange">
												<el-collapse-item title="Detalles" name="1">

													<div v-if="event.rubros.length > 0">
														<h5><label>Servicios y/o Productos</label></h5>
														<div v-for="rubro in event.rubros" v-if="rubro.servicio || rubro.producto">
															<li>{{rubro.nombre}}</li>
														</div>
													</div>
													<div v-if="event.articulos.length > 0">
														<hr>
														<h5><label>Articulos</label></h5>
														<div v-for="articulo in event.articulos">
															<li>{{articulo.nombre}} <template v-if="articulo.pivot.cantidad > 0"> x {{articulo.pivot.cantidad}}</template></li>
														</div>
													</div>
												</el-collapse-item>
											</el-collapse>
								        </div>
								      </template>
									</vue-event-calendar>
									<div class="col-md-12" v-if="!loaded">
									    <div class="text-center">
									        No se econtraron eventos!.
									    </div>
									</div>
								</div>
								<div class="box-footer">
									<div style="text-align:center;">
										<button @click="goBack()" class="btn btn-default">
											<i class="glyphicon glyphicon-chevron-left"></i> Atras
										</button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</section>
			</div>
		</template>

	</div>
</template>
<script>
	import moment from 'moment';
	import accounting from 'accounting-js';
	import auth from './../../../auth.js';
	import role from '../../../config.js';
	import route from './../../../routes.js';

	export default {
		props: {
			publicacionId:{
				dafault: null
			},
			with_box:{
				default: false
			}
		},
		data(){
		    return {
				dataEvents: [],
				activeNames: [],
				auth: auth,
				role: role,
				loaded: false,
				listPath: [
					{route: '/', name: 'Inicio'}, 
					{route: '/reservas', name: 'Reservas'}
				]
		    }
		},
		beforeMount(){
			this.getReservas();
		},
		created() {
			if(this.with_box){
				this.$events.fire('changePath', this.listPath, 'Reservas' );
			}
		},
		methods: {
			getReservas() {
				this.loaded = false;
				var url = '';
				this.dataEvents = [];
				if(this.publicacionId != null)
					url = 'api/publicacion/'+this.publicacionId+'/reserva';
				else if( auth.user.profile.roles_id == role.USUARIO)
					url = 'api/user/'+this.auth.user.profile.id+'/reserva';
				else
					url = 'api/proveedor/me/reserva';

				this.$http.get(url).then(response => {
					var data = response.data;
					for(var reserva of data){
						this.dataEvents.push({
							id: reserva.id,
							publicacion_id: reserva.publicacion_id,
							user_id: reserva.user_id,
							domicilio_id: reserva.domicilio_id,
							date: moment(reserva.fecha, 'YYYY-MM-DD').format('YYYY/MM/DD'),
							title: moment(reserva.fecha, 'YYYY-MM-DD').format('DD MMM YYYY'),
							hora_inicio: reserva.hora_inicio,
							hora_finalizacion: reserva.hora_finalizacion,
							estado: reserva.estado,
							precio_total: reserva.precio_total,
							created_at: reserva.created_at,
							updated_at: reserva.updated_at,
							publicacion: reserva.publicacion,
							user: reserva.user,
							rubros: reserva.rubros,
							articulos: reserva.articulos
						});
					}
					this.loaded = true;
				}, response => {
                    if(response.status === 404){
                        router.push('/404');
                    }
                    if(response.status === 500){
                        router.push('/500');
                    }
				});
			},
			handleChange(val){},
			formatTime (value) {
                return (value == null)
                    ? ''
                    : moment(value, 'hh:mm:ss').format('hh:mm')
            },
            formatMoney(value){
            	return (value == null)
                    ? ''
            		: accounting.formatMoney(value, "$", 2, ".", ",");
            },
			goBack: function(){
	            route.go(-1)
	        }
		}
	}
</script>