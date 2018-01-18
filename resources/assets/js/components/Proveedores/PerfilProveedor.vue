<template>
    <div>
        <div class="col-md-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Proveedor</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div>
                </div>

                <div class="box-body" style="display: block;">
                    <div class="nav-tabs-custom">
                        
                        <ul class="nav nav-tabs">

                            <li class="active">
                                <a href="#publicaciones" data-toggle="tab">Publicaciones</a>
                            </li>
                            <li>
                                <a href="#rubros" data-toggle="tab">Rubro</a>
                            </li>
                            <li>
                                <a href="#articulos" data-toggle="tab">Productos</a>
                            </li>
                        </ul>
                    
                        <div class="tab-content">
                            
                            <div class="active tab-pane" id="publicaciones">
                                <div v-if="publicaciones.length" class="box-header">
                                    <div class="box-tools pull-right">
                                        <select v-model="selected" @change="changeFilter()">
                                            <option v-for="option in optionsEstados" v-bind:value="option.value">
                                                {{ option.text }}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="box-body">
                                    <index-publicaciones 
                                        :publicaciones="publicaciones" :optionsProveedor="true" :loading="loading">
                                    </index-publicaciones>
                               </div>
                                <div class="box-footer">
                        
                                    <pagination-box
                                        ref="paginationProvedorPublicaciones"
                                        :current-page="pageOne.current_page"
                                        :items-per-page="pageOne.per_page"
                                        :total-pages="pageOne.last_page"
                                        :total-items="pageOne.total"
                                        @page-changed="pageOneChanged">
                                    </pagination-box> 
                                </div>
                            </div>

                            <div class="tab-pane" id="rubros">
                                <div v-if="perfil.user.proveedor">
                                    <index-rubros :perfil="perfil"></index-rubros>
                                </div>
                            </div>

                            <div class="tab-pane" id="articulos">
                                <div v-if="perfil.user.proveedor">
                                    <index-articulos></index-articulos>
                                </div>
                            </div>
                            <!-- /.tab-pane -->

                        </div>
                    </div>
                <!-- /.tab-content -->
                </div>
            </div>
            <!-- /.nav-tabs-custom -->
        </div>
    </div>
</template>

<script>

    import IndexRubros from './Prestaciones/Index.vue';
    import IndexPublicaciones from './Publicaciones/Index.vue';
    import IndexArticulos from './Articulos/Index.vue';
    Vue.component('pagination-box', require('./../Plugins/pagination/pagination-v1.vue'));

    export default {
        props: {
                perfil: {
                    type: Object,
                    required: true
                }
        },
        data(){
            return {
                publicaciones: [],
                loading: true,
                pageOne: {
                    total:0,
                    per_page:10,
                    current_page: 1,
                    last_page:1,
                    next_page_url:null,
                    prev_page_url:null,
                    from:0,
                    to:0 
                },
                selected : '',
                optionsEstados: [
                    { text: 'Todas', value: '' },
                    { text: 'Activas', value: 1 },
                    { text: 'De baja', value: 0 }
                ]
            }
        },
        created(){
            this.getProductos();
        },
        components: {
            IndexRubros, IndexPublicaciones, IndexArticulos
        }, 
        methods:{
            getProductos: function(){
                this.loading = true;
                this.$http.get('api/proveedor/'+this.perfil.user.proveedor.id+'/publicacion?with_estado='+this.selected+'&page='+this.pageOne.current_page+'&per_page='+this.pageOne.per_page )
                    .then(response => {
                        this.publicaciones = response.data.publicaciones.data
                        this.setDataPagination(response.data.publicaciones);
                        this.loading = false;
                    }, response => {
                        this.$toast.error({
                            title:'¡Error!',
                            message:'No se han podido cargar los productos. :('
                        });
                        this.loading = false;
                    })
            },
            changeFilter() {
                this.getProductos();
            },
            pageOneChanged (pageOne) {
                this.pageOne.current_page = pageOne;
                this.getProductos();
            },
            setDataPagination(data){
                    this.pageOne.total = data.total;
                    this.pageOne.per_page = data.per_page;
                    this.pageOne.last_page = data.last_page;
                    this.pageOne.next_page_url = data.next_page_url;
                    this.pageOne.prev_page_url = data.prev_page_url;
                    this.pageOne.from = data.from;
                    this.pageOne.to = data.to;
            }

        }
    }
</script>