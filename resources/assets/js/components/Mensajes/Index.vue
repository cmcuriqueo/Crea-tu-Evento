<template>
	<div class="default-content">
		<section class="content">
			<div class="row">
				<div class="col-md-12">
					<div class="box box-primary">
						<div class="box-header with-border">
							<h3 class="box-title">Mensajes</h3>

							<div class="box-tools pull-right"
								 v-if="role.PROVEEDOR == auth.user.profile.roles_id">
								<div class="has-feedback">
									<input type="checkbox" v-model="unanswered" name="unanswered" @change="onFilterSet"> Solicitudes no respondidas
								</div>
							</div>
							<!-- /.box-tools -->
						</div>
						<!-- /.box-header -->
						<div class="box-body no-padding">
							<div class="mailbox-controls">
								<!-- /.btn-group -->
								<button type="button" class="btn btn-default btn-sm" @click.prevent="getMensajes">
									<i class="fa fa-refresh"></i>
								</button>
								<!-- /.pull-right -->
							</div>
							<div class="table-responsive mailbox-messages">
								<table class="table table-hover table-striped">
									<tbody>
										<tr v-for="mensaje in mensajes">
											<td v-if="mensaje.nuevos > 0">
												<el-tooltip class="item" effect="dark" 
													content="Nuevos mensajes." placement="top-start">
													<small class="label pull-right bg-green">{{mensaje.nuevos}}</small> 
												</el-tooltip>
											</td>
											<td v-else></td>
											<td v-if="!mensaje.reserva.publicacion.estado">
												<el-tooltip class="item" effect="dark" 
													content="La publicación se ha eliminado." placement="top-start">
													<i style="color:#dd4b39" class="fa fa-exclamation-triangle" aria-hidden="true"></i>
												</el-tooltip>
											</td>
											<td v-else></td>
											<td class="mailbox-name">
					 							De: <router-link  tag="a" v-bind:to="'/mensaje/'+ mensaje.id">
													{{ getNameFromUser(mensaje) }}
												</router-link>
											</td>
											<td class="mailbox-subject">
												<b>{{ mensaje.reserva.publicacion.titulo}}</b> - 
												<router-link v-bind:to="'/mensaje/'+ mensaje.id">
													<a v-truncate="30">{{mensaje.mensaje}}</a>
												</router-link>
											</td>
											<td class="mailbox-attachment" v-if="mensaje.reserva.estado != 'cancelado'">
												<small class="label pull-left bg-green">
													{{mensaje.reserva.estado}}
												</small>
											</td>
											<td v-else></td>
											<template v-if="role.USUARIO == auth.user.profile.roles_id">
												<td v-if="isAfterNow(mensaje.reserva.fecha) && mensaje.reserva.estado == 'reservado'">
												<td v-if="isAfterNow(mensaje.reserva.fecha) && 
													mensaje.reserva.presupuestado == true && mensaje.reserva.estado == 'presupuesto'">
													<el-tooltip class="item" effect="dark" 
														content="El proveedor a respondido a su solicitud de presupuesto." placement="top-start">
						                            	<small class="label pull-left bg-yellow">Presupuestado</small>
						                            </el-tooltip>
						                   		</td>
												<td v-else-if="isAfterNow(mensaje.reserva.fecha) && 
													mensaje.reserva.presupuestado == false && mensaje.reserva.estado == 'presupuesto'">
													<el-tooltip class="item" effect="dark" 
														content="Esperando respuesta del proveedor." placement="top-start">
						                            	<small class="label pull-left bg-yellow">Sin respuesta</small>
						                            </el-tooltip>
						                   		</td>
						                   		<td v-else-if="!isAfterNow(mensaje.reserva.fecha) && mensaje.reserva.estado != 'confirmado'">
						                   			<el-tooltip class="item" effect="dark" 
						                   				content="La fecha de esta solicitud debe ser actuliazda." placement="top-start">
						                            	<small class="label pull-left bg-red">Actulizar</small>
						                            </el-tooltip>
						                        </td>
						                    </template>
						                    <template v-if="role.PROVEEDOR == auth.user.profile.roles_id">
						                    	<td v-if="isAfterNow(mensaje.reserva.fecha) && mensaje.reserva.estado == 'reservado'">
												</td>
						                    	<td v-if="!isAfterNow(mensaje.reserva.fecha) && mensaje.reserva.estado != 'confirmado'">
						                    		<el-tooltip class="item" effect="dark" 
						                   				content="La fecha elegida para este evento debe de ser actualizada por el adquiriente." placement="top-start">
														<small class="label pull-left bg-red">Actulizar</small>
													</el-tooltip>
												</td>
						                    	<td  v-if="mensaje.reserva.presupuestado == true && isAfterNow(mensaje.reserva.fecha) && mensaje.reserva.estado == 'presupuesto'">
													<el-tooltip class="item" effect="dark" 
														content="Esperando respuesta del adquiriente." placement="top-start">
						                            	<small class="label pull-left bg-yellow">Esperando respuesta</small>
						                            </el-tooltip>
						                    	</td>
						                    	<td v-if="mensaje.reserva.presupuestado == false && isAfterNow(mensaje.reserva.fecha) && mensaje.reserva.estado == 'presupuesto'">
													<el-tooltip class="item" effect="dark" 
														content="Solicitud pendiente de respuesta." placement="top-start">
						                            	<small class="label pull-left bg-yellow">Pendiente</small>
						                            </el-tooltip>
						                    	</td>
						                    </template>
											<td v-if="mensaje.reserva.estado == 'confirmado' && !isAfterNow(mensaje.reserva.fecha)">
												<small class="label pull-left bg-green">Finalizado</small>
											</td>
											<td v-else-if="mensaje.reserva.estado == 'confirmado' && isAfterNow(mensaje.reserva.fecha)">
												
											</td>
											<td v-else-if="mensaje.reserva.estado == 'cancelado'">
												<small class="label pull-left bg-red">Cancelado</small>
											</td>
											<td class="mailbox-date">{{formatData(mensaje.created_at)}}</td>
										</tr>
										<tr v-if="mensajes.length == 0">
											<td style="text-align:center">Sin mensajes...</td>
										</tr>
									</tbody>
								</table>
								<!-- /.table -->
							</div>
							<!-- /.mail-box-messages -->
						</div>
						<!-- /.box-body -->
						<div class="box-footer no-padding">

							<div class="pull-left" style="margin: 20px 0;">
								<button @click="goBack()" class="btn btn-default" style="margin-left: 15px;">
									<i class="glyphicon glyphicon-chevron-left"></i> Atras
								</button>
							</div>

		                    <pagination-mensajes
		                        ref="paginationH"
		                        :current-page="pageOne.current_page"
		                        :items-per-page="pageOne.per_page"
		                        :total-pages="pageOne.last_page"
		                        :total-items="pageOne.total"
		                        @page-changed="pageOneChanged">
		                    </pagination-mensajes> 

						</div>
					</div>
					<!-- /. box -->
				</div>
			</div>
		</section>
	</div>
