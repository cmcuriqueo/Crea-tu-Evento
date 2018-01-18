export default [
                    {
                        name: 'usuario.nombre',
                        title: 'Nombre',
                        titleClass: 'text-center',
                        dataClass: 'text-center'           
                    },
                    {
                        name: 'usuario.apellido',
                        title: 'Apellido',
                        titleClass: 'text-center',
                        dataClass: 'text-center'
                    },
                    {
                        name: 'usuario.sexo',
                        title: 'Genero',
                        titleClass: 'text-center',
                        dataClass: 'text-center',
                        callback: 'genderLabel'
                    },
                    {
                        name: 'email',
                        title: 'Email',
                        //sortField: 'email',
                        titleClass: 'text-center',
                        dataClass: 'text-center'
                    },
                    {
                        name: 'created_at',
                        title: 'Fecha de registro',
                        titleClass: 'text-center',
                        dataClass: 'text-center',
                        callback: 'formatDate|DD-MM-YYYY'
                    },
                    {
                        name: 'estado',
                        title: 'Estado',
                        titleClass: 'text-center',
                        dataClass: 'text-center',
                        callback: 'formatEstado'
                    },
                    {
                        name: '__slot:actions',   // <----
                        title: 'Acciones',
                        titleClass: 'center aligned',
                        dataClass: 'center aligned'
                    }
                ]