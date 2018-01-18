<template>
	<div>
	    <div>
	        <div v-if="showNewSubcategoria" class="modal" role="dialog" :style="{ display : showNewSubcategoria  ? 'block' : 'none' }">
	            <div class="modal-dialog">
	            <!-- Modal content-->
	                <div class="modal-content">
	                    <div class="modal-header">
	                        <button type="button" class="close" @click="closeModal()">&times;</button>
	                        <h4 class="modal-title">Crear Subcategoria</h4>
	                    </div>
	                    <new-subcategoria></new-subcategoria>
	                </div>
	            </div>    
	        </div>
	    </div>
	    <div class="row">
	        <div class="col-xs-12">
	            <div class="box box-primary">
	                <div class="box-header">
	                    <filter-bar-subcategoria></filter-bar-subcategoria>
	                </div>
	                <!-- /.box-header -->
	                <div class="box-body table-responsive no-padding">
	                    <vuetable-s
	                        :fields="tableColumns"
	                        tableClass="table table-bordered"
	                        :noDataTemplate="noDataTemplate"
	                        :css="css"
	                        :append-params="moreParams"
	                        ref="vuetableS"
	                        :api-url="url"
	                        pagination-path=""
	                        @vuetable:pagination-data="onPaginationData"
	                        @vuetable:cell-clicked="onCellClicked">

	                            <template slot="actions" slot-scope="props">
	                                <div class="custom-actions">

	                                    <!-- Modificar Categoria -->
	                                    <button class="btn-xs btn-default"
	                                        @click="showModificarSubcategoria = true, idSubcategoria = props.rowData.id">
	                                        <i class="glyphicon glyphicon-pencil"></i> Modificar
	                                    </button>
	                                </div>
	                            </template>
	                    </vuetable-s>
	                </div>

	                <div class="box-footer clearfix">

	                    <vuetable-pagination-info-s
	                        ref="paginationInfo"
	                        :info-template='info'
	                        :no-data-template='noData'>
	                    </vuetable-pagination-info-s>

	                    <vuetable-pagination-s 
	                        ref="pagination"
	                        :css="css.pagination"
	                        @vuetable-pagination:change-page="onChangePage">
	                    </vuetable-pagination-s>

	                </div>

	            </div>
	        </div>
	    </div>
	    <!-- Modal Modificar-->
	    <div v-if="showModificarSubcategoria" id="modificar" class="modal" role="dialog" :style="{ display : showModificarSubcategoria  ? 'block' : 'none' }">
	        <div class="modal-dialog">
	          <!-- Modal content-->
	            <div class="modal-content">
	                <div class="modal-header">
	                    <button type="button" class="close" @click="closeModal()">&times;</button>
	                    <h4 class="modal-title">Modificar Subcategoria</h4>
	                </div>
	                <edit-subcategoria :idSubcategoria="idSubcategoria"></edit-subcategoria>
	            </div>
	        </div>
	    </div>
	</div>
</template>
<script>
	import VuetableS from 'vuetable-2/src/components/Vuetable';
    import VuetablePaginationS from 'vuetable-2/src/components/VuetablePagination';
    import VuetablePaginationInfoS from 'vuetable-2/src/components/VuetablePaginationInfo';

    import Style from './../../Layouts/Style-css.js';
    import FilterBar from './FilterBarSubcategoria';
    import NewSubcategoria from './New';
    import EditSubcategoria from './Edit';

    Vue.component('filter-bar-subcategoria', FilterBar);
    export default	{
    	data(){
            return {
                css: Style,
                noDataTemplate: 'No hay datos para visualizar',
                info: 'Mirando de {from} a {to} de {total} subcategorias',
                noData:'No hay datos',
                moreParams: {},
                tableColumns:  [
                    {
                        name: 'nombre',
                        title: 'Nombre ->',
                        titleClass: 'text-center',
                        dataClass: 'text-center'           
                    },
                    {
                        name: 'categoria.nombre',
                        title: 'Categoria',
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
                url: '/api/subcategoria',
                idSubcategoria: null,
                showModificarSubcategoria: false,
                showNewSubcategoria: false,
                validar: false

            }
    	},
        components: {
            VuetableS, VuetablePaginationS, VuetablePaginationInfoS, NewSubcategoria, EditSubcategoria
        },
        mounted() {
            this.$events.$on('filter-setSub', eventData => this.onFilterSet(eventData));
            this.$events.on('cerrar', () => this.closeModal());
            this.$events.on('reloadIndexSubcategoria', () => Vue.nextTick( () => this.$refs.vuetableS.refresh()) );

        },
        methods: {
            onPaginationData (paginationData) {
                this.$refs.pagination.setPaginationData(paginationData)
                this.$refs.paginationInfo.setPaginationData(paginationData)
            },
            onChangePage (page) {
                this.$refs.vuetableS.changePage(page)
            },
            onCellClicked (data, field, event) {
                this.$refs.vuetableS.toggleDetailRow(data.id)
            },
            //filtros de busqueda
            onFilterSet (filterText) {
                this.moreParams.filter = filterText;
                this.$nextTick( () => this.$refs.vuetableS.refresh() )
            },
            onActionModificar(data, index){
                this.showModificarSubcategoria = true, 
                this.idSubcategoria=data.id
            },
            closeModal: function(){
                this.showModificarSubcategoria = false;
                this.showNewSubcategoria= false;
                this.idSubcategoria = null;
            },
            clearForm(){
                this.showModificarSubcategoria= false;
                this.showNewSubcategoria= false;
                this.validar = false;
            }
        },
    }

</script>