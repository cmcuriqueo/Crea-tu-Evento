<template>
    <div>
        <h3 class="box-title">Proveedores Pendientes</h3>
        <div class="input-group input-group-sm pull-right" style="width: 250px;">
            <input type="text" v-model="filterText"  class="form-control pull-right" @change="doFilter" placeholder="Nombre o Email">
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
	      this.$events.fire('filter-set', this.filterText)
	    }
    }
  }
</script>