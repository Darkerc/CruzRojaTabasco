export default {
  mode: 'spa',
  /*
  ** Headers of the page
  */
  head: {
    titleTemplate: '%s - ' + process.env.npm_package_name,
    title: process.env.npm_package_name || '',
    meta: [
      { charset: 'utf-8' },
      { name: 'viewport', content: 'width=device-width, initial-scale=1' },
      { hid: 'description', name: 'description', content: process.env.npm_package_description || '' }
    ],
    link: [
      { rel: 'icon', type: 'image/x-icon', href: '/admin/favicon.ico' }
    ]
  },
  /*
  ** Customize the progress-bar color
  */
  loading: '~/components/loading-page.vue',
  /*
  ** Global CSS
  */
  // server: {
  //   port: 80,   // default: 3000
  //   host: '192.168.49.195' // default: localhost
  // },

  router: {
    base: '/admin/'
  },
  css: [
  ],
  /*
  ** Plugins to load before mounting the App
  */
  plugins: [
    { src:'~/plugins/FormValidator.js',  ssr:false , mode:'client'},
    { src:'~/plugins/VuetifyConfig.js',  ssr:false , mode:'client'},
  ],
  /*
  ** Nuxt.js dev-modules
  */
  buildModules: [
    '@nuxtjs/vuetify',
  ],
  /*
  ** Nuxt.js modules
  */
  modules: [
    // Doc: https://axios.nuxtjs.org/usage
    '@nuxtjs/axios',
    '@nuxtjs/pwa',
    '@nuxtjs/style-resources'
  ],

  styleResources: {
      scss: [
        '~/assets/scss/index.scss'
      ]
  },


  workbox: {
    offlinePage:'/admin/Offline'
  },

  manifest: {
    name: 'Cruz Roja Administracion',
    short_name:"CR ADMIN",
    lang: 'es',
    theme_color: "#004580",
    description:"Sitio de administracion de la Cruz Roja Tabasco",
    start_url: "/admin/"
  },
  /*
  ** Axios module configuration
  ** See https://axios.nuxtjs.org/options
  */
  axios: {
    baseURL:'http://localhost/admin/Api/'
  },
  /*
  ** vuetify module configuration
  ** https://github.com/nuxt-community/vuetify-module
  */
  vuetify: {
    customVariables: ['~/assets/variables.scss'],
    defaultAssets: false,
    theme: {
      themes: {
        dark: {
          navDrawer: '#004580',
          danger: '#d14541',
          background: '#292b2c',
          success: '#329436',
          warning: '#e37f00',
          orange: '#f17c0e',
          male: '#02b3b3',
          female: '#db05b8'
        },
        light: {
          navDrawer: '#005dad',
          danger: '#ff5f59',
          background: '#f7f7f7',
          success: '#4caf50',
          warning: '#fb8c00',
          orange: '#fc922f',
          male: '#15cccc',
          female: '#ff1dd9'
        },
      }
    }
  },
  /*
  ** Build configuration
  */
  build: {
    transpile: [
      "vee-validate/dist/rules"
    ],
    extend (config, ctx) {
    }
  }
}
