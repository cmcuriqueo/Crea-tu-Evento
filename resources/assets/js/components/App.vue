<template>
    <div v-bind:class="classWrapper" >
        <bar v-if="showComponent"></bar>
        <barra-lateral v-if="showComponent"></barra-lateral>
        <div v-bind:class="classContentWrapper">
            <path-content 
                v-if="showComponent">
            </path-content>

            <router-view transition="fade"></router-view>
        </div>
    </div>
</template>
<script>
import auth from './../auth.js'
import routes from './../routes.js'
import Bar from './Layouts/Barra.vue'
import BarraLateral from './Layouts/BarraLateral.vue'
import Foo from './Layouts/Footer.vue'
import PathContent from './Layouts/Path.vue'

export default {
    data() {
        return {
            auth: auth,
            routes: routes,
            showComponentLayout: true
        }
    },
    ready() {
        auth.check()
    },
    mounted() {
        this.$events.listen('reloadComponents', this.reloadComponents);

        $(this).ajaxStart(function() { Pace.restart(); });
    },
    computed: {
        showComponent: function(){
            return this.showComponentLayout && (this.auth.user.authenticated || this.$route.name == 'home' || this.$route.name == 'publicacion');
        },
        classContentWrapper: function () {
            return {
                'content-wrapper': this.showComponent == true,
                '': !this.showComponent == true,
            }
        },
        classWrapper: function () {
            return {
                'wrapper': this.showComponent == true,
                '': !this.showComponent == true,
            }
        }
    },
    components: {Bar, BarraLateral, Foo, PathContent },
    methods: {
        reloadComponents(){
            this.showComponentLayout = false;
            setTimeout(this.showComponentes, 1);
            
        },
        showComponentes(){
            this.showComponentLayout = true;
        }
    }
}
</script>
