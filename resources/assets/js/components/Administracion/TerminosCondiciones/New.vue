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
							<form-term :errorsApi="errorsApi" :terminoCondicion="terminoCondicion" @validoFormTerm="sendNewForm"></form-term>
		        		</div>
		        		<div class="box-footer">
		        			<div style="text-align:center;">
		        				<button class="btn btn-default" @click="goToBack()">
		        					Atras
		        				</button>
		        				<button class="btn btn-primary" @click="validateBeforeSubmit()">
		        					Guardar
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
				terminoCondicion: {
	                cuerpo: '',
				},
				errorsApi:[],
				listPath : [{route: '/', name: 'Inicio'}, {route: '/terminos-condiciones/new', name: 'Terminos y Condiciones'}],
			}
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
	        sendNewForm(){
	            this.$http.post(
	                'api/terminos-condiciones/', 
	                {
	                    cuerpo: this.terminoCondicion.cuerpo
	                })
	                .then(response => {
	                    this.$toast.success({
	                        title:'¡Creada!',
	                        message:'Se creado correctamente'
	                    });
						route.push('/terminos-condiciones');
	                }, response => {
	                    this.$toast.error({
	                        title:'¡Error!',
	                        message:'No se han podido crear'
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