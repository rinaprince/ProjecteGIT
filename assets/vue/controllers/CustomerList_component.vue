<template>
  <div>
    <h1>Customer index</h1>

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
          <th>Customer_type</th>
          <th>Operations</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="customer in customers">
          <td data-title="Name:">{{ customer.name }}</td>
          <td data-title="Lastname">{{ customer.lastname }}</td>
          <td data-title="Address">{{ customer.address }}</td>
          <td data-title="DNI">{{ customer.dni }}</td>
          <td data-title="Phone">{{ customer.phone }}</td>
          <td data-title="Email">{{ customer.email }}</td>
          <td data-title="Type">{{ customer.type }}</td>
        </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
import { defineProps } from 'vue';

defineProps({
  customers: Array
});
</script>
