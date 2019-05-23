<template>
  <div id='app'>
    <app-navbar></app-navbar>
    <router-view></router-view>
    <app-footer></app-footer>
  </div>
</template>

<script>
// import MainComponent from './components/MainComponent.vue';
import Navbar from './components/Navbar.vue';
import Footer from './components/Footer.vue';
export default {
  watch: {
    $route(to,from){
      this.$root.bloqueo = true;
      axios.get('http://localhost:8000/configuracion/').then((response) => {
      this.$root.info = response.data;
      this.$root.bloqueo = false;
      })
    }
  },
  components: {
    AppNavbar: Navbar, //les puse nombre precedido por App para que cuando uses el tag no se confunda con alguno de HTML
    AppFooter: Footer //por este de Footer sobre todo...
  }
}
</script>
