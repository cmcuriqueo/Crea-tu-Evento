<template>
	<div>
		<div class="col-md-12">
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title">Proveedores Registrados</h3>
				</div>
		        <div class="box-body table-responsive no-padding">
		            <vuetable-pr
		                :fields="tableColumns"
		                tableClass="table table-bordered"
		                :noDataTemplate="noDataTemplate"
		                :css="css"
		                :append-params="moreParams"
		                ref="vuetablePR"
		                :api-url="url"
		                pagination-path=""
		                @vuetable:pagination-data="onPaginationData"
	                    detail-row-component="detail-row-proveedor-pr"
	                    @vuetable:cell-clicked="onCellClicked">
		            </vuetable-pr>
		        </div>

		        <div class="box-footer clearfix">

		            <vuetable-pagination-info-pr
		                ref="paginationInfo"
		                :info-template='info'
		                :no-data-template='noData'>
		            </vuetable-pagination-info-pr>

		            <vuetable-pagination-pr 
		                ref="pagination"
		                :css="css.pagination"
		                @vuetable-pagination:change-page="onChangePage">
		            </vuetable-pagination-pr>
		            
		        </div>
			</div>
		</div>
		<div class="col-md-12" v-if="loadedProveedores">
			<div class="box box-primary">
				<div class="box-body no-padding">
					<line-chart style="height: 200px;" :data="dataLineCharts"></line-chart>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
    import VuetablePr from 'vuetable-2/src/components/Vuetable';
    import VuetablePaginationPr from 'vuetable-2/src/components/VuetablePagination';
    import VuetablePaginationInfoPr from 'vuetable-2/src/components/VuetablePaginationInfo';
    import Style from './../Layouts/Style-css.js';
    import DetailRowProveedor from './DetailRowProveedor';
    Vue.component('detail-row-proveedor-pr', DetailRowProveedor);
	import LineChart from './LineChart';
	import moment from 'moment';

	export default {
		data(){
			return {
				url: '/api/operador/'+this.$route.params.userId +'/actividad/proveedores',
				css: Style,
				proveedores: [],
				loadedProveedores: false,
                noDataTemplate: 'No hay datos para visualizar',
                info: 'Mirando de {from} a {to} de {total} proveedores registrados',
                noData:'No hay datos',
                moreParams: {},
                tableColumns:  [
				    {
				        name: 'nombre',
				        title: 'Nombre',
				        titleClass: 'text-center',
				        dataClass: 'text-center'           
				    },
				    {
				        name: 'email',
				        title: 'Email',
				        titleClass: 'text-center',
				        dataClass: 'text-center'           
				    },
				    {
				        name: 'cuit',
				        title: 'N° Cuit',
				        titleClass: 'text-center',
				        dataClass: 'text-center'           
				    },
				    {
				        name: 'domicilio.localidad.nombre',
				        title: 'Ubicacion',
				        titleClass: 'text-center',
				        dataClass: 'text-center'           
				    },
				    {
				        name: 'created_at',
				        title: 'Fecha',
				        titleClass: 'text-center',
				        dataClass: 'text-center'           
				    },
				    {
				        name: 'estado',
				        title: 'Estado',
				        titleClass: 'text-center',
				        dataClass: 'text-center'           
				    }
                ],
            }
        },
        beforeMount(){
        	this.getProveedores();
        },
        components: {VuetablePr, VuetablePaginationPr, VuetablePaginationInfoPr, LineChart},
        methods: {
        	getProveedores(){
        		this.$http.get('/api/operador/'+this.$route.params.userId +'/actividad/proveedores').then(response =>{
        			this.proveedores = response.data;
        			this.loadedProveedores = true;
        		});
        	},
        	onPaginationData (paginationData) {
                this.$refs.pagination.setPaginationData(paginationData)
                this.$refs.paginationInfo.setPaginationData(paginationData)
            },
            onChangePage (page) {
                this.$refs.vuetablePR.changePage(page)
            },
            onCellClicked (data, field, event) {
                this.$refs.vuetablePR.toggleDetailRow(data.id)
            },
            onCellClicked (data, field, event) {
                this.$refs.vuetablePR.toggleDetailRow(data.id)
            },
        },
        computed: {
	        dataLineCharts(){
	        	var hoy = moment({});
				var labels = [];
				var data = [];
				var meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 
						'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
	        	var mesesCount = [];
	        	for (var i = 0; i < 12; i++) {
	        		mesesCount.push({ agregados:0, aceptados:0, rechazados:0 })
	        	}
				for (var proveedor of this.proveedores) {
					if( moment(proveedor.created_at, 'YYYY-MM-DD').year() == hoy.year()){
						switch(moment(proveedor.created_at, 'YYYY-MM-DD').month()) {
						    case 1:
						        mesesCount[0].agregados = mesesCount[0].agregados + 1;
						        if(proveedor.estado == 'Aprobado')
						        	mesesCount[0].aceptados = mesesCount[0].aceptados + 1;
						        else if(proveedor.estado == 'Rechazado')
									mesesCount[0].rechazados = mesesCount[0].rechazados + 1;
						        break;
						    case 2:
						        mesesCount[1].agregados = mesesCount[1].agregados + 1;
						        if(proveedor.estado == 'Aprobado')
						        	mesesCount[1].aceptados = mesesCount[1].aceptados + 1;
						        else if(proveedor.estado == 'Rechazado')
									mesesCount[1].rechazados = mesesCount[1].rechazados + 1;
						        break;
						    case 3:
						        mesesCount[2].agregados = mesesCount[2].agregados + 1;
						        if(proveedor.estado == 'Aprobado')
						        	mesesCount[2].aceptados = mesesCount[2].aceptados + 1;
						        else if(proveedor.estado == 'Rechazado')
									mesesCount[2].rechazados = mesesCount[2].rechazados + 1;
						        break;
						    case 4:
						        mesesCount[3].agregados = mesesCount[3].agregados + 1;
						        if(proveedor.estado == 'Aprobado')
						        	mesesCount[3].aceptados = mesesCount[3].aceptados + 1;
						        else if(proveedor.estado == 'Rechazado')
									mesesCount[3].rechazados = mesesCount[3].rechazados + 1;
						        break;
						    case 5:
						        mesesCount[4].agregados = mesesCount[4].agregados + 1;
						        if(proveedor.estado == 'Aprobado')
						        	mesesCount[4].aceptados = mesesCount[4].aceptados + 1;
						        else if(proveedor.estado == 'Rechazado')
									mesesCount[4].rechazados = mesesCount[4].rechazados + 1;
						        break;
						    case 6:
						        mesesCount[5].agregados = mesesCount[5].agregados + 1;
						        if(proveedor.estado == 'Aprobado')
						        	mesesCount[5].aceptados = mesesCount[5].aceptados + 1;
						        else if(proveedor.estado == 'Rechazado')
									mesesCount[5].rechazados = mesesCount[5].rechazados + 1;
						        break;
						    case 7:
						        mesesCount[6].agregados = mesesCount[6].agregados + 1;
						        if(proveedor.estado == 'Aprobado')
						        	mesesCount[6].aceptados = mesesCount[6].aceptados + 1;
						        else if(proveedor.estado == 'Rechazado')
									mesesCount[6].rechazados = mesesCount[6].rechazados + 1;
						        break;
						    case 8:
						        mesesCount[7].agregados = mesesCount[7].agregados + 1;
						        if(proveedor.estado == 'Aprobado')
						        	mesesCount[7].aceptados = mesesCount[7].aceptados + 1;
						        else if(proveedor.estado == 'Rechazado')
									mesesCount[7].rechazados = mesesCount[7].rechazados + 1;
						        break;
						    case 9:
						        mesesCount[8].agregados = mesesCount[8].agregados + 1;
						        if(proveedor.estado == 'Aprobado')
						        	mesesCount[8].aceptados = mesesCount[8].aceptados + 1;
						        else if(proveedor.estado == 'Rechazado')
									mesesCount[8].rechazados = mesesCount[8].rechazados + 1;
						        break;
						    case 10:
						        mesesCount[9].agregados = mesesCount[9].agregados + 1;
						        if(proveedor.estado == 'Aprobado')
						        	mesesCount[9].aceptados = mesesCount[9].aceptados + 1;
						        else if(proveedor.estado == 'Rechazado')
									mesesCount[9].rechazados = mesesCount[9].rechazados + 1;
						        break;
						    case 11:
						        mesesCount[10].agregados = mesesCount[10].agregados + 1;
						        if(proveedor.estado == 'Aprobado')
						        	mesesCount[10].aceptados = mesesCount[10].aceptados + 1;
						        else if(proveedor.estado == 'Rechazado')
									mesesCount[10].rechazados = mesesCount[10].rechazados + 1;
						        break;
						    case 12:
						        mesesCount[11].agregados = mesesCount[11].agregados + 1;
						        if(proveedor.estado == 'Aprobado')
						        	mesesCount[11].aceptados = mesesCount[11].aceptados + 1;
						        else if(proveedor.estado == 'Rechazado')
									mesesCount[11].rechazados = mesesCount[11].rechazados + 1;
						        break;
						}
					}
				}
				for (var i = 0; i <= hoy.month(); i++) {
					labels.push(meses[i]);
				}
				var returnData = [];
				for (var j = 0; j < 3; j++) {
					var data = [];
					for (var i = 0; i <= hoy.month(); i++) {
						if(j == 0)
							data.push(mesesCount[i].agregados);
						if(j == 1)
							data.push(mesesCount[i].aceptados);
						if(j == 2)
							data.push(mesesCount[i].rechazados);
					}
					if(j == 0){
						returnData.push({label:'Agregados', backgroundColor:'#00a65a', data:data});
					}
					if(j == 1){
						returnData.push({label:'Aprovados', backgroundColor:'#f39c12', data:data});
					}
					if(j == 2){
						returnData.push({label:'Rechazados', backgroundColor:'#dd4b39', data:data});
					}
				}
				return  { labels: labels, datasets: returnData }
	        }
	    }
    }
</script>