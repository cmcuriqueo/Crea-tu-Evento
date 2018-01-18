/** Objeto user utilizado para gurdar el estado de sesion y la informacion de este.
 *
 */

export default {
    user: {
        authenticated: false,
        profile: null
    },
    check() {
        if (localStorage.getItem('id_token') !== null) {
            Vue.http.get(
                'api/user',
            ).then(response => {
                this.user.authenticated = true
                this.user.profile = response.data.data
                return true;
            })
        } else {
        	return false;
        }
    },
    signout() {
        localStorage.removeItem('id_token')
        this.user.authenticated = false
        this.user.profile = null
    }
}
