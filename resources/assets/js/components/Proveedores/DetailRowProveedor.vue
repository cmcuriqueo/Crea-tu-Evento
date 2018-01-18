<template>
    <div class="col-sm-12">
        <div class="col-sm-3">
            <img :src="'/storage/avatars/'+ rowData.user.usuario.avatar" width="150" height="150" class="img-responsive">
        </div>
        <div class="col-sm-5">
            <p class="inline-block">
                <label>Nombre: </label>
                <span>{{rowData.nombre}}</span>
            </p>
            <p class="inline-block">
                <label>Email: </label>
                <span>{{rowData.email}}</span>
            </p>
            <p class="inline-block">
                <label>N° de Cuit: </label>
                <span>{{rowData.cuit}}</span>
            </p>
            <p class="inline-block">
                <label>Estado: </label>
                <span>{{rowData.estado}}</span>
            </p>
            <p class="inline-block">
                <label>IIBB: </label>
                <span>{{rowData.ingresos_brutos}}</span>
            </p>
            <p class="inline-block">
                <label>Adjunto: </label>
                <a :href="'/storage/proveedores/'+ rowData.adjunto" target="_blank">
                    {{rowData.adjunto}}
                </a>
            </p>
        </div>
        <div class="col-sm-4">
            <p><label>DATOS DE USUARIO</label></p>
            <p class="inline-block">
                <label>Nombre: </label>
                <span>{{rowData.user.usuario.nombre}} {{rowData.user.usuario.apellido}}</span>
            </p>
            <p class="inline-block">
                <label>Email: </label>
                <span>{{rowData.user.email}}</span>
            </p>
            <p class="inline-block">
                <label>Genero: </label>
                <span>{{rowData.user.usuario.sexo == 'F' ? 'Femenino' : 'Masculino'}}</span>
            </p>
            <p class="inline-block">
                <label>Ubicaci&oacute;n: </label>
                <span>{{rowData.domicilio.localidad.nombre}}-{{rowData.domicilio.localidad.provincia.nombre}}</span>
            </p>
            <p class="inline-block">
                <label>Registrado Por: </label>
                <router-link :to="'/usuario/'+rowData.register_by_user.id+'/perfil'" tag="a">
                    {{rowData.register_by_user.usuario.nombre}} {{rowData.register_by_user.usuario.apellido}}
                </router-link>
            </p>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
          rowData: {
            type: Object,
            required: true
          },
          rowIndex: {
            type: Number
          }
        }
    }
</script>