</template>
<script>
	import auth from './../../auth';
	import route from './../../routes';
	import Role from './../../config';
	import moment from 'moment';
	Vue.component('pagination-mensajes', require('./../Plugins/pagination/pagination-v1.vue'));
    export default {
        data(){
        	return {
            	mensajes: [],
            	filter: '',
            	auth: auth,
            	role: Role,
            	unanswered: false,
            	pageOne: {
                    total:0,
                    per_page:10,
                    current_page: 1,
                    last_page:1,
                    next_page_url:null,
                    prev_page_url:null,
                    from:0,
                    to:0 
                },
				listPath: [
					{route: '/', name: 'Inicio'}, 
					{route: '/mensajes', name: 'Mensajes'}
				]
            }
        },
        beforeMount(){
        	this.getMensajes();
        	this.$events.fire('changePath', this.listPath, 'Mensajes' );
        },
        methods: {
            getMensajes: function(){
            	this.mensajes = [];
            	var api = 'api/mensaje'
            	api = api + '?page='+this.pageOne.current_page+'&per_page='+this.pageOne.per_page+'&unanswered='+ this.unanswered;
            	if(this.filter != '')
            	{
            		api = api + '?filter=' + this.filter;
            	}

                this.$http.get(api)
                .then(response => {
                    this.mensajes = response.data.data;
                    this.setDataPagination(response.data);
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
            pageOneChanged (pageOne) {
                this.pageOne.current_page = pageOne;
                this.getMensajes()
            },
        	onFilterSet(){
        		this.getMensajes();
        	},
			formatDateEvent(value) {
                return (value == null)
                    ? ''
                    : moment(value, 'YYYY-MM-DD').format('D MMM YYYY')
            },
        	formatData: function(value){
                moment.locale('es');
                return (value == null)
                    ? ''
                    : moment(value, 'YYYY-MM-DD hh:mm:ss').fromNow();
            },
            setDataPagination(data){
                    this.pageOne.total = data.total;
                    this.pageOne.per_page = data.per_page;
                    this.pageOne.last_page = data.last_page;
                    this.pageOne.next_page_url = data.next_page_url;
                    this.pageOne.prev_page_url = data.prev_page_url;
                    this.pageOne.from = data.from;
                    this.pageOne.to = data.to;
            },
            isAfterNow(value){
                return moment(value, 'YYYY-MM-DD').isAfter(moment({}));
            },
			getNameFromUser(mensaje){
				if(auth.user.profile.roles_id == this.role.PROVEEDOR)
					if(mensaje.to_user_id != auth.user.profile.id)
					{
						return mensaje.to_user.usuario.nombre + ' ' +mensaje.to_user.usuario.apellido
					} else {
						return mensaje.from_user.usuario.nombre + ' ' +mensaje.from_user.usuario.apellido
					}
				else
				{
					return mensaje.reserva.publicacion.proveedor.nombre;
				}
			},
			goBack: function(){
	            route.go(-1)
	        }
        },
        directives: {
            truncate: {
                bind: function(el, binding) {
                    el.textContent = el.textContent.substring(0, binding.value) + '...';
                }
            }
        }
    }
</script>