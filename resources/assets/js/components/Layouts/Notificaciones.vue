<template>
    <!-- Notifications: style can be found in dropdown.less -->
    <li class="dropdown notifications-menu">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <i class="fa fa-bell-o"></i>
            <span class="label label-warning">
                1
            </span>

        </a>
        <ul class="dropdown-menu">
            <li class="header">
                Sin Notificaciones
            </li>

            <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">

                    <template>
                        <li v-for="notificacion in notificaciones">
                            Line:
                            <span v-text="notificacion"></span>
                        </li>
                        <infinite-loading @infinite="infiniteHandler">
                            <span slot="no-more">
                                No hay mas notificaciones!.
                            </span>
                        </infinite-loading>
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
    import InfiniteLoading from 'vue-infinite-loading';

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
        components: {InfiniteLoading},
        beforeMount(){
            this.notificaciones = [0,1,2,3,4,5,6,7,8,9,10];
        },
        methods: {
            getNotificaciones(){
                this.notificaciones = [];
                if(auth.checkRole(role.ADMINISTRADOR) || auth.checkRole(role.SUPERVISOR))
                this.$http.get('api/usuario/me/notificaciones').then(response => {
                    for(var notificacion of response.data){
                        this.notificaciones.push(
                            {descripcion: notificacion.descripcion, log_id:notificacion.log_id, log: notificacion.log, id: notificacion.id})
                    }
                });
            },

            isAfterNow(value){
                return moment(value, 'YYYY-MM-DD').isAfter(moment({}));
            },
            infiniteHandler($state) {
                setTimeout(() => {
                    const temp = [];
                for (let i = this.notificaciones.length + 1; i <= this.notificaciones.length + 20; i++) {
                    temp.push(i);
                }
                this.notificaciones = this.notificaciones.concat(temp);
                    $state.loaded();
                }, 1000);
            }
        },
        watch: {

        }
    }
</script>