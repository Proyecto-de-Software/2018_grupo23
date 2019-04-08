<template>
  <div class="">
    {{ users }}
  </div>
</template>

<script>
  import axios from 'axios';
  export default {
    name: 'UserIndex',
    data() {
      return {
        users: null
      }
    },
    created() {
      axios
      .get('http://localhost:8000/api/usuario')
      .then(response => (this.users = response.data))
    }
  }
</script>
