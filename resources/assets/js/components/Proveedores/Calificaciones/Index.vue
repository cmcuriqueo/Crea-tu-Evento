<template>
	<div>
		<template v-if="with_box">
			<div class="default-content">
				<section class="content">
					<div class="row">
						<div class="col-md-12" v-if="reservasPendientes.length > 0">
							<div class="box box-primary">
								<div class="box-header with-border">
									<h3 class="box-title">Calificaciones Pendientes</h3>
								</div>
								<div class="box-body no-padding">
									<table class="table table-condensed">
										<tbody>
											<tr>
												<th style="width: 10px">#</th>
												<th>Publicaci&oacute;n</th>
												<th>Proveedor</th>
												<th>Fecha</th>
												<th style="width: 40px">Accion</th>
							                </tr>
											<tr v-for="(pendiente, index) in reservasPendientes">
												<td>{{ index+1 }}.</td>
												<td>{{ pendiente.publicacion.titulo }}</td>
												<td>{{ pendiente.publicacion.proveedor.nombre }}</td>
												<td><span class="badge bg-red">{{ formatDateEvent(pendiente.fecha) }}</span></td>
												<td><button class="btn btn-primary btn-xs" @click="showModal(pendiente)">Calificar</button></td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						    <!-- modal presupuesto -->
						    <div v-if="showCalificar" id="modificar" class="modal" role="dialog" :style="{ display : showCalificar  ? 'block' : 'none' }">
						        <div class="modal-dialog modal-lg">
						            <div class="modal-content">
						                <div class="modal-header">
						                    <button type="button" class="close" @click="closeModal()">&times;</button>
						                    <h4>Calificar a {{reservaSelect.publicacion.titulo}}</h4>
						                </div>
						                <new-form :reserva="reservaSelect" :publicacion="reservaSelect.publicacion"></new-form>
						            </div>
						        </div>
						    </div>
						</div>
						<div class="col-md-12">
							<div class="box box-primary">
								<div class="box-header with-border">
									<h3 class="box-title">Historico de mis calificaciones</h3>
								</div>
								<div class="box-body no-padding">
									<vuetable-calificacion
		                                :fields="tableColumns"
		                                tableClass="table table-bordered"
		                                :noDataTemplate="noDataTemplate"
		                                :css="css"
		                                ref="vuetableCali"
		                                :api-url="url"
		                                pagination-path=""
		                                @vuetable:pagination-data="onPaginationData"
		                                detail-row-component="detail-row-calificaciones"
		                                @vuetable:cell-clicked="onCellClicked">
		                                	<template slot="puntuacion_total" slot-scope="props">
									            <el-rate
													v-model="props.rowData.puntuacion_total"
													disabled
													show-text
													text-color="#ff9900"
													text-template="{value} puntos.">
												</el-rate>
		                                	</template>
		                            </vuetable-calificacion>
								</div>
		                        <div class="box-footer clearfix">

		                            <vuetable-pagination-info-calificacion 
		                                ref="paginationInfo"
		                                :info-template='info'
		                                :no-data-template='noData'>
		                            </vuetable-pagination-info-calificacion>

		                            <vuetable-pagination-calificacion 
		                                ref="pagination"
		                                :css="css.pagination"
		                                @vuetable-pagination:change-page="onChangePage">
		                            </vuetable-pagination-calificacion>

		          					<div class="pull-left" style="margin: 20px 0;">
					          			<button @click="goBack()" class="btn btn-default" style="margin-left: 15px;">
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
		<template v-if="!with_box">

			<vuetable-calificacion
                :fields="tableColumns"
                tableClass="table table-bordered"
                :noDataTemplate="noDataTemplate"
                :css="css"
                ref="vuetableCali"
                :api-url="url"
                pagination-path=""
                @vuetable:pagination-data="onPaginationData"
                detail-row-component="detail-row-calificaciones"
                @vuetable:cell-clicked="onCellClicked">
                	<template slot="puntuacion_total" slot-scope="props">
			            <el-rate
							v-model="props.rowData.puntuacion_total"
							disabled
							show-text
							text-color="#ff9900"
							text-template="{value} puntos.">
						</el-rate>
                	</template>
            </vuetable-calificacion>


            <vuetable-pagination-info-calificacion 
                ref="paginationInfo"
                :info-template='info'
                :no-data-template='noData'>
            </vuetable-pagination-info-calificacion>

            <vuetable-pagination-calificacion 
                ref="pagination"
                :css="css.pagination"
                @vuetable-pagination:change-page="onChangePage">
            </vuetable-pagination-calificacion>

		</template>
	</div>
