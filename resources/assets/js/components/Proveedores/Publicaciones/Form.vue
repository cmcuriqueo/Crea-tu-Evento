<template>
    <form role="form" enctype="multipart/form-data">
        <div class="col-sm-12">
            <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('titulo')&&validarPublicacion}">
                <div class="col-sm-12">
                    <label for="titulo" class="control-label">Titulo (*) </label>
                    <input 
                        name="titulo"  
                        v-validate="'required|min:5'" 
                        type="text" class="form-control" 
                        v-model="publicacion.titulo" 
                        placeholder="Titulo">
                    <!-- validacion vee-validation -->
                    <span v-show="errors.has('titulo')&&validarPublicacion" class="help-block">{{ errors.first('titulo') }}</span>
                    <!-- validacion api-->
                    <div class="text-red" v-if="errorsApi.titulo">
                        <div v-for="msj in errorsApi.titulo">
                            <p>{{ msj }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div :class="{'form-group has-feedback': true, 'form-group has-error': (errors.has('categoria') || errors.has('subcategoria') ) &&validarPublicacion}">
                <div class="col-sm-12"><label class="control-label">Categoria (*)</label><br></div>
                <div class="col-sm-6">

                    <select
                        name="categoria"                  
                        v-model="categoria_id"
                        class="form-control" 
                        v-bind:disabled="categorias.length == 0"
                        v-validate="'required'"
                        @change="changeCategory()">
                        <option value="" disabled="">Seleccione una categoria</option>
                        <option v-for="option in categorias" v-bind:value="option.value">
                            {{ option.text }}
                        </option>
                    </select>
                    <!-- validacion vee-validation -->
                    <span v-show="errors.has('categoria')&&validarPublicacion" class="help-block">{{ errors.first('categoria') }}</span>
                </div>
                <div class="col-sm-6" v-if="categoria_id != ''">

                    <select
                        name="subcategoria"                  
                        v-model="publicacion.subcategoria_id"
                        v-bind:disabled="subcategorias.length == 0"
                        class="form-control" 
                        v-validate="'required'">
                        <option value="" disabled="">Seleccione una subcategoria</option>
                        <option v-for="option in subcategorias" v-bind:value="option.value">
                            {{ option.text }}
                        </option>
                    </select>
                    
                    <!-- validacion vee-validation -->
                    <span v-show="errors.has('subcategoria')&&validarPublicacion" class="help-block">{{ errors.first('subcategoria') }}</span>
                    <!-- validacion api-->
                    <div class="text-red" v-if="errorsApi.subcategoria_id">
                        <div v-for="msj in errorsApi.subcategoria_id">
                            <p>{{ msj }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('fotos[]')}">
                <div class="col-sm-12">
                    <label for="fotos" class="control-label" style="text-align: center;">Fotos <i class="fa fa-file-image-o"></i></label><br>
                    <input v-if="nuevo"
                        data-vv-as="fotos"
                        type="file"
                        accept="image/*"
                        ref="files"
                        v-validate.reject="'required|ext:jpg,png,jpeg|size:4096'" 
                        @change="onFilesChange" 
                        name="fotos[]">

                    <input v-else
                        data-vv-as="fotos"
                        type="file"
                        accept="image/*"
                        ref="files"
                        v-validate.reject="isRequiredInputFile ? 'required|ext:jpg,png,jpeg|size:4096' : 'ext:jpg,png,jpeg|size:4096'" 
                        @change="onFilesChange" 
                        name="fotos[]"><br>

                    <div v-if="publicacion.fotos" v-for="(fotos,index) in publicacion.fotos">
                        <div v-if="fotos.nombre != null" class="col-sm-3">
                            <div class="box" style="height: 120px; width: 100px;">
                                <div  class="box-body" style="display: block;">
                                    <img :src="'/storage/proveedores/publicaciones/'+fotos.nombre" class="img-thumbnail" style="height: 80px; width: 80px;">
                                    <button type="button" class="btn btn-box-tool pull-right" @click="deleteImage(index)">
                                        <i class="fa fa-close"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3 with-border" v-if="src" v-for="(fotos,index) in src">
                        <div class="box" style="height: 120px; width: 100px;">
                             <div class="box-body" style="display: block;">
                                <img :src="fotos" class="img-thumbnail" style="height: 80px; width: 80px;">
                                <button type="button" class="btn btn-box-tool pull-right" @click="deleteImageSrc(index)">
                                    <i class="fa fa-close"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- validacion vee-validation -->
                    <span v-show="errors.has('fotos[]')" class="help-block">{{ errors.first('fotos[]') }}</span>
                    <!-- validacion api-->
                    <div class="text-red" v-if="errorsApi.fotos">
                        <div v-for="msj in errorsApi.fotos">
                            <p>{{ msj }}</p>
                        </div>
                    </div>
                </div>                    
            </div>

            <div v-if="rubros_id&&(caracteristicas.length>0||caracteristicas_no.length>0)">
                <div class="col-sm-8">
                    <label for="caracteristicas" class="control-label">Caracteristicas</label><br>
                    <div class="direct-chat-messages col-sm-12" style="height: 180px;">
                        <div v-for="caracteristica in caracteristicas">
                            <div class="col-sm-12">
                                <div class="col-sm-5">
                                    <input type="checkbox" id="checkbox1" @click="selected(caracteristica, $event)" checked> {{caracteristica.nombre}}
                                </div>
                                <div v-if="caracteristica.adicional==true" class="col-sm-7">
                                    <input 
                                        name="inf_adicional"
                                        type="text" class="form-control"
                                        :value="caracteristica.pivot.informacion"
                                        @change="c_adicional(caracteristica,$event)">
                                </div>
                            </div>
                        </div>
                        <div v-for="caracteristica in caracteristicas_no">
                            <div class="col-sm-12">
                                <div class="col-sm-5">
                                    <input type="checkbox" id="checkbox" @click="selected(caracteristica, $event)"> {{caracteristica.nombre}}
                                </div>
                                <div v-if="caracteristica.adicional==true" class="col-sm-7">
                                    <input 
                                        name="inf_adicional"
                                        type="text" class="form-control"
                                        @change="c_adicional(caracteristica,$event)" >
                               </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('descripcion')&&validarPublicacion}">
                <div class="col-sm-12">
                    <label for="descripcion" class="control-label">Descripci&oacute;n (*)</label>

                    <vue-editor 
                        data-vv-name="descripcion"  
                        v-model="publicacion.descripcion" 
                        v-validate="'required|min:20|max:25000'"
                        :editorToolbar="customToolbar">
                    </vue-editor>

                    <!-- validacion vee-validation -->
                    <span v-show="errors.has('descripcion')&&validarPublicacion" class="help-block">{{ errors.first('descripcion') }}</span>
                    <!-- validacion api-->
                    <div class="text-red" v-if="errorsApi.descripcion">
                        <div v-for="msj in errorsApi.descripcion">
                            <p>{{ msj }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('precio')&&validarPublicacion}">
                <div class="col-sm-4">
                    <label for="precio" class="control-label">Precio desde</label><br>
                    <div class="input-group">
                        <vue-numeric currency="$" class="form-control" separator="," v-bind:precision="2" v-model="publicacion.precio"  v-validate="'required|min_value:1'" data-vv-name="precio"></vue-numeric>
                    </div>
                    <!-- validacion vee-validation -->
                    <span v-show="errors.has('precio')&&validarPublicacion" class="help-block"> El campo precio es requerido.</span>
                    <!-- validacion api-->
                    <div class="text-red" v-if="errorsApi.precio">
                        <div v-for="msj in errorsApi.precio">
                            <p>{{ msj }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group has-feedback">
                <div class="col-sm-12">
                    <label class="control-label">Oferta</label><br>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" v-model="ofertaChecked"> {{ ofertaChecked ? 'Si' : 'No'}}
                        </label>
                    </div>
                </div>
            </div>

            <div v-if="ofertaChecked">
                <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('descuento')&&validarPublicacion}">
                    <div class="col-sm-3">
                        
                        <label for="descuento" class="control-label">Porsentaje de descuento </label>
                        <div class="input-group">
                            <input type="text" v-model="publicacion.descuento" name="descuento" class="form-control">
                            <div class="input-group-addon">
                                %
                            </div>
                        </div>

                    </div>
                </div>

                <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('fecha')&&validarPublicacion}">
                    <div class="col-sm-12">
                        <label for="fecha" class="control-label">Fecha de finalización de la promoción</label>
                        <input
                            name="fecha" 
                            type="date"
                            v-model="publicacion.fecha_finalizacion" 
                            class="form-control">
                    </div>
                </div>

                <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('oferta')&&validarPublicacion}">
                    <div class="col-sm-12">
                        <label for="oferta" class="control-label">Descripcion de la promoci&oacute;n</label>
                        <textarea
                            name="oferta" 
                            type="text"
                            style="min-height:100px;" 
                            v-model="publicacion.oferta" 
                            class="form-control">
                        </textarea>
                    </div>
                </div>
            </div>
        </div>
    </form>
