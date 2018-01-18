<template>
    <div class="default-content">
        <section class="content">
            <div class="row">
                <div class="col-md-9 col-md-offset-1 col-xs-12">
                    <form v-on:submit.prevent="searchPublicacion()" role="form" >
                        <div class="col-sm-4" style="padding-right: 0px; padding-left: 0px;">
                            <el-select style="width: 100%;"
                                v-model="localidad_id"
                                filterable=""
                                remote=""
                                no-data-text="Elija una localidad"

                                clearable
                                placeholder="Ubicación"
                                :remote-method="remoteMethod"
                                :loading="loading">
                                    <el-option
                                    v-for="item in localidades"
                                    :key="item.value"
                                    :label="item.label"
                                    :value="item.value">
                                    </el-option>
                            </el-select>
                        </div>
                        <div class="col-sm-8" style="padding-left: 0px; padding-right: 0px;">
                            <div class="input-group stylish-input-group">
                                <input type="text" name="q" class="form-control" v-model="q" placeholder="Busqueda" style="height: 36px;">
                                <span class="input-group-addon">
                                    <button v-if="category_name != ''">
                                        <input type="checkbox" v-model="with_category" name="with_category"> En {{ category_name }}
                                    </button>
                                    <button type="submit" name="search" id="search-btn">
                                    <i class="fa fa-search"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-xs-12">&nbsp;</div>
            </div>
            <div class="box">
                <div class="box-header" style="height: 35px;">
                    <div class="box-tools">
                        <el-radio-group v-model="order" size="mini" @change="managerSearch('')">
                              <el-radio-button label="ASC">Menores precios</el-radio-button>
                              <el-radio-button label="DESC">Mayores precios</el-radio-button>
                        </el-radio-group>
                    </div>
                </div>
                <div class="box-body">
                    <search
                        :publicaciones="publicaciones" :loading="loading">
                    </search>  
                </div>
                <div class="box-footer">
                    <pagination-home
                        ref="paginationH"
                        :current-page="pageOne.current_page"
                        :items-per-page="pageOne.per_page"
                        :total-pages="pageOne.last_page"
                        :total-items="pageOne.total"
                        @page-changed="pageOneChanged">
                    </pagination-home> 
                </div>
            </div>
        </section>
    </div>
</template>

