<template>
<div>
    <v-card>
        <v-card-title>
            <v-toolbar-title class="display-1">Registros</v-toolbar-title>
            <v-divider class="mx-4" inset vertical></v-divider>
            <v-spacer></v-spacer>
            <v-text-field v-model="search" append-icon="mdi-search" label="Buscar" single-line hide-details></v-text-field>
        </v-card-title>
        <v-card-text>
            <v-data-table id="main_table" :headers="headers" :items="registros" :search="search" sort-by="rc_id" sort-desc class="elevation-1" :mobile-breakpoint="$store.state.tableBreakpoint">
                <template v-slot:top>
                    <v-sheet :elevation="24" class="pa-3 my-3">
                        <v-row no-gutters>
                            <v-col cols="12" class="d-flex">
                                <v-btn @click="dialog = true" color="success" dark class="mx-auto my-1">Crear nuevo registro</v-btn>
                            </v-col>
                        </v-row>
                    </v-sheet>
                    <v-dialog v-model="dialog" max-width="90%" scrollable persistent>>
                        <validation-observer v-slot="{ invalid, handleSubmit }">
                            <v-card>
                                <v-card-title>
                                    {{ formTitle }}
                                </v-card-title>
                                <v-card-text>
                                    <v-container>
                                        <v-form ref="foliosForm" id="foliosForm" name="foliosForm">
                                          <v-row>
                                            <v-col cols="12" md="6">
                                                <validation-provider v-slot="{ errors }" rules="required" name="Nombre">
                                                    <v-text-field 
                                                        name="rc_nombre" 
                                                        label="Nombre" 
                                                        append-icon="mdi-account" 
                                                        :error-messages="errors[0]"
                                                        v-model="currentPreregistro.nombre"
                                                        outlined 
                                                        shaped 
                                                        required
                                                    ></v-text-field>
                                                </validation-provider>
                                            </v-col>
                                            <v-col cols="12" md="6">
                                                <validation-provider v-slot="{ errors }" rules="required" name="Apellido paterno">
                                                    <v-text-field 
                                                        name="rc_app" 
                                                        label="Apellido paterno" 
                                                        append-icon="mdi-account" 
                                                        :error-messages="errors[0]"
                                                        v-model="currentPreregistro.nombre"
                                                        outlined 
                                                        shaped 
                                                        required
                                                    ></v-text-field>
                                                </validation-provider>
                                            </v-col>
                                          </v-row>
                                        </v-form>
                                    </v-container>
                                </v-card-text>
                                <v-card-actions>
                                    <v-row>
                                        <v-col cols="12" md="6">
                                            <v-btn color="success" :disabled="invalid" @click="handleSubmit(validForm)" block>{{ submitTitle }}</v-btn>
                                        </v-col>
                                        <v-col cols="12" sm="6" md="3">
                                            <v-btn @click="$refs.foliosForm.reset()" color="warning" block>Limpiar campos</v-btn>
                                        </v-col>
                                        <v-col cols="12" sm="6" md="3">
                                            <v-btn color="danger" @click="close" dark block>Cancelar</v-btn>
                                        </v-col>
                                    </v-row>
                                </v-card-actions>
                            </v-card>
                        </validation-observer>
                    </v-dialog>
                </template>
                <template v-slot:item.rc_comentarios_curso="{ item }">
                    <div style="white-space: pre-line;">
                      {{ item.rc_comentarios_curso }}
                    </div>
                </template>
                <template v-slot:item.opciones="{ item }">
                    <v-row>
                        <v-col cols="12" lg="6">
                            <v-tooltip left>
                                <template v-slot:activator="{ on }">
                                    <v-btn dark fab small color="success" v-on="on">
                                        <v-icon>
                                            mdi-printer
                                        </v-icon>
                                    </v-btn>
                                </template>
                                <span>Generar Reporte</span>
                            </v-tooltip>
                        </v-col>
                        <v-col cols="12" lg="6">
                            <v-tooltip left>
                                <template v-slot:activator="{ on }">
                                    <v-btn dark fab small color="navDrawer" @click="editItem(item)" v-on="on">
                                        <v-icon class="mr-2">
                                            mdi-pencil
                                        </v-icon>
                                    </v-btn>
                                </template>
                                <span>Actualizar</span>
                            </v-tooltip>
                        </v-col>
                        <v-col cols="12">
                            <v-tooltip left>
                                <template v-slot:activator="{ on: toolActivator}">
                                    <v-btn dark fab small color="danger" @click="deleteItem(item)" v-on="{ ...toolActivator }">
                                        <v-icon>
                                            mdi-delete
                                        </v-icon>
                                    </v-btn>
                                </template>
                                <span>Eliminar</span>
                            </v-tooltip>
                        </v-col>
                    </v-row>
                </template>
                <template v-slot:no-data>
                    <v-alert type="info" color="navDrawer">
                        No se obtuvieron resultados
                    </v-alert>
                    <v-btn color="navDrawer" @click="initialize" dark>Resetear</v-btn>
                </template>
                <template v-slot:no-results>
                    <v-alert type="error" color="danger">
                        No se encontraron resultados
                    </v-alert>
                </template>
            </v-data-table>
        </v-card-text>
    </v-card>
    <v-snackbar v-model="toastMessage.show" width="350px" :timeout="10000" absolute top right>
        <v-icon color="white">{{ toastMessage.icon }}</v-icon>- {{ toastMessage.message }}
        <v-btn text @click="toastMessage.show = false">Cerrar</v-btn>
    </v-snackbar>
