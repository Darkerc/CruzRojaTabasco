<template>
<v-app class="full_size">
    <v-navigation-drawer v-model="drawer" color="navDrawer" width="325px" app>
        <v-list-item dark>
            <v-list-item-content>
                <v-list-item-title class="title">
                    Cruz Roja Tabasco
                </v-list-item-title>
                <v-list-item-subtitle>
                    Administracion
                </v-list-item-subtitle>
            </v-list-item-content>
        </v-list-item>

        <v-divider></v-divider>
        <v-list dark>
                <div v-for="item in items" :key="item.title">
                    <div v-if="item.groupItems == undefined">
                        <v-tooltip right>
                            <template v-slot:activator="{ on }">
                                <v-list-item v-on="on" nuxt :to="{ name:item.link }" @click="setDashTitle(item)">
                                    <v-list-item-icon>
                                        <v-icon>{{ item.icon }}</v-icon>
                                    </v-list-item-icon>
                                    <v-list-item-content>
                                        <v-list-item-title>{{ item.title }}</v-list-item-title>
                                    </v-list-item-content>
                                </v-list-item>
                            </template>
                            <span v-html="item.description"></span>
                        </v-tooltip>
                    </div>
                    <div v-else>
                        <v-list-group :prepend-icon="item.icon" no-action color="#fff" value="true">
                            <template v-slot:activator>
                                <v-list-item-content>
                                <v-list-item-title v-html="item.title"></v-list-item-title>
                                </v-list-item-content>
                            </template>

                            <v-list-item v-for="(itemGroup, i) in item.groupItems" :key="i" nuxt :to="{ name:itemGroup.link }" @click="setDashTitle(itemGroup)" link>
                                <v-list-item-icon>
                                    <v-icon v-text="itemGroup.icon"></v-icon>
                                </v-list-item-icon>
                                <v-list-item-content>
                                    <v-list-item-title v-text="itemGroup.title">
                                        
                                    </v-list-item-title>
                                </v-list-item-content>
                            </v-list-item>
                        </v-list-group>
                    </div>
                </div>
        </v-list>
        <template v-slot:append>
            <div class="pa-2">
                <v-btn block href="http://cruzrojatabasco.com" color="danger" dark>Cerrar sesion</v-btn>
            </div>
        </template>
    </v-navigation-drawer>
    <v-app-bar color="navDrawer" dark hide-on-scroll v-scroll:#scroll-target max-height="60px" min-height="60px" app>
        <v-app-bar-nav-icon @click="drawer = !drawer"></v-app-bar-nav-icon>

        <v-toolbar-title class="d-flex">
            <v-icon class="m-auto" size="30px">
                {{ titleIcon }}
            </v-icon>
            <div class="m-auto pl-3">
                {{ title  }}
            </div>
        </v-toolbar-title>

        <v-spacer></v-spacer>

        <v-btn @click="$vuetify.theme.dark = !$vuetify.theme.dark" icon>
            <v-icon v-if="$vuetify.theme.dark">
                mdi-brightness-5
            </v-icon>
            <v-icon v-else>
                mdi-brightness-2
            </v-icon>
        </v-btn>

        <v-btn icon @click="loadConfig">
            <v-icon>
                mdi-settings
            </v-icon>
        </v-btn>
    </v-app-bar>
    <v-content class="background">
        <div class="content_page scroll-info degraded transparent">
            <nuxt />
        </div>
    </v-content>
    <v-dialog v-model="dialog" fullscreen hide-overlay transition="dialog-bottom-transition">
        <v-card color="background">
            <v-toolbar dark color="navDrawer">
                <v-btn icon dark @click="dialog = false">
                    <v-icon>mdi-close</v-icon>
                </v-btn>
                <v-toolbar-title>Configuracion</v-toolbar-title>
                <v-spacer></v-spacer>
                <v-toolbar-items>
                    <v-btn dark text @click="saveConfig">Guardar</v-btn>
                </v-toolbar-items>
            </v-toolbar>
            <v-list color="background" three-line subheader>
                <v-subheader>Configuracion del usuario</v-subheader>
                <v-list-item v-if="supportLocalStorage">
                    <v-list-item-content>
                        <v-list-item-title>Establecer breakpoint-table</v-list-item-title>
                        <v-list-item-subtitle>Asigna en que medida las tablas de la aplicacion pasaran de un estado vertical a horizontal (Se recomienda 1365)</v-list-item-subtitle>
                    </v-list-item-content>
                    <v-list-item-action>
                        <v-text-field type="number" v-model="config.tableBreakpoint"></v-text-field>
                    </v-list-item-action>
                </v-list-item>
            </v-list>
            <v-divider></v-divider>
        </v-card>
    </v-dialog>
