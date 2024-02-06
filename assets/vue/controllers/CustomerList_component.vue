<template>
  <div>
    <div id="div-table">
      <table class="table mt-3 contingut">
        <thead>
        <tr>
          <th v-for="key in Object.keys(config)">{{ config[key] }}</th>
          <th>Operations</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="element in elements" :key="element.id">
          <td v-for="property in Object.keys(config)" :data-title="config[property]">{{ element[property] }}</td>
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
</script>



<!--
<template>
  <div>
    <div id="div-table">
      <table class="table mt-3 contingut">
        <thead>
          <tr>
            <th>Name</th>
            <th>Lastname</th>
            <th>Address</th>
            <th>Dni</th>
            <th>Phone</th>
            <th>Email</th>
            <th>element_type</th>
            <th>Operations</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="element in elements" >
            <td data-title="Name:">{{ element.name }}</td>
            <td data-title="Lastname">{{ element.lastname }}</td>
            <td data-title="Address">{{ element.address }}</td>
            <td data-title="DNI">{{ element.dni }}</td>
            <td data-title="Phone">{{ element.phone }}</td>
            <td data-title="Email">{{ element.email }}</td>
            <td data-title="Type">{{ element.type }}</td>
            <td data-title="Operations">
              <template v-if="element.type === 'private'">
                <a :href="privateShowPath(element.id)"><button class="details-button" data-invoice-id="3"><i class="fas fa-eye"></i></button></a>
                <a :href="privateEditPath(element.id)"><button class="edit-button" data-invoice-id="3"><i class="fas fa-pencil-alt"></i></button></a>
              </template>
              <template v-else-if="element.type === 'professional'">
                <a :href="professionalShowPath(element.id)"><button class="details-button" data-invoice-id="3"><i class="fas fa-eye"></i></button></a>
                <a :href="professionalEditPath(element.id)"><button class="edit-button" data-invoice-id="3"><i class="fas fa-pencil-alt"></i></button></a>
              </template>
            </td>
          </tr>
        </tbody>
      </table>
    </div>


  </div>

</template>

<script setup>

defineProps({
  elements: Array
});

const privateShowPath = (id) => `/private/element/${id}`;
const privateEditPath = (id) => `/private/element/${id}/edit`;
const professionalShowPath = (id) => `/professional/${id}`;
const professionalEditPath = (id) => `/professional/${id}/edit`;

</script>


-->