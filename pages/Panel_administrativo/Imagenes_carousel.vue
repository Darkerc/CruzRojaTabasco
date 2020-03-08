<template>
<div>
    <h2 class="title_nav">Imagenes del carousel</h2>
    <v-row justify="center" no-gutters>
        <v-col class="py-4 text-center white--text" cols="12">
            <v-btn color="success" @click="dialog = true">Añadir imagen</v-btn>
            <!--<v-btn>Añadir imagen</v-btn>-->
        </v-col>
    </v-row>
    <!-- <v-sheet elevation="6" width="95%" class="assets_container"> -->
    <v-container>
        <div class="assets_container">
            <div v-for="(item, i) in assets" :key="i" class="asset">
                <v-sheet elevation="24">
                    <v-card>
                        <v-img class="white--text align-end" height="275px" :src="item.url_web_archivo">
                            <template v-slot:placeholder>
                                <v-row class="fill-height ma-0" align="center" justify="center">
                                    <v-progress-circular indeterminate color="danger"></v-progress-circular>
                                </v-row>
                            </template>
                            <v-card-title class="overlay_background" dark>
                                Orden {{ i+1 }}
                            </v-card-title>
                        </v-img>
                        <v-card-actions>
                            <v-btn color="orange" text @click="eliminarImagen(item.id,item)">
                                Eliminar
                            </v-btn>
                        </v-card-actions>
                    </v-card>
                </v-sheet>
            </div>
        </div>
    </v-container>

    <!-- </v-sheet> -->
    <v-dialog v-model="dialog" persistent max-width="600px">
        <v-card hover outlined shaped>
            <validation-observer v-slot="{ invalid, handleSubmit }">
                <v-form id="añadirImagenFile" name="añadirImagenFile" enctype="multipart/form-data">
                    <v-card-title>
                        <span class="headline">Seleccione una imagen</span>
                    </v-card-title>
                    <v-card-text>
                        <v-container>
                            <validation-provider v-slot="{ errors }" rules="required|image" name="Archivo de imagen">
                                <v-file-input 
                                    label="Archivo de imagen" 
                                    append-icon="mdi-image" 
                                    accept="image/png, image/jpeg, image/bmp, image/jpg" 
                                    v-model="FileImage" 
                                    name="FileImage" 
                                    id="FileImage" 
                                    type="file" 
                                    :error-messages="errors[0]"
                                    outlined 
                                    shaped 
                                    required
                                ></v-file-input>
                            </validation-provider>
                        </v-container>
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="success" @click="handleSubmit(añadirImagenFile)" :disabled="invalid">Enviar</v-btn>
                    </v-card-actions>
                </v-form>
            </validation-observer>
            <validation-observer v-slot="{ invalid, handleSubmit }">
                <v-form id="añadirImagenURL" method="post">
                    <v-card-title>
                        <span class="headline">Introduzca una URL</span>
                    </v-card-title>
                    <v-card-text>
                        <v-container>
                            <validation-provider v-slot="{ errors }" rules="required|url" name="URL de imagen">
                                <v-text-field 
                                    label="URL de imagen" 
                                    append-icon="mdi-image" 
                                    v-model="URLImage" 
                                    name="URLImage" 
                                    id="URLImage" 
                                    :error-messages="errors[0]"
                                    outlined 
                                    shaped 
                                    required
                                ></v-text-field>
                            </validation-provider>
                        </v-container>
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="success" @click="handleSubmit(añadirImagenURL)" :disabled="invalid">Enviar</v-btn>
                    </v-card-actions>
                </v-form>
            </validation-observer>
            <v-card-actions>
                <v-btn color="error" @click="dialog = false">Cerrar</v-btn>
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
        title: "Imagenes del carousel"
    },
    data() {
        return {
            dialog: false,
            URLImage: '',
            FileImage: undefined,
            assets: [],
            toastMessage: {
                show: false,
                message: "",
                icon: ""
            },
        }
    },
    mounted() {
        this.listarAssets();
    },
    methods: {
        async añadirImagenFile() {
            this.showToastMessage("Guardando imagen", "mdi-timer-sand")
            try {
                let formData = new FormData(document.getElementById('añadirImagenFile'));
                await this.$axios.post("Administracion/admin_assets/Crear.php", formData, {
                    headers: {
                        "Content-Type": "multipart/form-data"
                    }
                })
                this.showToastMessage("Imagen guardada", "mdi-check-bold")
                this.listarAssets();
                this.dialog = false
            } catch (error) {
                this.showToastMessage("A ocurrido un error inesperado", "mdi-close")
                console.error(error)
            }
        },
        async añadirImagenURL() {
            this.showToastMessage("Guardando URL", "mdi-timer-sand")
            try {
                let formData = new FormData(document.getElementById('añadirImagenURL'));
                await this.$axios.post("Administracion/admin_assets/Crear.php", formData)
                this.showToastMessage("URL Guardada", "mdi-check-bold")
                this.listarAssets();
                this.dialog = false
            } catch (error) {
                this.showToastMessage("A ocurrido un error inesperado", "mdi-close")
                console.error(error)
            }

        },
        async listarAssets() {
            this.showToastMessage("Obteniendo imagenes", "mdi-timer-sand")
            try {
                const {
                    data
                } = await this.$axios.get("Administracion/admin_assets/")
                this.assets = data
                this.showToastMessage("Imagenes obtenidas", "mdi-check-bold")
            } catch (error) {
                this.showToastMessage("A ocurrido un error inesperado", "mdi-close")
                console.error(error)
            }

        },
        async eliminarImagen(id, item) {
            this.showToastMessage("Eliminando imagen", "mdi-timer-sand")
            try {
                let formData = new FormData();
                formData.append("id", id);
                await this.$axios.post("Administracion/admin_assets/Eliminar.php", formData)
                const idx = this.assets.indexOf(item)
                this.assets.splice(idx, 1)
                this.showToastMessage("Imagen eliminada", "mdi-check-bold")
            } catch (error) {
                this.showToastMessage("A ocurrido un error inesperado", "mdi-close")
                console.error(error)
            }
        },
        showToastMessage(message, icon) {
            this.toastMessage.show = true
            this.toastMessage.message = message
            this.toastMessage.icon = icon
        }
    },
    components: {
        ValidationProvider,
        ValidationObserver
    },
}
</script>

<style lang="scss">
.assets_container {
    display: grid;
    /* min-width: 95vw; */
    grid-template-columns: repeat(auto-fit, minmax(375px, 375px));
    grid-template-rows: auto-fit;
    grid-auto-rows: auto-fit;
    grid-gap: 5px;
    justify-content: center;
    /* margin: 30px auto; */
    box-sizing: border-box;
    /* padding: 30px 10px; */

}

.overlay_background {
    position: relative;
    color: white;

    &::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, .5) !important;
        z-index: -1;
    }
}

.asset {
    margin: 5px;
}

.title_nav {
    text-align: center;
    font-size: 45px;
    color: #dc3545;
}
</style>