</v-app>
</template>

<script>

export default {
    data() {
        return {
            items: [{
                    title: 'Panel administrativo',
                    icon: 'mdi-view-dashboard',
                    description: 'Panel principal de administacion',
                    groupItems:[
                        { title: 'Bienvenido', icon:'mdi-account-group', link:'index' },
                        { title: 'Imagenes del carousel', icon:'mdi-account-group', link:'Panel_administrativo-Imagenes_carousel' },
                    ]
                },
                {
                    title: 'Cursos',
                    icon: 'mdi-plus-outline',
                    link: 'Cursos',
                    description: 'Cursos Activos'
                },
                {
                    title: 'Preregistro',
                    icon: 'mdi-note-text',
                    description: 'Preregistros',
                    groupItems:[
                        { title: 'Publico general', icon:'mdi-account-group', link:'Preregistro-Publico_general' },
                        { title: 'Empresarial', icon:'mdi-domain', link:'Preregistro-Empresarial' }
                    ]
                },
                {
                    title: 'Folio status',
                    icon: 'mdi-table-edit',
                    link: 'Folio_status',
                    description: 'Folio status'
                },
                {
                    title: 'Registros',
                    icon: 'mdi-library-books',
                    link: 'Registros',
                    description: 'Registros'
                },
                {
                    title: 'Constancias',
                    icon: 'mdi-pencil',
                    link: 'Constancias',
                    description: 'Constancias'
                },
                {
                    title: 'Crear post',
                    icon: 'mdi-monitor-cellphone',
                    link: 'Crear_post',
                    description: 'Crear un nuevo post para la pagina principal'
                },
                {
                    title: 'Crear usuario',
                    icon: 'mdi-account-plus',
                    link: 'Crear_usuario',
                    description: 'Crear una nueva cuenta de departamento'
                },
            ],
            config:{
                tableBreakpoint: 1365
            },
            drawer: false,
            dialog: false,
            title: "Bienvenido",
            titleIcon: 'mdi-home',
        }
    },
    mounted() {
        this.title = this.$route.name.replace('_', " ")
    },
    beforeCreate(){
        if(window.localStorage){
            if(window.localStorage.getItem('TableBreakpoint') == null){
                window.localStorage.setItem('TableBreakpoint', 1365)
                this.$store.commit('setTableBreakpoint', 1365)
            }else{
                this.$store.commit('setTableBreakpoint', window.localStorage.getItem('TableBreakpoint'))
            }
        }
    },
    methods: {
        setDashTitle(item) {
            this.title = item.title
            this.titleIcon = item.icon
        },
        onScroll(e) {
            console.log(e)
        },
        loadConfig(){
            this.config.tableBreakpoint = window.localStorage.getItem('TableBreakpoint')
            this.dialog = true
        },
        saveConfig(){
            window.localStorage.setItem('TableBreakpoint', this.config.tableBreakpoint)
            this.$store.commit('setTableBreakpoint', this.config.tableBreakpoint)
            this.dialog = false
        }
    },
    computed:{
        supportLocalStorage(){
            return window.localStorage ? true : false;
        }
    }
}
</script>

<style lang="scss">
body,
html,
.full_size {
    padding: 0 !important;
    margin: 0 !important;
    min-height: 100vh !important;
    min-width: 100vw !important;
    max-height: 100vh !important;
    max-width: 100vw !important;
    overflow: hidden !important;
}

.content_page {
    height: calc(100vh - 60px);
    overflow-y: auto;
}

.v-navigation-drawer__content{
    &::-webkit-scrollbar {
        width: .45em;
        // height:.65em;
    }

    &::-webkit-scrollbar-thumb {
        border-radius: 10px;
        background: #d14541;
 
        &:hover {
            background-color: #d14541;
        }
    }
  
    &::-webkit-scrollbar-track {
        border-radius:10px;
        background-color: transparent;
    }
    &::-webkit-scrollbar-thumb {
        border: none;
    }
 
}

.v-dialog{
    overflow-x: hidden !important;
}
</style>
