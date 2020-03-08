<template>
<v-app>
    <v-container class="d-flex flex-column">
        <v-alert v-if="error.statusCode === 404" class="text-center title" type="error" dark>
            No se ha encontrado la pagina que ha estado buscado :c
        </v-alert>
        <v-alert v-else-if="error.statusCode === 500" class="text-center title" type="error" dark>
            Ha ocurrido un error en el servidor intente mas tarde
        </v-alert>
        <v-alert v-else class="text-center title" type="error" dark>
            Ha ocurrido un error inesperado
        </v-alert>

        <v-alert v-if="error.message" type="info" class="text-center title">
            {{ error.message }}
        </v-alert>

        <v-btn nuxt dark color="danger" :to="{ name:'index' }" width="75%" class="ma-auto text-center text-decoration-none">
            <v-icon>mdi-arrow-left-thick</v-icon> Regresar a la aplicacion
        </v-btn>
    </v-container>
</v-app>
</template>

<script>
export default {
  props: ['error'],
  layout: 'empty',
  head() {
    return {
      title: `Error ${this.error.statusCode}`
    }
  }
}
</script>

