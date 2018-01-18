<template>
    <div class="default-content">
        <div class="content"> 
            <div class="row">
                <div class="col-xs-12">
                    <div class="box box-primary">
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div v-html="terminoCondicion.cuerpo"></div>
                        </div>
                        <div class="box-footer clearfix">
                            <div style="text-align:center;">
                                <button class="btn btn-default" @click="goToBack()">
                                    Atras
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import route from './../../../routes.js';
  
    export default {

        data() {
            return {
                terminoCondicion: '',
                listPath : [{route: '/', name: 'Inicio'}, {route: '/terminos-condiciones', name: 'Terminos y Condiciones'}],
            }
        },
        beforeMount(){
            this.getTerm();
        },
        mounted(){
            this.$events.fire('changePath', this.listPath, 'Terminos y Condiciones');
        },
        methods: {
            getTerm: function(){
                this.$http.get('api/terminos-condiciones/')
                .then(response =>{
                        this.terminoCondicion = response.data
                        this.loaded = true;
                    }, response => {
                        if(response.status === 404){
                            this.$toast.error({
                                title:'¡Error!',
                                message:'No se ha cargado correctamente.'
                            });
                        }
                    });
            },
            goToBack(){
                route.go(-1);
            }
        }
    }

</script>