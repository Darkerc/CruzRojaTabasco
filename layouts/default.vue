<template>
<v-app>
    <v-navigation-drawer v-model="drawer" color="navDrawer" width="350px" app>
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
            <v-list-item-group v-model="item">
                <v-list-item v-for="item in items" :key="item.title" nuxt :to="{ name:item.link }">
                    <v-list-item-icon>
                        <v-icon>{{ item.icon }}</v-icon>
                    </v-list-item-icon>
                    <v-list-item-content>
                        <v-list-item-title>{{ item.title }}</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
            </v-list-item-group>
        </v-list>

        <template v-slot:append>
            <div class="pa-2">
                <v-btn block color="danger" dark>Cerrar sesion</v-btn>
            </div>
        </template>
    </v-navigation-drawer>
    <v-app-bar color="navDrawer" dark app>
        <v-app-bar-nav-icon v-show="$vuetify.breakpoint.xs || $vuetify.breakpoint.sm || $vuetify.breakpoint.md" @click="drawer = !drawer"></v-app-bar-nav-icon>

        <v-toolbar-title v-html="title"></v-toolbar-title>

        <v-spacer></v-spacer>

        <v-btn @click="$vuetify.theme.dark = !$vuetify.theme.dark" icon>
            <v-icon v-if="$vuetify.theme.dark">
                mdi-brightness-5
            </v-icon>
            <v-icon v-else>
                mdi-brightness-2
            </v-icon>
        </v-btn>

        <v-btn icon @click="dialog = !dialog">
            <v-icon>
                mdi-settings
            </v-icon>
        </v-btn>
    </v-app-bar>
    <v-content class="background">
        <v-container fluid>
            <nuxt ref="nuxt" />
        </v-container>
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
                    <v-btn dark text @click="dialog = false">Save</v-btn>
                </v-toolbar-items>
            </v-toolbar>
            <v-list color="background" three-line subheader>
                <v-subheader>User Controls</v-subheader>
                <v-list-item>
                    <v-list-item-content>
                        <v-list-item-title>Content filtering</v-list-item-title>
                        <v-list-item-subtitle>Set the content filtering level to restrict apps that can be downloaded</v-list-item-subtitle>
                    </v-list-item-content>
                </v-list-item>
                <v-list-item>
                    <v-list-item-content>
                        <v-list-item-title>Password</v-list-item-title>
                        <v-list-item-subtitle>Require password for purchase or use password to restrict purchase</v-list-item-subtitle>
                    </v-list-item-content>
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
            item: 3,
            items: [{
                    title: 'Panel administrativo',
                    icon: 'mdi-view-dashboard',
                    link: 'index'
                },
                {
                    title: 'Cursos',
                    icon: 'mdi-plus-outline',
                    link: 'Cursos'
                },
                {
                    title: 'Preregistro',
                    icon: 'mdi-note-text',
                    link: 'Preregistro'
                },
                {
                    title: 'Folio status',
                    icon: 'mdi-table-edit',
                    link: 'Folio_status'
                },
                {
                    title: 'Registros',
                    icon: 'mdi-library-books',
                    link: 'Registros'
                },
                {
                    title: 'Constancias',
                    icon: 'mdi-pencil',
                    link: 'Constancias'
                },
                {
                    title: 'Crear post',
                    icon: 'mdi-monitor-cellphone',
                    link: 'Crear_post'
                },
                {
                    title: 'Crear usuario',
                    icon: 'mdi-account-plus',
                    link: 'Crear_usuario'
                },
            ],
            drawer: false,
            dialog: false,
            title: "Inicio"
        }
    },
    mounted() {
        this.title = this.$route.name.replace('_', " ")
    },
    watch: {
        $route() {
            this.title = this.$route.name.replace('_', " ")
        }
    }
}
</script>

<style lang="scss">
html,
body {
    min-width: 100vw !important;
    max-width: 100vw !important;
    min-height: 100vh !important;
    overflow-x: hidden !important;
    overflow-y: auto !important;
}
</style>
