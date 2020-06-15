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
                    <!-- <v-sheet :elevation="24" class="pa-3 my-3">
                        <v-row no-gutters>
                            <v-col cols="12" class="d-flex">
                                <v-btn @click="dialog = true" color="success" dark class="mx-auto my-1">Crear nuevo registro</v-btn>
                            </v-col>
                        </v-row>
            </v-sheet>-->
                    <v-dialog v-model="dialog" max-width="90%" scrollable persistent>
                        <validation-observer v-slot="{ invalid, handleSubmit }">
                            <v-card>
                                <v-card-title>{{ formTitle }}</v-card-title>
                                <v-card-text>
                                    <v-container>
                                        <v-form ref="foliosForm" id="foliosForm" name="foliosForm">
                                            <div v-if="editedIndex == -1">
                                                <v-row>
                                                    <v-col cols="12" md="6">
                                                        <validation-provider v-slot="{ errors }" rules="required" name="Nombre">
                                                            <v-text-field name="rc_nombre" label="Nombre" append-icon="mdi-account" :error-messages="errors[0]" v-model="newRegistro.rc_nombre" outlined shaped required></v-text-field>
                                                        </validation-provider>
                                                    </v-col>
                                                    <v-col cols="12" md="6">
                                                        <validation-provider v-slot="{ errors }" rules="required" name="Apellido paterno">
                                                            <v-text-field name="rc_app" label="Apellido paterno" append-icon="mdi-account" :error-messages="errors[0]" v-model="newRegistro.rc_app" outlined shaped required></v-text-field>
                                                        </validation-provider>
                                                    </v-col>
                                                    <v-col cols="12" md="6">
                                                        <validation-provider v-slot="{ errors }" rules="required" name="Apellido materno">
                                                            <v-text-field name="rc_apm" label="Apellido materno" append-icon="mdi-account" :error-messages="errors[0]" v-model="newRegistro.rc_apm" outlined shaped required></v-text-field>
                                                        </validation-provider>
                                                    </v-col>
                                                    <v-col cols="12" md="6">
                                                        <validation-provider v-slot="{ errors }" rules="required" name="Fecha de nacimiento">
                                                            <v-alert outlined type="info">Fecha de nacimiento</v-alert>
                                                            <v-alert v-show="errors[0]" type="error" outlined>{{ errors[0] }}</v-alert>
                                                            <div class="d-flex">
                                                                <v-date-picker v-model="newRegistro.rc_fecha_nacimiento" :first-day-of-week="0" locale="es-mx" class="mx-auto"></v-date-picker>
                                                                <input type="hidden" name="rc_fecha_nacimiento" :value="newRegistro.rc_fecha_nacimiento" />
                                                            </div>
                                                        </validation-provider>
                                                    </v-col>
                                                    <v-col cols="12" md="6">
                                                        <validation-provider v-slot="{ errors }" rules="required" name="Lugar de nacimiento">
                                                            <v-text-field name="rc_lugar_nacimiento" label="Lugar de nacimiento" append-icon="mdi-account" :error-messages="errors[0]" v-model="newRegistro.rc_lugar_nacimiento" outlined shaped required></v-text-field>
                                                        </validation-provider>
                                                    </v-col>
                                                    <v-col cols="12" md="6">
                                                        <validation-provider v-slot="{ errors }" rules="required" name="Medio de contacto">
                                                            <v-text-field name="rc_medio_contacto" label="Medio de contacto" append-icon="mdi-account" :error-messages="errors[0]" v-model="newRegistro.rc_medio_contacto" outlined shaped required></v-text-field>
                                                        </validation-provider>
                                                    </v-col>
                                                    <v-col cols="12" md="6">
                                                        <validation-provider v-slot="{ errors }" rules="required" name="CURP">
                                                            <v-text-field name="rc_curp" label="CURP" append-icon="mdi-account" :error-messages="errors[0]" v-model="newRegistro.rc_curp" outlined shaped required></v-text-field>
                                                        </validation-provider>
                                                    </v-col>
                                                    <v-col cols="12" md="6">
                                                        <validation-provider class="input_form" v-slot="{ errors }" rules="required" name="Genero">
                                                            <v-select name="rc_genero" :items="['Masculino', 'Femenino']" label="Genero" append-icon="mdi-domain" :error-messages="errors[0]" v-model="newRegistro.rc_genero" dense outlined shaped></v-select>
                                                        </validation-provider>
                                                        <input type="hidden" name="rc_genero" :value="newRegistro.rc_genero" />
                                                    </v-col>
                                                    <v-col cols="12" md="6">
                                                        <validation-provider class="input_form" v-slot="{ errors }" rules="required" name="Estado civil">
                                                            <v-select name="rc_estado_civil" :items="['Soltero', 'Casado', 'Divorciado', 'Sin especificar']" label="Estado civil" append-icon="mdi-domain" :error-messages="errors[0]" v-model="newRegistro.rc_estado_civil" dense outlined shaped></v-select>
                                                        </validation-provider>
                                                        <input type="hidden" name="rc_estado_civil" :value="newRegistro.rc_estado_civil" />
                                                    </v-col>
                                                    <v-col cols="12" md="6">
                                                        <v-row>
                                                            <v-col cols="12">
                                                                <v-alert outlined type="info">En caso de emergencia avisar a:</v-alert>
                                                            </v-col>
                                                            <v-col cols="12" md="6">
                                                                <validation-provider v-slot="{ errors }" rules="required" name="Nombre completo">
                                                                    <v-text-field name="rc_emergencia[rc_nombre]" label="Nombre completo" append-icon="mdi-account" :error-messages="errors[0]" v-model="newRegistro.rc_emergencia.rc_nombre" outlined shaped required></v-text-field>
                                                                </validation-provider>
                                                            </v-col>
                                                            <v-col cols="12" md="6">
                                                                <validation-provider v-slot="{ errors }" rules="required" name="Parentesco">
                                                                    <v-text-field name="rc_emergencia[rc_parentesco]" label="Parentesco" append-icon="mdi-account" :error-messages="errors[0]" v-model="newRegistro.rc_emergencia.rc_parentesco" outlined shaped required></v-text-field>
                                                                </validation-provider>
                                                            </v-col>
                                                            <v-col cols="12" md="6">
                                                                <validation-provider v-slot="{ errors }" rules="required" name="Telefono">
                                                                    <v-text-field name="rc_emergencia[rc_telefono]" label="Telefono" append-icon="mdi-account" :error-messages="errors[0]" v-model="newRegistro.rc_emergencia.rc_telefono" outlined shaped required></v-text-field>
                                                                </validation-provider>
                                                            </v-col>
                                                            <v-col cols="12" md="6">
                                                                <validation-provider v-slot="{ errors }" rules="required" name="Domicilio">
                                                                    <v-text-field name="rc_emergencia[rc_domicilio]" label="Domicilio" append-icon="mdi-account" :error-messages="errors[0]" v-model="newRegistro.rc_emergencia.rc_domicilio" outlined shaped required></v-text-field>
                                                                </validation-provider>
                                                            </v-col>
                                                        </v-row>
                                                    </v-col>
                                                    <v-col cols="12" md="6">
                                                        <validation-provider v-slot="{ errors }" rules="required" name="Utilidad curso">
                                                            <v-text-field name="rc_comentarios[rc_utilidad_curso]" label="¿Que utilidad tiene para ti el curso?" append-icon="mdi-account" :error-messages="errors[0]" v-model="newRegistro.rc_comentarios.rc_utilidad_curso" outlined shaped required></v-text-field>
                                                        </validation-provider>
                                                    </v-col>
                                                    <v-col cols="12" md="6">
                                                        <validation-provider class="input_form" v-slot="{ errors }" rules="required" name="¿Como se entero del curso?">
                                                            <v-select name="rc_comentarios[rc_enterar_curso]" :items="['Amigo', 'Familiar', 'Escuela', 'Trabajo', 'Redes Sociales']" label="¿Como se entero del curso?" append-icon="mdi-domain" :error-messages="errors[0]" v-model="newRegistro.rc_comentarios.rc_enterar_curso" dense outlined shaped></v-select>
                                                        </validation-provider>
                                                        <input type="hidden" name="rc_comentarios[rc_enterar_curso]" :value="newRegistro.rc_comentarios.rc_enterar_curso" />
                                                    </v-col>
                                                </v-row>
                                                <v-alert outlined type="info">Servicio medico</v-alert>
                                                <v-row>
                                                    <v-col cols="12" md="6">
                                                        <validation-provider v-slot="{ errors }" rules="required" name="Servicio medico">
                                                            <v-text-field name="rc_servicio_medico[rc_servicio_medico]" label="Servicio medico" append-icon="mdi-account" :error-messages="errors[0]" v-model="newRegistro.rc_servicio_medico.rc_servicio_medico" outlined shaped required></v-text-field>
                                                        </validation-provider>
                                                    </v-col>
                                                    <v-col cols="12" md="6">
                                                        <validation-provider v-slot="{ errors }" rules="required" name="Numero de afiliacion">
                                                            <v-text-field name="rc_servicio_medico[rc_afiliacion]" label="Numero de afilizacion" append-icon="mdi-account" :error-messages="errors[0]" v-model="newRegistro.rc_servicio_medico.rc_afiliacion" outlined shaped required></v-text-field>
                                                        </validation-provider>
                                                    </v-col>
                                                    <v-col cols="12" md="6">
                                                        <validation-provider v-slot="{ errors }" rules="required" name="Seguro">
                                                            <v-text-field name="rc_servicio_medico[rc_seguro]" label="Seguro" append-icon="mdi-account" :error-messages="errors[0]" v-model="newRegistro.rc_servicio_medico.rc_seguro" outlined shaped required></v-text-field>
                                                        </validation-provider>
                                                    </v-col>
                                                    <v-col cols="12" md="6">
                                                        <validation-provider v-slot="{ errors }" rules="required" name="Tipo">
                                                            <v-text-field name="rc_servicio_medico[rc_tipo]" label="Tipo" append-icon="mdi-account" :error-messages="errors[0]" v-model="newRegistro.rc_servicio_medico.rc_tipo" outlined shaped required></v-text-field>
                                                        </validation-provider>
                                                    </v-col>
                                                    <v-col cols="12" md="6">
                                                        <validation-provider v-slot="{ errors }" rules="required" name="Compañia">
                                                            <v-text-field name="rc_servicio_medico[rc_compañia]" label="Compañia" append-icon="mdi-account" :error-messages="errors[0]" v-model="newRegistro.rc_servicio_medico.rc_compañia" outlined shaped required></v-text-field>
                                                        </validation-provider>
                                                    </v-col>
                                                    <v-col cols="12" md="6">
                                                        <validation-provider v-slot="{ errors }" rules="required" name="Grupo sanguineo">
                                                            <v-text-field name="rc_servicio_medico[rc_grupo_sanguineo]" label="Grupo sanguineo" append-icon="mdi-account" :error-messages="errors[0]" v-model="newRegistro.rc_servicio_medico.rc_grupo_sanguineo" outlined shaped required></v-text-field>
                                                        </validation-provider>
                                                    </v-col>
                                                    <v-col cols="12" md="6">
                                                        <validation-provider v-slot="{ errors }" rules="required" name="Alergias">
                                                            <v-text-field name="rc_servicio_medico[rc_alergias]" label="Alergias" append-icon="mdi-account" :error-messages="errors[0]" v-model="newRegistro.rc_servicio_medico.rc_alergias" outlined shaped required></v-text-field>
                                                        </validation-provider>
                                                    </v-col>
                                                    <v-col cols="12" md="6">
                                                        <validation-provider v-slot="{ errors }" rules="required" name="Enfermedades cronicas">
                                                            <v-text-field name="rc_servicio_medico[rc_enfermedades_cronicas]" label="Enfermedades cronicas" append-icon="mdi-account" :error-messages="errors[0]" v-model="newRegistro.rc_servicio_medico.rc_enfermedades_cronicas" outlined shaped required></v-text-field>
                                                        </validation-provider>
                                                    </v-col>
                                                    <v-col cols="12" md="6">
                                                        <validation-provider v-slot="{ errors }" rules="required" name="Cirugias">
                                                            <v-text-field name="rc_servicio_medico[rc_cirugias]" label="Cirugias" append-icon="mdi-account" :error-messages="errors[0]" v-model="newRegistro.rc_servicio_medico.rc_cirugias" outlined shaped required></v-text-field>
                                                        </validation-provider>
                                                    </v-col>
                                                    <v-col cols="12" md="6">
                                                        <validation-provider v-slot="{ errors }" rules="required" name="Problemas fisicos">
                                                            <v-text-field name="rc_servicio_medico[rc_problemas_fisicos]" label="Problemas fisicos" append-icon="mdi-account" :error-messages="errors[0]" v-model="newRegistro.rc_servicio_medico.rc_problemas_fisicos" outlined shaped required></v-text-field>
                                                        </validation-provider>
                                                    </v-col>
                                                </v-row>
                                                <v-alert outlined type="info">Trabajo o escuela</v-alert>
                                                <v-row>
                                                    <v-col cols="12" md="6">
                                                        <validation-provider v-slot="{ errors }" rules="required" name="Nombre">
                                                            <v-text-field name="rc_trabajo_escuela[rc_nombre]" label="Nombre" append-icon="mdi-account" :error-messages="errors[0]" v-model="newRegistro.rc_trabajo_escuela.rc_nombre" outlined shaped required></v-text-field>
                                                        </validation-provider>
                                                    </v-col>
                                                    <v-col cols="12" md="6">
                                                        <validation-provider v-slot="{ errors }" rules="required" name="Telefono">
                                                            <v-text-field name="rc_trabajo_escuela[rc_telefono]" label="Telefono" append-icon="mdi-account" :error-messages="errors[0]" v-model="newRegistro.rc_trabajo_escuela.rc_telefono" outlined shaped required></v-text-field>
                                                        </validation-provider>
                                                    </v-col>
                                                    <v-col cols="12" md="6">
                                                        <validation-provider v-slot="{ errors }" rules="required" name="Direccion">
                                                            <v-text-field name="rc_trabajo_escuela[rc_direccion]" label="Direccion" append-icon="mdi-account" :error-messages="errors[0]" v-model="newRegistro.rc_trabajo_escuela.rc_direccion" outlined shaped required></v-text-field>
                                                        </validation-provider>
                                                    </v-col>
                                                    <v-col cols="12" md="6">
                                                        <validation-provider v-slot="{ errors }" rules="required" name="Puesto">
                                                            <v-text-field name="rc_trabajo_escuela[rc_puesto]" label="Puesto" append-icon="mdi-account" :error-messages="errors[0]" v-model="newRegistro.rc_trabajo_escuela.rc_puesto" outlined shaped required></v-text-field>
                                                        </validation-provider>
                                                    </v-col>
                                                    <v-col cols="12" md="6">
                                                        <validation-provider v-slot="{ errors }" rules="required" name="Carrera">
                                                            <v-text-field name="rc_trabajo_escuela[rc_carrera]" label="Carrera" append-icon="mdi-account" :error-messages="errors[0]" v-model="newRegistro.rc_trabajo_escuela.rc_carrera" outlined shaped required></v-text-field>
                                                        </validation-provider>
                                                    </v-col>
                                                    <v-col cols="12" md="6">
                                                        <validation-provider v-slot="{ errors }" rules="required" name="Horario">
                                                            <v-text-field name="rc_trabajo_escuela[rc_horario]" label="Horario" append-icon="mdi-account" :error-messages="errors[0]" v-model="newRegistro.rc_trabajo_escuela.rc_horario" outlined shaped required></v-text-field>
                                                        </validation-provider>
                                                    </v-col>
                                                    <v-col cols="12" md="6">
                                                        <validation-provider v-slot="{ errors }" rules="required" name="Titulo">
                                                            <v-text-field name="rc_trabajo_escuela[rc_titulo]" label="Titulo" append-icon="mdi-account" :error-messages="errors[0]" v-model="newRegistro.rc_trabajo_escuela.rc_titulo" outlined shaped required></v-text-field>
                                                        </validation-provider>
                                                    </v-col>
                                                </v-row>
                                                <v-alert type="info" outlined>Domicilio</v-alert>
                                                <v-row>
                                                    <v-col cols="12" md="6">
                                                        <validation-provider v-slot="{ errors }" rules="required" name="Calle">
                                                            <v-text-field name="rc_domicilio[rc_calle]" label="Calle" append-icon="mdi-account" :error-messages="errors[0]" v-model="newRegistro.rc_domicilio.rc_calle" outlined shaped required></v-text-field>
                                                        </validation-provider>
                                                    </v-col>
                                                    <v-col cols="12" md="6">
                                                        <validation-provider v-slot="{ errors }" rules="required" name="Colonia">
                                                            <v-text-field name="rc_domicilio[rc_colonia]" label="Colonia" append-icon="mdi-account" :error-messages="errors[0]" v-model="newRegistro.rc_domicilio.rc_colonia" outlined shaped required></v-text-field>
                                                        </validation-provider>
                                                    </v-col>
                                                    <v-col cols="12" md="6">
                                                        <validation-provider v-slot="{ errors }" rules="required" name="Municipio">
                                                            <v-text-field name="rc_domicilio[rc_municipio]" label="Municipio" append-icon="mdi-account" :error-messages="errors[0]" v-model="newRegistro.rc_domicilio.rc_municipio" outlined shaped required></v-text-field>
                                                        </validation-provider>
                                                    </v-col>
                                                </v-row>
                                            </div>
                                            <div v-if="editedIndex > -1">
                                                <v-row>
                                                    <v-col cols="12" md="6" v-show="false">
                                                        <validation-provider v-slot="{ errors }" rules="required" name="ID">
                                                            <v-text-field 
                                                                name="rc_id" 
                                                                label="ID" 
                                                                append-icon="mdi-account" 
                                                                :error-messages="errors[0]" 
                                                                v-model="currentRegistro.rc_id" 
                                                                outlined 
                                                                shaped 
                                                                required
                                                            ></v-text-field>
                                                        </validation-provider>
                                                    </v-col>
                                                    <v-col cols="12" md="6" v-show="false">
                                                        <validation-provider v-slot="{ errors }" rules="required" name="Clave registro">
                                                            <v-text-field 
                                                                name="rc_clave_registro" 
                                                                label="Clave registro" 
                                                                append-icon="mdi-account" 
                                                                :error-messages="errors[0]" 
                                                                v-model="currentRegistro.rc_clave_registro" 
                                                                outlined 
                                                                shaped 
                                                                required
                                                            ></v-text-field>
                                                        </validation-provider>
                                                    </v-col>
                                                    <v-col cols="12" md="6"  v-show="false">
                                                        <validation-provider v-slot="{ errors }" rules="required" name="Clave registro">
                                                            <v-text-field 
                                                                name="rc_clave_registro" 
                                                                label="Clave registro" 
                                                                append-icon="mdi-account" 
                                                                :error-messages="errors[0]" 
                                                                v-model="currentRegistro.rc_clave_registro" 
                                                                outlined 
                                                                shaped 
                                                                required
                                                            ></v-text-field>
                                                        </validation-provider>
                                                    </v-col>
                                                    <v-col cols="12" md="6" v-show="false">
                                                        <validation-provider v-slot="{ errors }" rules="required" name="Clave curso">
                                                            <v-text-field 
                                                                name="rc_clave_curso" 
                                                                label="Clave curso" 
                                                                append-icon="mdi-account" 
                                                                :error-messages="errors[0]" 
                                                                v-model="currentRegistro.rc_clave_curso" 
                                                                outlined 
                                                                shaped 
                                                                required
                                                            ></v-text-field>
                                                        </validation-provider>
                                                    </v-col>
                                                    <v-col cols="12" md="6" v-show="false">
                                                        <validation-provider v-slot="{ errors }" rules="required" name="Folio preregistro">
                                                            <v-text-field 
                                                                name="rc_folio_preregistro" 
                                                                label="Folio preregistro" 
                                                                append-icon="mdi-account" 
                                                                :error-messages="errors[0]" 
                                                                v-model="currentRegistro.rc_folio_preregistro" 
                                                                outlined 
                                                                shaped 
                                                                required
                                                            ></v-text-field>
                                                        </validation-provider>
                                                    </v-col>
                                                    <v-col cols="12" md="6">
                                                        <validation-provider v-slot="{ errors }" rules="required" name="Nombre">
                                                            <v-text-field 
                                                                name="rc_nombre" 
                                                                label="Nombre" 
                                                                append-icon="mdi-account" 
                                                                :error-messages="errors[0]" 
                                                                v-model="currentRegistro.rc_nombre" 
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
                                                                v-model="currentRegistro.rc_app" 
                                                                outlined 
                                                                shaped 
                                                                required
                                                            ></v-text-field>
                                                        </validation-provider>
                                                    </v-col>
                                                    <v-col cols="12" md="6">
                                                        <validation-provider v-slot="{ errors }" rules="required" name="Apellido materno">
                                                            <v-text-field 
                                                                name="rc_apm" 
                                                                label="Apellido materno" 
                                                                append-icon="mdi-account" 
                                                                :error-messages="errors[0]" 
                                                                v-model="currentRegistro.rc_apm" 
                                                                outlined 
                                                                shaped 
                                                                required
                                                            ></v-text-field>
                                                        </validation-provider>
                                                    </v-col>
                                                    <v-col cols="12" md="6">
                                                        <validation-provider class="input_form" v-slot="{ errors }" rules="required" name="Genero">
                                                            <v-select 
                                                                name="rc_genero" 
                                                                :items="['Masculino', 'Femenino']" 
                                                                label="Genero" 
                                                                append-icon="mdi-domain" 
                                                                :error-messages="errors[0]" 
                                                                v-model="currentRegistro.rc_genero" 
                                                                dense 
                                                                outlined 
                                                                shaped
                                                            ></v-select>
                                                        </validation-provider>
                                                        <input type="hidden" name="rc_genero" :value="currentRegistro.rc_genero" />
                                                    </v-col>
                                                    <v-col cols="12" md="6">
                                                        <validation-provider v-slot="{ errors }" rules="required" name="Fecha de nacimiento">
                                                            <v-text-field 
                                                                name="rc_fecha_nacimiento" 
                                                                label="Fecha de nacimiento" 
                                                                append-icon="mdi-account" 
                                                                :error-messages="errors[0]" 
                                                                v-model="currentRegistro.rc_fecha_nacimiento" 
                                                                outlined 
                                                                shaped 
                                                                required
                                                            ></v-text-field>
                                                        </validation-provider>
                                                    </v-col>
                                                    <v-col cols="12" md="6">
                                                        <validation-provider v-slot="{ errors }" rules="required" name="Lugar de nacimiento">
                                                            <v-text-field 
                                                                name="rc_lugar_nacimiento" 
                                                                label="Lugar de nacimiento" 
                                                                append-icon="mdi-account" 
                                                                :error-messages="errors[0]" 
                                                                v-model="currentRegistro.rc_lugar_nacimiento" 
                                                                outlined 
                                                                shaped 
                                                                required
                                                            ></v-text-field>
                                                        </validation-provider>
                                                    </v-col>
                                                    <v-col cols="12" md="6">
                                                        <validation-provider v-slot="{ errors }" rules="required" name="Nacionalidad">
                                                            <v-text-field 
                                                                name="rc_nacionalidad" 
                                                                label="Nacionalidad" 
                                                                append-icon="mdi-account" 
                                                                :error-messages="errors[0]" 
                                                                v-model="currentRegistro.rc_nacionalidad" 
                                                                outlined 
                                                                shaped 
                                                                required
                                                            ></v-text-field>
                                                        </validation-provider>
                                                    </v-col>
                                                    <v-col cols="12" md="6">
                                                        <validation-provider class="input_form" v-slot="{ errors }" rules="required" name="Estado civil">
                                                            <v-select 
                                                                name="rc_estado_civil" 
                                                                :items="['Soltero', 'Casado', 'Divorciado', 'Sin especificar']" 
                                                                label="Estado civil" 
                                                                append-icon="mdi-domain" 
                                                                :error-messages="errors[0]" 
                                                                v-model="currentRegistro.rc_estado_civil" 
                                                                dense 
                                                                outlined 
                                                                shaped
                                                            ></v-select>
                                                        </validation-provider>
                                                        <input type="hidden" name="rc_estado_civil" :value="currentRegistro.rc_estado_civil" />
                                                    </v-col>
                                                    <v-col cols="12" md="6">
                                                        <validation-provider v-slot="{ errors }" rules="required" name="Curp">
                                                            <v-text-field 
                                                                name="rc_curp" 
                                                                label="Curp" 
                                                                append-icon="mdi-account" 
                                                                :error-messages="errors[0]" 
                                                                v-model="currentRegistro.rc_curp" 
                                                                outlined 
                                                                shaped 
                                                                required
                                                            ></v-text-field>
                                                        </validation-provider>
                                                    </v-col>
                                                    <v-col cols="12" md="6">
                                                        <validation-provider v-slot="{ errors }" rules="required" name="Domicilio">
                                                            <v-text-field 
                                                                name="rc_domicilio" 
                                                                label="Domicilio" 
                                                                append-icon="mdi-account" 
                                                                :error-messages="errors[0]" 
                                                                v-model="currentRegistro.rc_domicilio" 
                                                                outlined 
                                                                shaped 
                                                                required
                                                            ></v-text-field>
                                                        </validation-provider>
                                                    </v-col>
                                                    <v-col cols="12" md="6">
                                                        <validation-provider v-slot="{ errors }" rules="required" name="Medio de contacto">
                                                            <v-text-field 
                                                                name="rc_medio_contacto" 
                                                                label="Medio de contacto" 
                                                                append-icon="mdi-account" 
                                                                :error-messages="errors[0]" 
                                                                v-model="currentRegistro.rc_medio_contacto" 
                                                                outlined 
                                                                shaped 
                                                                required
                                                            ></v-text-field>
                                                        </validation-provider>
                                                    </v-col>
                                                    <v-col cols="12" md="6">
                                                        <validation-provider v-slot="{ errors }" rules="required" name="Servicio medico">
                                                            <v-text-field 
                                                                name="rc_servicio_medico" 
                                                                label="Servicio medico" 
                                                                append-icon="mdi-account" 
                                                                :error-messages="errors[0]" 
                                                                v-model="currentRegistro.rc_servicio_medico" 
                                                                outlined 
                                                                shaped 
                                                                required
                                                            ></v-text-field>
                                                        </validation-provider>
                                                    </v-col>
                                                    <v-col cols="12" md="6">
                                                        <validation-provider v-slot="{ errors }" rules="required" name="Aviso">
                                                            <v-text-field 
                                                                name="rc_aviso" 
                                                                label="Aviso" 
                                                                append-icon="mdi-account" 
                                                                :error-messages="errors[0]" 
                                                                v-model="currentRegistro.rc_aviso" 
                                                                outlined 
                                                                shaped 
                                                                required
                                                            ></v-text-field>
                                                        </validation-provider>
                                                    </v-col>
                                                    <v-col cols="12" md="6">
                                                        <validation-provider v-slot="{ errors }" rules="required" name="Comentarios del curso">
                                                            <v-text-field 
                                                                name="rc_comentarios_curso" 
                                                                label="Comentarios del curso" 
                                                                append-icon="mdi-account" 
                                                                :error-messages="errors[0]" 
                                                                v-model="currentRegistro.rc_comentarios_curso" 
                                                                outlined 
                                                                shaped 
                                                                required
                                                            ></v-text-field>
                                                        </validation-provider>
                                                    </v-col>
                                                    <v-col cols="12" md="6">
                                                        <validation-provider v-slot="{ errors }" rules="required" name="Trabajo y estudios">
                                                            <v-text-field 
                                                                name="rc_trabajo_estudios" 
                                                                label="Trabajo y estudios" 
                                                                append-icon="mdi-account" 
                                                                :error-messages="errors[0]" 
                                                                v-model="currentRegistro.rc_trabajo_estudios" 
                                                                outlined 
                                                                shaped 
                                                                required
                                                            ></v-text-field>
                                                        </validation-provider>
                                                    </v-col>
                                                </v-row>
                                            </div>
                                        </v-form>
                                    </v-container>
                                </v-card-text>
                                <v-card-actions>
                                    <v-row>
                                        <v-col cols="12" md="6">
                                            <v-btn color="success" @click="handleSubmit(validForm)" block>{{ submitTitle }}</v-btn>
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
                    <div style="white-space: pre-line;">{{ item.rc_comentarios_curso }}</div>
                </template>
                <template v-slot:item.opciones="{ item }">
                    <v-row>
                        <v-col cols="12" lg="6">
                            <v-form method="POST" target="_blank" action="http://cruzrojatabasco.com/cruzrojatabasco/pdf.php">
                                <input type="hidden" name="rc_clave_registro" :value="item.rc_clave_registro" />
                                <input type="hidden" name="rc_clave_curso" :value="item.rc_clave_curso" />
                                <input type="hidden" name="rc_folio_preregistro" :value="item.rc_folio_preregistro" />
                                <input type="hidden" name="rc_nombre" :value="item.rc_nombre" />
                                <input type="hidden" name="rc_app" :value="item.rc_app" />
                                <input type="hidden" name="rc_apm" :value="item.rc_apm" />
                                <input type="hidden" name="rc_genero" :value="item.rc_genero" />
                                <input type="hidden" name="rc_fecha_nacimiento" :value="item.rc_fecha_nacimiento" />
                                <input type="hidden" name="rc_lugar_nacimiento" :value="item.rc_lugar_nacimiento" />
                                <input type="hidden" name="rc_nacionalidad" :value="item.rc_nacionalidad" />
                                <input type="hidden" name="rc_estado_civil" :value="item.rc_estado_civil" />
                                <input type="hidden" name="rc_curp" :value="item.rc_curp" />
                                <input type="hidden" name="rc_domicilio" :value="item.rc_domicilio" />
                                <input type="hidden" name="rc_medio_contacto" :value="item.rc_medio_contacto" />
                                <input type="hidden" name="rc_servicio_medico" :value="item.rc_servicio_medico" />
                                <input type="hidden" name="rc_aviso" :value="item.rc_aviso" />
                                <input type="hidden" name="rc_comentarios_curso" :value="item.rc_comentarios_curso" />
                                <input type="hidden" name="rc_trabajo_estudios" :value="item.rc_trabajo_estudios" />
                                <v-tooltip left>
                                    <template v-slot:activator="{ on }">
                                        <v-btn dark fab small color="success" v-on="on" type="submit">
                                            <v-icon>mdi-printer</v-icon>
                                        </v-btn>
                                    </template>
                                    <span>Generar Reporte</span>
                                </v-tooltip>
                            </v-form>
                        </v-col>
                        <v-col cols="12" lg="6">
                            <v-tooltip left>
                                <template v-slot:activator="{ on }">
                                    <v-btn dark fab small color="navDrawer" @click="editItem(item)" v-on="on">
                                        <v-icon class="mr-2">mdi-pencil</v-icon>
                                    </v-btn>
                                </template>
                                <span>Actualizar</span>
                            </v-tooltip>
                        </v-col>
                        <v-col cols="12">
                            <v-tooltip left>
                                <template v-slot:activator="{ on: toolActivator}">
                                    <v-btn dark fab small color="danger" @click="deleteItem(item)" v-on="{ ...toolActivator }">
                                        <v-icon>mdi-delete</v-icon>
                                    </v-btn>
                                </template>
                                <span>Eliminar</span>
                            </v-tooltip>
                        </v-col>
                    </v-row>
                </template>
                <template v-slot:no-data>
                    <v-alert type="info" color="navDrawer">No se obtuvieron resultados</v-alert>
                    <v-btn color="navDrawer" @click="initialize" dark>Resetear</v-btn>
                </template>
                <template v-slot:no-results>
                    <v-alert type="error" color="danger">No se encontraron resultados</v-alert>
                </template>
            </v-data-table>
        </v-card-text>
    </v-card>
    <v-dialog v-model="deleteDialog.show" width="75%">
        <v-card>
            <v-card-text>
                <div class="headline" style="text-align: center;">¿Seguro que desea eliminar el folio de {{ deleteDialog.nombre }} con el ID {{ deleteDialog.rc_id }}?</div>
            </v-card-text>
            <v-card-actions>
                <v-btn color="danger" class="headline" @click="confirmDeleteItem" text>Eliminar</v-btn>
                <v-divider vertical></v-divider>
                <v-btn color="success" class="headline" @click="deleteDialog.show = false" dark text>Cancelar</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
    <v-snackbar v-model="toastMessage.show" width="350px" :timeout="10000" absolute top right>
        <v-icon color="white">{{ toastMessage.icon }}</v-icon>
        - {{ toastMessage.message }}
        <v-btn text @click="toastMessage.show = false">Cerrar</v-btn>
    </v-snackbar>
