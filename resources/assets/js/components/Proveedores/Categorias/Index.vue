<template>
	<div class="default-content">
        <div class="content">
            <div class="row">
                <div>
                    <div class="box-header">
                        <div class="col-sm-12">
                            <button class="btn btn-primary btn-sm"
                            @click="showNewCategoria = true">
                            Agregar Categoria
                            </button>
                        </div>
                    </div>
                    <div v-if="showNewCategoria" class="modal" role="dialog" :style="{ display : showNewCategoria  ? 'block' : 'none' }">
                        <div class="modal-dialog">
                        <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" @click="closeModal()">&times;</button>
                                    <h4 class="modal-title">Agregar Categoria</h4>
                                </div>
                                <new-categorias></new-categorias>
                            </div>
                        </div>    
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="box box-primary">
                                <div class="box-header">
                                    <filter-bar-categoria></filter-bar-categoria>
                                </div>
                                <!-- /.box-header -->
                                <div class="box-body table-responsive no-padding">
                                    <vuetable-p
                                        :fields="tableColumns"
                                        tableClass="table table-bordered"
                                        :noDataTemplate="noDataTemplate"
                                        :css="css"
                                        :append-params="moreParams"
                                        ref="vuetableC"
                                        :api-url="url"
                                        pagination-path=""
                                        @vuetable:pagination-data="onPaginationData"
                                        @vuetable:cell-clicked="onCellClicked">

                                            <template slot="actions" slot-scope="props">
                                                <div class="custom-actions">

                                                    <!-- Modificar Categoria -->
                                                    <button class="btn-xs btn-default"
                                                        @click="showModificarCategoria = true, idCategoria = props.rowData.id">
                                                        <i class="glyphicon glyphicon-pencil"></i> Modificar
                                                    </button>
                                                </div>
                                            </template>
                                    </vuetable-p>
                                </div>

                                <div class="box-footer clearfix">

                                    <vuetable-pagination-info-p 
                                        ref="paginationInfo"
                                        :info-template='info'
                                        :no-data-template='noData'>
                                    </vuetable-pagination-info-p>

                                    <vuetable-pagination-p 
                                        ref="pagination"
                                        :css="css.pagination"
                                        @vuetable-pagination:change-page="onChangePage">
                                    </vuetable-pagination-p>

                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- Modal Modificar-->
                    <div v-if="showModificarCategoria" id="modificar" class="modal" role="dialog" :style="{ display : showModificarCategoria  ? 'block' : 'none' }">
                        <div class="modal-dialog">
                          <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" @click="closeModal()">&times;</button>
                                    <h4 class="modal-title">Modificar datos de Proveedor</h4>
                                </div>
                                <edit-categoria :idCategoria="idCategoria"></edit-categoria>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7">
                    <index-subcategorias></index-subcategorias>
                </div>

            </div>
        </div>
	</div>
</template>

<script>
    import VuetableP from 'vuetable-2/src/components/Vuetable';
    import VuetablePaginationP from 'vuetable-2/src/components/VuetablePagination';
    import VuetablePaginationInfoP from 'vuetable-2/src/components/VuetablePaginationInfo';

    import Style from './../../Layouts/Style-css.js';
    import FilterBar from './FilterBarCategoria';
    import route from '../../../routes.js';
    import PathContent from '../../Layouts/Path';
    import NewCategorias from './New';
    import NewSubcategorias from './../Subcategorias/New';
    import EditCategoria from './Edit';

   import IndexSubcategorias from './../Subcategorias/Index.vue';

    Vue.component('filter-bar-categoria', FilterBar);

    export default {
        data(){
            return {
                css: Style,
                noDataTemplate: 'No hay datos para visualizar',
                info: 'Mirando de {from} a {to} de {total} categorias',
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
                         name: '__slot:actions',   // <----
                         title: 'Acciones',
                         titleClass: 'center aligned',
                         dataClass: 'center aligned'
                    },
                ],
                url: '/api/categoria',
                titlePath: 'Categorias',
                showModalObservationCategoria: false,
                listPath : [{route: '/', name: 'Inicio'}, {route: '/categorias', name: 'Categorias'}],
                idCategoria: null,
                showModificarCategoria: false,
                showNewCategoria: false,

                errorsApi: []
            }
        },
        components: {
            VuetableP, VuetablePaginationP, VuetablePaginationInfoP, PathContent, NewCategorias, EditCategoria, IndexSubcategorias, NewSubcategorias
        },
        mounted() {
            this.$events.$on('filter-set', eventData => this.onFilterSet(eventData));
            this.$events.fire('changePath', this.listPath, this.titlePath);
            this.$events.on('cerrar', () => this.closeModal());
            this.$events.on('reloadIndexCategoria', () => Vue.nextTick( () => this.$refs.vuetableC.refresh()) );

        },
        beforeDestroy() {
            this.$events.$off('reloadIndexCategoria');
            this.$events.$off('cerrar');
            this.$events.$off('filter');
        },
        methods: {
            onPaginationData (paginationData) {
                this.$refs.pagination.setPaginationData(paginationData)
                this.$refs.paginationInfo.setPaginationData(paginationData)
            },
            onChangePage (page) {
                this.$refs.vuetableC.changePage(page)
            },
            onCellClicked (data, field, event) {
                this.$refs.vuetableC.toggleDetailRow(data.id)
            },

            //filtros de busqueda
            onFilterSet (filterText) {
                this.moreParams.filter = filterText

                this.$nextTick( () => this.$refs.vuetableC.refresh() )
            },
            onActionModificar(data, index){
                this.showModificarCategoria = true, 
                this.idCategoria=data.id
            },

            closeModal: function(){
                this.showModificarCategoria = false;
                this.showNewCategoria= false;

                this.idCategoria = null;
            },
            clearForm(){
                this.showModificarCategoria= false;
                this.showNewCategoria= false;

            }
        },

    }
</script>