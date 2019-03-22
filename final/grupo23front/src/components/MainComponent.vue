<template>
  <div >
    <h5>{{ msg }}</h5>
    <input v-model="message" placeholder="test">
    <p>Escribiste: {{ message }}</p>
    <p>Lo que escribiste pero al reves: {{ voltear }}</p>


    <input v-model="numbers" placeholder="Escribi numeros" v-on:keypress="isNumber(event)" >
    <div v-if="esMayor()">
        <p v-bind:class=" {'red-text ': yaSonMuchos() }">Escribiste muchos numeros: {{ numbers }}</p>
    </div>
    <div v-else-if="numbers == ''" >
      <p> No escribiste nada</p>
    </div>
    <div v-else >
        <p>Escribiste poquitos numeros: {{ numbers}}</p>
    </div>

      <!-- Aca instancio/llamo/ubico el componente hijo tambien se relaciona el valor de la variable number con texto_boton del componente hijo utilizando v-bind -->
      <BotoncitosComponent v-bind:texto_boton="numbers" v-bind:texto="message" > </BotoncitosComponent>

    <p>Lo que escribiste mas los numeros: {{todoJunto || capitalize}} </p>
  </div>
</template>

<script>

import BotoncitosComponent from './BotoncitosComponent' //lo importo porq llamo al componentee en el template//

export default {
  /**
   *Se registrar los componentes usando esto
   */
  components: {BotoncitosComponent},


  name: 'MainComponent',
  props: {
    msg: String
  },

  data: function () {
    return {
      message: '',
      numbers: '',
    }
  },

  /**Las variables computadas son variables que su valor es determinado (computado) en base a otras variables y/o una serie de operaciones, NO son funciones */
  computed: {
    todoJunto: function () {
      return this.message + this.numbers
    },



    voltear: function () {
      return this.message.split('').reverse().join('')
    }
  },

  /**
   * en methods van las funciones
   *
   */
  methods: {

    esMayor() {
      if (this.numbers.length > 8) return true
    },

    yaSonMuchos() {
      if (this.numbers.length > 11) return true
    },

    /**
     * Este metodo/funcion recibe un evento
     */
    isNumber: function(evt) {
      evt = (evt) ? evt : window.event; // (condicion) ? siEsVerdadero : siEsFalso; evt si es un evento se almacena como el evento, sino instancia un window.event vacio
      var charCode = (evt.which) ? evt.which : evt.keyCode; //  ASCII de la tecla
      if ((charCode > 31 && (charCode < 48 || charCode > 57)) && charCode !== 46) {
        evt.preventDefault(); //Si no es un NUMERO preventDefault no permitir que pase//
      } else {
        return true; //Es un numero
      }
    }
  }

}
</script>
