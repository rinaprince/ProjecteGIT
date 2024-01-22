
<!--<form method="GET" class="col-12 col-lg-auto mb-3 mb-lg-0 me-lg-3" role="search">
<input name="q" type="search" class="form-control text-bg-light" placeholder="Search..."
       aria-label="Search">
</form>-->
<template>

  <div>
    <label for="global-filter">Global Filter:</label>
    <input type="text" id="global-filter" v-model="filters.global.value" @input="applyFilters"/>
  </div>

  <table id="backoffice-table">
    <thead>
    <tr>
      <th>Numero</th>
      <th>Usuario</th>
      <th>Precio</th>
      <th>Fecha</th>
      <th>Operaciones</th>
    </tr>
    </thead>
    <tbody>
    <tr v-for="invoice in filteredInvoices" :key="invoice.id">
      <td data-title="Numero:">{{invoice.number}}</td>
      <td data-title="Usuario:">{{invoice.customer.name}}</td>
      <td data-title="Precio:">{{invoice.price}}</td>
      <td data-title="Fecha:">{{invoice.date.date.substring(0, 10)}}</td>
      <td>
        <a :href="invoiceShowPath(invoice.id)">
          <button class="details-button"><i class="fas fa-eye"></i></button>
        </a>
        <a :href="invoiceEditPath(invoice.id)">
          <button class="edit-button"><i class="fas fa-pencil-alt"></i></button>
        </a>
        <a :href="invoiceDeletePath(invoice.id)">
          <button class="delete-button"><i class="fas fa-trash"></i></button>
        </a>
      </td>
    </tr>
    </tbody>
  </table>


  <a :href="invoiceCreatePath"><button>Create new</button></a>

</template>

<script setup>
import { ref, onMounted, computed } from 'vue';

// Define la variable loading
const loading = ref(true);

// Define los props
defineProps({
  invoices: Array
});

// Define el resto de las variables y funciones
const invoiceShowPath = (id) => `/invoices/${id}`;
const invoiceCreatePath = '/invoices/new';
const invoiceEditPath = (id) => `/invoices/${id}/edit`;
const invoiceDeletePath = (id) => `/invoices/${id}/delete`;

// Filtros de búsqueda
const filters = ref({
  global: { value: null, matchMode: 'CONTAINS' },
  number: { value: null, matchMode: 'STARTS_WITH' },
  customer: { value: null, matchMode: 'STARTS_WITH' },
  price: { value: null, matchMode: 'IN' },
  date: { value: null, matchMode: 'EQUALS' },
});

// Función para aplicar filtros
const applyFilters = (data, filters) => {
  return data.filter((invoice) => {
    return (
        (!filters.global.value ||
            JSON.stringify(invoice).toLowerCase().includes(filters.global.value.toLowerCase())) &&
        (!filters.number.value ||
            invoice.number.toLowerCase().startsWith(filters.number.value.toLowerCase())) &&
        (!filters.customer.value ||
            invoice.customer.name.toLowerCase().startsWith(filters.customer.value.toLowerCase())) &&
        (!filters.price.value ||
            filters.price.value.includes(invoice.price.toString())) &&
        (!filters.date.value ||
            invoice.date.date.substring(0, 10) === filters.date.value)
    );
  });
};

// Propiedad computada para facturas filtradas
const filteredInvoices = computed(() => {
  if (!loading.value) {
    return applyFilters(invoices, filters.value);
  }
  return invoices;
});

// Hook onMounted
onMounted(() => {
  // Establece Loading en false para indicar que la carga terminó
  loading.value = false;
});

</script>