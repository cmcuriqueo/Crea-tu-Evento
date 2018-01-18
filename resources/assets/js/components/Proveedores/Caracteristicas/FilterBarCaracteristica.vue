<template>
    <div>
        <h3 class="box-title">Caracteristicas de Rubros</h3>
        <div class="input-group input-group-sm pull-right" style="width: 150px;">
            <input type="text" v-model="filterText"  class="form-control pull-right" @change="doFilter" placeholder="Nombre">
        </div>
    </div>
</template>

  <script>
  export default {
    data () {
      return {
        filterText: ''
      }
    },
    methods: {
	    doFilter () {
	      this.$events.fire('filter-setCaracteristica', this.filterText)
	    }
    }
  }
</script>