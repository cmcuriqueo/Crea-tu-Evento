<template>
    <div>
        <template v-if="publicaciones.length > 0" class="row">
            <div  v-for="(item, index) in publicaciones" class="col-sm-12">
                <div>
                    <hr v-if="index > 0">
                    <div class="col-sm-3 col-xs-12" 
                        v-for="(img, index) in item.fotos" 
                        v-if="index==0" style= "text-align: center;">
                        <img 
                            :src="'/storage/proveedores/publicaciones/'+img.nombre" 
                            class="img-responsive" 
                            style="width: 120px; height: 100px; cursor:pointer; text-align:center;" @click="ver(item.id)">
                    </div>
                    <div class="col-sm-9 col-xs-12">
                        <div class="col-sm-11 col-xs-10">
                            <div class="col-sm-9" @click="ver(item.id)">
                                <strong style="color: rgb(139, 116, 178); cursor:pointer"><h4><p ref="descripcion" v-html="item.titulo" v-truncate="30"></p></h4></strong>
                            </div>
                            <div class="col-sm-3">
                                <h6><p>{{formatData(item.created_at)}}</p></h6>
                            </div>
                        </div>
                        <div class="col-sm-1 col-xs-1">
                            <div v-if="!optionsProveedor&&(auth.user.profile.id!=item.prestacion.proveedor.user_id)">
                                <div style="cursor: pointer" v-if="verificar_favorite(item.id)">
                                    <div @click="favorite(item.id)">
                                        <i class="fa fa-fw fa-heart"></i>
                                    </div>
                                </div>

                                <div v-else>
                                    <div style="cursor: pointer" @click="favorite(item.id)">
                                        <i class="fa fa-fw fa-heart-o"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="col-sm-8">
                                <p><h6>{{item.prestacion.domicilio.localidad.provincia.nombre}} - {{item.prestacion.domicilio.localidad.nombre}}</h6></p>
                            </div>
                            <div class="col-sm-4" v-if="item.calificacion > 0">
                                <el-rate
                                    v-model="item.calificacion"
                                    disabled
                                    text-color="#ff9900"
                                    text-template="{value} puntos.">
                                </el-rate>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="col-sm-8">
                                <h6>{{item.subcategoria.categoria.nombre}} - {{item.subcategoria.nombre}} </h6>
                            </div>
                            <div class="col-sm-4">
                                Desde: {{formatMoney(item.precio)}} 
                                    <div v-if="showDescuento(item)">
                                        <span class="label label-primary">En oferta</span>
                                    </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12" v-if="optionsProveedor && auth.user.profile.roles_id == role.PROVEEDOR && 
                                item.proveedor.user_id == auth.user.profile.id">
                        <div style="text-align:center">
                            <div class="btn-group" >
                                <button class="btn btn-sm btn-default" @click="modificar(item.id)">
                                    Modificar
                                </button>
                                <button class="btn btn-sm" v-bind:class="styleButton(item.estado)" @click="showModalDelete = true, publicacion = item,
                                accion = item.estado">
                                    {{ item.estado == 1 ? 'Dar de baja' : 'Dar de alta'}}
                                </button>
                            </div>
                        </div>

                    </div>
                </div>
                <br><br>
            </div>

        </template>
        <template v-else>
            <div class="col-sm-12">
                <h3 v-if="loading" class="text-center"> Cargando publicaciones... </h3>
                <h3 v-else class="text-center">No se encontraron publicaciones.</h3>
                
            </div>
        </template>
         <template>
            <!-- Modal cambiar observaciones (baja/alta)-->
            <div class="modal" role="dialog" :style="{ display : showModalDelete  ? 'block' : 'none' }">
                <div class="modal-dialog">

                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" @click="showModalDelete = false">&times;</button>
                            <h4 class="modal-title">Dar de baja publicacion</h4>
                        </div>
                        <div class="modal-body">

                            <div class="box-body">
                                <p>¿Esta seguro de dar de {{ accion != 1 ? 'Alta' : 'Baja'}} esta publicación?</p>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <div class="col-sm-12">
                                <div class="pull-right">
                                    <button type="button" @click="showModalDelete = false" class="btn btn-default">
                                        Atras
                                    </button>
                                    <button
                                        @click="estado(publicacion)" 
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
    </div>
