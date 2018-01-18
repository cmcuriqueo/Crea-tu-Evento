<template>
  <div>
    <div v-if="horario.length>0 && showFormH" class="default-content">
          <div class="row">
              <div class="col-lg-12">
  	            <div class="box box-primary">
  	                <!-- /.box-header -->
                     <h2>Horarios</h2>
                     <table class="table" style="width='50%'">
                       <thead>
                         <tr>
                           <th>#</th>
                           <th>Domingo</th>
                           <th>Lunes</th>
                           <th>Martes</th>
                           <th>Miercoles</th>
                           <th>Jueves</th>
                           <th>Viernes</th>
                           <th>Sábado</th>
                         </tr>
                       </thead>
                       <tbody v-if="loadH">
                      
                         <tr v-for="hora in 24" v-html="handle(hora)" @click.stop ="editHorario($event)"  >
                         </tr>
                       </tbody>
                     </table>
  	            </div>
  	        </div>
  	    </div>
    </div>
        <!-- Modal Modificar-->
     <div v-if="showModificarHorario" id="modificar" class="modal" role="dialog" :style="{ display : showModificarHorario  ? 'block' : 'none' }">
          <div class="modal-dialog">
            
              <div class="modal-content">
                  <div class="modal-header">
                      <button type="button" class="close" @click="closeModal()">&times;</button>
                      <h4 class="modal-title">Modificar Horario</h4>
                  </div>
                 <edit-horario :horarioId="horarioId" :publicacionId="publicacionId" :horariosId= "horariosId"> </edit-horario>
              </div>
           </div>
     </div>
	</div>
</template>

<script>
    import Style from './../../Layouts/Style-css.js';
    import EditHorario from './Edit';
    import moment from 'moment';
    import auth from '../../../auth.js';

    export default	{
        props:{ 
             publicacionId: {
                type: Number
            },
            horariosId:{
              type: Array
            }
        },
    	data(){
            return {
                horario: [],
                hora:0,
                horarioId: 0,
                loadH: false,
                showFormH : false,
                red: Math.floor(Math.random() * (200 - 50)),
                green: Math.floor(Math.random() * (200 - 50)),
                blue: Math.floor(Math.random() * (200 - 50)),
                showModificarHorario: false,
                auth: auth,
                dias: [
                        {nombre:'domingo'},
                        {nombre:'lunes'},
                        {nombre:'martes'},
                        {nombre:'miercoles'},
                        {nombre:'jueves'},
                        {nombre:'viernes'},
                        {nombre:'sabado'}]  
            }
    	},
        components: {
             EditHorario
        },
        beforeMount: function(){
           this.getHorarios();
        },
        mounted() {
            this.$events.on('reloadIndexHorario', () => this.getHorarios());
            this.$events.on('close', () => this.closeModal());
            this.$events.on('showFormHClose', () => this.showFormH = false);
            this.$events.on('showFormH', () => this.showFormH = true);
        },
        methods: {
            getHorarios: function(){
                this.endHandle=false;
                this.horario=[];
                if (this.publicacionId != null){
                    this.$http.get('api/publicacion/'+this.publicacionId+'/horarios')
                        .then(response =>{
                            this.horario = response.data.horarios
                            this.loadH =true;
                            this.showFormH = true;
                            this.$emit('update:horario', this.horario);
                        }, response => {
                            if(response.status === 404){
                                this.$toast.error({
                                    title:'¡Error!',
                                    message:'No se han cargado sus horarios.'
                                });
                            }
                        });
                } else{
                    this.showFormH = false;
                    this.loadH = false;
                    var lengthIds = this.horariosId.length;
                    if ( this.publicacionId == null && this.horariosId.length > 0 )
                    {
                      this.$http.get('api/horarios/'+this.auth.user.profile.id )
                          .then(response =>{
                              this.horario = response.data
                              if ( this.horario){
                                  this.loadH =true;
                                  this.showFormH = true;
                                  this.$emit('update:horario', this.horario);
                                }
                          }, response => {
                              if(response.status === 404){
                                  this.$toast.error({
                                      title:'¡Error!',
                                      message:'No se han cargado sus horarios.'
                                  });
                              }
                          });
                    }  
                }
            },
            /* Con los datos obtenidos de Horarios, crea una tabla con los valores correspondientes*/
             handle(hora) {
               var mostrar = false
               var masUnaHora = hora + 1;
                  this.lineHtml= '<td style="text-align: center;">'+hora +':00hs</td>';
                  for (var i = 0; i < this.dias.length; i++) 
                  { 
                     mostrar= false
                     for ( var j = 0;(j < this.horario.length); j++) 
                     {
                        if(this.horario[j].dia == this.dias[i].nombre)
                        {
                           if ((
                              moment(hora+':00', 'HH:mm').
                              isSameOrAfter(moment(this.horario[j].hora_inicio, 'HH:mm')) 
                              &&
                              moment(masUnaHora+':00', 'HH:mm').
                              isSameOrBefore(moment(this.horario[j].hora_fin, 'HH:mm'))
                           ) || (
                              moment(hora+':00', 'HH:mm').
                              isAfter(moment(this.horario[j].hora_inicio, 'HH:mm')) 
                              &&
                              moment(hora+':00', 'HH:mm').
                              isBefore(moment(this.horario[j].hora_fin, 'HH:mm')) 
                           ) ||
                              (moment(this.horario[j].hora_fin, 'HH:mm')
                              .isBetween(moment(hora+':00', 'HH:mm'), moment(masUnaHora+':00', 'HH:mm'))
                           ))
                              {
                              if (this.horario[j].difHs == null || this.horario[j].difHs =='undefined' || this.horario[j].difHs == 0)
                              {
                                 var ms = moment(this.horario[j].hora_fin, 'HH:mm').diff(moment(this.horario[j].hora_inicio, 'HH:mm'))
                                 var d = moment.duration(ms);
                                 var s = moment(d, 'HH:mm');
                                 this.horario[j].difHs= s._i._data.hours;

                                 if (this.horario[j].difHs > 0)
                                 {
                                       this.lineHtml= this.lineHtml +'<td id="'+this.horario[j].id+'" style="text-align: center;vertical-align: middle;background-color:rgba('+this.red+','+ this.green+', '+this.blue+', 0.30);cursor: pointer; width: 120px; border-color: #FFFFFF; border-width:1px; border-style: solid" rowspan="'+this.horario[j].difHs +'">Inicio '+this.horario[j].hora_inicio+'hs. - Fin  '+this.horario[j].hora_fin+'hs. Precio: $'+this.horario[j].precio+'</td>'
                                       mostrar = true
                                 }
                              }else
                              {
                                if ((this.horario[j].difHs != null || this.horario[j].difHs !='undefined')  &&  (this.horario[j].difHs > 0))
                                {
                                   this.horario[j].difHs= (this.horario[j].difHs - 1)
                                   if (this.horario[j].difHs == 0)
                                   {
                                      this.horario[j].difHs= null
                                   }
                                  mostrar = true
                                }
                              }
                              
                           }
                        }
                    }
                    if (!mostrar)
                    {
                        this.lineHtml=  this.lineHtml+'<td id="'+0+'" style="text-align: center;"> </td>'
                    }
                  }
                  
                  return this.lineHtml  
            },
            editHorario(event){
               if (event.path[0].id != 0){
                  this.horarioId= event.path[0].id
                  this.showModificarHorario= true
               }
            },
            closeModal(){
               this.getHorarios()
               this.showModificarHorario=false
               this.showFormH=true
            },

        },
    }

</script>