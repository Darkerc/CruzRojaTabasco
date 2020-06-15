<template>
<div>
    <v-card>
        <v-card-title>
            <v-toolbar-title class="display-1">Lista de cursos</v-toolbar-title>
            <v-divider class="mx-4" inset vertical></v-divider>
            <v-spacer></v-spacer>
            <v-text-field v-model="search" append-icon="mdi-search" label="Buscar" single-line hide-details></v-text-field>
        </v-card-title>
        <v-data-table id="main_table" :headers="headers" :items="cursos" :search="search" sort-by="curso_clave" class="elevation-1" :mobile-breakpoint="$store.state.tableBreakpoint" :footer-props="{
                showFirstLastPage: true,
                firstIcon: 'mdi-arrow-collapse-left',
                lastIcon: 'mdi-arrow-collapse-right',
                prevIcon: 'mdi-minus',
                nextIcon: 'mdi-plus'
            }">
            <template v-slot:top>
                <v-toolbar class="ma-5" flat>
                    <v-dialog v-model="dialog" width="90%" scrollable persistent>
                        <template v-slot:activator="{ on }">
                            <v-btn color="success" dark class="mb-2 mx-auto" @click="ActionForm = 'Crear'" v-on="on">Registrar nuevo curso</v-btn>
                        </template>
                        <v-card>
                            <v-card-title>
                                <span class="display-1">{{ formTitle }}</span>
                            </v-card-title>
                            <v-card-text>
                                <v-container>
                                    <validation-observer v-slot="{ invalid, handleSubmit }">
                                        <v-form ref="cursoForm" id="cursoForm" name="cursoForm">
                                            <v-row>
                                                <v-col v-show="disableCurso_Clave" cols="12" md="6">
                                                    <validation-provider  class="input_form" v-slot="{ errors }" rules="required" name="Clave del curso">
                                                        <v-text-field 
                                                            name="curso_clave" 
                                                            label="Clave del curso" 
                                                            append-icon="mdi-notebook" 
                                                            :error-messages="errors[0]"
                                                            v-model="addCurso.curso_clave"
                                                            outlined 
                                                            shaped 
                                                            required
                                                        ></v-text-field>
                                                    </validation-provider>
                                                </v-col>
                                                <v-col cols="12" md="6">
                                                    <validation-provider class="input_form" v-slot="{ errors }" rules="required" name="Nombre de curso">
                                                        <v-text-field 
                                                            name="curso_nombre" 
                                                            label="Nombre del curso" 
                                                            append-icon="mdi-notebook" 
                                                            :error-messages="errors[0]"
                                                            v-model="addCurso.curso_nombre"
                                                            outlined 
                                                            shaped 
                                                            required
                                                        ></v-text-field>
                                                    </validation-provider>
                                                </v-col>
                                                <v-col cols="12" md="6">
                                                    <validation-provider class="input_form" v-slot="{ errors }" rules="required" name="Tipo de curso">
                                                        <v-select 
                                                            name="curso_tipo" 
                                                            :items="['Público general', 'Empresarial']" 
                                                            label="Tipo de curso" 
                                                            append-icon="mdi-domain" 
                                                            :error-messages="errors[0]" 
                                                            v-model="addCurso.curso_tipo" 
                                                            dense 
                                                            outlined 
                                                            shaped>
                                                        </v-select>
                                                    </validation-provider>
                                                    <input type="hidden" name="curso_tipo" :value="addCurso.curso_tipo == 'Público general' ? 1 : 2">
                                                </v-col>
                                                <v-col cols="12" md="6">
                                                    <validation-provider class="input_form" v-slot="{ errors }" rules="required|numeric" name="Duracion">
                                                        <v-text-field 
                                                            name="curso_duracion" 
                                                            label="Duracion" 
                                                            append-icon="mdi-notebook" 
                                                            hint="El valor numero esta expresado en dias"
                                                            :error-messages="errors[0]" 
                                                            v-model="addCurso.curso_duracion"
                                                            outlined 
                                                            shaped 
                                                        ></v-text-field>
                                                    </validation-provider>
                                                </v-col>
                                                <v-col cols="12" md="6">
                                                    <validation-provider class="input_form" v-slot="{ errors }" rules="required|numeric" name="Costo">
                                                        <v-text-field 
                                                            name="curso_costo" 
                                                            label="Costo" 
                                                            append-icon="mdi-notebook" 
                                                            :error-messages="errors[0]"
                                                            v-model="addCurso.curso_costo"
                                                            outlined 
                                                            shaped 
                                                            required
                                                        ></v-text-field>
                                                    </validation-provider>
                                                </v-col>
                                                <v-col cols="12" md="6">
                                                    <validation-provider class="input_form" v-slot="{ errors }" rules="required" name="Contacto">
                                                        <v-text-field 
                                                            name="curso_contacto" 
                                                            label="Contacto" 
                                                            append-icon="mdi-notebook" 
                                                            :error-messages="errors[0]"
                                                            v-model="addCurso.curso_contacto"
                                                            outlined 
                                                            shaped 
                                                            required
                                                        ></v-text-field>
                                                    </validation-provider>
                                                </v-col>
                                                <v-col cols="12" md="6">
                                                    <validation-provider class="input_form" v-slot="{ errors }" rules="required" name="Descripcion corta">
                                                        <v-textarea 
                                                            name="curso_descripcion_corta" 
                                                            label="Descripcion corta" 
                                                            clear-icon="mdi-broom" 
                                                            hint="Se recomienda una descripcion breve" 
                                                            :error-messages="errors[0]" 
                                                            v-model="addCurso.curso_descripcion_corta"
                                                            counter 
                                                            clearable 
                                                            auto-grow 
                                                            outlined 
                                                            shaped 
                                                            filled
                                                        ></v-textarea>
                                                    </validation-provider>
                                                </v-col>
                                                <v-col cols="12" md="6">
                                                    <validation-provider class="input_form" v-slot="{ errors }" rules="required" name="Descripcion larga">
                                                        <v-textarea 
                                                            name="curso_descripcion_larga" 
                                                            label="Descripcion larga" 
                                                            clear-icon="mdi-broom" 
                                                            :error-messages="errors[0]" 
                                                            v-model="addCurso.curso_descripcion_larga"
                                                            counter 
                                                            clearable 
                                                            auto-grow 
                                                            outlined 
                                                            shaped 
                                                            filled
                                                        ></v-textarea>
                                                    </validation-provider>
                                                </v-col>
                                                <v-col cols="12" md="6">
                                                    <v-alert outlined type="info">Fecha de inicio</v-alert>
                                                    <div class="d-flex">
                                                        <v-date-picker
                                                            v-model="addCurso.curso_fecha_inicio"
                                                            :first-day-of-week="0"
                                                            locale="es-mx"
                                                            class="mx-auto"
                                                        ></v-date-picker>
                                                    </div>
                                                    <input type="hidden" name="curso_fecha_inicio" :value="addCurso.curso_fecha_inicio">
                                                </v-col>
                                                <v-col cols="12" md="6">
                                                    <v-alert outlined type="info">Fecha de terminacion</v-alert>
                                                    <div class="d-flex">
                                                        <v-date-picker
                                                            v-model="addCurso.curso_fecha_terminacion"
                                                            :first-day-of-week="0"
                                                            locale="es-mx"
                                                            class="mx-auto"
                                                        ></v-date-picker>
                                                        <input type="hidden" name="curso_fecha_terminacion" :value="addCurso.curso_fecha_terminacion">
                                                    </div>
                                                </v-col>
                                                <v-col cols="12" v-if="disableCurso_Clave">
                                                    <validation-provider class="input_form" v-slot="{ errors }" rules="url" name="Foto del curso">
                                                        <v-text-field 
                                                            name="curso_foto" 
                                                            label="Foto del curso" 
                                                            append-icon="mdi-notebook" 
                                                            :error-messages="errors[0]"
                                                            v-model="addCurso.curso_foto"
                                                            hint="Si no introduce ninguna URL se usara una imagen por defecto"
                                                            persistent-hint
                                                            outlined 
                                                            shaped 
                                                            required
                                                        ></v-text-field>
                                                    </validation-provider>
                                                </v-col>
                                            </v-row>
                                            <v-row>
                                                <v-col cols="12" md="6">
                                                    <v-btn v-if="ActionForm == 'Crear'" :disabled="invalid" @click="handleSubmit(createCurso)" color="success" block>Crear</v-btn>
                                                    <v-btn v-if="ActionForm == 'Editar'" :disabled="invalid" @click="handleSubmit(updateCurso)" color="success" block>Editar</v-btn>
                                                </v-col>
                                                <v-col cols="12" sm="6" md="3">
                                                    <v-btn color="warning" @click="clear" block>Limpiar campos</v-btn>
                                                </v-col>
                                                <v-col cols="12" sm="6" md="3">
                                                    <v-btn color="danger" @click="close" dark block>Cancelar</v-btn>
                                                </v-col>
                                            </v-row>
                                        </v-form>
                                    </validation-observer>
                                </v-container>
                            </v-card-text>
                        </v-card>
                    </v-dialog>
                </v-toolbar>
            </template>
            <template v-slot:item.opciones="{ item }">
                <v-row>
                    <v-col cols="12">
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
                            <template v-slot:activator="{ on }">
                                <v-btn dark fab small color="danger" @click="deleteItem(item)" v-on="on">
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
            <template v-slot:item.curso_tipo="{ item }">
                <div v-if="item.curso_tipo == 1 || item.curso_tipo == 'Público general'">
                    <v-chip color="success" dark>
                        <strong>
                            Publico general
                        </strong>
                    </v-chip>
                </div>
                <div v-else-if="item.curso_tipo ==  2 || item.curso_tipo == 'Empresarial'">
                    <v-chip color="navDrawer" dark>
                        <strong>
                            Empresarial
                        </strong>
                    </v-chip>
                </div>
                <div v-else>
                    <v-chip color="danger" dark>
                        <strong>
                            {{ item.curso_tipo }}
                        </strong>
                    </v-chip>
                </div>
            </template>
            <template v-slot:item.curso_foto="{ item }">
                <div>
                    {{ item.curso_foto.replace("/admin/Api/Cursos/assets/","") }}
                </div>
            </template>
            <template v-slot:item.curso_clave="{ item }">
                <v-chip color="orange" dark>
                    <strong>
                        {{ item.curso_clave }}
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
    </v-card>
    <v-dialog v-model="deleteDialog.show" width="75%">
        <v-card>
            <v-card-title>
                <div class="headline" style="text-align: center;">
                    Seguro que desea eliminar el curso: {{ deleteDialog.curso_nombre }}
                </div>
            </v-card-title>
            <v-card-text>
                <v-btn color="danger" class="headline" @click="confirmDeleteItem" text>Eliminar</v-btn>
                <v-divider vertical></v-divider>
                <v-btn color="success" class="headline" @click="deleteDialog.show = false" dark text>Cancelar</v-btn>
            </v-card-text>
        </v-card>
    </v-dialog>
    <v-snackbar v-model="toastMessage.show" width="350px" :timeout="10000" absolute top right>
         <v-icon color="white">{{ toastMessage.icon }}</v-icon>-  {{ toastMessage.message }}
         <v-btn text @click="toastMessage.show = false">Cerrar</v-btn>
    </v-snackbar>
