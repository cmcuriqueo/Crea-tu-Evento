  <template>
    <div class="col-sm-12">
        <div class="col-sm-3">
            <img :src="'/storage/avatars/'+ rowData.usuario.avatar" class="img-responsive" width="150" height="150">
        </div>
        <div class="col-sm-9">
            <p class="inline-block">
                <label>Nombre: </label>
                <span><template v-if="rowData.usuario.apellido != null && rowData.usuario.apellido != ''">{{rowData.usuario.apellido}}, </template><template v-if="rowData.usuario.nombre != null && rowData.usuario.nombre != ''">{{rowData.usuario.nombre}}</template></span>
            </p>
            <p class="inline-block">
                <label>Email: </label>
                <span>{{rowData.email}}</span>
            </p>
            <p class="inline-block">
                <label>Nombre de Usuario: </label>
                <span>{{rowData.name}}</span>
            </p>
            <p class="inline-block" v-if="rowData.usuario.fecha_nac != null && rowData.usuario.fecha_nac != '0000-00-00'">
                <label>Cumpleaños </label>
                <span>{{rowData.usuario.fecha_nac}}</span>
            </p>
            <p class="inline-block">
                <label>Genero: </label>
                <span>{{rowData.usuario.sexo == 'F' ? 'Femenino' : 'Masculino'}}</span>
            </p>
        </div>

    </div>
  </template>

  <script>
  export default {
    props: {
      rowData: {
        type: Object,
        required: true
      },
      rowIndex: {
        type: Number
      }
    },
    methods: {
      onClick (event) {
        console.log('my-detail-row: on-click')
      }
    },
  }
  </script>