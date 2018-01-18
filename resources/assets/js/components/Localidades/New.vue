 <template>
    <div class="modal-body">
        <div class="box-body">
        	<form-localidad 
                :localidad="localidad"  
                :nuevo="true"
                :errorsApi="errorsApi"
                @validadoNewLocalidad="sendForm()">
            </form-localidad>
        </div>
        <div class="modal-footer" style="text-align:center;">
            <button class="btn btn-default" @click="closeModal()">
                <i class="glyphicon glyphicon-chevron-left"></i>
                Atras
            </button>
            <button @click="validateBeforeSubmit()" type="button" class="btn btn-primary">
                Guargar
            </button>
        </div>
    </div>
</template>

<script> 
import auth from '../../auth.js';
import FormLocalidad from './Form.vue';

export default {
    data() {
        return {
            localidad:{ type: Object },
            errorsApi: {}
        }
    },
    components: {
        FormLocalidad
    },
    methods: {
        //envio de formulario de nueva
        sendForm: function() {
            this.$http.post(
                'api/localidad', 
                {
                    nombre: this.localidad.nombre,
                    provincia_id: this.localidad.provincia_id
                })
                .then(response => {
                    this.$events.fire('reloadIndexLocalidad')
                    this.closeModal(),
                    this.$toast.success({
                        title:'¡Cambios realizados!',
                        message:'Se han realizado correctamente los cambios. :D'
                    });
                    this.resetForm()
                }, response => {
                    this.$toast.error({
                        title:'¡Error!',
                        message:'No se han podido guardar los cambios. :('
                    });
                    if(response.status === 500)
                    {
                        this.errorsApi = response.body;
                    }
                })
        },
        //form validation
        validateBeforeSubmit: function() {                 
                    this.validarLocalidad = true;
                    this.$events.fire('validarFormLocalidad')

        },
        resetForm() {
            this.localidad={
                nombre: null
            }
        },
        closeModal: function(){
            this.$events.fire('cerrar')
        }
    }
}
</script>
