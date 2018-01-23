// componentes requeridos para rutas
import VueRouter from 'vue-router';
import auth from './auth.js';
import role from './config.js';

//templates
var Login = require('./components/Auth/Login');
var Home = require('./components/Home');

var NewTerminosCondiciones = require('./components/Administracion/TerminosCondiciones/New');
var EditTerminosCondiciones = require('./components/Administracion/TerminosCondiciones/Edit');
var ShowTerminosCondiciones = require('./components/Administracion/TerminosCondiciones/Show');

var Registrar = require('./components/Auth/Register');

var FormPasswordReset = require('./components/Auth/Password/Email');
var PasswordReset = require('./components/Auth/Password/Reset');

var Perfil = require('./components/Layouts/Perfil');

var IndexUsuarios = require('./components/Usuarios/Index');
var IndexProveedor = require('./components/Proveedores/Index');
var NewProveedor = require('./components/Proveedores/New');

var Form = require('./components/Proveedores/Form');

var NewPublicacion = require('./components/Proveedores/Publicaciones/NewPublicacion');
var EditPublicacion = require('./components/Proveedores/Publicaciones/EditPublicacion');
var ShowPublicacion = require('./components/Proveedores/Publicaciones/Show');

var IndexRubro = require('./components/Proveedores/Rubros/Index');
var IndexMensajes = require('./components/Mensajes/Index');
var ShowMensajes = require('./components/Mensajes/Show');

var NewRubro = require('./components/Proveedores/Prestaciones/NewRubro');
var EditRubro = require('./components/Proveedores/Prestaciones/EditRubro');

var IndexCategoria = require('./components/Proveedores/Categorias/Index');
var IndexCaracteristica = require('./components/Proveedores/Caracteristicas/Index');

var IndexLocalidad = require('./components/Localidades/Index');

var IndexReserva = require('./components/Proveedores/Reservas/Index');
var IndexCalificaciones = require('./components/Proveedores/Calificaciones/Index');

var IndexEstadisticas = require('./components/Estadisticas/Index');

var InternalServerError = require('./components/Errors/500');
var PageNotFound = require('./components/Errors/404');

let routes = [
		{
			path: "*", 
			component: PageNotFound
		},
		{
			path: '/',
			name: 'home',
			component: Home,
			meta: { requiresAuth: false },
			beforeEnter: guardRoute
		},
		{
			path: '/login',
			name: 'login',
			component: Login,
			beforeEnter: guardLogin
		},
		{
			path: '/terminos-condiciones/new',
			component: NewTerminosCondiciones,
			beforeEnter: guardRoute,
			meta: { Role: [role.ADMINISTRADOR], requiresAuth: true }		
		},
		{
			path: '/terminos-condiciones/edit',
			component: EditTerminosCondiciones,
			beforeEnter: guardRoute,
			meta: { Role: [role.ADMINISTRADOR], requiresAuth: true }		
		},
		{
			path: '/terminos-condiciones',
			component: ShowTerminosCondiciones,
			beforeEnter: guardRoute,
			meta: { requiresAuth: false }		
		},
		{
			path: '/registrar',
			name: 'registrar',
			component: Registrar,
			beforeEnter: guardLogin
		},
		{
			path: '/password-reset',
			name: 'passwordResetForm',
			component: FormPasswordReset,
			beforeEnter: guardLogin
		},
		{
			path: '/password/reset/:token',
			name: 'passwordReset',
			component: PasswordReset,
			beforeEnter: guardLogin
		},
		{
			path: '/usuario',
			component: IndexUsuarios,
			beforeEnter: guardRoute,
			meta: { Role: [role.ADMINISTRADOR, role.SUPERVISOR], requiresAuth: true }		
		},
		{
			path: '/proveedores',
			component: IndexProveedor,
			beforeEnter: guardRoute,
			meta: { Role: [role.ADMINISTRADOR, role.SUPERVISOR, role.OPERADOR], requiresAuth: true }	
		},
		{
			path: '/proveedor/new',
			component: NewProveedor,
			beforeEnter: guardRoute,
			meta: { Role: [role.ADMINISTRADOR, role.SUPERVISOR, role.OPERADOR], requiresAuth: true }	
		},
		{
			path: '/rubros',
			name: 'IndexRubro',
			component: IndexRubro,
			beforeEnter: guardRoute,
			meta: { Role: [role.ADMINISTRADOR, role.SUPERVISOR, role.OPERADOR], requiresAuth: true }				
		},
		{
			path: '/rubro/:idRubro/edit',
			name: 'EditRubro',
			component: EditRubro,
			beforeEnter: guardRoute,
			meta: { Role: [role.PROVEEDOR], requiresAuth: true }	
		},
		{
			path: '/rubro/new',
			name: 'NewRubro',
			component: NewRubro,
			beforeEnter: guardRoute,
			meta: { Role: [role.PROVEEDOR], requiresAuth: true}	
		},
		{
			path: '/categorias',
			component: IndexCategoria,
			beforeEnter: guardRoute,
			meta: { Role: [role.ADMINISTRADOR, role.SUPERVISOR], requiresAuth: true }	
		},
		{
			path: '/caracteristicas',
			component: IndexCaracteristica,
			beforeEnter: guardRoute,
			meta: { Role: [role.ADMINISTRADOR, role.SUPERVISOR], requiresAuth: true }	
		},
		{
			path: '/usuario/:userId/perfil',
			name: 'user',
			component: Perfil,
			beforeEnter: guardRoute,
			meta: { requiresAuth: true }

		},	
		{
			path: '/localidades',
			component: IndexLocalidad,
			beforeEnter: guardRoute,
			meta: { Role: [role.ADMINISTRADOR, role.SUPERVISOR], requiresAuth: true }	
		},
		//publicaciones
		{
			path: '/publicacion/new',
			name: 'new-publicacion',
			component: NewPublicacion,
			beforeEnter: guardRoute,
			meta: { Role: [role.PROVEEDOR], requiresAuth: true }	
		},
		{
			path: '/publicacion/:publicacionId',
			name: 'publicacion',
			component: ShowPublicacion,
			beforeEnter: guardRoute,
			meta: { requiresAuth: false }
		},
		{
			path: '/reservas',
			name: 'reservas',
			component: IndexReserva,
			props: { default: true, with_box: true },
			beforeEnter: guardRoute,
			meta: { requiresAuth: true }
		},
		{
			path: '/mensajes',
			name: 'mensajes',
			component: IndexMensajes,
			beforeEnter: guardRoute,
			meta: { requiresAuth: true }
		},
		{
			path: '/mensaje/:mensajeId',
			name: 'show-mensajes',
			component: ShowMensajes,
			beforeEnter: guardRoute,
			meta: { Role: [role.USUARIO, role.PROVEEDOR], requiresAuth: true }
		},
		{
			path: '/publicacion/:publicacionId/edit',
			name: 'edit-publicacion',
			component: EditPublicacion,
			beforeEnter: guardRoute,
			meta: { Role: [role.PROVEEDOR], requiresAuth: true }	
		},
		{
			path: '/calificaciones',
			name: 'calificaciones',
			component: IndexCalificaciones,
			props: { default: true, with_box: true },
			beforeEnter: guardRoute,
			meta: { Role: [role.USUARIO], requiresAuth: true }	
		},
		{
			path: '/estadisticas',
			name: 'estadisticas',
			component: IndexEstadisticas,
			beforeEnter: guardRoute,
			meta: { Role: [role.PROVEEDOR, role.ADMINISTRADOR, role.SUPERVISOR], requiresAuth: true }	
		},

		//errors
		{
			path: '/404',
			name: 'pageNotFound',
			component: PageNotFound,
			beforeEnter: guardRoute

		},
		{
			path: '/500',
			name: 'internalServerError',
			component: InternalServerError,
			beforeEnter: guardRoute

		}
 ];

