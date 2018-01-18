<template>
    <div>
        <div class="content" v-bind:style="styleContent">
            <p>
                <b>Nombre: </b>
                <template v-if="usuario.apellido != null && usuario.apellido != ''">{{usuario.apellido}}, </template><template v-if="usuario.nombre != null && usuario.nombre != ''">{{usuario.nombre}}</template>
            </p>

            <p v-if='usuario.localidad_id != null'>
                <b>Localidad: </b>
                    {{usuario.localidad.nombre}} - {{usuario.localidad.provincia.nombre}}
            </p>
        
            <p>
                <b>Sexo: </b>
               {{usuario.sexo=='F'?'Femenino':'Masculino'}}
            </p>
        
            <p v-if="usuario.fecha_nac != null && usuario.fecha_nac != '0000-00-00'">
                <b>Fecha de Nacimiento: </b>
            
                {{usuario.fecha_nac}}
            </p>
        </div>
        <div class="content" v-if="usuario.user.proveedor != null" v-bind:style="styleContent">
            <h3 style="text-align:center">Informaci&oacute;n de Proveedor</h3>
            <p>
                <b>Nombre: </b>
                {{usuario.user.proveedor.nombre}}
            </p>
            <p>
                <b>Cuit: </b>
                {{usuario.user.proveedor.cuit}}
            </p>
            <p>
                <b>Email de contacto: </b>
                {{usuario.user.proveedor.email}}
            </p>
            <p>
                <b>Telefono: </b>
                {{usuario.user.proveedor.telefono.cod_area}} - {{usuario.user.proveedor.telefono.numero}}
            </p>
            <p>
                <b>Ubicaci&oacute;n: </b>
                {{usuario.user.proveedor.domicilio.localidad.nombre}} - {{usuario.user.proveedor.domicilio.localidad.provincia.nombre}}<br>
                <b>Calle: </b>{{ usuario.user.proveedor.domicilio.calle }}  <b>Numero: </b>{{ usuario.user.proveedor.domicilio.numero }}  <b>Piso: </b>{{ usuario.user.proveedor.domicilio.piso }}
            </p>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            usuario: {
                required: true
            }
        },
        computed: {
            styleContent(){
                if(this.usuario.user.proveedor != null)
                    return {
                      minHeight:'130px'
                    }
            }
        }
    }
</script>