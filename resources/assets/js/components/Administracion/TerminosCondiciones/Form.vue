<template>
    <form role="form">
        <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('descripcion')&&validarPublicacion}">
            <div class="col-sm-12">
                <label for="descripcion" class="control-label">Terminos y Condiciones (*)</label>

                <vue-editor 
                    data-vv-name="descripcion"  
                    v-model="terminoCondicion.cuerpo" 
                    v-validate="'required|min:200|max:33000'"
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
    </form>
</template>
<style>
	.ql-container.ql-snow {
		min-height: 550px;
	}
</style>
<script>
	import { VueEditor } from 'vue2-editor';
	import auth from '../../../auth.js';

	export default {
		props: {
			terminoCondicion: {
				type: Object,
				required: true
			},
			errorsApi: {
				required: true
			}
		},
		data() {
			return {
				auth: auth,
				validarPublicacion: false,
                customToolbar: [
                      ['bold', 'italic', 'underline'],
                      [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                      [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                      [{ 'color': [] }],
                      [{ 'font': [] }],
                      [{ 'align': [] }],
                      ['clean']
                  ]
			}        
		},
		mounted(){
			this.$events.on("validarFormTerm", () => this.validateSubmit());
		},
		beforeDestroy() {
		    this.$events.$off('validarFormTerm');
		},
		components: { VueEditor },
		methods: {
            validateSubmit: function() {
                this.$validator.validateAll().then((result) => {
                    if(result){
                        this.validarPublicacion = false;
                        this.$emit('validoFormTerm'); 
                    }else{
                        this.validarPublicacion = true;
                    }
                    return;
                })
            },
        }
    }
</script>