</div>
</template>

<script>
import {
    ValidationProvider,
    ValidationObserver
} from "vee-validate";

export default {
    transition: "slide",
    head: {
        title: "Registros"
    },
    data() {
        return {
            search: "",
            headers: [{
                    text: "ID",
                    value: "rc_id"
                },
                {
                    text: "Clave registro",
                    value: "rc_clave_registro"
                },
                {
                    text: "Clave curso",
                    value: "rc_clave_curso"
                },
                {
                    text: "Folio",
                    value: "rc_folio_preregistro"
                },
                {
                    text: "Nombre",
                    value: "rc_nombre"
                },
                {
                    text: "Apellido paterno",
                    value: "rc_app"
                },
                {
                    text: "Apellido materno",
                    value: "rc_apm"
                },
                {
                    text: "Genero",
                    value: "rc_genero"
                },
                {
                    text: "Fecha de nacimiento",
                    value: "rc_fecha_nacimiento"
                },
                {
                    text: "Lugar de nacimiento",
                    value: "rc_lugar_nacimiento"
                },
                {
                    text: "Nacionalidad",
                    value: "rc_nacionalidad"
                },
                {
                    text: "Estado civil",
                    value: "rc_estado_civil"
                },
                {
                    text: "CURP",
                    value: "rc_curp"
                },
                {
                    text: "Domicilio",
                    value: "rc_domicilio"
                },
                {
                    text: "Medio de contacto",
                    value: "rc_medio_contacto"
                },
                {
                    text: "Servicio medico",
                    value: "rc_servicio_medico"
                },
                {
                    text: "Aviso",
                    value: "rc_aviso"
                },
                {
                    text: "Comentarios",
                    value: "rc_comentarios_curso"
                },
                {
                    text: "Trabajo o estudios",
                    value: "rc_trabajo_estudios"
                },
                {
                    text: "Opciones",
                    value: "opciones",
                    sortable: false,
                    align: "center"
                }
            ],
            registros: [],
            dialog: false,
            editedIndex: -1,
            toastMessage: {
                show: false,
                message: "",
                icon: ""
            },
            deleteDialog: {
                show: false,
                rc_id: "",
                nombre: ""
            },
            currentRegistro: {
                rc_id: "",
                rc_clave_registro: "",
                rc_clave_curso: "",
                rc_folio_preregistro: "",
                rc_nombre: "",
                rc_app: "",
                rc_apm: "",
                rc_genero: "",
                rc_fecha_nacimiento: "",
                rc_lugar_nacimiento: "",
                rc_nacionalidad: "",
                rc_estado_civil: "",
                rc_curp: "",
                rc_domicilio: "",
                rc_medio_contacto: "",
                rc_servicio_medico: "",
                rc_aviso: "",
                rc_comentarios_curso: "",
                rc_trabajo_estudios: ""
            },
            newRegistro: {
                rc_nombre: "",
                rc_app: "",
                rc_apm: "",
                rc_fecha_nacimiento: "",
                rc_lugar_nacimiento: "",
                rc_nacionalidad: "",
                rc_medio_contacto: "",
                rc_curp: "",
                rc_genero: "",
                rc_estado_civil: "",
                rc_emergencia: {
                    rc_nombre: "",
                    rc_parentesco: "",
                    rc_telefono: "",
                    rc_domicilio: ""
                },
                rc_comentarios: {
                    rc_utilidad_curso: "",
                    rc_enterar_curso: ""
                },
                rc_servicio_medico: {
                    rc_servicio_medico: "",
                    rc_afiliacion: "",
                    rc_seguro: "",
                    rc_tipo: "",
                    rc_compañia: "",
                    rc_grupo_sanguineo: "",
                    rc_alergias: "",
                    rc_enfermedades_cronicas: "",
                    rc_cirugias: "",
                    rc_problemas_fisicos: ""
                },
                rc_trabajo_escuela: {
                    rc_nombre: "",
                    rc_telefono: "",
                    rc_direccion: "",
                    rc_puesto: "",
                    rc_carrera: "",
                    rc_horario: "",
                    rc_titulo: ""
                },
                rc_domicilio: {
                    rc_calle: "",
                    rc_colonia: "",
                    rc_municipio: ""
                }
            }
        };
    },
    mounted() {
        this.initialize();
    },
    methods: {
        async initialize() {
            this.showToastMessage("Espere un momento...", "mdi-timer-sand");
            try {
                const {
                    data
                } = await this.$axios.post("/admin/Api/Registros/");
                this.registros = data;
                this.showToastMessage("Hecho", "mdi-check-bold");
            } catch (error) {
                this.showToastMessage("A ocurrido un error inesperado", "mdi-close");
                console.error(error);
            }
        },
        editItem(item) {
            this.editedIndex = this.registros.indexOf(item);
            this.dialog = true;
            this.currentRegistro = Object.assign({}, item);
        },
        deleteItem(item) {
            this.deleteDialog.show = true;
            this.deleteDialog.rc_id = item.rc_id;
            this.deleteDialog.nombre = item.rc_nombre;
        },
        async confirmDeleteItem() {
            this.showToastMessage("Espere un momento...", "mdi-timer-sand");
            try {
                const formData = new FormData();
                formData.append("rc_id", this.deleteDialog.rc_id);
                const {
                    data
                } = await this.$axios.post(
                    "/admin/Api/Registros/Eliminar.php",
                    formData
                );
                this.deleteDialog.show = false;
                this.showToastMessage("Operacion completada", "mdi-check-bold");
                this.initialize();
            } catch (error) {
                console.log(error);
                this.showToastMessage("A ocurrio un error inesperado", "mdi-close");
            }
        },
        async validForm() {
            if (this.editedIndex > -1) {
                this.showToastMessage("Actualizando registro", "mdi-timer-sand");
                try {
                    // Object.assign(this.Preregistros[this.editedIndex], this.editedItem);
                    const form = document.getElementById("foliosForm");
                    let formData = new FormData(form);
                    const {
                        data
                    } = await this.$axios.post(
                        "/admin/Api/Registros/Actualizar.php",
                        formData
                    );
                    console.log(data);
                    this.close();
                    this.showToastMessage("Registro actualizado", "mdi-check-bold");
                    this.initialize();
                } catch (error) {
                    this.showToastMessage("A ocurrido un error inesperado", "mdi-close");
                    console.error(error);
                }
            } else {
                this.showToastMessage("Creando registro", "mdi-timer-sand");
                try {
                    const form = document.getElementById("foliosForm");
                    let formData = new FormData(form);
                    const {
                        data
                    } = await this.$axios.post(
                        "/admin/Api/Registros/Crear.php",
                        formData
                    );
                    console.log(data);
                    this.close();
                    this.showToastMessage("Registro creado", "mdi-check-bold");
                    this.initialize();
                } catch (error) {
                    this.showToastMessage("A ocurrido un error inesperado", "mdi-close");
                    console.error(error);
                }
            }
        },
        close() {
            this.dialog = false;
            setTimeout(() => {
                this.editedIndex = -1;
                
                // this.currentPreregistro.idPreregistro = ""
                // this.currentPreregistro.nombre = ""
                // this.currentPreregistro.apellido_p = ""
                // this.currentPreregistro.apellido_m = ""
                // this.currentPreregistro.genero = ""
                // this.currentPreregistro.edad = ""
                // this.currentPreregistro.tipo_registro = ""
                // this.currentPreregistro.clave_curso = ""
                // this.currentPreregistro.contacto = ""
            }, 300);
        },
        showToastMessage(message, icon) {
            this.toastMessage.show = true;
            this.toastMessage.message = message;
            this.toastMessage.icon = icon;
        }
    },
    computed: {
        formTitle() {
            return this.editedIndex === -1 ? "Crear folio" : "Editar folio";
        },
        submitTitle() {
            return this.editedIndex === -1 ? "Crear" : "Editar";
        }
    },
    components: {
        ValidationProvider,
        ValidationObserver
    }
};
</script>
