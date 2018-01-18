<template>
	<div class="default-content">
        <div class="content"> 
            <div class="box-header">
                <div class="col-sm-2">
                    <router-link to='/proveedor/new'><button type="button" class="btn btn-block btn-primary btn-sm">Nuevo Proveedor</button></router-link>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <div class="box box-primary">
                        <div class="box-header">
                            <filter-bar-proveedor></filter-bar-proveedor>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body table-responsive no-padding">
                            <vuetable-p
                                :fields="tableColumns"
                                tableClass="table table-bordered"
                                :noDataTemplate="noDataTemplate"
                                :css="css"
                                :append-params="moreParams"
                                ref="vuetable"
                                :api-url="url"
                                pagination-path=""
                                @vuetable:pagination-data="onPaginationData"
                                detail-row-component="detail-row-proveedor"
                                @vuetable:cell-clicked="onCellClicked">

                                    <template slot="actions" slot-scope="props">
                                        <div class="custom-actions" v-if="auth.user.authenticated">

                                            <!-- Ver perfil -->
                                            <button class="btn-xs btn-default"
                                                @click="onActionShow(props.rowData, props.rowIndex)">
                                                <i class="glyphicon glyphicon-search"></i> Ver
                                            </button>

                                            <!-- Modificar Proveedor -->
                                            <button 
                                                v-if="props.rowData.estado === 'Tramite'"
                                                class="btn-xs btn-default"
                                                @click="showModificar = true, idProveedor = props.rowData.id">
                                                <i class="glyphicon glyphicon-pencil"></i> Modificar
                                            </button>

                                            <!-- Aprobar a proveedor -->
                                            <button 
                                                v-if="(props.rowData.estado === 'Tramite' ||
                                                        props.rowData.estado === 'Baja') &&
                                                        (role.ADMINISTRADOR == auth.user.profile.roles_id ||
                                                        role.SUPERVISOR == auth.user.profile.roles_id)" 
                                                        class="btn-xs btn-default"
                                                @click="onActionEstado('Aprobado', props.rowData)">
                                                <i class="fa fa-check"></i> Aprobar
                                            </button>

                                            <!-- Rechazar a proveedor -->
                                            <button 
                                                v-if="props.rowData.estado === 'Tramite' &&
                                                    (role.ADMINISTRADOR == auth.user.profile.roles_id ||
                                                    role.SUPERVISOR == auth.user.profile.roles_id)" 
                                                class="btn-xs btn-default"
                                                @click="showModalObservation = true, action = 'Rechazado', dataUser = props.rowData">
                                                <i class="fa fa-close"></i> Rechazar
                                            </button>

                                            <!-- Baja a proveedor  -->
                                            <button 
                                                v-if="props.rowData.estado === 'Aprobado' &&
                                                    (role.ADMINISTRADOR == auth.user.profile.roles_id ||
                                                    role.SUPERVISOR == auth.user.profile.roles_id)" 
                                                class="btn-xs btn-default"
                                                @click="showModalObservation = true, action = 'Baja', dataUser = props.rowData">
                                                <i class="fa fa-close"></i> Baja
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
            <template>
                <!-- Modal cambiar observaciones (baja/rechazo)-->
                <div class="modal" role="dialog" :style="{ display : showModalObservation  ? 'block' : 'none' }">
                    <div class="modal-dialog">

                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" @click="clearForm()">&times;</button>
                                <h4 class="modal-title">Agregue el motivo de la accion</h4>
                            </div>
                            <div class="modal-body">

                                <div class="box-body">
                                    <div class="col-sm-12">
                                        <div class="col-sm-12">
                                            <div class="callout callout-success" v-if="action == 'Baja'">
                                                <h4>¡Motivo de la acción!</h4>

                                                <p>Se le notificará al proveedor y a los adquirientes de sus productos y servicios 
                                                    el motivo por cual se le ha dado de baja.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <form role="form">
                                        <div class="col-sm-12">
                                            <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('observaciones')&&validar}">
                                                <div class="col-sm-12">
                                                    <textarea  
                                                        v-model="observaciones" 
                                                        placeholder="Agregue un observación"
                                                        name="observaciones" 
                                                        v-validate="'required|min:15'" 
                                                        class="form-control"></textarea>

                                                    <!-- validacion vee-validation -->
                                                    <span v-show="errors.has('observaciones')&& validar" class="help-block">{{ errors.first('observaciones') }}</span>

                                                    <!-- validacion api -->
                                                    <div class="text-red" v-if="errorsApi.observaciones">
                                                        <div v-for="msj in errorsApi.observaciones">
                                                            <p>{{ msj }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                            </div>
                            <div class="modal-footer">
                                <div class="col-sm-12">
                                    <div class="pull-right">
                                        <button
                                            @click="validateBeforeSubmit(action, dataUser)" 
                                            type="button" class="btn btn-danger">
                                         Guargar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
            <!-- Modal Modificar-->
            <div v-if="showModificar" id="modificar" class="modal" role="dialog" :style="{ display : showModificar  ? 'block' : 'none' }">
                <div class="modal-dialog">
                  <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" @click="closeModal()">&times;</button>
                            <h4 class="modal-title">Modificar datos de Proveedor</h4>
                        </div>
                        <edit-proveedor :idProveedor="idProveedor"></edit-proveedor>
                    </div>
                </div>
            </div>
        </div>
	</div>
</template>

<script>
    import VuetableP from 'vuetable-2/src/components/Vuetable';
    import VuetablePaginationP from 'vuetable-2/src/components/VuetablePagination';
    import VuetablePaginationInfoP from 'vuetable-2/src/components/VuetablePaginationInfo';
    import Style from './../Layouts/Style-css.js';
    import FilterBar from './FilterBarProveedor';
    import DetailRowProveedor from './DetailRowProveedor';
    import tableColumns from './colums.js';
    import route from '../../routes.js';
    import PathContent from '../Layouts/Path';
    import NewProveedor from './New';
    import EditProveedor from './Edit';
    import Role from '../../config.js';
    import auth from '../../auth.js';
    import moment from 'moment';
    Vue.component('filter-bar-proveedor', FilterBar);
    Vue.component('detail-row-proveedor', DetailRowProveedor);

    export default {
        data(){
            return {
                css: Style,
                noDataTemplate: 'No hay datos para visualizar',
                info: 'Mirando de {from} a {to} de {total} proveedores',
                noData:'No hay datos',
                moreParams: {},
                tableColumns: tableColumns,
                url: '/api/proveedor',
                titlePath: 'Proveedores',
                observaciones: null,
                showModalObservation: false,
                listPath : [{route: '/', name: 'Inicio'}, {route: '/proveedores', name: 'Proveedores'}],
                action: '',
                idProveedor: null,
                showModificar: false,
                dataUser: null,
                validar: false,
                errorsApi: [],
                role: Role,
                auth: auth
            }
        },
        components: {
            VuetableP, VuetablePaginationP, VuetablePaginationInfoP, PathContent, NewProveedor, EditProveedor
        },
        mounted() {
            this.$events.$on('filter-set', eventData => this.onFilterSet(eventData));

            this.$events.fire('changePath', this.listPath, this.titlePath);
            this.$events.on('cerrar', () => this.closeModal());
            this.$events.on('reloadIndexProveedor', () => Vue.nextTick( () => this.$refs.vuetable.refresh()) );

        },
        beforeDestroy() {
            this.$events.$off('cerrar')
            this.$events.$off('filter-set')
            this.$events.$off('reloadIndexProveedor')
        },
        methods: {
            formatData: function(value){

                return (value == null)
                    ? ''
                    : moment(value, 'YYYY-MM-DD').format('D MMM YYYY');
            },
            onPaginationData (paginationData) {
                this.$refs.pagination.setPaginationData(paginationData)
                this.$refs.paginationInfo.setPaginationData(paginationData)
            },
            onChangePage (page) {
                this.$refs.vuetable.changePage(page)
            },
            onCellClicked (data, field, event) {
                this.$refs.vuetable.toggleDetailRow(data.id)
            },
            //filtros de busqueda
            onFilterSet (filterText) {
                this.moreParams.filter = filterText

               this.$nextTick( () => this.$refs.vuetable.refresh() )
            },

            onActionShow(data, index){
                this.listPath.push({route: '/usuario/'+data.user_id+'/perfil', name: 'Perfil'})
                this.$events.fire('changePath', this.listPath, 'Perfil');

                route.push("/usuario/"+data.user_id+"/perfil")
            },

            onActionModificar(data, index){
                this.showModificar = true, 
                this.idProveedor=data.id
            },

            closeModal: function(){
                this.showModificar = false;
                this.idProveedor = null;
            },

            onActionEstado(action, data){
                this.$http.patch(
                    'api/proveedor/'+data.user_id+'/estado',
                    {
                        action: action,
                        observaciones: this.observaciones
                    }
                ).then(response => {
                    this.$toast.success({
                        title:'¡Acción realizada!',
                        message:'La acción se ha realizado correctamente.'
                    });
                    data.estado = action;
                }, response => {
                    this.$toast.error({
                        title:'¡Error!',
                        message:'No se han podido realizar los cambios. :('
                    });
                })
            },
            clearForm(){
                this.observaciones = '';
                this.showModalObservation = false;
                this.validar = false;
            },
            validateBeforeSubmit: function(action, data) {
                this.$validator.validateAll().then((result) => {
                    if (result) {
                        this.onActionEstado(action, data); 
                        this.clearForm();
                    } else {
                        this.validar = true;
                    }
                    return;
                });
            }
        }
    }
</script>