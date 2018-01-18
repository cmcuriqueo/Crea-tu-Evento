<template>
    <div>
        <div class="input-group input-group-sm pull-right" style="width: 250px;">
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
	      this.$events.fire('filter-set', this.filterText)
	    }
    }
  }
</script>