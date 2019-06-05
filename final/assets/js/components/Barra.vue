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
            <div class="navbar-start">
                <!-- navbar items izquierda -->
                <p class="is-hidden-touch navbar-item"></p>

                    <router-link class="navbar-item" :to="{ path: '/app/paciente'}" replace v-if="authenticated_user">Pacientes</router-link>

                    <div class="navbar-item has-dropdown is-hoverable" v-if="authenticated_user">
                        <!-- navbar-link, navbar-dropdown etc. -->

                        <a class="navbar-link">Administración</a>
                        <div class="navbar-dropdown">
                            <router-link class="navbar-item" :to="{ path: '/app/usuario'}" replace>Usuarios</router-link>
                            <router-link class="navbar-item" :to="{ path: '/app/reportes'}" replace>Reportes</router-link>
                            <hr class="navbar-divider">
                            <router-link class="navbar-item" :to="{ path: '/app/roles'}" replace>Roles y permisos</router-link>
                            <router-link class="navbar-item" :to="{ path: '/app/config'}" replace>Configuración</router-link>
                        </div>

                    </div>

            </div>

            <div class="navbar-end" v-if="authenticated_user">
                <!-- navbar items derecha -->
                    <p class="is-hidden-touch navbar-item">{{ loggedUser.first_name }} {{ loggedUser.last_name }}</p>
                    <a class="navbar-item" title="Salir" href="" v-on:click="logout">
                        <span class="is-hidden-desktop">Salir</span>
                        <i class="fas fa-sign-out-alt fa-lg is-hidden-touch"></i>
                    </a>
            </div>

                <div class="navbar-end" v-else>
                    <router-link class="navbar-item" :to="{ path: '/app/login'}" replace>Ingresar</router-link>
                </div>

            </div>
    </nav>
</template>

<script>
export default {
  data() {
    return {
      burgerIsOpen: false,
      loading_config: true,
      authenticated_user: false,
      base_url: window.location.host,
    }
  },
  mounted() {
      events.$on('loading_config:finish', () => this.loading_config = false)
      events.$on('loading_user:finish', () => this.authenticated_user = true)
  },
  methods: {
      logout(){
          this.authenticated_user = false;
          axios.defaults.headers.common['Authorization'] = null;
          localStorage.removeItem('token');
          this.jwtToken.clear;
          this.loggedUser.clear;
          events.$emit('user:logout');
      }
  }
  }

</script>
