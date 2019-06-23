<template>
    <nav class="navbar is-info is-fixed-top" role="navigation" aria-label="main navigation">
        <div class="navbar-brand">
            <router-link class="navbar-item" :to="{ path: '/home'}" replace title="Logo Hospital" >
                <img :src="asset('/build/images/logo.png')" width="60" height="30" alt="Logo Hospital">
            </router-link>
            <a role="button" class="navbar-burger" aria-label="menu" aria-expanded="false" @click="burgerIsOpen = !burgerIsOpen" :class="{'is-active': burgerIsOpen}">
                <span aria-hidden="true"></span>
                <span aria-hidden="true"></span>
                <span aria-hidden="true"></span>
            </a>
        </div>

        <div class="navbar-menu" :class="{'is-active': burgerIsOpen}">
            <span v-if="authenticated_user && ( ( !mantenimiento && loggedUser.roles.length > 0 ) || ( mantenimiento && loggedUser.roles.includes('ROLE_ADMINISTRADOR') ) )" class="navbar-start">
                <!-- navbar items izquierda -->
                <p class="is-hidden-touch navbar-item"></p>

                <router-link v-if="authenticated_user && loggedUser.permisos.includes('paciente_index')" class="navbar-item" :to="{ path: '/app/paciente' }" @click.native="burgerIsOpen = !burgerIsOpen" replace>Pacientes</router-link>

                <div v-if="authenticated_user" class="navbar-item has-dropdown is-hoverable">
                        <!-- navbar-link, navbar-dropdown etc. -->
                    <a class="navbar-link">Administración</a>
                    <div class="navbar-dropdown">
                        <router-link v-if="loggedUser.permisos.includes('usuario_index')" class="navbar-item" :to="{ path: '/app/usuario' }" @click.native="burgerIsOpen = !burgerIsOpen" replace>Usuarios</router-link>
                        <router-link class="navbar-item" :to="{ path: '/app/reportes' }" @click.native="burgerIsOpen = !burgerIsOpen" replace>Reportes</router-link>
                        <hr class="navbar-divider">
                        <router-link v-if="loggedUser.permisos.includes('rol_index')" class="navbar-item" :to="{ path: '/app/roles' }" @click.native="burgerIsOpen = !burgerIsOpen" replace>Roles y permisos</router-link>
                        <router-link v-if="loggedUser.permisos.includes('configuracion_index')" class="navbar-item" :to="{ path: '/app/config' }" @click.native="burgerIsOpen = !burgerIsOpen" replace>Configuración</router-link>
                    </div>
                </div>

                <router-link v-if="authenticated_user" class="navbar-item" :to="{ path: '/app/institucion' }" @click.native="burgerIsOpen = !burgerIsOpen" replace>Instituciones</router-link>

            </span>

            <div v-if="authenticated_user" class="navbar-end">
                <!-- navbar items derecha -->
                <p class="is-hidden-touch navbar-item">{{ loggedUser.first_name }} {{ loggedUser.last_name }}</p>
                <a class="navbar-item" title="Salir" @click="logout">
                    <span class="is-hidden-desktop">Salir</span>
                    <i class="fas fa-sign-out-alt fa-lg is-hidden-touch"></i>
                </a>
            </div>
                <div v-else class="navbar-end">
                    <router-link class="navbar-item" :to="{ path: '/app/login' }" @click.native="burgerIsOpen = false" replace>Ingresar</router-link>
                </div>
            </div>
    </nav>
</template>

<script>

export default {
  data() {
    return {
      burgerIsOpen: false,
      mantenimiento: false,
      loading_config: true,
      authenticated_user: false,
      base_url: window.location.host
    }
  },
  mounted() {
    events.$on('loading_config:finish', () => this.loading_config = false)
    events.$on('loading_user:finish', () => this.authenticated_user = true)
    events.$on('mantenimiento:active',() => this.mantenimiento = true)
    events.$on('mantenimiento:inactive',() => this.mantenimiento = false)
  },
  methods: {
    logout() {
      events.$emit('user:logout')
      this.burgerIsOpen = false
      this.authenticated_user = false
      axios.defaults.headers.common['Authorization'] = null
      localStorage.removeItem('token')
      this.jwtToken.clear
      this.loggedUser.clear
      this.$router.replace("/")
    }
  }
}

</script>
