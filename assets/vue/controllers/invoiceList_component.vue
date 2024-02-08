
<script setup>
const { invoices } = defineProps(['invoices']);
//const q = props.q;
import { ref, onMounted, computed } from 'vue';
//Importem axios
import axios from 'axios';


//Rutas de los botones
const invoiceShowPath = (id) => `/invoices/${id}`;
const invoiceCreatePath = '/invoices/new';
const invoiceEditPath = (id) => `/invoices/${id}/edit`;
const invoiceDeletePath = (id) => `/invoices/${id}/delete`;

//Tipos de filtrado
const filters = ref({
  global: { value: null, matchMode: 'CONTAINS' },
  number: { value: null, matchMode: 'CONTAINS' },
  customer: { value: null, matchMode: 'STARTS_WITH' },
  price: { value: null, matchMode: 'IN' },
  date: { value: null, matchMode: 'EQUALS' },
});

//Filtrador
const applyFilters = (data, filters) => {
  return data.filter((invoice) => {
    return (
        (!filters.global.value || JSON.stringify(invoice).toLowerCase().includes(filters.global.value.toLowerCase())) &&
        (!filters.number.value || invoice.number.toLowerCase().includes(filters.number.value.toLowerCase())) &&
        (!filters.customer.value || invoice.customer.name.toLowerCase().startsWith(filters.customer.value.toLowerCase())) &&
        (!filters.price.value || filters.price.value.includes(invoice.price.toString())) &&
        (!filters.date.value || invoice.date.date.substring(0, 10) === filters.date.value)
    );
  });
};

const filteredInvoices = computed(() => {
  return applyFilters(invoices, filters.value);
});


//Modal editar factures
const selectedInvoice = ref(null); // La factura seleccionada para editar

const openEditModal = (invoice) => {
  selectedInvoice.value = invoice; // Guardar la factura seleccionada
  showModal();
};

//Funció per tancar el modal
const closeModal = () => {
  selectedInvoice.value = null; // Limpiar la factura seleccionada
};
// Función para mostrar el modal de edición
const showModal = () => {
  if (selectedInvoice.value !== null) {
    axios.get(`/invoices/${selectedInvoice.value.id}/edit`)
        .then(response => {
          //Filtrem per a que sols ens mostre el formulari
          const html = response.data;
          const parser = new DOMParser();
          const doc = parser.parseFromString(html, 'text/html');
          const form = doc.querySelector('form');
          const modalContent = document.querySelector('.modal-content');
          modalContent.innerHTML = form.outerHTML;
          form.action = '/invoices/$ {selectedInvoice.value.id}/edit'
          // Mostrar el modal
          const modal = document.querySelector('.modal');
          modal.style.display = 'block';
        })
        .catch(error => {
          console.error('Error fetching modal content:', error);
        });
  }
};
</script>

<template>
  <div>
    <input type="text" id="global-filter" v-model="filters.global.value" @input="applyFilters" placeholder="Buscador "/>
    <input type="text" id="number-filter" v-model="filters.number.value" @input="applyFilters" placeholder="Buscar por Numero"/>
    <input type="text" id="customer-filter" v-model="filters.customer.value" @input="applyFilters" placeholder="Buscar por Usuario"/>
  </div>

  <table id="table">
    <thead>
    <tr>
      <th>Numero</th>
      <!---<th>Usuario</th>-->
      <th>Precio</th>
      <th>Fecha</th>
      <th>Operaciones</th>
    </tr>
    </thead>
    <tbody>
    <tr v-for="invoice in filteredInvoices" :key="invoice.id">
      <td data-title="Numero:">{{invoice.number}}</td>
     <!-- <td data-title="Usuario:">{{invoice.customer.name}}</td>-->
      <td data-title="Precio:">{{invoice.price}}</td>
      <td data-title="Fecha:">{{invoice.date.date.substring(0, 10)}}</td>
      <td>
        <a :href="invoiceShowPath(invoice.id)">
          <button class="btn btn-success"><i class="fas fa-eye"></i></button>
        </a>
       <!-- <a :href="invoiceEditPath(invoice.id)">-->
          <button class="btn btn-info" @click="openEditModal(invoice)"><i class="fas fa-pencil-alt"></i></button>
       <!-- </a>-->
        <a :href="invoiceDeletePath(invoice.id)">
          <button class="btn btn-danger"><i class="fas fa-trash"></i></button>
        </a>
      </td>
    </tr>
    </tbody>
  </table>
  <!-- Modal de edición -->
  <div class="modal" v-if="selectedInvoice !== null">
    <div class="modal-content">
      {{ selectedInvoice.modalContent }}
      <button @click="closeModal">Cerrar</button>
    </div>
  </div>


  <a :href="invoiceCreatePath"><button class="">Create new</button></a>
</template>