</template>
<script>
	import route from './../../../routes.js'
	import auth from './../../../auth.js'
	import Role from '../../../config.js'
    import VuetableCalificacion from 'vuetable-2/src/components/Vuetable';
    import VuetablePaginationCalificacion from 'vuetable-2/src/components/VuetablePagination';
    import VuetablePaginationInfoCalificacion from 'vuetable-2/src/components/VuetablePaginationInfo';
    import DetailRow from './DetailRowCalificacion';
    import Style from './../../Layouts/Style-css.js';
    import moment from 'moment';
    import NewForm from './New'

    Vue.component('detail-row-calificaciones', DetailRow);
	export default {
		props: {
			with_box: {
				default: false
			}
		},
		data(){
			return {
				reservasPendientes: [],
				css: Style,
				showCalificar: false,
                tableColumns:  [
                    {
                        name: 'publicacion.titulo',
                        title: 'Publicacion'         
                    },
                    {
                        name: 'publicacion.proveedor.nombre',
                        title: 'Proveedor'         
                    },
                    {
                    	name: '__slot:puntuacion_total',
                    	title: 'Puntuacion'
                    },
                    {
                        name: 'reserva.fecha',
                        title: 'Fecha',
                        titleClass: 'text-center',
                        dataClass: 'text-center',
                        callback: 'formatDateEvent'           
                    }
                ],
                url: '/api/user/me/calificaciones',
                noDataTemplate: 'No hay datos para visualizar',
                info: 'Mirando de {from} a {to} de {total} calificaciones',
                noData:'No hay datos',
                reservaSelect: null,
				listPath: [
					{route: '/', name: 'Inicio'}, 
					{route: '/calificaciones', name: 'Calificaciones'}
				]
			}
		},
		components: {
			VuetableCalificacion, 
			VuetablePaginationCalificacion, 
			VuetablePaginationInfoCalificacion,
			NewForm
		},
		beforeMount(){
			this.getCalificacionesPendientes();
		},
		mounted(){
            this.$events.on('cerrar', () => this.closeModal());
            this.$events.on('reloadIndexCalificacion', () => this.reloadIndex() );
		},
		created() {
			if(this.with_box){
				this.$events.fire('changePath', this.listPath, 'Calificaciones' );
			}
		},
		beforeDestroy() {
			this.$events.$off('reloadIndexCalificacion');
			this.$events.$off('cerrar');
		},
		methods: {
            getCalificacionesPendientes(){
                this.$http.get('api/user/me/calificaciones/pendientes').then(response => {
                    this.reservasPendientes = response.data;
                }, response => {
                    if(response.status === 404){
                        router.push('/404');
                    }
                    if(response.status === 500){
                        router.push('/500');
                    }
                    this.$toast.error({
                        title:'¡Error!',
                        message:'No se ha podido cargar los datos de calificaciones.'
                    });
                });
            },
            onPaginationData (paginationData) {
                this.$refs.pagination.setPaginationData(paginationData)
                this.$refs.paginationInfo.setPaginationData(paginationData)
            },
            onChangePage (page) {
                this.$refs.vuetableCali.changePage(page)
            },
            onCellClicked (data, field, event) {
                this.$refs.vuetableCali.toggleDetailRow(data.id)
            },
			formatDateEvent(value) {
                return (value == null)
                    ? ''
                    : moment(value, 'YYYY-MM-DD').format('D MMM YYYY')
            },
            showModal(reserva){
            	this.reservaSelect = reserva;
            	this.showCalificar = true;
            },
            closeModal(){
            	this.reservaSelect = null;
            	this.showCalificar = false;

            },
            reloadIndex(){
            	this.getCalificacionesPendientes();
            	Vue.nextTick( () => this.$refs.vuetableCali.refresh() );
            },
            goBack: function(){
	            route.go(-1)
	        },
        }
	}
</script>