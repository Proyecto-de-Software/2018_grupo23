<template>
  <div>
    <nav class="navbar is-info is-fixed-top" role="navigation" aria-label="main navigation">
        <div class="navbar-brand">
            <router-link class="navbar-item" to="/home" title="Logo Hospital">
                <img src="../images/logo.png" width="60" height="30" alt="Logo Hospital">
            </router-link>
            <div class="navbar-burger burger" data-target="nav-acciones">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>

        <div id="nav-acciones" class="navbar-menu">
            <div class="navbar-start">
                <!-- navbar items izquierda -->
                <p class="is-hidden-touch navbar-item"></p>

                    <router-link class="navbar-item" to="paciente">Pacientes</router-link>

                    <div class="navbar-item has-dropdown is-hoverable">
                        <!-- navbar-link, navbar-dropdown etc. -->

                        <a class="navbar-link">Administración</a>
                        <div class="navbar-dropdown">
                            <router-link class="navbar-item" to="usuarios">Usuarios</router-link>
                            <router-link class="navbar-item" to="reportes">Reportes</router-link>
                            <hr class="navbar-divider">
                            <router-link class="navbar-item" to="roles_y_permisos">Roles y permisos</router-link>
                            <router-link class="navbar-item" to="config">Configuración</router-link>
                        </div>

                    </div>

            </div>

            <div class="navbar-end">
                <!-- navbar items derecha -->

                <!-- {% if loged_user %} -->
                    <!-- <p class="is-hidden-touch navbar-item">{{ "#{loged_user.first_name} #{loged_user.last_name}" }}</p>
                    <a class="navbar-item" title="Salir" href="./?action=logout">
                        <span class="is-hidden-desktop">Salir
                        </span>
                        <i class="fas fa-sign-out-alt fa-lg is-hidden-touch"></i>
                    </a> -->
                <!-- {% else %} -->
                <router-link class="navbar-item" to="/login">Ingresar</router-link>

                    <!-- <a class="navbar-item" href="./?action=view_login">Ingresar</a> -->
                <!-- {% endif %} -->
            </div>
        </div>
    </nav>
  </div>
</template>

<script>

import { mapGetters, mapActions } from 'vuex';

  export default {
       computed: {
    ...mapGetters([
      'jwt'
    ])
  },
  /*beforMounted : [
    ( function() {
      axios.get('http://localhost:8000/configuracion/').then((response) => {
        var datos = JSON.parse(response.data);
       for (var i = 0; i < datos.length; i++) { //queda algo del tipo { titulo: "Hospital...", email: email@gmail.com...}
       this.$root.global.configuracion[datos[i].variable] = datos[i].valor;
    } }
   )})()]*/
  }
  
  
</script>

<style>

</style>
