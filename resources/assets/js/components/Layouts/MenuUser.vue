<template>
    <!-- User Account: style can be found in dropdown.less -->
    <li v-if="auth.user.authenticated" class="dropdown user user-menu">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <img :src="srcUrl" class="user-image" alt="User Image">
            <span class="hidden-xs">{{ auth.user.profile.name }}</span>
        </a>
        <ul class="dropdown-menu">
            <!-- User image -->
            <li class="user-header" v-if="auth.user.authenticated">
                <img :src="srcUrl" class="img-circle" alt="User Image">
                <p>
                    {{ auth.user.profile.name }}
                    <small>Usuario desde: {{formatData(auth.user.profile.created_at)}}</small>
                </p>
            </li>
            <!-- Menu Footer-->
            <li class="user-footer">
                <div class="pull-left">
                    <a @click="pushToProfile" class="btn btn-default btn-flat">Perfil</a>
                </div>
                <div class="pull-right">
                    <a  @click="logout" class="btn btn-default btn-flat">
                        Logout
                    </a>
                </div>
            </li>
        </ul>
    </li>
</template>
<script>
    import auth from '../../auth.js';
    import router from '../../routes.js';
    import moment from 'moment';

    export default {
        data() {
            return {
                auth: auth,
                pathUser: '',
                srcUrl: '',
                titlePath: 'Perfil',
                listPath : [{route: '/', name: 'Inicio'}, {route: '/usuario/'+auth.user.profile.id +'/perfil' , name: 'Perfil'}]
            }
        },
        mounted: function(){
            this.avatarUpdate();
            this.pathUser = '/usuario/'+auth.user.profile.id +'/perfil';
        },
        methods: {
            logout: function() {
                localStorage.removeItem('id_token')
                auth.user.authenticated = false
                auth.user.profile = null
                router.push('/login')
            },
            avatarUpdate: function(){
                this.srcUrl = '/storage/avatars/'+ auth.user.profile.usuario.avatar
            },
            formatData: function(value){
                moment.locale('es');
                return (value == null)
                    ? ''
                    : moment(value, 'YYYY-MM-DD').fromNow();
            },
            pushToProfile: function(){
                this.$events.fire('changePath', this.listPath, this.titlePath);
                router.push(this.pathUser);
            }
        }
    }
</script>