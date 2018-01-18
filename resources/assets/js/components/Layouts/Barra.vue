<template>
    <header class="main-header">
        <!-- Logo -->
        <router-link to="/">
            <a href="#" class="logo">
                <!-- logo for regular state and mobile devices -->
                <span class="logo-mini">
                    <b>C</b>tu<b>E</b>
                </span>
                <span class="logo-lg">Crea tu Evento</span>
            </a>
        </router-link>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top">
        <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>



            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">

                    <notificaciones v-if="auth.user.authenticated"></notificaciones>

                    <!-- Control Sidebar Toggle Button -->
                    
 
                   <menu-user v-if="auth.user.authenticated"></menu-user>

                    <li v-if="!auth.user.authenticated"><router-link  :to="{ name: 'login' }">Iniciar Sesion</router-link></li>
                    <li v-if="!auth.user.authenticated"><router-link  :to="{ name: 'registrar' }">Registrar</router-link></li>

                </ul>
            </div>
        </nav>
    </header>
</template>
<script>
import auth from '../../auth.js';
import role from '../../config.js';
import router from '../../routes.js';
import MenuUser from './MenuUser.vue';
import Notificaciones from './Notificaciones.vue';


export default {
    data() {
        return {
            auth: auth,
            role: role
        }
    },
    components:{ MenuUser, Notificaciones }
}
</script>
