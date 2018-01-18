<template>
    <!-- Notifications: style can be found in dropdown.less -->
    <li class="dropdown notifications-menu">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <i class="fa fa-bell-o"></i>
            <span v-if="pendientes > 0" class="label label-warning">
                1
            </span>
            <span v-if="mensajesPresupuesto > 0" class="label label-warning">
                1
            </span>
            <span v-if="notificaciones.length > 0" class="label label-warning">
                {{notificaciones.length}}
            </span>
        </a>
        <ul class="dropdown-menu">
            <li v-if="(auth.user.profile.roles_id == role.ADMINISTRADOR || auth.user.profile.roles_id == role.SUPERVISOR) && notificaciones.length == 0" class="header">
                Sin Notificaciones
            </li>
            <li v-if="auth.user.profile.roles_id == role.ADMINISTRADOR || auth.user.profile.roles_id == role.SUPERVISOR && notificaciones.length >= 0" class="header">
                {{ notificaciones.length }} Notificaciones
            </li>
            <li v-if="auth.user.profile.roles_id == role.PROVEEDOR" class="header">
                {{mensajesPresupuesto == 0 ? 'Sin Notificaciones' : '1 Notificaci&oacute;n' }}
            </li>
            <li v-if="auth.user.profile.roles_id == role.USUARIO" class="header">
                {{pendientes == 0 ? 'Sin Notificaciones' : '1 Notificaci&oacute;n' }}
            </li>
            <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">
                    <template v-if="pendientes > 0  && auth.user.profile.roles_id == role.USUARIO">
                        <li>                     
                            <router-link 
                                tag="a" 
                                to="/calificaciones">                
                                <i class="fa fa-smile-o text-aqua"></i> 
                                {{pendientes}} {{pendientes == 1 ? 'calificacion pendiente' : 'calificaciones pendientes'}}.
                            </router-link>
                        </li>
                    </template>
                    <template v-if="mensajesPresupuesto > 0  && auth.user.profile.roles_id == role.PROVEEDOR">
                        <li>                     
                            <router-link 
                                tag="a" 
                                to="/mensajes">
                                <i class="fa fa-envelope-o text-aqua"></i>               
                                    {{mensajesPresupuesto}} {{mensajesPresupuesto == 1 ? 'presupuesto pendiente' : 'presupuestos pendientes'}}.
                            </router-link>
                        </li>
                    </template>
                    <template v-if="notificaciones.length > 0  && (auth.user.profile.roles_id == role.SUPERVISOR || auth.user.profile.roles_id == role.ADMINISTRADOR)">
                        <li v-for="notificacion in notificaciones">

                            <template v-if="notificacion.log_id != null && (notificacion.log.accion == 'create' &&  notificacion.log.tabla == 'proveedores')">                     
                                <a href="#" @click.prevent="notificacionVista(notificacion)">
                                    <i class="fa fa-user-plus text-aqua"></i>               
                                        {{notificacion.descripcion}}
                                </a>
                            </template>

                            <template v-if="notificacion.log_id != null && ((notificacion.log.accion == 'baja' || notificacion.log.accion == 'rechazado') &&  notificacion.log.tabla == 'proveedores')">                     
                                <a href="#" @click.prevent="notificacionVista(notificacion)">
                                    <i class="fa fa-user-times text-aqua"></i>               
                                        {{notificacion.descripcion}}
                                </a>
                            </template>

                            <template v-if="notificacion.log_id != null && (notificacion.log.accion == 'aprobado' &&  notificacion.log.tabla == 'proveedores')">                     
                                <a href="#" @click.prevent="notificacionVista(notificacion)">
                                    <i class="fa fa-check text-aqua"></i>               
                                        {{notificacion.descripcion}}
                                </a>
                            </template>
                            <template v-if="notificacion.log_id != null && (notificacion.log.accion == 'reportado' &&  notificacion.log.tabla == 'calificaciones')">                     
                                <a href="#" @click.prevent="notificacionVista(notificacion)">
                                    <i class="fa fa-flag text-aqua"></i>               
                                        {{notificacion.descripcion}}
                                </a>
                            </template>
                        </li>
                    </template>
                </ul>
            </li>
        </ul>
    </li>
</template>
<script>
    import auth from '../../auth.js';
    import route from '../../routes.js';
    import role from '../../config.js';
    import moment from 'moment';

    export default {
        data() {
            return {
                pendientes: 0,
                role: role,
                auth: auth,
                mensajesPresupuesto: 0,
                notificaciones: [],
                route: route
            }
        },
        beforeMount(){
            if(auth.user.profile.roles_id == role.USUARIO)
                this.calificacionesPendientes();
            if(auth.user.profile.roles_id == role.PROVEEDOR)
                this.getMensajes();
            if(auth.user.profile.roles_id == role.ADMINISTRADOR || auth.user.profile.roles_id == role.SUPERVISOR)
                this.getNotificaciones();
        },
        methods: {
            getNotificaciones(){
                this.notificaciones = [];
                if(auth.user.profile.roles_id == role.ADMINISTRADOR || auth.user.profile.roles_id == role.SUPERVISOR)
                this.$http.get('api/usuario/me/notificaciones').then(response => {
                    for(var notificacion of response.data){
                        this.notificaciones.push(
                            {descripcion: notificacion.descripcion, log_id:notificacion.log_id, log: notificacion.log, id: notificacion.id})
                    }
                });
            },
            notificacionVista(notificacion){
                this.$http.get('api/notificacion/'+notificacion.id).then(response => {
                    for (var i = 0; i < this.notificaciones.length; i++) {
                        if(this.notificaciones[i].id == notificacion.id){
                            this.notificaciones.splice(i, 1);
                            break;
                        }
                    }
                }, response => {
                    console.log('error')
                });
                if(notificacion.log.tabla == 'calificaciones'){
                    this.$http.get('api/calificacion/'+notificacion.log.registro_id).then(response => {
                        this.route.push('/publicacion/'+response.data.publicacion_id);
                    }, response => {
                        this.$toast.error({
                            title:'¡Error!',
                            message:'No se han podido acceder a esta información.'
                        });
                    }); 
                } else {
                    this.route.push('/proveedores');
                }
            },
            calificacionesPendientes(){
                this.$http.get('api/user/me/calificaciones/pendientes').then(response => {
                    this.pendientes = response.data.length;
                }, response => {});
            },
            /** 
            * Consulta de todas los mensajes.
            * 
            * @getMensajes 
            */
            getMensajes: function(){
                this.mensajesPresupuesto = 0;
                this.$http.get('api/mensaje')
                .then(response => {
                    for (let mensaje of response.data){
                        if(this.isAfterNow(mensaje.reserva.fecha) && mensaje.reserva.presupuestado == false && mensaje.reserva.estado == 'presupuesto')
                            this.mensajesPresupuesto++;

                    }
                })
            },
            isAfterNow(value){
                return moment(value, 'YYYY-MM-DD').isAfter(moment({}));
            }
        },
        watch: {
            '$route.path' () {
                if(auth.user.profile.roles_id == role.USUARIO)
                    this.calificacionesPendientes();
                if(auth.user.profile.roles_id == role.PROVEEDOR)
                    this.getMensajes();
                if(auth.user.profile.roles_id == role.ADMINISTRADOR || auth.user.profile.roles_id == role.SUPERVISOR)
                    this.getNotificaciones();
            }
        }
    }
</script>