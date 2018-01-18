<template>
	<div>
	    <div>
	        <div class="box-header">
	            <div class="col-sm-2">
	                <button class="btn btn-primary btn-sm"
	                @click="showNewCaracteristica = true">
	                Agregar nueva Caracteristica
	                </button>
	            </div>
	        </div>
	        <div v-if="showNewCaracteristica" class="modal" role="dialog" :style="{ display : showNewCaracteristica  ? 'block' : 'none' }">
	            <div class="modal-dialog">
	            <!-- Modal content-->
	                <div class="modal-content">
	                    <div class="modal-header">
	                        <button type="button" class="close" @click="closeModal()">&times;</button>
	                        <h4 class="modal-title">Crear Caracteristica</h4>
	                    </div>
	                    <new-caracteristica></new-caracteristica>
	                </div>
	            </div>    
	        </div>
	    </div>
        <div class="default-content">
            <div class="content"> 
        	    <div class="row">
        	        <div class="col-xs-12">
        	            <div class="box box-primary">
        	                <div class="box-header">
        	                    <filter-bar-caracteristica></filter-bar-caracteristica>
        	                </div>
        	                <!-- /.box-header -->
        	                <div class="box-body table-responsive no-padding">
        	                    <vuetable-ca
        	                        :fields="tableColumns"
        	                        tableClass="table table-bordered"
        	                        :noDataTemplate="noDataTemplate"
        	                        :css="css"
        	                        :append-params="moreParams"
        	                        ref="vuetableCa"
        	                        :api-url="url"
        	                        pagination-path=""
        	                        @vuetable:pagination-data="onPaginationData"
        	                        @vuetable:cell-clicked="onCellClicked">
                                        <template slot="aditional" slot-scope="props">
                                            <div class="custom-aditional">
                                            {{props.rowData.adicional== 1 ? 'Sí': 'No'}}
                                            </div>
                                        </template>
        	                            <template slot="actions" slot-scope="props">
        	                                <div class="custom-actions">

        	                                    <!-- Modificar Categoria -->
        	                                    <button class="btn-xs btn-default"
        	                                        @click="showModificarCaracteristica = true, idCaracteristica = props.rowData.id">
        	                                        <i class="glyphicon glyphicon-pencil"></i> Modificar
        	                                    </button>
        	                                </div>
        	                            </template>
        	                    </vuetable-ca>
        	                </div>

        	                <div class="box-footer clearfix">

        	                    <vuetable-pagination-info-ca
        	                        ref="paginationInfo"
        	                        :info-template='info'
        	                        :no-data-template='noData'>
        	                    </vuetable-pagination-info-ca>

        	                    <vuetable-pagination-ca 
        	                        ref="pagination"
        	                        :css="css.pagination"
        	                        @vuetable-pagination:change-page="onChangePage">
        	                    </vuetable-pagination-ca>

        	                </div>

        	            </div>
        	        </div>
        	    </div>
            </div>
        </div>
	    <!-- Modal Modificar-->
	    <div v-if="showModificarCaracteristica" id="modificar" class="modal" role="dialog" :style="{ display : showModificarCaracteristica  ? 'block' : 'none' }">
	        <div class="modal-dialog">
	          <!-- Modal content-->
	            <div class="modal-content">
	                <div class="modal-header">
	                    <button type="button" class="close" @click="closeModal()">&times;</button>
	                    <h4 class="modal-title">Modificar Caracteristica</h4>
	                </div>
	                <edit-caracteristica :idCaracteristica="idCaracteristica"></edit-caracteristica>
	            </div>
	        </div>
	    </div>
	</div>
</template>
<script>
	import VuetableCa from 'vuetable-2/src/components/Vuetable';
    import VuetablePaginationCa from 'vuetable-2/src/components/VuetablePagination';
    import VuetablePaginationInfoCa from 'vuetable-2/src/components/VuetablePaginationInfo';

    import Style from './../../Layouts/Style-css.js';
    import FilterBar from './FilterBarCaracteristica';
    import NewCaracteristica from './New';
    import EditCaracteristica from './Edit';

    Vue.component('filter-bar-caracteristica', FilterBar);
    export default	{
    	data(){
            return {
                css: Style,
                noDataTemplate: 'No hay datos para visualizar',
                info: 'Mirando de {from} a {to} de {total} caracteristicas',
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
                        name: '__slot:aditional',
                        title: 'Informacion Adicional',
                        titleClass: 'text-center',
                        dataClass: 'text-center'           
                    },
                    {
                         name: '__slot:actions',   // <----
                         title: 'Acciones',
                         titleClass: 'center aligned',
                         dataClass: 'center aligned'
                    },
                ],
                url: '/api/caracteristica',
                listPath : [{route: '/', name: 'Inicio'}, {route: '/caracteristicas', name: 'Caracteristicas'}],
                idCaracteristica: null,
                showModificarCaracteristica: false,
                showNewCaracteristica: false,
                validar: false

            }
    	},
        components: {
            VuetableCa, VuetablePaginationCa, VuetablePaginationInfoCa, NewCaracteristica, EditCaracteristica
        },
        mounted() {
            this.$events.fire('changePath', this.listPath, 'Caracteristicas');
            this.$events.$on('filter-setCaracteristica', eventData => this.onFilterSet(eventData));
            this.$events.on('cerrar', () => this.closeModal());
            this.$events.on('reloadIndexCaracteristica', () => Vue.nextTick( () => this.$refs.vuetableCa.refresh()) );

        },
        beforeDestroy() {
            this.$events.$off('reloadIndexCaracteristica');
            this.$events.$off('cerrar');
            this.$events.$off('filter-setCaracteristica');
        },
        methods: {
            onPaginationData (paginationData) {
                this.$refs.pagination.setPaginationData(paginationData)
                this.$refs.paginationInfo.setPaginationData(paginationData)
            },
            onChangePage (page) {
                this.$refs.vuetableCa.changePage(page)
            },
            onCellClicked (data, field, event) {
                this.$refs.vuetableCa.toggleDetailRow(data.id)
            },
            //filtros de busqueda
            onFilterSet (filterText) {
                this.moreParams.filter = filterText;
                this.$nextTick( () => this.$refs.vuetableCa.refresh() )
            },
            onActionModificar(data, index){
                this.showModificarCaracteristica = true, 
                this.idCaracteristica=data.id
            },
            closeModal: function(){
                this.showModificarCaracteristica = false;
                this.showNewCaracteristica= false;
                this.idCaracteristica = null;
            },
            clearForm(){
                this.showModificarCaracteristica= false;
                this.showNewCaracteristica= false;
                this.validar = false;
            }
        },
    }

</script>