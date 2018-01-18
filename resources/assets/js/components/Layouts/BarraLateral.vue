<template>
    <!-- Left side column. conta ins the logo and sidebar -->
    <aside class="main-sidebar" >
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
            <!-- Sidebar user panel -->
            <div class="user-panel" v-if="auth.user.authenticated">
                <div class="pull-left image">
                    <img v-bind:src="srcUrl" class="img-circle" alt="Avatar">
                </div>
                <div class="pull-left info">
                    <p v-if="auth.user.authenticated">{{ auth.user.profile.name }}</p>
                    <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                </div>
            </div>
            <!-- search form -->
            <form v-on:submit.prevent="searchPublicacion()" class="sidebar-form" :style="{ display : showFormSearch  ? 'block' : 'none' }">
                <div class="input-group">
                    <input type="text" name="q" class="form-control" v-model="q" placeholder="Busqueda">
                    <span class="input-group-btn">
                        <button type="submit" name="search" id="search-btn" class="btn btn-flat">
                        <i class="fa fa-search"></i>
                        </button>
                    </span>
                </div>
            </form>
            <!-- /.search form -->
            <!-- sidebar menu: : style can be found in sidebar.less -->
            <ul class="sidebar-menu">
                <li class="header">MENU</li>
                <template  v-if="auth.user.authenticated">
                    <!--  Administracion -->
       
                    <li class="treeview" 
                        v-if="auth.user.profile.roles_id == role.ADMINISTRADOR || 
                            auth.user.profile.roles_id == role.SUPERVISOR ||
                            auth.user.profile.roles_id == role.OPERADOR">
                        
                        <a href="#">
                            <i class="fa fa-dashboard"></i>
                            <span>Administrar</span>
                            <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">

                            <router-link 
                                v-if="auth.user.profile.roles_id == role.ADMINISTRADOR ||
                                auth.user.profile.roles_id == role.SUPERVISOR"
                                tag="li" 
                                to="/usuario">
                                <a>
                                    <i class="fa fa-th-list"></i> Usuarios
                                </a>
                            </router-link>

                            <router-link
                                v-if="auth.user.profile.roles_id == role.ADMINISTRADOR ||
                                    auth.user.profile.roles_id == role.SUPERVISOR ||
                                    auth.user.profile.roles_id == role.OPERADOR" 
                                    tag="li"
                                    to="/proveedores">
                                    <a>
                                        <i class="fa fa-th-list"></i> Proveedores
                                    </a>
                            </router-link>

                            <router-link
                                v-if="auth.user.profile.roles_id == role.ADMINISTRADOR ||
                                    auth.user.profile.roles_id == role.SUPERVISOR"
                                    tag="li"
                                    to="/categorias">
                                        <a>
                                            <i class="fa fa-th-list"></i> <span> Categorias</span>
                                        </a>
                            </router-link>

                            <router-link
                                v-if="auth.user.profile.roles_id == role.ADMINISTRADOR ||
                                    auth.user.profile.roles_id == role.SUPERVISOR"
                                    tag="li"
                                    to="/rubros">
                                        <a>
                                            <i class="fa fa-th-list"></i> <span> Rubros</span>
                                        </a>
                            </router-link>

                            <router-link
                                v-if="auth.user.profile.roles_id == role.ADMINISTRADOR ||
                                    auth.user.profile.roles_id == role.SUPERVISOR"
                                    tag="li"
                                    to="/caracteristicas">
                                        <a>
                                            <i class="fa fa-th-list"></i> <span> Caracteristicas</span>
                                        </a>
                            </router-link>

                              <router-link
                                v-if="auth.user.profile.roles_id == role.ADMINISTRADOR ||
                                    auth.user.profile.roles_id == role.SUPERVISOR"
                                    tag="li"
                                    to="/localidades">
                                        <a>
                                            <i class="fa fa-th-list"></i> <span> Localidades</span>
                                        </a>
                            </router-link>
                        </ul>
                    </li>
                    <li class="treeview" 
                        v-if="auth.user.profile.roles_id == role.ADMINISTRADOR || 
                            auth.user.profile.roles_id == role.SUPERVISOR ||
                            auth.user.profile.roles_id == role.OPERADOR">
                        
                        <a href="#">
                            <i class="fa fa-dashboard"></i>
                            <span>Terminos y Condiciones</span>
                            <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">

                            <router-link 
                                v-if="auth.user.profile.roles_id == role.ADMINISTRADOR ||
                                auth.user.profile.roles_id == role.SUPERVISOR"
                                tag="li" 
                                to="/terminos-condiciones/new">
                                <a>
                                    <i class="fa fa-th-list"></i> Nuevo
                                </a>
                            </router-link>

                            <router-link
                                v-if="auth.user.profile.roles_id == role.ADMINISTRADOR ||
                                    auth.user.profile.roles_id == role.SUPERVISOR ||
                                    auth.user.profile.roles_id == role.OPERADOR" 
                                    tag="li"
                                    to="/terminos-condiciones/edit">
                                    <a>
                                        <i class="fa fa-th-list"></i> Editar
                                    </a>
                            </router-link>

                        </ul>
                    </li>
                    <li class="treeview" v-if="favourites > 0">
                        <router-link :to="'/?favorite='+ true" tag="a" @click="goToFavourites()">
                            <i class="fa fa-dashboard"></i>
                            <span>Favoritos</span>
                            <span class="pull-right-container">
                                 <span class="label label-primary pull-right">{{favourites}}</span>
                            </span>
                        </router-link>
                    </li>

                    <!-- Proveedores-->
                    <li class="treeview" v-if="auth.user.profile.roles_id == role.PROVEEDOR">
                        <a v-if="auth.user.profile.roles_id == role.PROVEEDOR" style="cursor: pointer">
                            <i class="fa fa-dashboard"></i>
                            <span>Agregar</span>
                            <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu" v-if="auth.user.profile.roles_id == role.PROVEEDOR">
                            <li>
                                <a v-if="auth.user.profile.roles_id == role.PROVEEDOR" @click="goToNewPublicacion()" style="cursor: pointer">
                                    <i class="fa fa-plus"></i> <span> Publicaci&oacute;n</span>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li v-if="auth.user.profile.roles_id == role.PROVEEDOR || auth.user.profile.roles_id == role.USUARIO">
                        <router-link to="/mensajes" tag="a">
                            <i class="fa fa-envelope-o"></i> <span>Reservas</span>
                            <span class="pull-right-container" 
                                v-if="mensajesSinLeer > 0 || mensajesPorActulizar > 0 || mensajesPresupuestados > 0 ">

                                <el-tooltip class="item" effect="dark" 
                                    content="Solicitudes de presupuestado nuevas." placement="top-start">
                                    <small class="label pull-right bg-yellow" v-if="mensajesPresupuestados > 0">
                                        {{mensajesPresupuestados}}
                                    </small>
                                </el-tooltip>

                                <el-tooltip class="item" effect="dark" 
                                    content="Fechas de presupuestos que deben de ser actualizadas." placement="top-start">
                                    <small class="label pull-right bg-red" 
                                        v-if="mensajesPorActulizar > 0 && auth.user.profile.roles_id == role.USUARIO">
                                        {{mensajesPorActulizar}}
                                    </small>
                                </el-tooltip>

                                <el-tooltip class="item" effect="dark" 
                                    content="Nuevos mensajes." placement="top-start">
                                    <small class="label pull-right bg-green" v-if="mensajesSinLeer > 0">
                                        {{mensajesSinLeer}}
                                    </small>
                                </el-tooltip>

                            </span>
                        </router-link>
                    </li>

                    <li v-if="auth.user.profile.roles_id == role.PROVEEDOR">
                        <router-link to="/reservas" tag="a">
                            <i class="fa fa-calendar"></i> <span>Calendar</span>
                            <span class="pull-right-container" v-if="eventosProximaSemana > 0">
                                <el-tooltip class="item" effect="dark" 
                                    content="Proximos eventos." placement="top-start">
                                    <small class="label pull-right bg-blue">
                                        {{eventosProximaSemana}}
                                    </small>
                                </el-tooltip>
                            </span>
                        </router-link>
                    </li>

                    <li class="treeview" v-if="auth.user.profile.roles_id != role.USUARIO">
                          <router-link to="/estadisticas" tag="a">
                            <i class="fa fa-line-chart"></i>
                            <span>Estadisticas</span>
                        </router-link>
                    </li>

                </template>
                <template v-if="showFilter && categorias.length > 0 && showCategories">
                    <li class="treeview active">
                        <a href="#">
                            <i class="fa fa-bars"></i>
                            <span>Categorias</span>
                            <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <router-link 
                            v-if="categorias.length > 0"
                            v-for="categoria in categorias" :key="categoria.value" tag="li" 
                            v-bind:to="categoria.value">
                                <a><i class="fa fa-tags"></i> <span>{{categoria.text}}</span>
                                    <span class="pull-right-container">
                                        <span class="label label-primary pull-right">{{ categoria.quantity}}</span>
                                    </span>
                                </a>
                            </router-link>
                        </ul>
                    </li>
                </template>

            </ul>
        </section>
        <!-- /.sidebar -->
    </aside>

