<template>
<div>
    <form role="form">
        <div class="col-sm-12">
            <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('nombre')&&validarSubcategoria}">
                <div class="col-sm-12">
                    <label for="inputSubcategoria" class="control-label">Nombre</label><br>
                       <input name="nombre" v-validate="'required|min:4|max:22'" type="text" v-model="subcategoria.nombre" 
                        class="form-control" placeholder="Nombre">
                            <!-- validacion vee-validation -->
                    <span v-show="errors.has('nombre')&&validarSubcategoria" class="help-block">{{ errors.first('nombre') }}</span>
                    <!-- validacion api-->
                    <div class="text-red" v-if="errorsApi.nombre">
                        <div v-for="msj in errorsApi.nombre">
                            <p>{{ msj }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12">
            <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('categoria')&&validarSubcategoria}">
                <div class="col-sm-12">
                    <label class="control-label">Categoria</label><br>

                    <select 
                        name="categoria" 
                        @change="changeCategory()"
                        v-model="categoria_id" 
                        class="form-control" 
                        v-bind:disabled="categorias.length == 0"
                        v-validate="'required'">
                            <option disabled value="">Seleccione una categoria</option>
                            <option v-for="option in categorias" v-bind:value="option.value">
                                {{option.text}}
                            </option>
                    </select>
                    
                    <!-- validacion vee-validation -->
                    <span v-show="errors.has('categoria')&&validarSubcategoria" class="help-block">{{ errors.first('categoria') }}</span>
                    <!-- validacion api-->
                    <div class="text-red" v-if="errorsApi.categoria">
                        <div v-for="msj in errorsApi.categoria">
                            <p>{{ msj }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form> 
</div>
</template>



<script>
import auth from '../../../auth.js';
import vSelect from "vue-select";

export default {
    props: {
        subcategoria: {
            type: Object,
            required: true
        },
        nuevo: {
            type: Boolean,
            required: true
        },
        errorsApi: {
            type: Object,
            required: true
        }
    },
    data() {
        return {
            validarSubcategoria: false,
            categoria_id: '',
            categorias: []
        }
    },
    beforeMount(){
        this.getOptionsCategories();
    },
    mounted() {
        this.$events.$on('validarFormSubcategoria', () => this.validateBeforeSubmit());
    },
    created(){
        this.loadDefaultOption();  
    },
    beforeDestroy() {
            this.$events.$off('validarFormSubcategoria')
    },
    components: {
        vSelect
    },
    methods: {
        //form validation
        validateBeforeSubmit: function() {
            this.$validator.validateAll().then((result) => {
                if (result){
                    this.validarSubcategoria = false;
                    if (this.nuevo){
                        this.$emit('validadoNewSubcategoria')
                    }else{
                        this.$emit('validadoEditSubcategoria')
                    }
                } else {
                    this.validarSubcategoria = true;
                }
                return;
            }).catch(() => {
                
            });
        },
        changeCategory: function(){
            this.subcategoria.categoria_id = this.categoria_id;
        },
        //obtiene lista de categorias 
        getOptionsCategories: function() {
            this.$http.get('api/categoria/'
                ).then(response => {
                    let data = response.data
                    for (let categoria of data){
                        this.categorias.push({ value: categoria.id, text: categoria.nombre });
                    }
                })
        },
        loadDefaultOption: function(){
            this.categoria_id = this.subcategoria.categoria_id
        }
    }
}
</script>