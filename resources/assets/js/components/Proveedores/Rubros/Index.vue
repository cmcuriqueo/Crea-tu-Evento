<template>
    <div class="default-content">
        <div class="content">
            <div class="box-header">
                <div class="col-sm-2">
                    <button class="btn btn-primary btn-sm"
                    @click="showNewRubro = true">
                    Crear Rubro
                    </button>
                </div>
            </div>

            <div v-if="showNewRubro" class="modal" role="dialog" :style="{ display : showNewRubro  ? 'block' : 'none' }">
                <div class="modal-dialog">
                <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" @click="closeModal()">&times;</button>
                            <h4 class="modal-title">Crear Rubro</h4>
                        </div>
                        <new-rubro></new-rubro>
                    </div>
                </div>    
            </div>

            <div class="row">
                <div class="col-lg-12">
    	            <div class="box box-primary">
    	                <div class="box-header">
    	                    <filter-bar-rubro></filter-bar-rubro>
    	                </div>
    	                <!-- /.box-header -->
    	                <div class="box-body table-responsive no-padding">
    	                    <vuetable-r
    	                        :fields="tableColumns"
    	                        tableClass="table table-bordered"
    	                        :noDataTemplate="noDataTemplate"
    	                        :css="css"
    	                        :append-params="moreParams"
    	                        ref="vuetableR"
    	                        :api-url="url"
    	                        pagination-path=""
    	                        @vuetable:pagination-data="onPaginationData"
                                detail-row-component="detail-row-rubro"
    	                        @vuetable:cell-clicked="onCellClicked">
    	                            <template slot="actions" slot-scope="props">
    	                                <div class="custom-actions">

    	                                    <!-- Modificar Categoria -->
    	                                    <button class="btn-xs btn-default"
    	                                        @click="showModificarRubro = true, idRubro = props.rowData.id">
    	                                        <i class="glyphicon glyphicon-pencil"></i> Modificar
    	                                    </button>
    	                                </div>
    	                            </template>
    	                    </vuetable-r>
    	                </div>

    	                <div class="box-footer clearfix">

    	                    <vuetable-pagination-info-r
    	                        ref="paginationInfo"
    	                        :info-template='info'
    	                        :no-data-template='noData'>
    	                    </vuetable-pagination-info-r>

    	                    <vuetable-pagination-r 
    	                        ref="pagination"
    	                        :css="css.pagination"
    	                        @vuetable-pagination:change-page="onChangePage">
    	                    </vuetable-pagination-r>

    	                </div>

    	            </div>
    	        </div>
    	    </div>
    	    <!-- Modal Modificar-->
    	    <div v-if="showModificarRubro" id="modificar" class="modal" role="dialog" :style="{ display : showModificarRubro  ? 'block' : 'none' }">
    	        <div class="modal-dialog">
    	          <!-- Modal content-->
    	            <div class="modal-content">
    	                <div class="modal-header">
    	                    <button type="button" class="close" @click="closeModal()">&times;</button>
    	                    <h4 class="modal-title">Modificar Rubro</h4>
    	                </div>
    	                <edit-rubro :idRubro="idRubro"></edit-rubro>
    	            </div>
    	        </div>
    	    </div>
        </div>
	</div>
</template>

<script>
	import VuetableR from 'vuetable-2/src/components/Vuetable';
    import VuetablePaginationR from 'vuetable-2/src/components/VuetablePagination';
    import VuetablePaginationInfoR from 'vuetable-2/src/components/VuetablePaginationInfo';

    import Style from './../../Layouts/Style-css.js';
    import FilterBar from './FilterBarRubro';
    import NewRubro from './New';
    import EditRubro from './Edit';
    import DetailRowR from './DetailRowRubro';

    Vue.component('detail-row-rubro', DetailRowR);
    Vue.component('filter-bar-rubro', FilterBar);
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
                        title: 'Nombre',
                        titleClass: 'text-center',
                        dataClass: 'text-center'     
                    },
                    {
                        name: 'producto',
                        title: 'Producto',
                        titleClass: 'text-center',
                        dataClass: 'text-center',
                        callback: 'checked'           
                    },
                    {
                        name: 'salon',
                        title: 'Salón',
                        titleClass: 'text-center',
                        dataClass: 'text-center',
                        callback: 'checked'         
                    },
                    {
                        name: 'servicio',
                        title: 'Servicio',
                        titleClass: 'text-center',
                        dataClass: 'text-center',
                        callback: 'checked'           
                    },
                    {
                         name: '__slot:actions',   // <----
                         title: 'Acciones',
                         titleClass: 'center aligned',
                         dataClass: 'center aligned'
                    },
                ],
                url: '/api/rubro',
                idRubro: null,
                showModificarRubro: false,
                showNewRubro: false,
                titlePath: 'Rubros',
                listPath : [{route: '/', name: 'Inicio'}, {route: '/rubros', name: 'Rubros'}],
            }
    	},
        components: {
            VuetableR, VuetablePaginationR, VuetablePaginationInfoR, NewRubro, EditRubro
        },
        mounted() {
            this.$events.$on('filter-setRubro', eventData => this.onFilterSet(eventData));
            this.$events.on('cerrar', () => this.closeModal());
            this.$events.on('reloadIndexRubro', () => Vue.nextTick( () => this.$refs.vuetableR.refresh()) );
            this.$events.fire('changePath', this.listPath, this.titlePath);
        },
        beforeDestroy() {
            this.$events.$off('reloadIndexRubro');
            this.$events.$off('cerrar');
            this.$events.$off('filter-setRubro');
        },
        methods: {
            onPaginationData (paginationData) {
                this.$refs.pagination.setPaginationData(paginationData)
                this.$refs.paginationInfo.setPaginationData(paginationData)
            },
            onChangePage (page) {
                this.$refs.vuetableR.changePage(page)
            },
            onCellClicked (data, field, event) {
                this.$refs.vuetableR.toggleDetailRow(data.id)
            },
            //filtros de busqueda
            onFilterSet (filterText) {
                this.moreParams.filter = filterText;
                this.$nextTick( () => this.$refs.vuetableR.refresh() )
            },
            onActionModificar(data, index){
                this.showModificarRubro = true, 
                this.idRubro=data.id
            },
            closeModal: function(){
                this.showModificarRubro = false;
                this.showNewRubro= false;
                this.idRubro = null;
            },
            clearForm(){
                this.showModificarRubro= false;
                this.showNewRubro= false;
            },
            checked(value){
                      return value === 1
                ? '<i class="fa fa-check"></i>'
                : '<i class="fa fa-close"></i>'
            }
        },
    }

</script>