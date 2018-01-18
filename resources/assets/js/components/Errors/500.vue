<template>
<div class="default-content">
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
    <!-- Content Header (Page header) -->
        <path-content :titleContent="titleContent"></path-content>

	    <!-- Main content -->
	    <section class="content">

			<div class="error-page">
				<h2 class="headline text-red">500</h2>

				<div class="error-content">
					<h3><i class="fa fa-warning text-red"></i>¡Vaya! Algo salió mal.</h3>
					<p>
						Vamos a trabajar en la reparación del problema de inmediato. 
						Mientras tanto, usted puede
                        <router-link tag="a" to="/home">
                            regresar a home
                        </router-link>
					</p>
				</div>
			</div>
			<!-- /.error-page -->
	    </section>
		<!-- /.content -->
    </div>
</div>
</template>
<script>
import PathContent from './../Layouts/Path.vue';

export default {
    data(){
        return {

            titleContent: 'Internal Server Error'
        }
    },
    components:{PathContent}
}
</script>
