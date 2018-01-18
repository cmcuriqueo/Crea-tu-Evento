<template>
    <div>
        <h3 class="box-title">Usuarios</h3>
        <div class="input-group input-group-sm pull-right" style="width: 250px;">
            <input type="text" v-model="filterText"  class="form-control pull-right" @change="doFilter" placeholder="Nombre, Apellido o Email">
            <!--
            <div class="input-group-btn">
                <button type="submit" class="btn btn-default" @click="doFilter"><i class="fa fa-search"></i></button>
                <button class="btn btn-default" @click="resetFilter"><i class="fa fa-trash"></i></button>
            </div>
            -->
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