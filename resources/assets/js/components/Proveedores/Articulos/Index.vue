<template>
	<div>
		<section class="content">

			<filter-bar-articulos></filter-bar-articulos>

            <vuetable-articulos
                :fields="tableColumns"
                tableClass="table table-bordered"
                :noDataTemplate="noDataTemplate"
                :css="css"
                :append-params="moreParams"
                ref="vuetableArticulo"
                :api-url="url"
                pagination-path=""
                @vuetable:pagination-data="onPaginationData"
                @vuetable:cell-clicked="onCellClicked">

                    <template slot="actions" slot-scope="props" v-if="auth.user.authenticated && roleProveedor">
                        <div class="custom-actions">
                            <!-- Modificar Articulo -->
                            <button class="btn-xs btn-default"
                                @click="showModificarArticulo = true, articuloId = props.rowData.id">
                                <i class="glyphicon glyphicon-pencil"></i> Modificar
                            </button>
                        </div>
                    </template>

            </vuetable-articulos>

            <vuetable-pagination-info-articulos
                ref="paginationInfo"
                :info-template='info'
                :no-data-template='noData'>
            </vuetable-pagination-info-articulos>

            <vuetable-pagination-articulos
                ref="pagination"
                :css="css.pagination"
                @vuetable-pagination:change-page="onChangePage">
            </vuetable-pagination-articulos>

            <!-- Modal Articulo-->
            <div v-if="showModificarArticulo" id="modificar" class="modal" role="dialog" :style="{ display : showModificarArticulo  ? 'block' : 'none' }">
                <div class="modal-dialog">
                  <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" @click="closeModal()">&times;</button>
                            <h4 class="modal-title">Modificar</h4>
                        </div>
                        <edit-articulo :articulo_id="articuloId"></edit-articulo>
                    </div>
                </div>
            </div>
		</section>
	</div>
</template>
<script>
	import VuetableArticulos from 'vuetable-2/src/components/Vuetable';
    import VuetablePaginationArticulos from 'vuetable-2/src/components/VuetablePagination';
    import VuetablePaginationInfoArticulos from 'vuetable-2/src/components/VuetablePaginationInfo';

    import Style from './../../Layouts/Style-css.js';
    import FilterBar from './FilterBarArticulos';
    import route from '../../../routes.js';
    import auth from '../../../auth.js';
    import role from '../../../config.js';
    import accounting from 'accounting-js';
    import EditArticulo from './Edit';
    Vue.component('filter-bar-articulos', FilterBar);

	export default {
        data(){
            return {
                css: Style,
                noDataTemplate: 'No hay datos para visualizar',
                info: 'Mirando de {from} a {to} de {total} articulos',
                noData:'No hay datos',
                moreParams: {},
                auth: auth,
                tableColumns:  [
                    {
                        name: 'nombre',
                        title: 'Nombre'      
                    },
                    {
                        name: 'precio',
                        title: 'Precio',
                        titleClass: 'text-center',
                        dataClass: 'text-center',
                        callback: 'formatMoney'        
                    },
                    {
                        name: 'stock',
                        title: 'Stock',
                        titleClass: 'text-center',
                        dataClass: 'text-center'           
                    },
                    {
                        name: 'rubro.nombre',
                        title: 'Rubro',
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
                url: '/api/articulo',
                articuloId: null,
                showModificarArticulo: false
            }
        },
        components: {
            VuetableArticulos, VuetablePaginationArticulos, VuetablePaginationInfoArticulos, EditArticulo
        },
        created(){

            if(auth.user.profile.roles_id == role.ADMINISTRADOR || auth.user.profile.roles_id == role.SUPERVISOR)
                this.moreParams = {'user_id' : this.$route.params.userId }

        },
        mounted() {
            this.$events.$on('filter-set', eventData => this.onFilterSet(eventData));
            this.$events.on('cerrar', () => this.closeModal());
            this.$events.on('reloadIndexArticulos', () => Vue.nextTick( () => this.$refs.vuetableC.refresh()) );

        },
        beforeDestroy() {
            this.$events.$off('reloadIndexArticulos');
            this.$events.$off('cerrar');
            this.$events.$off('filter');
        },
        methods: {
            formatMoney(value){
                return (value == null)
                    ? ''
                    : accounting.formatMoney(value, "$", 2, ".", ",");
            },
            onPaginationData (paginationData) {
                this.$refs.pagination.setPaginationData(paginationData)
                this.$refs.paginationInfo.setPaginationData(paginationData)
            },
            onChangePage (page) {
                this.$refs.vuetableArticulo.changePage(page)
            },
            onCellClicked (data, field, event) {
                this.$refs.vuetableArticulo.toggleDetailRow(data.id)
            },

            //filtros de busqueda
            onFilterSet (filterText) {
                this.moreParams = {'filter' : filterText, 'user_id' : this.$route.params.userId }
                this.$nextTick( () => this.$refs.vuetableArticulo.refresh() )
            },
            closeModal(){
                this.articuloId = null;
                this.showModificarArticulo = false;
            }
        },
        computed: {
            roleProveedor(){
                if(auth.user.authenticated && (auth.user.profile.roles_id == role.PROVEEDOR))
                    return true;
                return false;
            },
            roleAdminOrSuper(){
                if(auth.user.authenticated && (auth.user.profile.roles_id == role.ADMINISTRADOR || auth.user.profile.roles_id == role.SUPERVISOR))
                    return true;
                return false;
            }
        }
    }
</script>