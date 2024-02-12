<template>
  <div>
    <div id="div-table">
      <table class="table mt-3 contingut">
        <thead>
        <tr>
          <th v-for="(label, key) in config['fields']">{{ label }}</th>
          <th>Operations</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="element in elements" :key="element.id">
          <td v-for="(label, key) in config['fields']" :data-title="label">{{ element[key] }}</td>
          <td>
            <template v-if="element['customer_type'] === 'private'">
              <button v-for="(label, key) in privateRoutes" :key="key" @click="navigate(key, element.id)">{{ label }}</button>
            </template>
            <template v-else-if="element['customer_type'] === 'professional'">
              <button v-for="(label, key) in professionalRoutes" :key="key" @click="navigate(key, element.id)">{{ label }}</button>
            </template>

          </td>

        </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
import { defineProps } from 'vue';

defineProps({
  elements: Array,
  config: Object,
});

const privateRoutes = {
  "private_show": "Visualitzar",
  "private_edit": "Editar",
  "private_delete": "Eliminar",
};

const professionalRoutes = {
  "professional_show": "Visualitzar",
  "professional_edit": "Editar",
  "professional_delete": "Cognoms",
};

const navigate = (route, id) => {
  // Lógica para redireccionar a las rutas de acuerdo al tipo de operación
  // Aquí deberías implementar tu lógica de navegación
  console.log(`Navegar a la ruta ${route} con ID ${id}`);
};

</script>