</template>
<style type="text/css">
    /* Enter and leave animations can use different */
    /* durations and timing functions.              */
    .slide-fade-enter-active {
      transition: all .5s ease;
    }
    .slide-fade-leave-active {
      transition: all .5s cubic-bezier(1.0, 0.5, 0.8, 1.0);
    }
    .slide-fade-enter, .slide-fade-leave-to
    /* .slide-fade-leave-active below version 2.1.8 */ {
      transform: translateX(10px);
      opacity: 0;
    }
</style>
<script>
    import route from './../../../routes.js'
	import auth from '../../../auth.js'
	import vSelect from "vue-select";
    import { VueEditor } from 'vue2-editor'

	export default {
		props: {
			publicacion: {
				type: Object,
				required: true
			},
			validarPublicacion: {
				type: Boolean,
				required: true
			},
			errorsApi: {
				required: true
			},
            nuevo: {
                type: Boolean,
                required: true
            },
            rubros_id: {
                type: Array
            }
		},
		data() {
			return {
				auth: auth,
				rubros: [],
                src: [],
                customToolbar: [
                      ['bold', 'italic', 'underline'],
                      [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                      [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                      [{ 'color': [] }],
                      [{ 'font': [] }],
                      [{ 'align': [] }],
                      ['clean']
                  ],
                isRequiredInputFile: false,
                categoria_id: '',
                ofertaChecked: false,
                categorias: [],
                subcategorias: [],
                options_caracteristicas: [],
                caracteristicas: [],
                caracteristicas_no: [],
                rubro_caracteristicas:{type: Object}
			}        
		},
        beforeMount(){
            this.getOptionsRubros();
            this.getOptionsCategorias();
        },
		mounted(){
			this.$events.on("validarFormPublicacion", () => this.validateSubmit())
		},
        created() {
            this.loadDefaultOptions();
        },
		components: { vSelect, VueEditor },
		methods: {
			getOptionsRubros: function() {
	            this.$http.get('api/rubro/'
	                ).then(response => {
                        var data = response.data;
                        for (var rubro of data) {
                            for (var id of this.rubros_id ) {
                                if(rubro.id == id){
                                    this.pushCaracteristicas(rubro.caracteristicas);
                                }
                            }
                            
                        }
                        this.noselected(this.caracteristicas, this.publicacion.caracteristicas)
                    })

            },
            getOptionsCategorias: function() {
                this.$http.get('api/categoria/'
                    ).then(response => {
                        var data = response.data;

                        for (let categoria of data){
                            this.categorias.push({ text: categoria.nombre, value: categoria.id });
                        }

                    })
            },
            pushCaracteristicas(caracteristicas){
                for (var caracteristica of caracteristicas) {
                    if(this.caracteristicas.length == 0)
                    {
                        this.caracteristicas.push(caracteristica);
                    }
                    else
                    {
                        for(var i  =0; i < this.caracteristicas.length; i++){
                            if(i == (this.caracteristicas.length - 1)
                                && this.caracteristicas[i].id != caracteristica.id)
                            {
                                this.caracteristicas.push(caracteristica);
                            }
                            if(this.caracteristicas[i].id == caracteristica.id)
                            {
                                break;
                            }
                        }
                    }
                }
            },
            onFilesChange(e) {
                var files = e.target.files || e.dataTransfer.files;
                let fotos = []
                if (!files.length){
                    return;
                }
                for (var i = 0; i < files.length; i++) {
                    var file = files[i];
                    var reader = new FileReader();
                    reader.onload = (e) => {
                        fotos.push(e.target.result);
                        this.src.push(reader.result);
                    }
                    reader.readAsDataURL(file);
                    if(!this.nuevo&&(this.publicacion.fotos.length > 0 || this.src.length > 0)){
                        this.isRequiredInputFile = false;
                    } 
                }
            },
            changeCategory(){
                this.publicacion.subcategoria_id = '';
                this.subcategorias = [];
                this.$http.get('api/subcategoria').then( response => {
                    var data = response.data;
                    for (let subcategoria of data) {
                        if(subcategoria.categoria_id == this.categoria_id)
                            this.subcategorias.push({ text: subcategoria.nombre, value: subcategoria.id});
                    }
                })
            },
            deleteImage(index){
                this.publicacion.fotos.splice(index,1),
                this.$forceUpdate()
                if(this.publicacion.fotos.length == 0 && this.src.length == 0){
                    this.$refs.files.value = ''
                    this.isRequiredInputFile = true;
                } else {
                    this.isRequiredInputFile = false;
                }
            },
            deleteImageSrc(index){
                this.src.splice(index,1),
                this.$forceUpdate()
                this.$refs.files.value = '';
                if(!this.nuevo&&(this.src.length == 0 && this.publicacion.fotos.length == 0)){
                    this.isRequiredInputFile = true;
                } else {
                    this.isRequiredInputFile = false;
                }
            },
            validateSubmit: function() {

                this.$validator.validateAll().then((result) => {
                    if(result){
                        this.remplaceStyle();
                        this.publicacion.caracteristicas = this.options_caracteristicas
                        if(this.nuevo){

                            this.publicacion.fotos = []
                            for (var i = 0; i < this.src.length; i++){
                                this.publicacion.fotos.push(this.src[i]);
                            }
                            
                            this.$emit('validadoNewPublicacion'); 
                        }
                        else
                        {
                            this.publicacion.fotosUpdate = []
                            for (var i = 0; i < this.src.length; i++){
                                this.publicacion.fotosUpdate.push(this.src[i]);
                            }
                            //this.src = []
                            this.$emit('validadoEditPublicacion'); 
                        }
                        this.$emit('update:validarPublicacion', false); 
                    }else{
                        this.$emit('update:validarPublicacion', true);
                    }
                    return;
                })
            },
            //
            remplaceStyle: function(){
                this.publicacion.descripcion = this.publicacion.descripcion.split('class=\"ql-align-center\"')
                    .join('style="text-align:center"');
                this.publicacion.descripcion = this.publicacion.descripcion.split('class=\"ql-align-right\"')
                    .join('style="text-align:right"');
                this.publicacion.descripcion = this.publicacion.descripcion.split('class=\"ql-align-justify\"')
                    .join('style="text-align:justify"')
            },
            selected(caracteristica,e){
                if (e.toElement.checked){
                        this.options_caracteristicas.push({caracteristica_id: caracteristica.id, informacion: null})

                }else{
                    for (var i = 0; i < this.options_caracteristicas.length; i++)
                    {
                        if (this.options_caracteristicas[i].caracteristica_id == caracteristica.id){
                            this.options_caracteristicas.splice(i, 1)
                            break;
                        }
                    }
                }
            },
            c_adicional(caracteristica,e){
                    for (var i = 0; i < this.options_caracteristicas.length; i++){
                        if (caracteristica.id == this.options_caracteristicas[i].caracteristica_id){
                            this.options_caracteristicas[i].informacion=  e.srcElement.value
                            break;
                        }
                    }
                
            },
            noselected: function(data1, data2){
                var option = [];
                if (!this.nuevo){
                    for (var i = 0; i < data1.length; i++) {
                        var igual=false;
                         for (var j = 0; j < data2.length & !igual; j++) {
                             if(data1[i]['id'] == data2[j]['id'] && 
                                data1[i]['nombre'] == data2[j]['nombre']) 
                                     igual=true;
                         }
                        if(!igual)
                            option.push(data1[i]);
                    }
                    
                     this.caracteristicas_no = option;
                    this.caracteristicas = data2;
                }else{
                    for (var i = 0; i < data1.length; i++) {
                            option.push(data1[i]);
                    }
                    this.caracteristicas_no = option;
                    this.caracteristicas = [];
                }
                this.agregarOptions();
            },
            agregarOptions: function(){
                if (this.publicacion.caracteristicas){ 
                    for (var i = 0; i < this.publicacion.caracteristicas.length; i++) { 
                        this.options_caracteristicas.push({caracteristica_id: this.publicacion.caracteristicas[i].pivot.caracteristica_id, informacion: this.publicacion.caracteristicas[i].pivot.informacion})
                    }
                }
            },
            loadDefaultOptions(){
                if(!this.nuevo){
                    if(this.publicacion.oferta || this.publicacion.descuento){
                        this.ofertaChecked = true;
                    }
                    this.categoria_id = this.publicacion.subcategoria.categoria_id
                    this.$http.get('api/subcategoria').then( response => {
                        var data = response.data;
                        for (let subcategoria of data) {
                            if(subcategoria.categoria_id == this.categoria_id)
                                this.subcategorias.push({ text: subcategoria.nombre, value: subcategoria.id});
                        }
                    })
                }
            }
        }
    }
</script>