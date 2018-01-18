<template >
<div>
     <div class="default-content">
        <div class="content"> 
            <div class="row">
                <div class="col-xs-12">
                    <div class="box-header">
                        <div class="col-sm-2">
                            <button class="btn btn-primary btn-sm"
                            @click="showNewLocalidad = true">
                            Crear Localidad
                            </button>
                        </div>
                    </div>

                    <div v-if="showNewLocalidad" class="modal" role="dialog" :style="{ display : showNewLocalidad  ? 'block' : 'none' }">
                        <div class="modal-dialog">
                        <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" @click="closeModal()">&times;</button>
                                    <h4 class="modal-title">Crear Localidad</h4>
                                </div>
                                <new-localidad></new-localidad>
                            </div>
                        </div>    
                    </div>

                    <div class="box box-primary">

                        <div class="box-header">
                            <filter-bar-l></filter-bar-l>
                        </div>

                        <!-- /.box-header -->
                        <div class="box-body table-responsive no-padding">
                            <vuetable-l
                                :fields="fieldDefs"
                                tableClass="table table-bordered"
                                :noDataTemplate="noDataTemplate"
                                :css="css"
                                :append-params="moreParams"
                                ref="vuetableL"
                                api-url="/api/localidadesAll"
                                pagination-path=""
                                @vuetable:pagination-data="onPaginationData">
                                 <template slot="actions" slot-scope="props">
                                        <div class="custom-actions">

                                          <!-- Modificar Provincia -->
                                            <button class="btn-xs btn-default"
                                                @click="showModificarLocalidad = true, idLocalidad = props.rowData.id">
                                                <i class="glyphicon glyphicon-pencil"></i> Modificar
                                            </button>

                                            <!-- Eliminar Provincia -->
                                             <button class="btn-xs btn-danger"
                                                @click="showDelete = true,  idLocalidad = props.rowData.id">
                                                <i class="fa fa-fw fa-trash-o"></i> Eliminar
                                            </button>
                                        </div>
                                    </template>

                            </vuetable-l>
                        </div>

                        <div class="box-footer clearfix">

                            <vuetable-pagination-info 
                                ref="paginationInfo"
                                :info-template='info'
                                :no-data-template='noData'>
                            </vuetable-pagination-info>

                            <vuetable-pagination 
                                ref="pagination"
                                :css="css.pagination"
                                @vuetable-pagination:change-page="onChangePage">
                            </vuetable-pagination>
                            
                        </div>
                    </div>
                       <!-- Modal Modificar-->
            <div v-if="showModificarLocalidad" id="modificar" class="modal" role="dialog" :style="{ display : showModificarLocalidad  ? 'block' : 'none' }">
                <div class="modal-dialog">
                  <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" @click="closeModal()">&times;</button>
                            <h4 class="modal-title">Modificar Localidad</h4>
                        </div>
                        <edit-localidad :idLocalidad="idLocalidad"></edit-localidad>
                    </div>
                </div>
            </div>
                </div>
            </div>
        </div>
        <template>
            <!-- Modal Eliminar Localidad-->
            <div class="modal" role="dialog" :style="{ display : showDelete  ? 'block' : 'none' }">
                <div class="modal-dialog" style="width: 300px; text-align: center;">

                    <!-- Modal content-->
                    <div class="modal-content" style="width: 350px; text-align: center;">
                        <div class="modal-body">
                            <button type="button" class="close" @click="showDelete = false">&times;</button>
                            <div class="box-body">
                                <p>¿Esta seguro que desea </p>
                                <p>eliminar la localidad?</p>
                            </div>
                             <button type="button" @click="showDelete = false" class="btn btn-default">
                                    Atras
                                </button>
                                <button
                                    @click="deleteP()" 
                                    type="button" class="btn btn-danger">
                                 Eliminar
                                </button>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>
</div>
        
</template>

<script>
    import VuetableL from 'vuetable-2/src/components/Vuetable';
    import VuetablePagination from 'vuetable-2/src/components/VuetablePagination';
    import VuetablePaginationInfo from 'vuetable-2/src/components/VuetablePaginationInfo';
    import Style from './../Layouts/Style-css.js';
    import FilterBar from './FilterBarLocalidad';
    import EditLocalidad from './Edit.vue';
    import NewLocalidad from './New.vue';

    import route from '../../routes.js';
    import auth from '../../auth.js';

    Vue.component('filter-bar-l', FilterBar);
  
    export default {

        data() {
            return {
                titlePath: 'Localidades',
                css: Style,
                noDataTemplate: 'No hay datos para visualizar',
                info: 'Mirando de {from} a {to} de {total} localidades',
                noData:'No hay datos',
                moreParams: {},
                fieldDefs:[
                            {
                                name: 'provincia.nombre',
                                title: 'Provincia',
                                titleClass: 'text-center',
                                dataClass: 'text-center'           
                            },
                            {
                                name: 'nombre',
                                title: 'Localidad',
                                titleClass: 'text-center',
                                dataClass: 'text-center'           
                            },
                            {
                                name: '__slot:actions',   
                                title: 'Acciones',
                                titleClass: 'center aligned text-center',
                                dataClass: 'center aligned text-center'
                            }],
                auth: auth,
                showModificarLocalidad: false,
                showNewLocalidad: false,
                idLocalidad: null,
                showDelete: false,
                listPath : [{route: '/', name: 'Inicio'}, {route: '/localidades', name: 'Localidades'}]
            }
        },
        components: {
            VuetableL, VuetablePagination, VuetablePaginationInfo, NewLocalidad, EditLocalidad
        },
        mounted() {
            this.$events.$on('filter-setLoc', eventData => this.onFilterSet(eventData))
            this.$events.fire('changePath', this.listPath, this.titlePath);
             this.$events.$on('cerrar', () =>this.closeModal());
             this.$events.on('reloadIndexLocalidad', () => Vue.nextTick( () => this.$refs.vuetableL.refresh()) );
        }, 
        methods: {
             closeModal: function(){
                this.showModificarLocalidad = false;
                this.showNewLocalidad = false;
                this.$nextTick( () => this.$refs.vuetableL.refresh() )
            },
            onPaginationData (paginationData) {
                this.$refs.pagination.setPaginationData(paginationData)
                this.$refs.paginationInfo.setPaginationData(paginationData)
            },
            onChangePage (page) {
                this.$refs.vuetableL.changePage(page)
            },
            //filtros de busqueda
            onFilterSet (filterText) {
                this.moreParams.filter = filterText
                this.$nextTick( () => this.$refs.vuetableL.refresh() )
            },
            deleteP(){
                this.$http.post(
                'api/localidad/'+this.idLocalidad, 
                {
                    _method: 'DELETE'
                })
                .then(response => {
                    this.$nextTick( () => this.$refs.vuetableL.refresh() )
                    this.$toast.success({
                        title:'¡Cambios realizados!',
                        message:'Se han realizado correctamente los cambios. :D'
                    });
                }, response => {
                    this.$toast.error({
                        title:'¡Error!',
                        message:'No se ha podido eliminar la localidad por que esta en uso. :('
                    });
                })
                this.showDelete = false
            }
        }
    }
</script>