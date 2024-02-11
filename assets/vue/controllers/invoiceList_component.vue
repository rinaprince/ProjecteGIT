
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

const openEditModal = (id) => {
  const invoice = invoices.find(inv => inv.id === id);
  selectedInvoice.value = invoice; // Guardar la factura seleccionada
  showModal(id); // Pasar el ID de la factura seleccionada a showModal
};


//Funció per tancar el modal
const closeModal = () => {
  selectedInvoice.value = null; // Limpiar la factura seleccionada
};
// Función para mostrar el modal de edición
function showModal(id) {
  axios.get(`/invoices/${id}/edit`)
      .then(response => {
        // Actualizar el contenido del modal con el formulario de edición
        const modalBody = document.querySelector('.modal-content');
        modalBody.innerHTML = response.data;

        // Modificar la acción del formulario para que envíe los datos actualizados a través de AJAX
        const form = modalBody.querySelector('form');
        form.action = `/invoices/${id}/edit`; // Ajusta la acción del formulario según tu ruta de edición
        form.addEventListener('submit', function(event) {
          event.preventDefault(); // Evitar el envío del formulario por defecto
          // Enviar los datos del formulario a través de AJAX
          axios.post(form.action, new FormData(form))
              .then(response => {
                // Manejar la respuesta según sea necesario (por ejemplo, cerrar el modal)
                closeModal();
              })
              .catch(error => {
                console.error('Error al enviar el formulario de edición:', error);
              });
        });

        // Mostrar el modal
        const modal = document.querySelector('.modal');
        modal.style.display = 'block';
      })
      .catch(error => {
        console.error('Error al obtener el contenido del modal:', error);
      });
}

</script>

<template>
  <div class="d-flex justify-content-center">
    <input type="text" id="global-filter" v-model="filters.global.value" @input="applyFilters" placeholder="Buscador "/>
    <a :href="invoiceCreatePath"><button class="btn p-1"><i class="bi bi-plus-square"></i> Create new</button></a>
  </div>

  <table id="table" class="d-sm-inline d-none">
    <thead class="theadInvoices">
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
        <button class="btn btn-info" @click="openEditModal(invoice.id)"><i class="fas fa-pencil-alt"></i></button>
        <!-- </a>-->
        <a :href="invoiceDeletePath(invoice.id)">
          <button class="btn btn-danger"><i class="fas fa-trash"></i></button>
        </a>
      </td>
    </tr>
    </tbody>
  </table>

  <div class="accordion accordion-flush d-flex justify-content-center">
    <div v-for="invoice in filteredInvoices" :key="invoice.id">
      <p data-title="Numero:">{{invoice.number}}</p>
      <!-- <p data-title="Usuario:">{{invoice.customer.name}}</p>-->
      <p data-title="Precio:">{{invoice.price}}</p>
      <p data-title="Fecha:">{{invoice.date.date.substring(0, 10)}}</p>
      <p>
        <a :href="invoiceShowPath(invoice.id)">
          <button class="btn btn-success"><i class="fas fa-eye"></i></button>
        </a>
        <!-- <a :href="invoiceEditPath(invoice.id)">-->
        <button class="btn btn-info" @click="openEditModal(invoice)"><i class="fas fa-pencil-alt"></i></button>
        <!-- </a>-->
        <a :href="invoiceDeletePath(invoice.id)">
          <button class="btn btn-danger"><i class="fas fa-trash"></i></button>
        </a>
      </p>
    </div>
  </div>


  <!-- Modal de edició -->
  <div class="modal" v-if="selectedInvoice !== null">
    <div class="modal-content">
      {{ selectedInvoice.modalContent }}
      <button @click="closeModal">Cerrar</button>
    </div>
  </div>

</template>
