<template>

    <div class="notification is-warning" v-if="mantenimiento">
        <button class="delete"></button>
        <i class="fas fa-exclamation-triangle"></i> El sitio se encuentra bajo
        <strong>mantenimiento </strong><i class="fas fa-wrench"></i>. El acceso se encuentra restringido solo a personal de  <strong>administracion</strong>. Disculpe las molestias.
    </div>

</template>

<script>
  export default {

    data() {
        return {
          mantenimiento: false,
        }
    },
    created(){
        events.$on('mantenimiento:active',() => this.mantenimiento = true);
        events.$on('mantenimiento:inactive',() => this.mantenimiento = false);
    },

  }
</script>