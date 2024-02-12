<template>

  <table class="table">
    <thead>
    <tr>
      <th>Customer Name</th>
      <th>Vehicle Model</th>
      <th>Order State</th>
      <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    <tr v-for="order in orders">
      <td data-title="Customer Name:">{{ order.customer.name }}</td>
      <td data-title="Model:">
        <span v-for="(vehicle, index) in order.vehicles" :key="index">
          {{ vehicle.model.name }}
        </span>
      </td>
      <td data-title="Estat:">{{ order.state }}</td>
      <td>
        <a href="{{ path('app_order_show', {'id': order.id}) }}" class="pe-2">show</a>
        <a href="{{ path('app_order_edit', {'id': order.id}) }}">edit</a>
      </td>
    </tr>
    </tbody>
  </table>
</template>

<script setup>
const props = defineProps({
  orders: Array
});
</script>