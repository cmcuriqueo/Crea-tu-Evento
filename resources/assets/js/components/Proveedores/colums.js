export default [
    {
        name: 'nombre',
        title: 'Nombre',
        titleClass: 'text-center',
        dataClass: 'text-center'           
    },
    {
        name: 'email',
        title: 'Email',
        titleClass: 'text-center',
        dataClass: 'text-center'           
    },
    {
        name: 'cuit',
        title: 'N° Cuit',
        titleClass: 'text-center',
        dataClass: 'text-center'           
    },
    {
        name: 'register_by_user.usuario.nombre',
        title: 'Agregado Por',
        titleClass: 'text-center',
        dataClass: 'text-center'  
    },
    {
        name: 'created_at',
        title: 'Fecha',
        titleClass: 'text-center',
        dataClass: 'text-center',
        callback: 'formatData'
    },
    {
        name: 'estado',
        title: 'Estado',
        titleClass: 'text-center',
        dataClass: 'text-center'           
    },
    {
         name: '__slot:actions',   // <----
         title: 'Acciones',
         titleClass: 'center aligned',
         dataClass: 'center aligned'
    }
]