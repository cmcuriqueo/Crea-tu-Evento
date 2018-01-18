<template>
    <div class="col-sm-12" >
        <div  class="col-sm-2"></div>
        <div class="col-sm-2"  style="text-align: center;">
             <img 
             :src="'/storage/avatars/'+ proveedor.user.usuario.avatar" 
             max-width="80" 
             max-height="80" 
             class="img-responsive">
        </div>
        <div class="col-sm-6">
        <br><br>
            <p class="inline-block">
                <label>Apellido y Nombre: </label>
                <span>{{proveedor.user.usuario.apellido}}, {{proveedor.user.usuario.nombre}} </span>
            </p>
            <p class="inline-block">
                <label>N° de Cuit: </label>
                <span>{{proveedor.cuit}}</span>
            </p>
            <p class="inline-block">
                <label>IIBB: </label>
                <span>{{proveedor.ingresos_brutos}}</span>
            </p>
        </div>
        <div  class="col-sm-2"></div>
    </div>
</template>

<script>
    export default {
        props: {
          proveedor: {
            type: Object,
            required: true
          }
        }
 }
</script>