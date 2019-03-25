<template>
  <div id='app'>
    <app-navbar></app-navbar>
    <!-- <img alt="Vue logo" src="./assets/logo.png">
    <ul>
      <li>
        <router-link to="/lugar1">Test get y boludeces</router-link>
      </li>
      <li>
        <router-link to="/lugar2">Test del submit</router-link>
      </li>
    </ul> -->
    <!--<MainComponent msg="Soy un parametro que viene del componente padre"/>-->
    <router-view></router-view>
    <app-footer></app-footer>
  </div>
</template>

<script>
// import MainComponent from './components/MainComponent.vue';
import Navbar from './components/Navbar.vue';
import Footer from './components/Footer.vue';

export default {
  data() { //es otra forma de escribir data: function() {}, más piola.
    return {
    }
  },
  mounted() { //esto se ejecuta cuando se carga este componente, y ejecuta la 'action' loadConfig del vueXStore
      this.$store.dispatch('loadConfig')
  },
  components: {
    AppNavbar: Navbar, //les puse nombre precedido por App para que cuando uses el tag no se confunda con alguno de HTML
    AppFooter: Footer //por este de Footer sobre todo...
  }
}
</script>
