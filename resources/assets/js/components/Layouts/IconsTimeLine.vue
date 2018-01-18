<template>
	<i v-bind:class="iconsAction(accion, table)"></i>
</template>
<script>
    export default {
        props: {
            accion: { required: true}, 
            table: { required: true}
        },
        methods: {
            iconsAction: function(action, table){
                //if(action === 'update' && table === 'usuarios') return 'fa fa-check-circle bg-blue'
                //if(action === 'update' && table === 'usuarios') return 'fa fa-ban bg-blue'
                //if(action === 'update' && table === 'usuarios') return 'fa fa-check-square-o bg-blue'
    
                if(action === 'updateRole' && table === 'users') return 'fa fa-users bg-red';
                if(action === 'updatePassword' && table === 'users') return 'fa fa-unlock-alt bg-blue';
                if(action === 'updateAvatar' && table === 'usuarios') return 'fa fa-file-image-o bg-blue';
                if(action === 'destroy' && table === 'users') return 'fa fa-user-circle bg-blue';
                if(action === 'unlock' && table === 'users') return 'fa fa-unlock bg-blue';
                if(action === 'lock' && table === 'users') return 'fa fa-ban bg-red';
                if(action === 'update' && table === 'usuarios') return 'fa fa-edit bg-blue';
                if(action === 'create' && table === 'proveedores') return 'fa fa-user-plus';
                if(action === 'baja' && table === 'proveedores') return 'fa fa-user-times';
                if(action === 'rechazado' && table === 'proveedores') return 'fa fa-user-times';
                if(action === 'aprobado' && table === 'proveedores') return 'fa fa-check';
                if(action === 'reportado' && table === 'calificaciones') return 'fa fa-flag';
                    return 'fa fa-check-circle bg-blue';
            }
        }
    }
</script>