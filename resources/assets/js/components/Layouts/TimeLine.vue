<template>
    <div v-if="actividades.length > 0">
        <!-- The timeline -->
            <ul class="timeline">

                <!-- timeline time label -->
                <template v-for="(actividad, index) in actividades" v-if="index <= quantityTimeLine">
                    <template v-if="index > 0 && compareTimeLabel(index)">

                            <li class="time-label">
                                <span  class="bg-red">
                                    {{formatDate(actividad.fecha)}} 
                                </span>
                            </li>
                    </template>
                    <template v-if="index == 0">
                        <li class="time-label">
                            <span class="bg-red">
                                {{formatDate(actividad.fecha)}} 
                            </span>
                        </li>
                    </template>

                    <!-- /.timeline-label -->
                    <!-- timeline item -->
                    <li>
                        
                        <icons-time :accion="actividad.accion" :table="actividad.tabla"></icons-time>

                        <div class="timeline-item">
                            <span class="time"><i class="fa fa-clock-o"></i> {{actividad.created_at}}</span>

                            <div v-if="actividad.descripcion" class="timeline-body">
                                {{ actividad.descripcion }} 

                                <template v-if="actividad.tabla === 'users'"> 

                                    <a
                                        class="btn link"
                                        @click="showUserProfil(actividad.id)">
                                            Ver Usuario
                                     </a>

                                                            
                                    <!-- Modal -->
                                    <div v-if="userData !== null" 
                                        class="modal" role="dialog" 
                                        :style="{ display : showUserData  ? 'block' : 'none' }">

                                        <div class="modal-dialog">
                                            <!-- Modal content-->
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" @click="showUserData = false">&times;</button>
                                                    <h4 class="modal-title">{{userData.usuario.nombre}} {{userData.usuario.apellido}}</h4>
                                                </div>
                                                <div class="modal-body defaul-content">
                                                    <div class="col-sm-12">
                                                        <div class="col-sm-4">
                                                            <div class="col-sm-12">
                                                                <img :src="'/storage/avatars/'+ userData.usuario.avatar" width="150" height="150" class="img-responsive">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-8">
                                                            <p>Email: {{userData.email}}</p>
                                                            <p>Genero: {{ userData.usuario.sexo == 'F' ? 'Femenino' : 'Masculino'}}</p>
                                                            <p>Rol: {{ userData.rol.nombre }}</p>
                                                            <p>Cumpleaños: {{ userData.usuario.fecha_nac }}</p>
                                                            <p>Estado: {{ formatEstado(userData.estado) }}</p>
                                                        </div>

                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button 
                                                        type="button" 
                                                        class="btn btn-default pull-left" 
                                                        @click="showUserData = false">
                                                        Cerrar
                                                    </button>

                                                    <button v-if="userData.estado === 1"
                                                        type="button" 
                                                        class="btn btn-primary pull-right"
                                                        @click="pushUserProfil(userData.id)">
                                                        Ver Perfil 
                                                    </button>

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </template>

                            </div>
                        </div>
                        
                    </li>
                    <li v-if="index == quantityTimeLine">
                        <div class="timeline-item">
                            <button class="btn 23btn-default btn-block" @click="verMas">Ver Mas</button>
                        </div>
                    </li>
                </template>
                <li>
                    <i class="fa fa-clock-o bg-gray"></i>
                </li>
            </ul>
    </div>
    <div v-else class="text-center">
        No se econtraron actividades!.
    </div>
</template>

<script>
    import router from '../../routes.js';
    import auth from '../../auth.js';
    import IconsTime from './IconsTimeLine'
    import moment from 'moment';

    export default {
        data(){
            return {
                titleContent: 'Linea de Tiempo',
                actividades: [],
                auth: auth,
                quantityTimeLine: 9,
                showUserData: false,
                userData: null,
                listPath : [
                        {route: '/', name: 'Inicio'}
                ]

            }
        },
        beforeMount: function() {
            this.getUserlog()
        },
        components: { IconsTime },
        methods:{
            getUserlog: function(){
                this.$http.get('api/user/'+ this.$route.params.userId+'/actividad' )
                    .then(response => {
                        this.actividades = response.data
                    }, response => {

                        if(response.status === 404){
                            router.push('/404');
                        }

                    })
            },
            reload: function(){
                this.getUserlog();
            },
            formatDate: function(value){
                moment.locale('es');
                return (value == null)
                    ? ''
                    : moment(value, 'YYYY-MM-DD').format('DD MMM YYYY');
            },
            compareTimeLabel: function(index){

                if(!moment(this.actividades[index-1].fecha, 'YYYY-MM-DD')
                    .isSame(moment(this.actividades[index].fecha, 'YYYY-MM-DD'))){
                    return true;
                } else {
                    return false;
                }
            },
            verMas: function(){
                if((this.quantityTimeLine + 10) > this.actividades.length )
                {
                    this.quantityTimeLine = this.quantityTimeLine + 10;
                } else {
                    this.quantityTimeLine = this.actividades.length;
                }
            },
            showUserProfil: function(id){
                this.$http.get('api/actividad/'+ id )
                    .then(response => {
                        this.userData = response.data.user;
                        this.showUserData = true;
                    }, response => {


                    })
            },
            pushUserProfil(id){
                this.listPath.push({route: '/usuario/'+this.$route.params.userId+'/perfil', name: 'Perfil'})
                this.$events.fire('changePath', this.listPath, 'Perfil');
                router.push('/usuario/'+ id +'/perfil');
            },
            formatEstado (value){
                if(value === 0) return 'Inactivo';
                else if(value === 1) return 'Activo';
                else return 'Bloqueado';
            },
        }
    }
</script>
<style>
    .text-center {
        margin-top: 75px;
    }
    .modal-body.defaul-content {
        min-height: 240px;
    }
</style>