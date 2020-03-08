<template>
<div>
    <v-card>
        <v-card-title>
            <v-toolbar-title class="display-1">Preregistros Publicos</v-toolbar-title>
            <v-divider class="mx-4" inset vertical></v-divider>
            <v-spacer></v-spacer>
            <v-text-field v-model="search" append-icon="mdi-search" label="Buscar" single-line hide-details></v-text-field>
        </v-card-title>
        <v-card-text>
            <v-data-table id="main_table" :headers="headers" :items="Preregistros" :search="search" sort-by="idPreregistro" sort-desc class="elevation-1" :mobile-breakpoint="$store.state.tableBreakpoint">
                <template v-slot:top>
                    <v-sheet :elevation="24" class="pa-3 my-3">
                        <v-row no-gutters>
                            <v-col cols="12" md="6" class="d-flex">
                                <v-btn @click="dialog = true" color="success" dark class="mx-auto my-1">Crear nuevo preregistro</v-btn>
                            </v-col>
                            <v-col cols="12" md="6" class="d-flex">
                                <v-btn color="danger" dark class="mx-auto my-1" @click="dialogCursos.show = true">Imprimir lista de cursos</v-btn>    
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
                                        <v-form ref="preregistroForm" id="preregistroForm" name="preregistroForm">
                                            <v-row>
                                                <input type="hidden" v-if="editedIndex > -1" name="idPreregistro" :value="currentPreregistro.idPreregistro">
                                                <v-col cols="12" md="6">
                                                    <validation-provider v-slot="{ errors }" rules="required" name="Nombre">
                                                        <v-text-field 
                                                            name="nombre" 
                                                            label="Nombre del preregistro" 
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
                                                            name="apellido_p" 
                                                            label="Apellido paterno" 
                                                            append-icon="mdi-account" 
                                                            :error-messages="errors[0]"
                                                            v-model="currentPreregistro.apellido_p"
                                                            outlined 
                                                            shaped 
                                                            required
                                                        ></v-text-field>
                                                    </validation-provider>
                                                </v-col>
                                                <v-col cols="12" md="6">
                                                    <validation-provider v-slot="{ errors }" rules="required" name="Apellido materno">
                                                        <v-text-field 
                                                            name="apellido_m" 
                                                            label="Apellido materno" 
                                                            append-icon="mdi-account" 
                                                            :error-messages="errors[0]"
                                                            v-model="currentPreregistro.apellido_m"
                                                            outlined 
                                                            shaped 
                                                            required
                                                        ></v-text-field>
                                                    </validation-provider>
                                                </v-col>
                                                <v-col cols="12" md="6">
                                                    <validation-provider v-slot="{ errors }" rules="required" name="Genero">
                                                        <v-select 
                                                            name="genero" 
                                                            :items="['Masculino', 'Femenino']" 
                                                            label="Genero" 
                                                            append-icon="mdi-domain" 
                                                            :error-messages="errors[0]" 
                                                            v-model="currentPreregistro.genero" 
                                                            dense 
                                                            outlined 
                                                            shaped>
                                                        </v-select>
                                                    </validation-provider>
                                                    <input type="hidden" name="genero" :value="currentPreregistro.genero">
                                                </v-col>
                                                <v-col cols="12" md="6">
                                                    <validation-provider v-slot="{ errors }" rules="required" name="Edad">
                                                        <v-text-field 
                                                            name="edad" 
                                                            label="Edad" 
                                                            append-icon="mdi-numbers" 
                                                            :error-messages="errors[0]"
                                                            v-model="currentPreregistro.edad"
                                                            outlined 
                                                            shaped 
                                                            required
                                                        ></v-text-field>
                                                    </validation-provider>
                                                </v-col>
                                                <v-col cols="12" md="6" v-if="editedIndex > -1">
                                                    <validation-provider class="input_form" v-slot="{ errors }" rules="required" name="Tipo de registro">
                                                        <v-select 
                                                            name="tipo_registro" 
                                                            :items="['Público general', 'Empresarial']" 
                                                            label="Tipo de registro" 
                                                            append-icon="mdi-domain" 
                                                            :error-messages="errors[0]" 
                                                            v-model="currentPreregistro.tipo_registro" 
                                                            dense 
                                                            outlined 
                                                            shaped>
                                                        </v-select>
                                                    </validation-provider>
                                                    <input type="hidden" name="tipo_registro" :value="currentPreregistro.tipo_registro == 'Público general' ? 1 : 2">
                                                </v-col>
                                                <input type="hidden" name="tipo_registro" :value="1" v-else>
                                                <v-col cols="12" md="6">
                                                    <validation-provider v-slot="{ errors }" rules="required" name="Clave del curso">
                                                        <v-text-field 
                                                            name="clave_curso" 
                                                            label="Clave del curso" 
                                                            append-icon="mdi-numbers" 
                                                            :error-messages="errors[0]"
                                                            v-model="currentPreregistro.clave_curso"
                                                            outlined 
                                                            shaped 
                                                            required
                                                        ></v-text-field>
                                                    </validation-provider>
                                                </v-col>
                                                <v-col cols="12" md="6">
                                                    <validation-provider v-slot="{ errors }" rules="required" name="Contacto">
                                                        <v-text-field 
                                                            name="contacto" 
                                                            label="Contacto" 
                                                            append-icon="mdi-numbers" 
                                                            :error-messages="errors[0]"
                                                            v-model="currentPreregistro.contacto"
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
                                            <v-btn @click="$refs.preregistroForm.reset()" color="warning" block>Limpiar campos</v-btn>
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
                <template v-slot:item.idPreregistro="{ item }">
                  <v-chip color="orange" dark>
                    <strong>
                      {{ item.idPreregistro }}
                    </strong>
                  </v-chip>
                </template>
                <template v-slot:item.opciones="{ item }">
                    <v-row>
                        <v-col cols="12" lg="6">
                            <v-tooltip left>
                                <template v-slot:activator="{ on }">
                                    <v-btn dark fab small color="success" v-on="on" :href="`/cruzrojatabasco/prerregistros/editar&idPreregistro=${ item.idPreregistro }&imprimir=true`">
                                        <v-icon>
                                            mdi-printer
                                        </v-icon>
                                    </v-btn>
                                </template>
                                <span>Imprimir PDF</span>
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
                <template v-slot:item.genero="{ item }">
                  <v-chip v-if="item.genero == 'Masculino'" color="male" dark>
                    <strong>
                      {{ item.genero }}
                    </strong>
                  </v-chip>
                  <v-chip v-else-if="item.genero == 'Femenino'" color="female" dark>
                    <strong>
                      {{ item.genero }}
                    </strong>
                  </v-chip>
                  <v-chip v-else color="warning" dark>
                    <strong>
                      {{ item.genero }}
                    </strong>
                  </v-chip>
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
    <v-dialog v-model="deleteDialog.show" width="75%">
        <v-card>
            <v-card-text>
                <div class="headline" style="text-align: center;">
                    ¿Seguro que desea eliminar el preregistro de {{ deleteDialog.nombre }} con el ID {{ deleteDialog.idPreregistro }}?
                </div>
            </v-card-text>
            <v-card-actions>
                <v-btn color="danger" class="headline" @click="confirmDeleteItem" text>Eliminar</v-btn>
                <v-divider vertical></v-divider>
                <v-btn color="success" class="headline" @click="deleteDialog.show = false" dark text>Cancelar</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
    <v-dialog v-model="dialogCursos.show" scrollable="">
        <v-card>
            <v-card-title>
                Lista de cursos
            </v-card-title>
            <v-card-text>
                <v-list>
                    <v-list-item v-for="(curso , i) in dialogCursos.cursos" :key="i">
                        <v-list-item-content>
                            <v-list-item-title class="title">
                                {{ curso.curso_clave }}
                            </v-list-item-title>
                            <v-list-item-subtitle>
                                {{ curso.curso_nombre }}
                            </v-list-item-subtitle>
                        </v-list-item-content>
                        <v-list-item-icon>
                            <v-tooltip left>
                                <template v-slot:activator="{ on }">
                                    <v-btn dark fab color="success" v-on="on" :href="`/cruzrojatabasco/prerregistros/imprimirCurso&curso_clave=${curso.curso_clave}`">
                                        <v-icon>
                                            mdi-printer
                                        </v-icon>
                                    </v-btn>
                                </template>
                                <span>Imprimir Lista</span>    
                            </v-tooltip>
                        </v-list-item-icon>
                    </v-list-item>
                </v-list>
            </v-card-text>
            <v-card-actions>
                <v-btn @click="dialogCursos.show = false" color="danger" outlined>Cerrar</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
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
        title: "Preregistros - Publico general"
    },
    data() {
        return {
            search: '',
            headers: [{
                    text: 'ID',
                    value: 'idPreregistro'
                },
                {
                    text: 'Nombre',
                    value: 'nombre'
                },
                {
                    text: 'Apellido paterno',
                    value: 'apellido_p'
                },
                {
                    text: 'Apellido materno',
                    value: 'apellido_m'
                },
                {
                    text: 'Genero',
                    value: 'genero'
                },
                {
                    text: 'Edad',
                    value: 'edad'
                },
                {
                    text: 'Clave del curso',
                    value: 'clave_curso'
                },
                {
                    text: 'Folio',
                    value: 'folio'
                },
                {
                    text: 'Opciones',
                    value: 'opciones',
                    sortable: false,
                    align: 'center'
                },
            ],
            Preregistros: [],
            dialog: false,
            editedIndex: -1,
            toastMessage: {
                show: false,
                message: "",
                icon: ""
            },
            deleteDialog: {
                show: false,
                idPreregistro: "",
                nombre: ""
            },
            currentPreregistro: {
                idPreregistro:"",
                nombre: "",
                apellido_p: "",
                apellido_m: "",
                genero: "",
                edad: "",
                tipo_registro: "",
                clave_curso: "",
                contacto: ""
            },
            dialogCursos:{
                show: false,
                cursos: []
            }
        }
    },
    mounted() {
        this.initialize()
        this.loadCursos()
    },
    methods: {
        async initialize() {
            try {
                const formData = new FormData()
                formData.append('tipo_registro', 1)
                const {
                    data
                } = await this.$axios.post('Preregistros/', formData)
                this.Preregistros = data    
            } catch (error) {
                this.showToastMessage("A ocurrido un error inesperado", "mdi-close")
                console.error(error)
            }
            
        },
        async loadCursos(){
            const formData = new FormData()
            formData.append('curso_tipo', 1)
            const {
                data
            } = await this.$axios.post('Cursos/getCursos.php', formData)
            this.dialogCursos.cursos = data
        },
        editItem(item) {
            this.editedIndex = this.Preregistros.indexOf(item)
            this.dialog = true
            if(item.tipo_registro == 1 || item.tipo_registro == 'Público general') 
                item.tipo_registro = 'Público general'
            else 
                item.tipo_registro = 'Empresarial'
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
        async validForm(){
            if (this.editedIndex > -1) {
                this.showToastMessage("Actualizando preregistro","mdi-timer-sand")
                try {
                    Object.assign(this.Preregistros[this.editedIndex], this.editedItem)
                    const form = document.getElementById('preregistroForm')
                    let formData = new FormData(form)
                    const { data } = await this.$axios.post('Preregistros/Actualizar.php',formData)
                    console.log(data)
                    this.close()
                    this.showToastMessage("Preregistro actualizado","mdi-check-bold")
                    this.initialize()
                } catch (error) {
                    this.showToastMessage("A ocurrido un error inesperado","mdi-close")
                    console.error(error)
                }
            } else {
                this.showToastMessage("Creando preregistro","mdi-timer-sand")
                try {
                    const form = document.getElementById('preregistroForm')
                    let formData = new FormData(form)
                    // formData.append('tipo_registro',this.currentPreregistro.tipo_registro == 'Público general' ? 1 : 2)
                    const { data } = await this.$axios.post('Preregistros/Crear.php',formData)
                    console.log(data)
                    this.close()
                    this.showToastMessage("Preregistro creado","mdi-check-bold")
                    this.initialize()
                } catch (error) {
                    this.showToastMessage("A ocurrido un error inesperado","mdi-close")
                    console.error(error)
                }
            }
            
        },
        close() {
            this.dialog = false
            setTimeout(() => {
                this.editedIndex = -1
                this.currentPreregistro.idPreregistro = ""
                this.currentPreregistro.nombre = ""
                this.currentPreregistro.apellido_p = ""
                this.currentPreregistro.apellido_m = ""
                this.currentPreregistro.genero = ""
                this.currentPreregistro.edad = ""
                this.currentPreregistro.tipo_registro = ""
                this.currentPreregistro.clave_curso = ""
                this.currentPreregistro.contacto = ""
            }, 300)
        },
        showToastMessage(message, icon) {
            this.toastMessage.show = true
            this.toastMessage.message = message
            this.toastMessage.icon = icon
        }
    },
    computed: {
        formTitle () {
            return this.editedIndex === -1 ? 'Crear preregistro' : 'Editar preregistro'
        },
        submitTitle () {
            return this.editedIndex === -1 ? 'Crear' : 'Editar'
        },
    },
    components: {
        ValidationProvider,
        ValidationObserver
    },
}
</script>