</div>
</template>

<script>
import {
    ValidationProvider,
    ValidationObserver
} from 'vee-validate';

export default {
    transition: "slide",
    head: {
        title: "Registros"
    },
    data() {
        return {
            search: '',
            headers: [{
                    text: 'ID',
                    value: 'rc_id'
                },
                {
                    text: 'Clave registro',
                    value: 'rc_clave_registro'
                },
                {
                    text: 'Clave curso',
                    value: 'rc_clave_curso'
                },
                {
                    text: 'Folio',
                    value: 'rc_folio_preregistro'
                },
                {
                    text: 'Nombre',
                    value: 'rc_nombre'
                },
                {
                    text: 'Apellido paterno',
                    value: 'rc_app'
                },
                {
                    text: 'Apellido materno',
                    value: 'rc_apm'
                },
                {
                    text: 'Genero',
                    value: 'rc_genero'
                },
                {
                    text: 'Fecha de nacimiento',
                    value: 'rc_fecha_nacimiento'
                },
                {
                    text: 'Lugar de nacimiento',
                    value: 'rc_lugar_nacimiento'
                },
                {
                    text: 'Nacionalidad',
                    value: 'rc_nacionalidad'
                },
                {
                    text: 'Estado civil',
                    value: 'rc_estado_civil'
                },
                {
                    text: 'CURP',
                    value: 'rc_curp'
                },
                {
                    text: 'Domicilio',
                    value: 'rc_domicilio'
                },
                {
                    text: 'Medio de contacto',
                    value: 'rc_medio_contacto'
                },
                {
                    text: 'Servicio medico',
                    value: 'rc_servicio_medico'
                },
                {
                    text: 'Aviso',
                    value: 'rc_aviso'
                },
                {
                    text: 'Comentarios',
                    value: 'rc_comentarios_curso'
                },
                {
                    text: 'Trabajo o estudios',
                    value: 'rc_trabajo_estudios'
                },
                {
                    text: 'Opciones',
                    value: 'opciones',
                    sortable: false,
                    align: 'center'
                },
            ],
            registros: [],
            dialog: false,
            editedIndex: -1,
            toastMessage: {
                show: false,
                message: "",
                icon: ""
            },
            currentRegistro:{
              rc_id:"",
              rc_clave_registro:"",
              rc_clave_curso:"",
              rc_folio_preregistro:"",
              rc_nombre:"",
              rc_app:"",
              rc_apm:"",
              rc_genero:"",
              rc_fecha_nacimiento:"",
              rc_lugar_nacimiento:"",
              rc_nacionalidad:"",
              rc_estado_civil:"",
              rc_curp:"",
              rc_domicilio:"",
              rc_medio_contacto:"",
              rc_servicio_medico:"",
              rc_aviso:"",
              rc_comentarios_curso:"",
              rc_trabajo_estudios:""
            },
            newRegistro:{
              rc_nombre:"",
              rc_app:"",
              rc_apm:"",
              rc_fecha_nacimiento:"",
              rc_lugar_nacimiento:"",
              rc_nacionalidad:"",
              rc_medio_contacto:"",
              rc_curp:"",
              rc_genero:"",
              rc_estado_civil:"",
              rc_emergencia:{
                rc_nombre:"",
                rc_parentesco:"",
                rc_telefono:""
              },
              rc_comentarios:{
                rc_utilidad_curso:"",
                rc_enterar_curso:""
              },
              rc_servicio_medico:{
                rc_servicio_medico:"",
                rc_afiliacion:"",
                rc_seguro:"",
                rc_tipo:"",
                rc_compañia:"",
                rc_grupo_sanguineo:"",
                rc_alergias:"",
                rc_enfermedades_cronicas:"",
                rc_cirugias:"",
                rc_problemas_fisicos:""
              },
              rc_trabajo_estudios:{
                rc_calle:"",
                rc_colonia:"",
                rc_municipio:""
              }
            }
        }
    },
    mounted() {
        this.initialize()
    },
    methods: {
        async initialize() {
            try {
                const {
                    data
                } = await this.$axios.post('Registros/')
                this.registros = data    
            } catch (error) {
                this.showToastMessage("A ocurrido un error inesperado", "mdi-close")
                console.error(error)
            }
            
        },
        editItem(item) {
            this.editedIndex = this.folios.indexOf(item)
            this.dialog = true
            this.currentPreregistro = Object.assign({}, item)
        },
        deleteItem(item) {
            this.deleteDialog.show = true
            this.deleteDialog.idPreregistro = item.idPreregistro
            this.deleteDialog.nombre = item.nombre
        },
        async confirmDeleteItem() {
            this.showToastMessage("Espere un momento...", "mdi-timer-sand")
            try {
                const formData = new FormData()
                formData.append('idPreregistro', this.deleteDialog.idPreregistro)
                const {
                    data
                } = await this.$axios.post('Preregistros/Eliminar.php', formData)
                this.deleteDialog.show = false
                this.showToastMessage("Operacion completada", "mdi-check-bold")
                this.initialize()
            } catch (error) {
                console.log(error)
                this.showToastMessage("A ocurrio un error inesperado", "mdi-close")
            }
        },
        async validForm() {
            if (this.editedIndex > -1) {
                this.showToastMessage("Actualizando preregistro", "mdi-timer-sand")
                try {
                    Object.assign(this.Preregistros[this.editedIndex], this.editedItem)
                    const form = document.getElementById('preregistroForm')
                    let formData = new FormData(form)
                    const {
                        data
                    } = await this.$axios.post('Preregistros/Actualizar.php', formData)
                    console.log(data)
                    this.close()
                    this.showToastMessage("Preregistro actualizado", "mdi-check-bold")
                    this.initialize()
                } catch (error) {
                    this.showToastMessage("A ocurrido un error inesperado", "mdi-close")
                    console.error(error)
                }
            } else {
                this.showToastMessage("Creando preregistro", "mdi-timer-sand")
                try {
                    const form = document.getElementById('preregistroForm')
                    let formData = new FormData(form)
                    // formData.append('tipo_registro',this.currentPreregistro.tipo_registro == 'Público general' ? 1 : 2)
                    const {
                        data
                    } = await this.$axios.post('Preregistros/Crear.php', formData)
                    console.log(data)
                    this.close()
                    this.showToastMessage("Preregistro creado", "mdi-check-bold")
                    this.initialize()
                } catch (error) {
                    this.showToastMessage("A ocurrido un error inesperado", "mdi-close")
                    console.error(error)
                }
            }

        },
        close() {
            this.dialog = false
            setTimeout(() => {
                this.editedIndex = -1
                // this.currentPreregistro.idPreregistro = ""
                // this.currentPreregistro.nombre = ""
                // this.currentPreregistro.apellido_p = ""
                // this.currentPreregistro.apellido_m = ""
                // this.currentPreregistro.genero = ""
                // this.currentPreregistro.edad = ""
                // this.currentPreregistro.tipo_registro = ""
                // this.currentPreregistro.clave_curso = ""
                // this.currentPreregistro.contacto = ""
            }, 300)
        },
        showToastMessage(message, icon) {
            this.toastMessage.show = true
            this.toastMessage.message = message
            this.toastMessage.icon = icon
        }
    },
    computed: {
        formTitle() {
            return this.editedIndex === -1 ? 'Crear folio' : 'Editar folio'
        },
        submitTitle() {
            return this.editedIndex === -1 ? 'Crear' : 'Editar'
        },
    },
    components: {
        ValidationProvider,
        ValidationObserver
    },
}
</script>
