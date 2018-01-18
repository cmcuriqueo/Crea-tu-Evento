<template>
	<div class="default-content">
		<section class="content">
			<div class="row">
				<div class="col-lg-6 col-xs-12" v-if="loadedUsuarios">
					<div class="info-box bg-yellow">
						<span class="info-box-icon"><i class="ion ion-person-add"></i></span>

						<div class="info-box-content">
							<span class="info-box-text">Usuarios registrados</span>
							<span class="info-box-number">{{usuariosRegistados}}</span>

							<div class="progress">
								<div class="progress-bar" v-bind:style="'width: '+registroAumento+'%'"></div>
							</div>
							<span class="progress-description">
								{{registroAumento}}% Incremento del ultimo mes.
							</span>
						</div>
						<!-- /.info-box-content -->
					</div>
				</div>
				<div class="col-lg-6 col-xs-12" v-if="loadedProveedores">
					<!-- small box -->
					<div class="info-box bg-red">
						<span class="info-box-icon"><i class="ion ion-person-add"></i></span>

						<div class="info-box-content">
							<span class="info-box-text">Proveedores registrados</span>
							<span class="info-box-number">{{proveedoresRegistrados}}</span>
							<div class="progress">
								<div class="progress-bar" v-bind:style="'width: '+proveedoresAumento+'%'"></div>
							</div>
							<span class="progress-description">
								{{proveedoresAumento}}% Incremento del ultimo mes.
							</span>
						</div>
					</div>
				</div>
				<div class="col-lg-3 col-xs-6">
					<!-- small box -->
					<div class="small-box bg-aqua">
						<div class="inner">
							<h3>{{publicacionesCantidad}}</h3>

							<p>Publicaciones</p>
						</div>
						<div class="icon">
							<i class="fa fa-shopping-cart"></i>
						</div>
						<!--<a href="#" class="small-box-footer">
							More info <i class="fa fa-arrow-circle-right"></i>
						</a>-->
					</div>
				</div>
				<div class="col-lg-5 col-xs-6" v-if="loadedReservas">
					<!-- small box -->
					<div class="small-box bg-green">
						<div class="inner">
							<h3>{{reservasAumento}}<sup style="font-size: 20px">%</sup></h3>
							<p>Incremento ultimo mes de reservas</p>
						</div>
						<div class="icon">
							<i class="ion ion-stats-bars"></i>
						</div>
					</div>
				</div>
				<div class="col-lg-4 col-xs-6">
					<!-- small box -->
					<div class="small-box bg-red">
						<div class="inner">
							<h3>{{usuariosOnline}} de {{ofUserOnline}}</h3>
							<p>Usuarios activos</p>
						</div>
						<div class="icon">
							<i class="ion ion-pie-graph"></i>
						</div>
					</div>
				</div>

			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="box box-primary">
						<div class="box-header with-border">
							<h3 class="box-title">Estadisticas de la plataforma</h3>
							<!-- /.box-tools -->
						</div>
						<!-- /.box-header -->
						<div class="box-body no-padding">
							<div class="col-sm-12" v-if="loadedReservas" style="min-height: 250px;">
								<bar-chart style="height: 200px;" :data="dataBarCharts"></bar-chart>
							</div>
							<div class="col-sm-12" v-if="loadedPublicaciones" style="min-height: 250px;">
								<line-chart style="height: 200px;" :data="dataLineCharts"></line-chart>
							</div>
							<div class="col-sm-6" v-if="loadedPublicaciones" style="min-height: 250px;">
								<pie-chart style="height: 200px;" :data="dataPieCharts"></pie-chart>
							</div>
							<div class="col-sm-6" v-if="loadedPublicaciones" style="min-height: 250px;">
								<pie-chart style="height: 200px;" :data="dataPieChartsRubros"></pie-chart>
							</div>
						</div>
					</div>
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
	import BarChart from './BarChart';
	import LineChart from './LineChart';
	import PieChart from './PieChart';
    export default {
        data(){
        	return {
        		usuariosRegistados: 0,
        		proveedoresRegistrados: 0,
        		proveedores: [],
        		publicaciones: [],
        		loadedPublicaciones: false,
        		loadedReservas: false,
        		reservas: [],
        		usuariosOnline: 0,
        		ofUserOnline: 0,
        		loadedProveedores: false,
        		loadedUsuarios: false,
        		usuarios: [],
        		publicacionesCantidad: 0
            }
        },
        beforeMount(){
        	this.$events.fire('changePath', [{route: '/', name: 'Inicio'},{route: '/estadisticas', name: 'Estadisticas'}],
        		 'Estadisticas' );
        	this.getUsuarios();
        	this.getUsuariosActivos();
        	this.getProveedores();
        	this.getPublicaciones();
        	this.getReservas();
        },
        components: { LineChart, BarChart, PieChart },
        methods: {
        	getUsuariosActivos(){
        		this.$http.get('api/usuario/active/count').then(response =>{
        			this.usuariosOnline = response.data.numberOfGuests;
        			this.ofUserOnline = response.data.ofUsers;
        		});
        	},
        	getUsuarios(){
        		this.$http.get('/api/usuario').then(response =>{
        			this.usuariosRegistados = response.data.length;
        			this.usuarios = response.data;
        			this.loadedUsuarios = true;
        		});
        	},
        	getProveedores(){
        		this.$http.get('/api/proveedor').then(response => {
        			this.proveedoresRegistrados = response.data.length;
        			this.proveedores = response.data;
        			this.loadedProveedores = true;
        		});
        	},
        	getPublicaciones(){
        		this.$http.get('api/publicacion').then(response =>{
        			this.publicaciones = response.data.publicaciones;
        			this.publicacionesCantidad =  response.data.publicaciones.length;
        			this.loadedPublicaciones = true;
        		});
        	},
        	getReservas(){
        		this.$http.get('api/reserva').then(response =>{
        			this.reservas = response.data;
        			this.loadedReservas = true;
        		});
        	},
        	getRubro(rubros){
        		var isSalon = false;
        		var isServicio = false;
        		var isProducto = false;

        		for (var rubro of rubros) {
        			if(rubro.salon)
        				isSalon = true;
        			if(rubro.producto)
        				isProducto = true;
        			if(rubro.serivcio)
        				isServicio = true;
        		}
        		if(isSalon)
        			return 'salon'
        		if(isServicio)
        			return 'serivcio'
        		return 'producto'
        	},
        	estaRubro(data, rubroCopm){
        		for (var i = 0; i < data.length; i++) {
        			if(data[i].id == rubroCopm.id){
        				return i;
        			}
        		}
        		return null;
        	}
        },
        computed: {
	        dataLineCharts(){
	        	var label = 'Nuevas Publicaciones';
	        	var backgroundColor = '#00a65a';
	        	var hoy = moment({});
				var labels = [];
				var data = [];
				var meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 
						'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

	        	var mesesCount = [ 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ];
					for (var publicacion of this.publicaciones) {
						if( moment(publicacion.created_at, 'YYYY-MM-DD').year() == hoy.year()){
							switch(moment(publicacion.created_at, 'YYYY-MM-DD').month()+1) {
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
	        dataBarCharts(){
	        	var label = 'Total de Reservas';
	        	var hoy = moment({});
				var labels = [];
				var meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 
						'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

	        	var mesesCount = [];
	        	for (var i = 0; i < 12; i++) {
	        		mesesCount.push({ presupuesto:0, confirmado:0, cancelado:0 })
	        	}
				for (var reserva of this.reservas) {
					if( moment(reserva.fecha, 'YYYY-MM-DD').year() == hoy.year()){
						switch(moment(reserva.fecha, 'YYYY-MM-DD').month()+1) {
						    case 1:
						    	if(reserva.estado == 'presupuesto')
						        	mesesCount[0].presupuesto = mesesCount[0].presupuesto + 1;
						    	if(reserva.estado == 'confirmado')
						        	mesesCount[0].confirmado = mesesCount[0].confirmado + 1;
						    	if(reserva.estado =='cancelado')
						        	mesesCount[0].cancelado = mesesCount[0].cancelado + 1;
						        break;
						    case 2:
						    	if(reserva.estado == 'presupuesto')
						        	mesesCount[1].presupuesto = mesesCount[1].presupuesto + 1;
						    	if(reserva.estado == 'confirmado')
						        	mesesCount[1].confirmado = mesesCount[1].confirmado + 1;
						    	if(reserva.estado =='cancelado')
						        	mesesCount[1].cancelado = mesesCount[1].cancelado + 1;
						        break;
						    case 3:
						    	if(reserva.estado == 'presupuesto')
						        	mesesCount[2].presupuesto = mesesCount[2].presupuesto + 1;
						    	if(reserva.estado == 'confirmado')
						        	mesesCount[2].confirmado = mesesCount[2].confirmado + 1;
						    	if(reserva.estado =='cancelado')
						        	mesesCount[2].cancelado = mesesCount[2].cancelado + 1;
						        break;
						    case 4:
						    	if(reserva.estado == 'presupuesto')
						        	mesesCount[3].presupuesto = mesesCount[3].presupuesto + 1;
						    	if(reserva.estado == 'confirmado')
						        	mesesCount[3].confirmado = mesesCount[3].confirmado + 1;
						    	if(reserva.estado =='cancelado')
						        	mesesCount[3].cancelado = mesesCount[3].cancelado + 1;
						        break;
						    case 5:
						    	if(reserva.estado == 'presupuesto')
						        	mesesCount[4].presupuesto = mesesCount[4].presupuesto + 1;
						    	if(reserva.estado == 'confirmado')
						        	mesesCount[4].confirmado = mesesCount[4].confirmado + 1;
						    	if(reserva.estado =='cancelado')
						        	mesesCount[4].cancelado = mesesCount[4].cancelado + 1;
						        break;
						    case 6:
						    	if(reserva.estado == 'presupuesto')
						        	mesesCount[5].presupuesto = mesesCount[5].presupuesto + 1;
						    	if(reserva.estado == 'confirmado')
						        	mesesCount[5].confirmado = mesesCount[5].confirmado + 1;
						    	if(reserva.estado =='cancelado')
						        	mesesCount[5].cancelado = mesesCount[5].cancelado + 1;
						        break;
						    case 7:
						    	if(reserva.estado == 'presupuesto')
						        	mesesCount[6].presupuesto = mesesCount[6].presupuesto + 1;
						    	if(reserva.estado == 'confirmado')
						        	mesesCount[6].confirmado = mesesCount[6].confirmado + 1;
						    	if(reserva.estado =='cancelado')
						        	mesesCount[6].cancelado = mesesCount[6].cancelado + 1;
						        break;
						    case 8:
						    	if(reserva.estado == 'presupuesto')
						        	mesesCount[7].presupuesto = mesesCount[7].presupuesto + 1;
						    	if(reserva.estado == 'confirmado')
						        	mesesCount[7].confirmado = mesesCount[7].confirmado + 1;
						    	if(reserva.estado =='cancelado')
						        	mesesCount[7].cancelado = mesesCount[7].cancelado + 1;
						        break;
						    case 9:
						    	if(reserva.estado == 'presupuesto')
						        	mesesCount[8].presupuesto = mesesCount[8].presupuesto + 1;
						    	if(reserva.estado == 'confirmado')
						        	mesesCount[8].confirmado = mesesCount[8].confirmado + 1;
						    	if(reserva.estado =='cancelado')
						        	mesesCount[8].cancelado = mesesCount[8].cancelado + 1;
						        break;
						    case 10:
						    	if(reserva.estado == 'presupuesto')
						        	mesesCount[9].presupuesto = mesesCount[9].presupuesto + 1;
						    	if(reserva.estado == 'confirmado')
						        	mesesCount[9].confirmado = mesesCount[9].confirmado + 1;
						    	if(reserva.estado =='cancelado')
						        	mesesCount[9].cancelado = mesesCount[9].cancelado + 1;
						        break;
						    case 11:
						    	if(reserva.estado == 'presupuesto')
						        	mesesCount[10].presupuesto = mesesCount[10].presupuesto + 1;
						    	if(reserva.estado == 'confirmado')
						        	mesesCount[10].confirmado = mesesCount[10].confirmado + 1;
						    	if(reserva.estado =='cancelado')
						        	mesesCount[10].cancelado = mesesCount[10].cancelado + 1;
						        break;
						    case 12:
						    	if(reserva.estado == 'presupuesto')
						        	mesesCount[11].presupuesto = mesesCount[11].presupuesto + 1;
						    	if(reserva.estado == 'confirmado')
						        	mesesCount[11].confirmado = mesesCount[11].confirmado + 1;
						    	if(reserva.estado =='cancelado')
						        	mesesCount[11].cancelado = mesesCount[11].cancelado + 1;
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
							data.push(mesesCount[i].presupuesto);
						if(j == 1)
							data.push(mesesCount[i].confirmado);
						if(j == 2)
							data.push(mesesCount[i].cancelado);
					}
					if(j == 0){
						returnData.push({label:'Presupuestos', backgroundColor:'#00a65a', data:data});
					}
					if(j == 1){
						returnData.push({label:'Reservas', backgroundColor:'#f39c12', data:data});
					}
					if(j == 2){
						returnData.push({label:'Canceladas', backgroundColor:'#dd4b39', data:data});
					}
				}
				return  { labels: labels, datasets: returnData }
	        },
	        dataPieCharts(){
	        	var label = 'Nuevas Publicaciones';
	        	var backgroundColor = ['#f39c12', '#dd4b39', '#00a65a'];
	        	var hoy = moment({});
				var data = [];
				var labels = ['Salones', 'Serivcios', 'Productos'];

	        	var tipoPublicacion = [ 0, 0, 0 ];
					for (var publicacion of this.publicaciones) {
						switch(this.getRubro(publicacion.prestacion.rubros)) {
						    case 'salon':
						        tipoPublicacion[0] = tipoPublicacion[0] + 1;
						        break;
						    case 'serivcio':
						        tipoPublicacion[1] = tipoPublicacion[1] + 1;
						        break;
						    case 'producto':
						        tipoPublicacion[2] = tipoPublicacion[2] + 1;
						        break;
						}
					}
					for (var i = 0; i < tipoPublicacion.length; i++) {
						data.push(tipoPublicacion[i]);
					}
					return  { labels: labels, datasets: [{label:label, backgroundColor:backgroundColor, data:data}] }
	        },
	        dataPieChartsRubros(){
	        	var label = 'Rubros de Publicaciones';
	        	var backgroundColor = ['#f39c12', '#dd4b39', '#00a65a', '#001F3F', '#001F3F', '#001F3F', '#001F3F','#001F3F', '#001F3F', '#001F3F'];
	        	var data = []
	        	var labels = [];
	        	var rubros = [];
	        	for(var publicacion of this.publicaciones){
	        		for(var rubro of publicacion.prestacion.rubros){
	        			var i = this.estaRubro(rubros, rubro);
						if(i != null){
							rubros[i].count = rubros[i].count+1;
						} else {
							rubros.push({id: rubro.id, count: 1, nombre: rubro.nombre});
						}
	        		}
	        	}
	        	rubros.sort(function(a, b){return b.count-a.count});
	        	var i = 1;
	        	var acumulador = 0;
	        	for(var rubro of rubros){
	        		data.push(rubro.count);
	        		labels.push(rubro.nombre);
	        		i++;
	        		if(i >= 10)break;
	        	}
	        	return  { labels: labels, datasets: [{label:label, backgroundColor:backgroundColor, data:data}] }
	        },
			registroAumento(){
				var count = 0;
				var countUltimos = 0;
				for ( var usuaria of this.usuarios) {
					if(moment(usuaria.created_at, 'YYYY-MM-DD').month() == moment({}).month()){
						countUltimos++;
					}
					count++;
				}
				return parseInt((countUltimos*100)/count);
			},
			reservasAumento(){
				var count = 0;
				var countUltimos = 0;
				for ( var reserva of this.reservas) {
					if(moment(reserva.created_at, 'YYYY-MM-DD').month() == moment({}).month()){
						countUltimos++;
					}
					count++;
				}
				return parseInt((countUltimos*100)/count);
			},
			proveedoresAumento(){
				var count = 0;
				var countUltimos = 0;
				for ( var proveedor of this.proveedores) {
					if(moment(proveedor.created_at, 'YYYY-MM-DD').month() == moment({}).month()){
						countUltimos++;
					}
					count++;
				}
				return parseInt((countUltimos*100)/count);
			}
	    }
    }
</script>