</template>

<script>
    import route from './../../../routes.js';
    import auth from './../../../auth.js';
    import role from './../../../config.js';
    import moment from 'moment';
    import accounting from 'accounting-js';

    export default {
        props: {
            publicaciones: {
                required: true
            },
            optionsProveedor: {
                required: false,
                default: false
            },
            loading: {
                default: true
            }
        },
        data(){
            return {
                favoritos: false,
                auth: auth,
                puntos: 2.5,
                showModalDelete: false,
                role: role,
                accion: '',
                publicacion: {}
            }  
        },
        mounted(){
            if (auth.user.authenticated){
                this.favoriteIcon()
            }
        },
        methods: {
            ver(id){
                var listPath = [
                        {route: '/', name: 'Inicio'}, 
                        {route: '/publicacion/'+id, name: 'Ver Publicacion'}
                    ]
                this.$events.fire('changePath', listPath, 'Ver Publicacion');
                route.push('/publicacion/'+id);
            },
            modificar(id){
                route.push('/publicacion/'+id+'/edit');
            },
            formatMoney(value){
                return (value == null)
                    ? ''
                    : accounting.formatMoney(value, "$", 2, ".", ",");
            },
            estado(publicacion){
                this.$http.delete('api/publicacion/'+publicacion.id )
                    .then(response => {
                        this.$toast.success({
                            title:'Acción realizada!',
                            message:'La acción se han realizado correctamente. :D'
                        });
                        if(publicacion.estado == 1) publicacion.estado = 0;
                        else publicacion.estado = 1;
                    }, response => {
                        this.$toast.error({
                            title:'¡Error!',
                            message:'No se han podido realizar la acción. :('
                        });
                    })
                    this.showModalDelete = false;
            },
            formatData: function(value){

                return (value == null)
                    ? ''
                    : moment(value, 'YYYY-MM-DD').format('D MMM YYYY');
            },
            favoriteIcon(){
                if (auth.user.authenticated)
                {
                    this.$http.get(
                        'api/favoritos/'+ auth.user.profile.id )
                       .then(response => {
                            this.favoritos = response.data.data
                    })
                }                         
            },
            verificar_favorite(publicacion_id){
                for (var i = 0; i < this.favoritos.length; i++) {
                    if (this.favoritos[i]==publicacion_id){
                        return true
                    }
                }
                return false
            },
            favorite(id){
                if (auth.user.authenticated){
                    this.$http.post(
                        'api/favoritos/', 
                        {
                            user_id: auth.user.profile.id,
                            publicacion_id : id
                        }).then(response => {
                            if (response.data.favoritos != null){
                                this.favoritos.push(response.data.favoritos.publicacion_id)
                            }else{
                                for (var i = 0; i <= this.favoritos.length; i++) {
                                    if (this.favoritos[i]==id){
                                        this.favoritos.splice(i, 1)
                                    }
                                }
                            }
                            this.$events.$emit('changeFavorite', eventData => this.getFavourite());
                        }, response => {
                            this.$toast.error({
                                title:'¡Error!',
                                message:'No se ha podido completar la accion. :('
                            });
                        })  
                } else{
                    var listPath = [
                        {route: '/login', name: 'Login'}
                    ]
                    this.$events.fire('changePath', listPath, 'Login');
                    route.push('/login');
                 }
            },
            styleButton: function(estado){
                if(estado == 1) return ' btn-danger';
                else return ' btn-success';
            },
            showDescuento(publicacion){
                if(publicacion.descuento != null){
                    if(publicacion.fecha_finalizacion != null){
                        
                        if(moment(publicacion.fecha_finalizacion, 'YYYY-MM-DD').isAfter(moment({}))){
                            return true;
                        }
                    } else {
                        return true;
                    }
                }
                return false;
            }
        },
        directives: {
            truncate: {
                bind: function(el, binding) {
                    el.textContent = el.textContent.substring(0, binding.value);
                }
            }
        },
        watch:
        {
            'auth.user.authenticated'(){
                if (auth.user.authenticated){
                    this.favoriteIcon()
                }
            }
        }
 }

</script>