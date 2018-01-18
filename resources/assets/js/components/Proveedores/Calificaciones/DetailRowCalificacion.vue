<template>
    <div class="col-sm-12">
        <div class="col-sm-6">
            <label>Calidad: </label> 
            <el-rate
				v-model="rowData.calidad"
				disabled
				show-score
				text-color="#ff9900"
				score-template="{value} puntos">
			</el-rate>
        </div>
        <div class="col-sm-6">
            <label>Relacion Precio Calidad: </label>
            <el-rate
				v-model="rowData.calidad_precio"
				disabled
				show-score
				text-color="#ff9900"
				score-template="{value} puntos">
			</el-rate>
        </div>
        <div class="col-sm-6">
            <label>Profesionalidad: </label>
            <el-rate
				v-model="rowData.profesionalidad"
				disabled
				show-score
				text-color="#ff9900"
				score-template="{value} puntos">
			</el-rate>
        </div>
        <div class="col-sm-6">
            <label>Respuesta: </label>
            <el-rate
				v-model="rowData.respuesta"
				disabled
				show-score
				text-color="#ff9900"
				score-template="{value} puntos">
			</el-rate>
        </div>
        <div class="col-sm-12">
            <label>Comentario: </label>
            <span>{{rowData.comentario}}</span>
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