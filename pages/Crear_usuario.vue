<template>
<div>
    <v-sheet elevation="12" class="ma-3 mt-3">
        <v-sheet elevation="12" class="pa-5 text-center caption_text display-1">
            <h2 class="text-danger">Crear nuevo usuario</h2>
        </v-sheet>
        <validation-observer class="page" v-slot="{ invalid, handleSubmit }">
            <v-form ref="usersForm" id="usersForm" name="usersForm" method="post" @submit.prevent="handleSubmit(submitForm)">
                <v-card>
                    <v-img class="white--text align-end" height="300px" src="/admin/admin_assets/blogBanner.jpg"></v-img>
                    <v-card-text>
                        <v-row>
                            <v-col cols="12" md="6">
                                <validation-provider class="input_form" v-slot="{ errors }" rules="required|min:3|max:25" name="Nombre de usuario">
                                    <v-text-field 
                                        name="usu_usuario" 
                                        label="Nombre del usuario" 
                                        append-icon="mdi-account" 
                                        :error-messages="errors[0]"
                                        v-model="formData.usuario_nombre"
                                        outlined 
                                        shaped 
                                        required>
                                    </v-text-field>
                                </validation-provider>
                            </v-col>
                            <v-col cols="12" md="6">
                                <validation-provider class="input_form" v-slot="{ errors }" rules="required|min:3|max:25" vid="usu_password" name="Contraseña del usuario">
                                    <v-text-field 
                                        name="usu_password" 
                                        id="usuario_contraseña"
                                        v-model="formData.usuario_contraseña"
                                        label="Contraseña del usuario" 
                                        :append-icon="show1 ? 'mdi-eye' : 'mdi-eye-off'" 
                                        :type="show1 ? 'text' : 'password'"
                                        :error-messages="errors[0]" 
                                        outlined 
                                        shaped 
                                        required 
                                        @click:append="show1 = !show1">
                                    </v-text-field>
                                </validation-provider>
                            </v-col>
                            <v-col cols="12" md="6">
                                <validation-provider class="input_form" v-slot="{ errors }" rules="required|confirmed:usu_password" name="Confirmar contraseña">
                                    <v-text-field 
                                        label="Confirmar contraseña" 
                                        :append-icon="show1 ? 'mdi-eye' : 'mdi-eye-off'" 
                                        :type="show1 ? 'text' : 'password'"
                                        :error-messages="errors[0]" 
                                        v-model="formData.contraseña_confirmacion"
                                        outlined 
                                        shaped 
                                        required 
                                        @click:append="show1 = !show1">
                                    </v-text-field>
                                </validation-provider>
                            </v-col>
                            <v-col cols="12" md="6">
                                <validation-provider class="input_form" v-slot="{ errors }" rules="required|min:3|max:25" name="Contraseña del usuario">
                                    <v-select 
                                        name="usu_departamento" 
                                        :items="items" 
                                        label="Departamento" 
                                        append-icon="mdi-domain" 
                                        :error-messages="errors[0]" 
                                        v-model="formData.select" 
                                        dense 
                                        outlined 
                                        shaped>
                                    </v-select>
                                    <input type="hidden" name="usu_departamento"  :value="formData.select"/>
                                </validation-provider>
                            </v-col>
                        </v-row>
                    </v-card-text>
                    <v-card-actions>
                    <v-row>
                        <v-col cols="12" md="8">
                        <v-btn color="success" :disabled="invalid" type="submit" block>Crear usuario</v-btn>
                        </v-col>
                        <v-col cols="12" md="4">
                        <v-btn color="warning" @click="clear" block>Limpiar campos</v-btn>
                        </v-col>
                    </v-row>
                    </v-card-actions>
                </v-card>
            </v-form>
        </validation-observer>
    </v-sheet>
    <v-snackbar v-model="toastMessage.show" width="350px" :timeout="10000" absolute top right>
        <v-icon color="white">{{ toastMessage.icon }}</v-icon>- {{ toastMessage.message }}
        <v-btn text @click="toastMessage.show = false">Cerrar</v-btn>
    </v-snackbar>
</div>
</template>

<script>
import { ValidationProvider, ValidationObserver } from 'vee-validate';

export default {
    transition: "slide",
    head: {
        title: "Crear Usuario"
    },
    data() {
        return {
            items: ['Juventud', 'Voluntariado', 'Capacitacion', 'Socorros', 'DamasVoluntarias'],
            show1: false,
            formData:{
                usuario_nombre:"",
                usuario_contraseña:"",
                contraseña_confirmacion:"",
                select: ""
            },
            toastMessage: {
                show: false,
                message: "",
                icon: ""
            },
        }
    },
    components:{
        ValidationProvider,
        ValidationObserver
    },
    methods: {
        clear() {
            this.$refs.usersForm.reset()
        },
        async submitForm() {
            this.showToastMessage("Creando usuario...","mdi-timer-sand")
            try {
                let form = document.getElementById('usersForm');
                let formData = new FormData(form);
                const { data } = await this.$axios.post("/admin/Api/Administracion/usuarios/Crear.php", formData)  
                console.log(data) 
                this.showToastMessage("Usuario creado","mdi-check-bold")
                this.clear()
            } catch (error) {
                this.showToastMessage("A ocurrido un error inesperado","mdi-close")
                console.error(error)
            }
        },
        showToastMessage(message, icon) {
            this.toastMessage.show = true
            this.toastMessage.message = message
            this.toastMessage.icon = icon
        }
    }
}
</script>

<style lang="scss" scoped>
.caption_text {
    $textColor : #e92a2a; 
    margin: auto;
    font-family:  Helvetica, Arial,sans-serif;
    color: white;
    font-weight: 100;
    text-shadow: -2px -2px 3px $textColor,
        2px -2px 3px $textColor,
        -2px 2px 3px $textColor,
        2px 2px 3px $textColor;
}
</style>