</template>

<script>
    import auth from '../../auth.js';
    import route from '../../routes.js';
    import Role from '../../config.js';
    import moment from 'moment';

    export default {
        data() {
            return {
                auth: auth,
                srcUrl: '',
                role: Role,
                q: '',
                mensajesSinLeer: 0,
                mensajesPresupuestados: 0,
                mensajesPorActulizar: 0,
                eventosProximaSemana: 0,
                categorias: [],
                favoritos:[],
                showCategories: false,
                favourites: 0
            }
        },
        beforeMount: function(){
            this.getCategorias();
        },
        mounted: function(){
            this.$events.$on('changeCategory', eventData => this.changeCategory(eventData));
            this.$events.$on('changeSubategory', eventData => this.changeSubcategory(eventData));
            this.$events.$on('changeFavorite', eventData => this.getFavourite());
            if(auth.user.authenticated){
                this.avatarUpdated();
                this.getFavourite();
                this.getMensajes();
                this.getReservas();
            }
        },
        methods: {
            avatarUpdated: function(){
                this.srcUrl = '/storage/avatars/'+ auth.user.profile.usuario.avatar
            },
            goToNewRubro(){
                this.$events.fire('changePath', this.listPath, 'Registrar Rubro');
                route.push('/rubro/new');
            },
            goToNewPublicacion(){
                this.$events.fire('changePath', this.listPath, 'Nueva Publicacion');
                route.push('/publicacion/new');
            },
            goToFavourites(){
                this.$events.fire('changePath', this.listPath, 'Favoritos');
                route.push('/?favorite='+ true);
            },
            searchPublicacion: function(){
                if(route.path !== '/' && this.q !== '' )
                    route.push('/?q='+this.q)
                //this.$events.fire('searchPublicacion', this.q);
            },
            getReservas(){
                if(auth.user.profile.roles_id == this.role.PROVEEDOR){
                    var url = 'api/proveedor/me/reserva';
                    this.eventosProximaSemana = 0;
                    this.$http.get(url).then(response => {
                        for (var reserva of response.data) {
                            if(moment(reserva.fecha, 'YYYY-MM-DD').isBetween(moment({}), moment().add(7, 'days')))
                            {
                                this.eventosProximaSemana++;
                            }
                        }
                    }, response => {

                    });
                }
            },
            /** 
            * Consulta de todas los mensajes.
            * 
            * @getMensajes 
            */
            getMensajes: function(){
                this.mensajesSinLeer = 0;
                this.mensajesPresupuestados = 0;
                this.mensajesPorActulizar = 0;
                this.$http.get('api/mensaje')
                .then(response => {
                    for (let mensaje of response.data){
                        if(mensaje.nuevos != undefined){
                            this.mensajesSinLeer = this.mensajesSinLeer + mensaje.nuevos;
                        }

                        if(this.isAfterNow(mensaje.reserva.fecha) && mensaje.reserva.presupuestado == true && mensaje.reserva.estado == 'presupuesto')
                        {
                            this.mensajesPresupuestados++;
                        }
                        if(!this.isAfterNow(mensaje.reserva.fecha) && mensaje.reserva.estado != 'confirmado')
                        {
                            this.mensajesPorActulizar++;
                        }

                    }
                })
            },
            /** 
            * Consulta de todas las categorias.
            * 
            * @getCategorias 
            */
            getCategorias: function(){
                this.showCategories = false;
                this.$http.get('api/categoria/')
                    .then(response => {
                        let data = response.data
                        this.categorias = [];
                        for (let categoria of data){
                            var lengthP = 0;
                            for (let subcategoria of categoria.subcategorias){
                                for(let publicacion of subcategoria.publicaciones){
                                    if(publicacion.estado == 1)
                                        lengthP = lengthP + 1;
                                }
                            }
                            if(lengthP > 0)
                            this.categorias.push({ value: '/?with_category=' + categoria.id, text: categoria.nombre, quantity: lengthP });
                        }
                        setTimeout(() => this.showCategories = true, 2000);
                    })
            },
            /** 
            * Consulta por una categoria al servidor
            * 
            * @changeCategory 
            */
            changeCategory: function(id){
                this.showCategories = false;
                this.$http.get('api/categoria/' +id
                    ).then(response => {
                        let data = response.data.data
                        this.categorias = [];
                        for (let subcategoria of data.subcategorias){
                            var lengthP = 0;
                            for(let publicacion of subcategoria.publicaciones){
                                if(publicacion.estado == 1)
                                    lengthP = lengthP + 1;
                            }
                            if(lengthP > 0)
                                this.categorias.push({ text: subcategoria.nombre, value: '/?with_subcategory=' + subcategoria.id, quantity: lengthP });
                        }
                        setTimeout(() => this.showCategories = true, 3000);
                    })
                    
            },
            /** 
            * Consulta de todas las publicaciones Favoritas.
            * 
            * @getFavourite
            */
            getFavourite: function(){
                if (auth.user.authenticated){
                    this.$http.get('api/favoritos/'+ auth.user.profile.id)
                        .then(response => {
                            this.favoritos = response.data.data
                            this.favourites = this.favoritos.length
                        })
                }
            },
            isAfterNow(value){
                return moment(value, 'YYYY-MM-DD').isAfter(moment({}));
            }
        },
        computed: {
            showFormSearch: function(){
                return this.$route.name != 'home';
            },
            showFilter : function(){
                return this.$route.name == 'home'
            }
        },
        watch: {
            'auth.user.profile.usuario'  (){
                 this.avatarUpdated();
            },
            'auth.user.authenticated'  (){
                 this.getFavourite();
            },
            '$route.query' (){
                if(JSON.stringify(this.$route.query) === JSON.stringify({})){
                    this.getCategorias();
                }
            },
            '$route.fullPath' (){
                this.getMensajes();
            },
            'favourites'(){
                if (JSON.stringify(this.$route.query) === '{"favorite":"true"}'){
                    route.push('/')
                    this.goToFavourites();
                }
            },
            'auth.user.authenticated'(){
                if(auth.user.authenticated)
                    this.getMensajes();
            }

        }

    }
</script>