</div>
</template>

<script>
import { ValidationProvider, ValidationObserver } from 'vee-validate';

export default {
    transition: "slide",
    head: {
        title: "Cursos"
    },
    data: () => ({
        search: '',
        ActionForm: '',
        dialog: false,
        headers: [{
                text: 'Clave',
                align: 'left',
                value: 'curso_clave',
            },
            {
                text: 'Nombre',
                value: 'curso_nombre'
            },
            {
                text: 'Tipo',
                value: 'curso_tipo',
                align: 'center'
            },
            {
                text: 'Descripcion',
                value: 'curso_descripcion_corta',
                width: '225px'
            },
            {
                text: 'Descripcion Larga',
                value: 'curso_descripcion_larga',
                width: '325px'
            },
            {
                text: 'Duracion',
                value: 'curso_duracion'
            },
            {
                text: 'Costo',
                value: 'curso_costo'
            },
            {
                text: 'Contacto',
                value: 'curso_contacto'
            },
            {
                text: 'Fecha de inicio ',
                value: 'curso_fecha_inicio',
            },
            {
                text: 'Fecha de terminacion',
                value: 'curso_fecha_terminacion',
            },
            {
                text: 'Foto del curso',
                value: 'curso_foto',
            },
            {
                text: 'Opciones',
                value: 'opciones',
                sortable: false
            },
            
        ],
        cursos: [],
        editedIndex: -1,
        addCurso: {
            curso_clave:"",
            curso_nombre:"",
            curso_tipo: "",
            curso_descripcion_corta:"",
            curso_descripcion_larga:"",
            curso_duracion:"",
            curso_costo:"",
            curso_contacto:"",
            curso_fecha_inicio: new Date().toISOString().substr(0, 10),
            curso_fecha_terminacion: new Date().toISOString().substr(0, 10),
            curso_foto:""
        },
        disableCurso_Clave: true,
        deleteDialog: {
            show: false,
            curso_nombre: "",
            curso_clave:""
        },
        toastMessage:{
            show: false,
            message: "",
            icon: ""
        }
    }),
    components:{
        ValidationProvider,
        ValidationObserver
    },
    computed: {
        formTitle() {
            return this.editedIndex === -1 ? 'Crear nuevo curso' : 'Editar curso'
        },
        actionForm() {
            return this.editedIndex === -1 ? 'Crear curso' : 'Editar'
        }
    },
    watch: {
        dialog(val) {
            val || this.close()
        },
    },
    mounted() {
        this.initialize()    
    },
    methods: {
        async initialize() {
            this.showToastMessage("Espere un momento...", "mdi-timer-sand");
            try {
                const { data } = await this.$axios.post('/admin/Api/Cursos/')
                console.log(data)
                this.cursos = data    
                this.showToastMessage("Hecho", "mdi-check-bold");
            } catch (error) {
                this.showToastMessage("A ocurrido un error inesperado", "mdi-close")
                console.error(error)   
            }
        },
        editItem(item) {
            this.editedIndex = this.cursos.indexOf(item)
            this.ActionForm = 'Editar'
            this.disableCurso_Clave = false
            this.dialog = true
            if(item.curso_tipo == 1 || item.curso_tipo == 'Público general') 
                item.curso_tipo = 'Público general'
            else 
                item.curso_tipo = 'Empresarial'
            this.addCurso = Object.assign({}, item)
        },
        deleteItem(item) {
            this.deleteDialog.show = true
            this.deleteDialog.curso_nombre = item.curso_nombre
            this.deleteDialog.curso_clave = item.curso_clave
        },
        async confirmDeleteItem(){
            this.showToastMessage("Espere un momento...", "mdi-timer-sand")
            try {
                let formData = new FormData()
                formData.append("curso_clave", this.deleteDialog.curso_clave)
                let { data } = await this.$axios.post('/admin/Api/Cursos/Eliminar.php', formData)
                this.deleteDialog.show = false
                this.showToastMessage("Operacion completada", "mdi-check-bold")
                this.initialize()
            } catch (error) {
                console.log(error)                
                this.showToastMessage("A ocurrio un error inesperado", "mdi-close")
            }
        },
        async createCurso(){
            //Crear
            this.showToastMessage("Creando el curso, espere...","mdi-timer-sand")
            try {
                const form = document.getElementById('cursoForm')
                let formData = new FormData(form)
                const { data } = await this.$axios.post('/admin/Api/Cursos/Crear.php',formData)
                console.log(data)
                formData.forEach( value =>{
                    console.log(value)
                })
                this.close()
                this.showToastMessage("Curso creado","mdi-check-bold")
                this.initialize()
            } catch (error) {
                this.showToastMessage("A ocurrido un error inesperado","mdi-close")
                console.error(error)
            }
        },
        async updateCurso(){
            //Editar
            this.showToastMessage("Actualizando curso","mdi-timer-sand")
            try {
                Object.assign(this.cursos[this.editedIndex], this.editedItem)
                const form = document.getElementById('cursoForm')
                let formData = new FormData(form)
                formData.append('curso_tipo',this.addCurso.curso_tipo == 'Público general' ? 1 : 2)
                const { data } = await this.$axios.post('/admin/Api/Cursos/Actualizar.php',formData)
                this.close()
                this.showToastMessage("Curso actualizado","mdi-check-bold")
                this.initialize()
            } catch (error) {
                this.showToastMessage("A ocurrido un error inesperado","mdi-close")
                console.error(error)
            }
        },
        showToastMessage(message, icon){
            this.toastMessage.show = true
            this.toastMessage.message = message
            this.toastMessage.icon = icon
        },
        close() {
            this.disableCurso_Clave = true
            this.dialog = false
            setTimeout(() => {
                this.editedItem = Object.assign({}, this.defaultItem)
                this.editedIndex = -1
                this.addCurso.curso_clave = ""
                this.addCurso.curso_nombre = ""
                this.addCurso.curso_descripcion_corta = ""
                this.addCurso.curso_descripcion_larga = ""
                this.addCurso.curso_duracion = ""
                this.addCurso.curso_costo = ""
                this.addCurso.curso_contacto = ""
                this.addCurso.curso_fecha_inicio = ""
                this.addCurso.curso_fecha_terminacion = ""
            }, 300)
        },
        clear() {
            this.$refs.cursoForm.reset();
        }
    }
}
</script>

<style lang="scss">
.page_cursos {
    padding-top: 10px !important;
    padding-bottom: 80px !important;
}

#main_table {
    tr {
        border-bottom: 10px solid #000 !important;
    }

    tbody>tr {
        border-bottom: 10px solid #000 !important;

    }
}
</style>
