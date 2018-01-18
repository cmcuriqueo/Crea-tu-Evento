  <template>
    <div class="col-sm-12">
    Caracteristicas: 
        <div class="custom-caracteristicas" v-for="item in rowData.caracteristicas">
            <div class="col-sm-3">
              <li>
                  {{item.nombre}}
              </li>
            </div>
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
        console.log('my-detail-row: on-click', event.target)
      }
    },
  }
  </script>