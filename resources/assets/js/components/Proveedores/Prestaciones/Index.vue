<template >
<div>
    <section class="content">
        <p class="text-center" style="margin-top:0px;">Habilitación de ingresos brutos: {{perfil.user.proveedor.ingresos_brutos}}</p>
        <div v-if="rubrosRegistrados.length > 0" class="row">
            <div v-for="item in rubrosRegistrados" class="col-sm-6">
                <div class="box box-default" style="height: 275px;">
                    <div class="box-header">
                        <!--
                        <div class="box-tools pull-right">
                            <button class="btn btn-box-tool" data-toggle="tooltip" @click="modificar(item.id)" >
                                <i class="fa fa-pencil"></i>
                            </button>
                        </div> -->
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <p>
                            <b>Rubro: </b> {{printRubros( item.rubros ) }}
                        </p>
                        <div v-if="item.habilitacion != null">
                            <p>
                                <b>Nro Habilitación: </b>
                                {{item.habilitacion}}
                            </p>
                        
                            <p>
                                <b>Fecha de habilitacion: </b>
                            
                                {{item.fecha_habilitacion}}
                            </p>
                        </div>
                        <div v-else>
                            <p><b>Habilitación: </b>Sin habilitación</p>
                        </div>
                        <p if= "item.domicilio">
                            <b>Domicilio: </b><br>
                            calle: {{item.domicilio.calle}}<br>
                            numero: {{item.domicilio.numero}}<br>
                            piso: {{item.domicilio.piso}}
                        </p>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div>
        </div>
        <div v-else class="content">
            <div class="col-sm-12">
                <h3 class="text-center">No se ha registrado en ningun rubro</h3>
                <h5 class="text-center">¡Para realizar publicaciones debe de encontrarse registrado en un rubro!.</h5>
            </div>
        </div>
        <!-- Modal Modificar-->
        <div v-if="showModificar &&rubrosRegistrados.length > 0" 
            id="modificar" class="modal" role="dialog" :style="{ display : showModificar  ? 'block' : 'none' }">
            <div class="modal-dialog">
              <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" @click="closeModal()">&times;</button>
                        <h4 class="modal-title">Modificar datos de Rubro</h4>
                    </div>
                    <edit-rubro :idRubro="idRubro"></edit-rubro>
                </div>
            </div>
        </div>
    </section>
</div>
        
</template>

<script>
import auth from '../../../auth.js';
import EditRubro from './EditRubro.vue';
import NewRubro from './NewRubro.vue';

export default {
    props: {
        perfil: {
            type: Object,
            required: true
        },
    },
    data() {
        return {
            idRubro: null,
            showModificar: false,
            rubrosRegistrados: []
        }
    },
    beforeMount(){
        this.getRubrosRegistrados();
    },
    mounted() {
        this.$events.$on('cerrar', () =>this.closeModal());
    },
    components: {
        EditRubro, NewRubro
    },
    methods: {
        closeModal: function(){
            this.showModificar = false;
            this.getRubrosRegistrados();

        },
        modificar: function(id){
            this.idRubro=id
            this.showModificar = true
        },
        getRubrosRegistrados: function() {
            this.$http.get('api/proveedor/'+this.$route.params.userId+'/rubro/'
                ).then(response => {
                    this.rubrosRegistrados = response.data.rubros
                });
        },
        printRubros(rubros){
            var list = '';
            var primero = true;

            for(var item of rubros){
                if(primero)
                    list = list + ' ' + item.nombre
                else
                    list = list + ', ' + item.nombre
                primero = false
            }
            

            return list+ '.';
        },
    }
}
</script>