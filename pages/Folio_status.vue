<template>
<div>
    <v-card>
        <v-card-title>
            <v-toolbar-title class="display-1">Folio status</v-toolbar-title>
            <v-divider class="mx-4" inset vertical></v-divider>
            <v-spacer></v-spacer>
            <v-text-field v-model="search" append-icon="mdi-search" label="Buscar" single-line hide-details></v-text-field>
        </v-card-title>
        <v-card-text>
            <v-data-table id="main_table" :headers="headers" :items="folios" :search="search" sort-by="fs_id" sort-desc class="elevation-1" :mobile-breakpoint="$store.state.tableBreakpoint">
                <template v-slot:top>
                    <v-dialog v-model="dialog" max-width="90%" scrollable persistent>>
                        <validation-observer v-slot="{ invalid, handleSubmit }">
                            <v-card>
                                <v-card-title>
                                    {{ formTitle }}
                                </v-card-title>
                                <v-card-text>
                                    <v-container>
                                        <v-form ref="folioForm" id="folioForm" name="folioForm">
                                          <input v-if="editedIndex > -1" type="hidden" name="fs_id" :value="currentFolio.fs_id">
                                          <v-row>
                                            <v-col cols="12" md="6">
                                                <validation-provider v-slot="{ errors }" rules="required" name="Folio">
                                                    <v-text-field 
                                                        name="fs_folio" 
                                                        label="Folio" 
                                                        append-icon="mdi-account" 
                                                        :error-messages="errors[0]"
                                                        v-model="currentFolio.fs_folio"
                                                        :disabled="editedIndex > -1"
                                                        outlined 
                                                        shaped 
                                                        required
                                                    ></v-text-field>
                                                </validation-provider>
                                              </v-col>
                                              <v-col cols="12" md="6">
                                                  <validation-provider disabled v-slot="{ errors }" rules="required" name="Tipo">
                                                      <v-select 
                                                          name="fs_tipos" 
                                                          :items="['Publico general', 'Empresarial']" 
                                                          label="Tipo" 
                                                          append-icon="mdi-domain" 
                                                          :error-messages="errors[0]" 
                                                          v-model="currentFolio.fs_tipo" 
                                                          :disabled="editedIndex > -1"
                                                          dense 
                                                          outlined 
                                                          shaped>
                                                      </v-select>
                                                  </validation-provider>
                                                  <input type="hidden" name="fs_tipo" :value="currentFolio.fs_tipo == 'Publico general' ? 1 : 2">
                                              </v-col>
                                              <v-col cols="12" md="6">
                                                  <validation-provider v-slot="{ errors }" rules="required" name="Estatus">
                                                      <v-select 
                                                          name="fs_estatus" 
                                                          :items="['Adeudo pendiente', 'Pagado']" 
                                                          label="Estatus" 
                                                          append-icon="mdi-domain" 
                                                          :error-messages="errors[0]" 
                                                          v-model="currentFolio.fs_estatus" 
                                                          dense 
                                                          outlined 
                                                          shaped>
                                                      </v-select>
                                                  </validation-provider>
                                                  <input type="hidden" name="fs_estatus" :value="currentFolio.fs_estatus == 'Adeudo pendiente' ? 0 : 1">
                                              </v-col>
                                              <v-col cols="12" md="6">
                                                <validation-provider v-slot="{ errors }" rules="required" name="Clave curso">
                                                    <v-text-field 
                                                        name="fs_clave_curso" 
                                                        label="Clave curso" 
                                                        append-icon="mdi-account" 
                                                        :error-messages="errors[0]"
                                                        v-model="currentFolio.fs_clave_curso"
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
                                            <v-btn @click="$refs.folioForm.reset()" color="warning" block>Limpiar campos</v-btn>
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
                <template v-slot:item.opciones="{ item }">
                    <v-row>
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
                        <v-col cols="12" lg="6">
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
                <template v-slot:item.fs_id="{ item }">
                  <v-chip color="orange" dark>
                    <strong>
                      {{ item.fs_id }}
                    </strong>
                  </v-chip>
                </template>
                <template v-slot:item.fs_estatus="{ item }">
                  <v-chip v-if="item.fs_estatus == 'Pagado' || item.fs_estatus == 1" color="success" dark>
                    <strong>
                      Pagado
                    </strong>
                  </v-chip>
                  <v-chip v-else-if="item.fs_estatus == 'Adeudo pendiente' || item.fs_estatus == 0" color="danger" dark>
                    <strong>
                      Adeudo pendiente
                    </strong>
                  </v-chip>
                  <v-chip v-else color="warning" dark>
                    <strong>
                      Desconocido
                    </strong>
                  </v-chip>
                </template>
                <template v-slot:item.fs_tipo="{ item }">
                  <v-chip v-if="item.fs_tipo == 'Publico general' || item.fs_tipo == 1" color="success" dark>
                    <strong>
                      Publico general
                    </strong>
                  </v-chip>
                  <v-chip v-else-if="item.fs_tipo == 'Empresarial' || item.fs_tipo == 2" color="navDrawer" dark>
                    <strong>
                      Empresarial
                    </strong>
                  </v-chip>
                  <v-chip v-else color="warning" dark>
                    <strong>
                      Desconocido
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
    <v-dialog v-model="deleteDialog.show" width="75%">
        <v-card>
            <v-card-text>
                <div class="headline" style="text-align: center;">
                    ¿Seguro que desea eliminar el folio de {{ deleteDialog.nombre }} con el ID {{ deleteDialog.fs_id }}?
                </div>
            </v-card-text>
            <v-card-actions>
                <v-btn color="danger" class="headline" @click="confirmDeleteItem" text>Eliminar</v-btn>
                <v-divider vertical></v-divider>
                <v-btn color="success" class="headline" @click="deleteDialog.show = false" dark text>Cancelar</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
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
        title: "Folio status"
    },
    data() {
        return {
            search: '',
            headers: [{
                    text: 'ID',
                    value: 'fs_id'
                },
                {
                    text: 'Folio',
                    value: 'fs_folio'
                },
                {
                    text: 'Nombre',
                    value: 'datosPreregistro.nombre'
                },
                {
                    text: 'Estatus',
                    value: 'fs_estatus'
                },
                {
                    text: 'Tipo',
                    value: 'fs_tipo'
                },
                {
                    text: 'Clave curso',
                    value: 'fs_clave_curso'
                },
                {
                    text: 'Opciones',
                    value: 'opciones',
                    sortable: false,
                    align: 'center'
                },
            ],
            folios: [],
            dialog: false,
            editedIndex: -1,
            toastMessage: {
                show: false,
                message: "",
                icon: ""
            },
            deleteDialog: {
                show: false,
                fs_id: "",
                nombre: ""
            },
            currentFolio:{
              fs_id:"",
              fs_folio:"",
              fs_estatus: "",
              fs_tipo: "",
              fs_clave_curso:""
            }
        }
    },
    mounted() {
        this.initialize()
    },
    methods: {
        async initialize() {
            this.showToastMessage("Espere un momento...", "mdi-timer-sand");
            try {
                const {
                    data
                } = await this.$axios.post('/admin/Api/Folio_status/')
                this.folios = data  
                this.showToastMessage("Hecho", "mdi-check-bold");
            } catch (error) {
                this.showToastMessage("A ocurrido un error inesperado", "mdi-close")
                console.error(error)
            }
        },
        editItem(item) {
            this.editedIndex = this.folios.indexOf(item)
            this.dialog = true
            if(parseInt(item.fs_estatus) == 0 || item.fs_estatus == 'Adeudo pendiente') {
                item.fs_estatus = 'Adeudo pendiente'
            } else { 
                item.fs_estatus = 'Pagado'
            }
            if(parseInt(item.fs_tipo) == 1 || item.fs_tipo == 'Publico general') {
                item.fs_tipo = 'Publico general'
            } else { 
                item.fs_tipo = 'Empresarial'
            }
            this.currentFolio = Object.assign({}, item)
        },
        deleteItem(item) {
            this.deleteDialog.show = true
            this.deleteDialog.fs_id = item.fs_id
            this.deleteDialog.nombre = item.datosPreregistro.nombre
        },
        async confirmDeleteItem() {
            this.showToastMessage("Espere un momento...", "mdi-timer-sand")
            try {
                const formData = new FormData()
                formData.append('fs_id', this.deleteDialog.fs_id)
                const {
                    data
                } = await this.$axios.post('/admin/Api/Folio_status/Eliminar.php', formData)
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
                this.showToastMessage("Actualizando folio","mdi-timer-sand")
                try {
                    const form = document.getElementById('folioForm')
                    let formData = new FormData(form)
                    const { data } = await this.$axios.post('/admin/Api/Folio_status/Actualizar.php',formData)
                    console.log(data)
                    this.close()
                    this.showToastMessage("Folio actualizado","mdi-check-bold")
                    this.initialize()
                } catch (error) {
                    this.showToastMessage("A ocurrido un error inesperado","mdi-close")
                    console.error(error)
                }
            } else {
                // this.showToastMessage("Creando preregistro","mdi-timer-sand")
                // try {
                //     const form = document.getElementById('preregistroForm')
                //     let formData = new FormData(form)
                //     const { data } = await this.$axios.post('Preregistros/Crear.php',formData)
                //     console.log(data)
                //     this.close()
                //     this.showToastMessage("Preregistro creado","mdi-check-bold")
                //     this.initialize()
                // } catch (error) {
                //     this.showToastMessage("A ocurrido un error inesperado","mdi-close")
                //     console.error(error)
                // }
            }
            
        },
        close() {
            this.dialog = false
            setTimeout(() => {
                this.editedIndex = -1
                this.currentFolio.fs_id = ""
                this.currentFolio.fs_folio = ""
                this.currentFolio.fs_clave_curso = ""
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
            return this.editedIndex === -1 ? 'Crear folio' : 'Editar folio'
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
