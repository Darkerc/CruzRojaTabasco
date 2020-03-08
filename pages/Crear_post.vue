<template>
<div>
    <v-sheet elevation="12" class="ma-3 mt-3">
        <v-sheet elevation="12" class="pa-5 text-center display-1">
            <h2 class="caption_text">Crear nuevo post</h2>
        </v-sheet>
        <validation-observer v-slot="{ invalid, handleSubmit }">
            <v-form ref="postForm" id="postForm" name="postForm" method="post" enctype="multipart/form-data">
                <v-card>
                    <v-img class="white--text align-end" height="300px" src="/admin/admin_assets/blogBanner.jpg"></v-img>
                    <v-card-text>
                        <validation-provider v-slot="{ errors }" rules="required" name="Titulo del post">
                            <v-text-field 
                                name="post_titulo" 
                                label="Titulo del post" 
                                append-icon="mdi-notebook" 
                                :error-messages="errors[0]" 
                                v-model="currentPost.post_titulo"
                                outlined 
                                shaped 
                                required
                            ></v-text-field>
                        </validation-provider>
                        <validation-provider v-slot="{ errors }" rules="required" name="Descripcion corta">
                            <v-textarea 
                                name="post_descripcion" 
                                label="Descripcion corta" 
                                clear-icon="mdi-broom" 
                                hint="Se recomienda una descripcion menor a 100 caracteres" 
                                :error-messages="errors[0]" 
                                v-model="currentPost.post_descripcion_corta"
                                counter 
                                clearable 
                                auto-grow 
                                outlined 
                                shaped 
                                filled
                            ></v-textarea>
                        </validation-provider>
                        <validation-provider v-slot="{ errors }" rules="required" name="Cuerpo del post">
                            <v-textarea 
                                name="post_cuerpo" 
                                label="Cuerpo del post" 
                                clear-icon="mdi-broom"
                                :error-messages="errors[0]" 
                                v-model="currentPost.post_cuerpo" 
                                counter 
                                clearable 
                                auto-grow 
                                outlined 
                                shaped 
                                filled
                            ></v-textarea>
                        </validation-provider>
                        <validation-provider v-slot="{ errors }" rules="required" name="Imagen principal del post">
                            <v-file-input 
                                name="post_img_principal" 
                                label="Imagen principal del post" 
                                prepend-icon="mdi-camera" 
                                accept="image/png, image/jpeg, image/bmp, image/jpg" 
                                type="file" 
                                :error-messages="errors[0]" 
                                v-model="currentPost.post_img" 
                                outlined 
                                shaped 
                                required
                            ></v-file-input>
                        </validation-provider>
                        <div v-for="(grupoNum , i) in grupoItem" :key="i">
                            <v-divider></v-divider>
                            <v-sheet elevation="12">
                                <v-card>
                                    <v-card-title>
                                        Grupo de imagenes
                                    </v-card-title>
                                    <v-card-text>
                                        <v-textarea :name="`GIDescripcion${i}`" label="Descripcion corta" clear-icon="mdi-broom" hint="Se recomienda una descripcion menor a 50 caracteres" counter clearable auto-grow outlined shaped filled></v-textarea>
                                        <v-file-input :name="`GIImg${i}[]`" label="Seleccione las imagenes del grupo" prepend-icon="mdi-camera" accept="image/png, image/jpeg, image/bmp, image/jpg" type="file" multiple chips outlined shaped required></v-file-input>
                                    </v-card-text>
                                </v-card>
                            </v-sheet>
                        </div>
                        <input type="hidden" name="GINum" :value="`${grupoItem}`">
                        <v-row class="my-5">
                            <v-col cols="8">
                                <h3 class="text-left text-sm-right headline">Añadir grupo de imagenes</h3>
                            </v-col>
                            <v-col cols="4">
                                <v-btn color="blue darken-1" fab @click="addGrupoImagenes()">
                                    <v-icon color="white" size="30px">
                                        mdi-plus
                                    </v-icon>
                                </v-btn>
                            </v-col>
                        </v-row>
                    </v-card-text>
                    <v-card-actions>
                        <v-row>
                            <v-col cols="12" md="8">
                                <v-btn color="success" :disabled="invalid" @click="handleSubmit(createPost)" block>Crear post</v-btn>
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
import {
    ValidationProvider,
    ValidationObserver
} from 'vee-validate';

export default {
    transition: "slide",
    head: {
        title: "Crear Post"
    },
    data() {
        return {
            grupoItem: 0,
            toastMessage: {
                show: false,
                message: "",
                icon: ""
            },
            currentPost:{
                post_titulo:"",
                post_descripcion_corta:"",
                post_cuerpo:"",
                post_img: undefined
            }
        }
    },
    methods: {
        clear() {
            this.$refs.postForm.reset();
        },
        addGrupoImagenes() {
            this.grupoItem++;
        },
        async createPost() {
            this.showToastMessage("Creando post, espere...", "mdi-timer-sand")
            try {
                let form = document.getElementById('postForm')
                let formData = new FormData(form)
                let {
                    data
                } = await this.$axios.post('post/guardarPost')
                this.showToastMessage("Post creado satisfactoriamente", "mdi-timer-sand")
                this.clear()
            } catch (error) {
                this.showToastMessage("A ocurrido un error durente la creacion del post", "mdi-timer-sand")
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
    }
}
</script>

<style lang="scss" scoped>
.caption_text {
    $textColor: #e92a2a;
    margin: auto;
    font-family: Helvetica, Arial, sans-serif;
    color: white;
    font-weight: 100;
    text-shadow: -2px -2px 3px $textColor,
        2px -2px 3px $textColor,
        -2px 2px 3px $textColor,
        2px 2px 3px $textColor;
}
</style>