<script>
    import VSelect from "vue-select";
    import route from '../routes.js';
    import Search from './Proveedores/Publicaciones/Index.vue';
    Vue.component('pagination-home', require('./Plugins/pagination/pagination-v1.vue'));

    export default {
        data(){
            return {
                api: 'api/publicacion',
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
                localidades: [],
                titlePath: 'Inicio',
                listaPath: [{route: '/', name: 'Inicio'}],
                publicaciones : [],
                loading: true,
                localidad_id: null,
                categoria_id: '',
                subcategoria_id: '',
                rubro_id: '',
                favorite_user:false,
                favourite: '',
                q: '',
                with_category: false, 
                category_name: '',
                nombre: false,
                order: 'ASC'
            }
        },
        beforeMount() {
            //this.getCategorias();
            this.$nextTick( function() {
                this.$events.fire('changePath',[{route: '/', name: 'Inicio'}], 'Inicio');
            });
        },
        mounted() {
            this.queryPath();
            this.searchPublicacion();
            this.with_category = false;
        },
        components: {
            VSelect, Search
        },
        methods: {
            /** 
            * Armar las consultas de publicaciones
            * para luego realizarlas al servidor. 
            * 
            * @managerSearch 
            * @ Param {String} filter Argumento 1 
            */
            managerSearch(filter){
                this.loading = true;
                let filtro = this.api+'?filter='+ filter; 

                if(this.categoria_id != undefined && this.categoria_id != '' && this.with_category)
                    filtro = filtro + '&with_category='+this.categoria_id;
                if(this.subcategoria_id != undefined && this.subcategoria_id != '' && this.with_category)
                    filtro = filtro + '&with_subcategory='+this.subcategoria_id;
                if(this.rubro_id  != undefined && this.rubro_id  != '' && this.with_category) 
                    filtro = filtro + '&with_denomination='+this.rubro_id;
                if(this.localidad_id !=  null)
                    filtro = filtro + '&with_localidad='+this.localidad_id ;
                if(this.favorite_user) 
                    filtro = filtro + '&favorite='+true;
                filtro = filtro + '&page='+this.pageOne.current_page+'&per_page='+this.pageOne.per_page+'&order_precie='+this.order;

                this.$http.get(filtro).then(response => {
                    this.publicaciones = response.data.publicaciones.data;
                    this.setDataPagination(response.data.publicaciones);
                    this.nombre=false;
                    this.q = '';
                    this.favorite_user=false;
                    this.loading = false;

                }, response => {
                    this.$toast.error({
                        title:'¡Error!',
                        message:'No se han podido cargar las publicaciones. :('
                    });
                    this.loading = false;
                    //if(response.status == '500')
                })
                
            },
            /** 
            * Cambia el pagina en la cual se ecuntra posicionado.
            * 
            * @pageOneChanged 
            * @ Param {Number} pageOne Argumento 1  
            */
            pageOneChanged (pageOne) {
                this.pageOne.current_page = pageOne;
                this.searchPublicacion()
            },
            /** 
            * Encapsula a la function @managerSearch .
            * 
            * @searchPublicacion  
            */
            searchPublicacion: function(){

                if(this.q != ''){
                    this.nombre=true;
                    this.$router.replace({ path : '/', query : {q: this.q} });
                }/* else{
                    this.$router.replace({ path : '/'})
                }*/
                if (this.nombre){
                    this.managerSearch(this.q)
                }
            },
            /** 
            * actualiza los datos del paginador de publicaciones. 
            * 
            * @setDataPagination 
            * @ Param {Object} data Argumento 1 
            */
            setDataPagination(data){
                    this.pageOne.total = data.total;
                    this.pageOne.per_page = data.per_page;
                    this.pageOne.last_page = data.last_page;
                    this.pageOne.next_page_url = data.next_page_url;
                    this.pageOne.prev_page_url = data.prev_page_url;
                    this.pageOne.from = data.from;
                    this.pageOne.to = data.to;
            },
            /** 
            * Captura las consultas de del path (si los hay) 
            * 
            * @queryPath 
            */
            queryPath(){
                if(this.$route.query.with_denomination != undefined){
                    this.rubro_id = this.$route.query.with_denomination;
                    this.with_category = true;
                    this.changePath(this.rubro_id, 'api/rubro/', 'rubro');
                }
                else
                {
                    if(this.$route.query.with_subcategory != undefined){
                        this.subcategoria_id = this.$route.query.with_subcategory;
                        this.with_category = true;
                        this.$events.fire('changeSubcategory', this.subcategoria_id);
                        this.changePath(this.subcategoria_id, 'api/subcategoria/', 'subcategoria');
                    }
                    else
                    {
                        if(this.$route.query.with_category != undefined){
                            this.categoria_id = this.$route.query.with_category;
                            this.with_category = true;
                            this.$events.fire('changeCategory', this.categoria_id);
                            this.changePath(this.categoria_id, 'api/categoria/', 'categoria');
                        }
                        else if(this.$route.query.q != undefined && this.$route.query.q != '')
                        {
                            this.q = this.$route.query.q;
                        }else {
                            if(this.$route.query.favorite != undefined){
                                this.favorite_user=this.$route.query.favorite;
                                this.listaPath.push( {
                                        route: '/?favorite='+true, 
                                        name: "Favoritos"
                                    } );
                            }
                        }
                    }
                }

                this.nombre=true;
            },
            /** 
            * Consulta por el parametro dado y 
            * actuliza la ruta en la que se encuentra.
            * 
            * @changePath 
            * @ Param {String} id Argumento 1 
            * @ Param {String} api Argumento 2
            * @ Param {String} filtro Argumento 3 
            */
            changePath(id, api, filtro){
                this.$http.get(api+''+id).then(response => {
                    var responseData = response.data.data
                    if(filtro == 'rubro')
                    {
                        this.category_name = responseData.nombre;
                        this.listaPath.push( {
                            route: '/?with_category='+responseData.subcategoria.categoria.id, 
                            name: responseData.subcategoria.categoria.nombre
                        } )
                        this.listaPath.push( {
                            route: '/?with_subcategory='+responseData.subcategoria.id, 
                            name: responseData.subcategoria.nombre
                        } )
                        this.listaPath.push( {
                            route: '/?with_denomination='+responseData.id, 
                            name: responseData.nombre
                        } )
                    }
                    else
                    {
                        if(filtro == 'subcategoria')
                        {
                            this.category_name = responseData.nombre;
                            this.listaPath.push( {
                                route: '/?with_category='+responseData.categoria.id, 
                                name: responseData.categoria.nombre
                            } )
                            this.listaPath.push( {
                                route: '/?with_subcategory='+responseData.id, 
                                name: responseData.nombre
                            } )
                        } 
                        else 
                        {
                            if(filtro == 'categoria')
                            {
                                this.category_name = responseData.nombre;
                                this.listaPath.push( {
                                    route: '/?with_category='+responseData.id, 
                                    name: responseData.nombre
                                } )
                            }
                        }
                    }
                    this.$events.fire('changePath', this.listaPath,  responseData.nombre);
                })
            },
            /** 
            * Cambia los valoresde los datos de consulta a sus valores por defecto.
            * 
            * @clearData 
            */
            clearData(){
                this.rubro_id = '';
                this.subcategoria_id = '';
                this.categoria_id = '';
                this.q = '';
                this.with_category = false;
                this.category_name = '';
                this.pageOne= {
                    total:0,
                    per_page:10,
                    current_page: 1,
                    last_page:1,
                    next_page_url:null,
                    prev_page_url:null,
                    from:0,
                    to:0 
                }
            },
            remoteMethod(query) {
                if (query !== '') {
                    this.loading = true;
                    this.$http.get('api/localidades/?q='+ query)
                        .then(response => {
                            this.localidades = response.data.data
                            this.loading = false;
                        });
                } else {
                    this.localidades = [];
                }
            }
        },
        watch: {
            '$route.query'() {

                if (!this.nombre){
                    this.clearData();
                    this.listaPath = [{route: '/', name: 'Inicio'}];
                    this.titlePath = 'Inicio';
                    this.$events.fire('changePath', this.listaPath, this.titlePath);
                    this.queryPath();
                    this.searchPublicacion();
                }

            }
        }
    }
</script>
