<template>
  <div class="loading-page" v-if="loading">
    <v-icon class="plus_loader">mdi-plus-outline</v-icon>
    <p class="feedback">Cargando</p>
  </div>
</template>

<script>
export default {
  data: () => ({
    loading: false
  }),
  methods: {
    start () {
      this.loading = true
    },
    finish () {
      this.loading = false
    }
  },
  throttle: 1000
}
</script>

<style lang="scss">
.loading-page{
  z-index: 1000;
  position: fixed;
  bottom: 1%;
  right: 1%;
  display: flex;
  flex-direction: column;
  font-family: "Titillium Web",'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  font-size: 1.25em;

  & > * {
    margin: auto !important;
  }

  .feedback{
    background-color: #eb312b;
    padding: 5px 10px;
    box-sizing: border-box;
    border-radius: 5px;
    color: #fff;
  }

  .plus_loader{
    color: #eb312b !important;
    font-size: 150px !important;
    animation: rotateLoader 1s ease-in-out infinite;
    font-weight: bold !important;
  }
}

@keyframes rotateLoader {
  to{
    transform: rotateZ(0deg);
  }
  from{
    transform: rotateZ(-180deg);
  }
}
</style>
