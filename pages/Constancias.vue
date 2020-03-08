<template>
  <div>
    <v-dialog v-model="dialog" min-width="75%" max-width="90%" persistent>
      <v-card>
        <v-card-title>
          INGRESE LA CLAVE DEL CURSO
        </v-card-title>
        <v-card-text>
          <v-text-field label="CLAVE DEL CURSO">

          </v-text-field>
        </v-card-text>
        <v-card-actions>
          <v-row>
              <v-col cols="12" md="6">
                  <v-btn color="success" block>Enviar</v-btn>
              </v-col>
              <v-col cols="12" md="6">
                  <v-btn @click="dialog = false" color="danger" dark block>Cerrar</v-btn>
              </v-col>
          </v-row>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>

export default {
  transition: "slide",
  head:{
    title: "Constancias"
  },
  data() {
    return {
      dialog: true
    }
  },
  mounted() {
    
  }
}
</script>
