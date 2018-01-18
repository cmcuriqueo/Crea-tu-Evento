<template>
    <div class="default-content">
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <new-user></new-user>
                    <div class="box">
                        <div class="box-header with-border">
                            <h4>Alta Proveedor</h4>
                        </div>
                        <div class="box-body table-responsive no-padding">
                        	<form-prov 
                                :proveedor="proveedor" 
                                :errorsApi="errorsApi"
                                @validadoProveedor="sendForm()"
                                :nuevo= "nuevo">
                                
                            </form-prov>
                            <div class="col-sm-12 box-footer clearfix" style="text-align:center;">
                                <button @click="goBack()" class="btn btn-default">
                                    <i class="glyphicon glyphicon-chevron-left"></i>
                                    Atras
                                </button>
                                <button @click="validateBeforeSubmit()" type="button" class="btn btn-primary">
                                    Guargar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>    
        </section>
    </div> 
</template>


<script>
import route from '../../routes.js';
import auth from '../../auth.js';
import vSelect from "vue-select";
import FormProv from './Form.vue';
import PathContent from '../Layouts/Path.vue';
import NewUser from '../Auth/RegisterUser.vue';

export default {
    data() {
        return {
            titlePath: 'Nuevo Proveedor',
            listaPath: [{route: '/', name: 'Inicio'},{route: '/proveedores', name: 'Proveedores'},{route: '/proveedor/new', name: 'Nuevo Proveedor'}],
            validarProveedor: false,
            proveedor: { 
                user_id: null,
                nombre: null,
                cuit: null,
                ingresos_brutos: null,
                email: null,
                dni: null,
                telefono: '',
                domicilio: {
                    calle: null,
                    numero: null,
                    piso: null,
                    localidad_id: null
                }
            },
            error: false,
            nuevo: true, 
            errorsApi: {}
        }
    },
    components: {
        FormProv, PathContent, NewUser
    },
    mounted() {
            this.$events.fire('changePath', this.listaPath, this.titlePath);
            //this.$events.$on('validadoProveedor', () =>this.sendForm());
    },
    methods: {
        //envio de formulario de modificación de informacion de usuario
        sendForm: function() {
            this.$http.post(
                'api/proveedor', 
                {
                    user_id: this.proveedor.user_id.value,
                    nombre: this.proveedor.nombre,
                    cuit: this.proveedor.cuit,
                    ingresos_brutos: this.proveedor.ingresos_brutos,
                    email: this.proveedor.email,
                    adjunto: this.proveedor.dni,
                    calle: this.proveedor.domicilio.calle,
                    numero: this.proveedor.domicilio.numero,
                    piso: this.proveedor.domicilio.piso,
                    telefono: this.proveedor.telefono,
                    localidad_id: this.proveedor.domicilio.localidad_id.value

                })
                .then(response => {
                    this.$emit('reload')
                    this.errorsApi={},
                    this.$toast.success({
                        title:'¡Cambios realizados!',
                        message:'Se han realizado correctamente los cambios. :D'
                    });
                    this.resetForm();
                    this.goBack();
                }, response => {
                    this.validarProveedor= false;
                    this.$toast.error({
                        title:'¡Error!',
                        message:'No se han podido guardar los cambios. :('
                    });
                    if(response.status === 422)
                    {
                        this.errorsApi = response.body;
                    }
                })
        },
          //form validation
        validateBeforeSubmit: function() {                 
                    this.validarProveedor = true;
                    this.$events.fire('validarFormProveedor')

        },
        resetForm() {
            this.proveedor = {
                user_id: null,
                nombre: null,
                cuit: null,
                ingresos_brutos: null,
                email: null,
                adjunto: null,
                domicilio: {
                    habilitacion: null,
                    calle: null,
                    numero: null,
                    piso: null,
                    localidad_id: null
                }
            }
        },
        goBack(){
            route.go(-1)
        }
    }
}
</script>
