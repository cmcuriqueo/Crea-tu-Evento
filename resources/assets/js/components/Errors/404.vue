<template>
<div class="default-content">
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
    <!-- Content Header (Page header) -->
        <path-content v-if="auth.user.authenticated" :titleContent="titleContent"></path-content>

        <!-- Main content -->
        <section class="content">
            <div class="error-page">
                <h2 class="headline text-yellow"> 404</h2>

                <div class="error-content">
                    <h3><i class="fa fa-warning text-yellow"></i> ¡Vaya! Página no encontrada.</h3>

                    <p>
                        No pudimos encontrar la página que buscabas.
                        <router-link  v-if="auth.user.authenticated" tag="a" to="/home">
                            regresar a home
                        </router-link>
                        <router-link v-else tag="a" to="/login">
                            regresar a iniciar sesión
                        </router-link>
                    </p>
                </div>
                <!-- /.error-content -->
            </div>
            <!-- /.error-page -->
        </section>
        <!-- /.content -->
    </div>
</div>
</template>
<script>
import PathContent from './../Layouts/Path.vue';
import auth from '../../auth.js';

export default {
    data(){
        return {
            auth: auth,
            titleContent: 'Page not found'
        }
    },
    components:{PathContent}
}
</script>