/** guardRoute 
 *
 * Esta función chequea si el usuario esta 
 * en condiciones de acceder a las rutas
 *
 * Parametros
 * to: objeto de ruta a la que se desea acceder.
 * from: objeto de ruta de la que se proviene.
 * next: funcion utilizada para direccionar.
 *
 * Retorna 
 *	void
 */
function guardRoute (to, from, next) 
{
 	if (!auth.check()) {
 		if(to.matched.some(record => record.meta.requiresAuth)){
 			//se redirecciona al usuario al login si no esta autenticado
			next('/login');
		} else{
			//se chequea si el usuario posee acceso a la ruta
			checkRole(to, from, next);
		}
  	}
  	else 
  	{
  		//se chequea si el usuario posee acceso a la ruta
		checkRole(to, from, next);

	}
}

/** guardLogin 
 *
 * Esta función chequea si el usuario esta 
 * en condiciones de acceder al login
 *
 * Parametros
 * to: objeto de ruta a la que se desea acceder.
 * from: objeto de ruta de la que se proviene.
 * next: funcion utilizada para direccionar.
 *
 * Retorna 
 *	void
 */
function guardLogin(to, from, next) 
{
	if(auth.check()){//si se intenta aceder desde alguna url
		next(from.path);//se redirecciona a la ruta de donde se proviene 
	} else {
		next();// se autoriza a acceder a login
	}

}

/** checkRole 
 *
 * Esta función chequea los roles necesarios que debe de tener 
 * un usuario para acceder a una ruta determinada.
 *
 * Parametros
 * to: objeto de ruta a la que se desea acceder.
 * from: objeto de ruta de la que se proviene.
 * next: funcion utilizada para direccionar.
 *
 * Retorna 
 *	void
 */
function checkRole(to, from, next){
	//Se chequea si la ruta a la que se desea acceder posee restricciones por roles
	if (to.matched.some(record => record.meta.Role)) {
		let match = false;
		for (let rol of to.meta.Role){
			//Se compara el rol del usuario con el los reoles requeridos para acceder
			if(auth.checkRole(rol)){
				match= true;
				break;
			}
		}

		if(match) next();
		else next(from.path);
	} else {
		next();
	}
}

/** checkAuth 
 *
 * Esta función chequea si el usuario posee una sesión activa 
 *
 * Retorna (Boolean)
 *	true: indicando que el usuario tiene una sesión iniciada
 *  false: indicando que el usuario no tiene una sesión iniciada
 */
function checkAuth(){
	return auth.check();
}

export default new VueRouter({
	mode: 'history',
	routes,
	linkActiveClass: 'is-active'
});