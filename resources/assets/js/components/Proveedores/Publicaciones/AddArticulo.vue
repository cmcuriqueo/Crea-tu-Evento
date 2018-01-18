<template>
	<div class="box" v-if="articulos.length>0">
        <div class="box-header with-border">
        	<h3 class="box-title">Articulos</h3>
				<!--<div class="pull-right box-tools">
					<input type="checkbox" name="selecionar"> Todos
             	</div>-->
        </div>
        <!-- /.box-header -->
		<div class="box-body">
			<div v-for="articulo in articulos" class="col-sm-3">
				<input v-if="selected(articulo.id)" type="checkbox" @change="selectArticle(articulo.id)" autocomplete="off" checked>
				<input v-else type="checkbox" @change="selectArticle(articulo.id)" autocomplete="off">
				{{articulo.nombre}}
			</div>
		</div>
    </div>
</template>
<script>
	export default {
		props:{
			rubros: {
				type: Array,
				required: true
			},
			articulosSelect: {
				type: Array,
				required: true
			}
		},
		data(){
			return {
				articulos: []
			}
		},
		beforeMount(){
			this.getArticles();
		},
		mounted(){
			this.$events.listen('reloadIndexArticulo', () => this.getArticles());
		},
		beforeDestroy() {
            this.$events.$off('reloadIndexArticulo')
        },
		methods:{
			//obtiene los articulos de el proveedor
			getArticles(){
				this.$http.get('api/articulo').then(response => {
					this.articulos = [];
					for(var articulo of response.data){
						for(var id of this.rubros){
							if(articulo.rubro_id == id){
								this.articulos.push(articulo);
								break;
							}
						}
					}
					this.$emit('update:articulos', this.articulos);
				}, response => {
					console.log('Error en articulos')
				});
			},
			/*
			 *	guarda el identificador del articulo seleccioando
			*/
			selectArticle(id){
				var esta = false;
				var index = 0;
				for (index; index < this.articulosSelect.length; index++) {
					if(this.articulosSelect[index] == id){
						esta = true;
						break;
					}
				}
				if(!esta){
					this.articulosSelect.push(id);
				} else {
					this.articulosSelect.splice(index, 1);
				}
				this.$emit('update:articulos', this.articulos);
			},
			/*
			 * Setea los articulos seleccionados en el componente vue-select al crearse
			*/
			selected(id){
				var index = 0;
				for (index; index < this.articulosSelect.length; index++) {
					if(this.articulosSelect[index] == id){
						return true;
					}
				}
				return false;
			},
			/*
			 * Elimina los articulos cuando se cambian los rubros 
			 * y deja seleccionados los que corresponda a los rubros selecionados
			*/
			deleteOfArticulosSelect(){
				
				for (var i = 0; i < this.articulosSelect.length; i++) {
					var esta = false;
					for(var articulo of this.articulos){
						console.log(articulo.id)
						console.log(this.articulosSelect[i]);
						if(articulo.id == this.articulosSelect[i] )
						{
							esta = true;
							break;
						}	
					}
					if(!esta){
						this.articulosSelect.splice(i, 1);
					}
				}
			}
		}
	}
</script>