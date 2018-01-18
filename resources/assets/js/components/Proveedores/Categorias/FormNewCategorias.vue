<template>
    <div>
        <div class="modal-body">
            <form role="form">

                <div class="col-sm-12">
                    <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('nombre')&&validarCategorias}">
                        <div class="col-sm-12">
                            <label for="inputCategoria" class="control-label">Rubro</label><br>
                               <input name="nombre" v-validate="'required|min:4|max:22'" type="text" v-model="nombre" class="form-control" placeholder="Nombre Categoria">
                                    <!-- validacion vee-validation -->
                            <span v-show="errors.has('nombre')&&validarCategorias" class="help-block">{{ errors.first('nombre') }}</span>
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
                    <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('categoria')&&validarCategorias}">
                        <div class="col-sm-12">
                            <label for="inputCategoria" class="control-label">Categoria</label><br>

                            <input type="checkbox" v-model="newCategoria"> Nueva Categoria

                            <template v-if="newCategoria">
                                <input 
                                    name="categoria" 
                                    v-validate="'required|min:4|max:22'" 
                                    type="text" 
                                    v-model="newCategoriaName" 
                                    class="form-control" 
                                    placeholder="Nombre Categoria">
                            </template>

                            <template v-else>
                                <select 
                                    name="categoria" 
                                    @change="changeCategory()"
                                    v-model="categoria_id" 
                                    class="form-control" 
                                    v-validate="'required'">
                                        <option disabled value="">Seleccione una categoria</option>
                                        <option v-for="option in categorias" v-bind:value="option.value">
                                            {{option.text}}
                                        </option>
                                </select>
                            </template>

                            <!-- validacion vee-validation -->
                            <span v-show="errors.has('categoria')&&validarCategorias" class="help-block">{{ errors.first('categoria') }}</span>
                            <!-- validacion api-->
                            <div class="text-red" v-if="errorsApi.categoria_nombre">
                                <div v-for="msj in errorsApi.categoria_nombre">
                                    <p>{{ msj }}</p>
                                </div>
                            </div>
                            <div class="text-red" v-if="errorsApi.categoria_id">
                                <div v-for="msj in errorsApi.categoria_id">
                                    <p>{{ msj }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-if="newCategoria" class="col-sm-12">
                    <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('proveedor')&&validarCategorias}">
                        <div class="col-sm-12">
                            <label  class="control-label">Tipo de Categoria</label><br>
                            <select       
                                name="proveedor"                  
                                v-model="tipo_proveedor"
                                class="form-control" 
                                v-validate="'required'">
                                <option disabled value="">Seleccione</option>
                                <option v-for="option in opcionesTipo" v-bind:value="option.value">
                                    {{ option.text }}
                                </option>
                            </select>
                            <!-- validacion vee-validation -->
                            <span v-show="errors.has('proveedor')&&validarCategorias" class="help-block">{{ errors.first('proveedor') }}</span>
                            <!-- validacion api-->
                            <div class="text-red" v-if="errorsApi.tipo_proveedor">
                                <div v-for="msj in errorsApi.tipo_proveedor">
                                    <p>{{ msj }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-12">
                    <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('subcategoria')&&validarCategorias}">
                        <div class="col-sm-12">
                            <label for="inputCategoria" class="control-label">Subcategoria</label><br>

                            <template v-if="!newCategoria">
                                <input newCategoria type="checkbox" v-model="newSubcategoria"> Nueva Subcategiria
                            </template>

                            <template v-if="newCategoria || newSubcategoria ">
                                <input 
                                    name="subcategoria" 
                                    v-validate="'required|min:4|max:22'" 
                                    type="text" 
                                    v-model="newSubcategoriaName" 
                                    class="form-control" 
                                    placeholder="Nombre Subcategoria">
                            </template>
                            <template v-if="!newCategoria && !newSubcategoria">
                                <select 
                                    name="subcategoria" 
                                    v-model="subcategoria_id" 
                                    class="form-control" 
                                    v-bind:disabled="categoria_id == ''"
                                    v-validate data-vv-rules="required">
                                    <option disabled value="">Seleccione una subcategoria</option>
                                    <option v-for="option in subcategorias" v-bind:value="option.value">{{option.text}}</option>
                                </select>
                            </template>

                            <!-- validacion vee-validation -->
                            <span v-show="errors.has('subcategoria')&&validarCategorias" class="help-block">{{ errors.first('subcategoria') }}</span>
                            <!-- validacion api-->
                            <div class="text-red" v-if="errorsApi.subcategoria_nombre">
                                <div v-for="msj in errorsApi.subcategoria_nombre">
                                    <p>{{ msj }}</p>
                                </div>
                            </div>
                            <div class="text-red" v-if="errorsApi.subcategoria_id">
                                <div v-for="msj in errorsApi.subcategoria_id">
                                    <p>{{ msj }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="col-sm-12">
                        <label for="inputCaracteristica" class="control-label">Caracteristicas: </label><br>
                        <div class="direct-chat-messages" style="height: 130px;">
                            <div class="direct-chat-msg">
                              <div v-for="caracteristica in caracteristicas">
                                <div class="col-sm-3">
                                  <input type="checkbox" @click="selected(caracteristica, $event)"> {{caracteristica.nombre}}
                                </div>

                              </div>
                            </div>
                        </div>
                    </div>
                </div>  

            </form> 
            <div class="col-sm-12 box-footer clearfix" style="text-align:center;">
                <button class="btn btn-default" @click="closeModal()">
                    <i class="glyphicon glyphicon-chevron-left"></i>
                    Atras
                </button>
                <button @click="validateBeforeSubmit()" type="button" class="btn btn-primary">
                    Guargar
                </button>
            </div>
        </div>
    </div>   
</template>
<script>


export default {
    data() {
        return {
            categorias: [],
            subcategorias: [],
            nombre: '',
            newCategoriaName: '',
            newSubcategoriaName: '',
            categoria_id: '',
            subcategoria_id: '',
            tipo_proveedor: '',
            newCategoria: true,
            newSubcategoria: true,
            errorsApi: {},
            validarCategorias: false,
            caracteristicas:[],
            options_caracteristicas: [],
            opcionesTipo: [
                  { text: 'Servicios', value: 'Servicio' },
                  { text: 'Productos', value: 'Producto' }
                ]
        }
    },

    beforeMount: function(){
        //selected data
        this.getCategorias();
        this.getCaracteristicas();
    },
    methods: {
        //envio de formulario de modificación de informacion de usuario
        sendForm: function() {
            var formData = { };
            if(!this.newSubcategoria){
                formData= {
                    nombre: this.nombre,
                    caracteristicas: this.options_caracteristicas,
                    subcategoria_id: this.subcategoria_id}

            } else {
                if(!this.newCategoria){
                    formData= 
                        {
                        nombre: this.nombre,
                        caracteristicas: this.options_caracteristicas,
                        subcategoria_nombre: this.newSubcategoriaName,
                        categoria_id: this.categoria_id
                        }
                } else {
                    formData= 
                        {
                        nombre: this.nombre,
                        caracteristicas: this.options_caracteristicas,
                        categoria_nombre: this.newCategoriaName,
                        subcategoria_nombre: this.newSubcategoriaName,
                        tipo_proveedor: this.tipo_proveedor
                        }
                }
            }

            console.log(formData)
            this.$http.post(
                'api/rubro/',
                    formData
                ).then(response => {
                    this.$events.fire('reloadIndexCategoria')
                    this.$events.fire('reloadIndexSubcategoria')
                    this.$events.fire('reloadIndexRubro')
                    this.closeModal(),
                    this.errorsApi= {},
                    this.$toast.success({
                        title:'¡Cambios realizados!',
                        message:'Se han realizado correctamente los cambios. :D'
                    });
                }, response => {
                    this.$toast.error({
                        title:'¡Error!',
                        message:'No se han podido guardar los cambios. :('
                    });
                    if(response.status === 422)
                    {
                        this.errorsApi = response.body;
                    }
                })
        },

        //form validation
        validateBeforeSubmit: function() {
            this.$validator.validateAll().then((result) => {
                if (result) {
                    this.validarCategorias = false;
                    this.sendForm();
                } else {
                    this.validarCategorias = true;
                }
                return;
            }).catch(() => {
                
            });
        },
        getCategorias: function(){
            this.$http.get('api/categoria/')
                .then(response => {
                    let data = response.data.data
                    for (let categoria of data){
                        this.categorias.push({ value: categoria.id, text: categoria.nombre });
                    }
                }, response => {
                    this.$toast.error({
                        title:'¡Error!',
                        message:'No se han podido cargar las opciones. :('
                    });
                    this.closeModal();
                })
        },
        changeCategory: function(){
            this.subcategorias = [];
            this.subcategoria_id = "";
            this.$http.get('api/categoria/' +this.categoria_id
                ).then(response => {
                    let data = response.data.data
                    for (let subcategoria of data.subcategorias){
                        this.subcategorias.push({ text: subcategoria.nombre, value: subcategoria.id });
                    }
                })
        },
        getCaracteristicas: function(){
            this.$http.get('api/caracteristica')
                .then(response => {
                    this.caracteristicas = response.data.data
                }, response => {
                    this.$toast.error({
                        title:'¡Error!',
                        message:'No se han podido cargar las caracteristicas. :('
                    });
                    this.closeModal();
                })
        },
        selected(caracteristica,e){

            if (e.toElement.checked){
                    this.options_caracteristicas.push(caracteristica.id)
            }else{
                for (var i = 0; i < this.options_caracteristicas.length; i++)
                {
                    if (this.options_caracteristicas[i] == caracteristica.id){

                        this.options_caracteristicas.splice(i, 1)
                    }
                }
            }

        },
        closeModal: function(){
            this.$events.fire('cerrar');
        }

    },
    watch: {
        'newCategoria' (){
            if(this.newCategoria)
            {
                this.newSubcategoria = true;
            }
        }
    },
}

</script>