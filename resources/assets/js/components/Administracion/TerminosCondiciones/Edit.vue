<template>
	<div class="default-content">
        <section class="content">
            <div class="row">
        		<div class="col-xs-12">
		        	<div class="box box-primary">
		        		<div class="box-header with-border">
		        			<h3 class="box-title">Nuevos Terminos y Condiciones</h3>
		        		</div>
		        		<div class="box-body">
							<form-term v-if="loaded" :errorsApi="errorsApi" :terminoCondicion="terminoCondicion"  @validoFormTerm="sendEditForm"></form-term>
		        		</div>
		        		<div class="box-footer">
		        			<div style="text-align:center;">
		        				<button class="btn btn-default" @click="goToBack()">
		        					Atras
		        				</button>
		        				<button class="btn btn-primary" @click="validateBeforeSubmit()">
		        					Guardar Cambios
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
	import FormTerm from './Form';
	import route from './../../../routes.js';
	export default {
		data() {
			return {
				terminoCondicion: {},
				errorsApi:[],
				loaded: false,
				listPath : [{route: '/', name: 'Inicio'}, {route: '/terminos-condiciones/edit', name: 'Terminos y Condiciones'}],
			}
		},
		beforeMount(){
			this.getTerm();
		},
		mounted(){
			this.$events.fire('changePath', this.listPath, 'Terminos y Condiciones');
		},
		components: {
			FormTerm
		},
		methods: {
			validateBeforeSubmit: function() {
	            this.$events.fire('validarFormTerm');
	        },
	        getTerm: function(){
	        	this.$http.get('api/terminos-condiciones/')
	        	.then(response =>{
		        		this.terminoCondicion = response.data
		        		this.loaded = true;
		        	}, response => {
	                    if(response.status === 404){
		                    this.$toast.error({
		                        title:'¡Error!',
		                        message:'No se ha cargado correctamente.'
		                    });
		                    route.push('/terminos-condiciones/new');
	                    }
		        	});
	        },
	        sendEditForm(){
	            this.$http.patch(
	                'api/terminos-condiciones/', 
	                {
	                    cuerpo: this.terminoCondicion.cuerpo,
	                })
	                .then(response => {
	                    this.$toast.success({
	                        title:'¡Creada!',
	                        message:'Se modificar correctamente'
	                    });
						route.push('/terminos-condiciones');
	                }, response => {
	                    this.$toast.error({
	                        title:'¡Error!',
	                        message:'No se han podido modificar'
	                    });
	                    if(response.status === 422)
	                    {
	                        this.errorsApi = response.body;
	                    }
	                });
	        },
	        goToBack(){
	        	route.go(-1);
	        }
		}
	}
</script>