<template>
  <div class="modal is-active">
    <div class="modal-background"></div>
      <div class="modal-card">
        <header class="modal-card-head">
          Agregar Usuario 
        </header>
          <section class="modal-card-body">
            <div v-if="isLoading">
              <h1 class="title">Cargando...</h1>
            </div>
            <div v-else>
              <form class="form">
                <div class="">
                  <input id="user_id" name="user_id" hidden>
                </div>
                <div class="field">
                  <label class="label">Apellido*</label>
                  <div class="control">
                    <input id="apellido" type="text" class="input" name="apellido" required>
                  </div>
                </div>
                <div class="field">
                  <label class="label">Nombre*</label>
                  <div class="control">
                    <input id="nombre" type="text" class="input" name="nombre" required>
                  </div>
                </div>
                <div class="field">
                  <label class="label">Email*</label>
                  <div class="control">
                    <input type="email" class="input" name="email" placeholder="ejemplo@ejemplo.com" required>
                  </div>
                </div>
                <div class="field">
                  <label class="label">Nombre de usuario*</label>
                  <div class="control">
                    <input type="text" class="input" name="username" required>
                  </div>
                  <p class="help">Debe tener entre 6 y 20 caracteres</p>
                </div>
                <div class="field">
                  <label class="label roles-label">Qué roles desea asignarle? Si no asigna ninguno puede hacerlo en "editar"</label>
                    <div class="control">
                        <div v-for="rol in roles">

                            <label>{{ rol.nombre }}</label>
                            <input name="roles[]" class="styled" type="checkbox" :value="rol.nombre">

                        </div>
                    </div>
                </div>
                <div class="field">
                  <label class="label">Contraseña*</label>
                  <div class="control">
                    <input id="password" type="password" class="input" name="password" required>
                  </div>
                  <p class="help">Debe tener entre 8 y 20 caracteres</p>
                </div>
                <div class="field">
                  <label class="label">Confirmar contraseña*</label>
                  <div class="control">
                    <input id="re_password" type="password" class="input" name="re_password" required>
                  </div>
                </div>
                <p>* campos obligatorios</p>
              </form>
            </div>
          </section>
        <footer class="modal-card-foot">
          <button type="submit" class="button is-success" value="add_user">Aceptar</button>
          <button type="button" class="button is-text" @click="$emit('close')">Cancelar</button>
        </footer>
        <button class="modal-close" @click="$emit('close')"></button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Modal',
  data() {
    return {
      roles: null,
      isLoading: true,
      error: ''
    }
  },
  created() { //cargar los roles desde la Api
    axios
      .get('http://localhost:8000/role')
      .then(response => {
        this.roles = JSON.parse(response.data);
        this.isLoading = false
      })
      .catch(error => {
        this.error = error
      })
  }
}
</script>

<style lang="css" scoped>
</style>
