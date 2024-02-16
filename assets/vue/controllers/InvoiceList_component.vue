<script setup>
const {invoices} = defineProps(['invoices']);
//const q = props.q;
import {ref, onMounted, computed} from 'vue';
//Importem axios
import axios from 'axios';


//Rutas de los botones
const invoiceShowPath = (id) => `/invoices/${id}`;
const invoiceCreatePath = '/invoices/new';
const invoiceEditPath = (id) => `/invoices/${id}/edit`;
const invoiceDeletePath = (id) => `/invoices/${id}/delete`;

//Tipos de filtrado
const filters = ref({
  global: {value: null, matchMode: 'CONTAINS'},
  number: {value: null, matchMode: 'CONTAINS'},
  customer: {value: null, matchMode: 'STARTS_WITH'},
  price: {value: null, matchMode: 'IN'},
  date: {value: null, matchMode: 'EQUALS'},
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
  showEditModal(id); // Pasar el ID de la factura seleccionada a showModal
};

const openShowModal = (id) => {
  const invoice = invoices.find(inv => inv.id === id);
  selectedInvoice.value = invoice;
  showDetailsModal(id);
}

//Funció per tancar el modal
const closeModal = () => {
  selectedInvoice.value = null;
  let modal = document.querySelector('.modal');
  modal.style.display = 'none';
  window.location.reload();
};

function openNewModal() {
  axios.get(`/invoices/new`)
      .then(response => {
        const modalBody = document.querySelector('.modal-content');
        const parsedHTML = new DOMParser().parseFromString(response.data, 'text/html');
        const detailsDiv = parsedHTML.querySelector('#newBody');

        if (detailsDiv) {
          modalBody.innerHTML = detailsDiv.outerHTML;
        }

        // Mostrar el modal
        const modal = document.querySelector('.modal');
        modal.style.display = 'block';
      })
      .catch(error => {
        console.error('Error al obtener los detalles de la factura:', error);
      });
}

function showDetailsModal(id) {
  axios.get(`/invoices/${id}`)
      .then(response => {
        const modalBody = document.querySelector('.modal-content');
        const parsedHTML = new DOMParser().parseFromString(response.data, 'text/html');
        const detailsDiv = parsedHTML.querySelector('.contingut');
        console.log(detailsDiv);
        console.log(response.data);

        if (detailsDiv) {
          modalBody.innerHTML = detailsDiv.outerHTML;
        }

        // Mostrar el modal
        const modal = document.querySelector('.modal');
        modal.style.display = 'block';
      })
      .catch(error => {
        console.error('Error al obtener los detalles de la factura:', error);
      });
}

function showEditModal(id) {
  axios.get(`/invoices/${id}/edit`)
      .then(response => {
        const modalBody = document.querySelector('.modal-content');
        const parsedHTML = new DOMParser().parseFromString(response.data, 'text/html');
        const formDiv = parsedHTML.querySelector('#form');
        formDiv.querySelector("form").action = `/invoices/${id}/edit`
        if (formDiv) {
          modalBody.innerHTML = formDiv.outerHTML;

          // Mostrar el modal
          const modal = document.querySelector('.modal');
          modal.style.display = 'block';
        } else {
          console.error('No se encontró el div con el ID "form" en la respuesta.');
        }
      })
      .catch(error => {
        console.error('Error al obtener el contenido del modal:', error);
      });
}

function showDeleteInvoice(id) {
  // Realiza una solicitud de eliminación al servidor
  axios.post(`/invoices/${id}/delete`)
      .then(response => {
        // Elimina la factura de la matriz de facturas
        const index = invoices.findIndex(inv => inv.id === id);
        if (index !== -1) {
          invoices.splice(index, 1);
        }
      })
      .catch(error => {
        console.error('Error deleting invoice:', error);
      });
}

</script>

<template>
  <div>
    <div>
      <div class="d-flex justify-content-between align-items-center bg-quaternary-BHEC col-10">
        <form method="GET" role="search">
          <div class="d-flex my-3 "><input name="q" type="search"
                                          class="rounded-start-pill border border-secondary-subtle px-4 "
                                          placeholder="Buscar..." aria-label="Search">
            <button type="submit" class="rounded-end-pill bg-tertiary-BHEC border border-0"><i class="bi bi-search"></i>
            </button>
          </div>
        </form>
        <a href="#" @click="openNewModal" class="button-text-primary-BHEC btn bg-tertiary-BHEC">
          <i class="bi bi-plus-square me-1"></i><p class="d-sm-inline d-none">Nueva Factura</p>
        </a>
      </div>
    </div>
  </div>

  <div class="col-10">
    <table class="table table-striped w-100 m-0 bg-tertiary-BHEC d-sm-table d-none">
      <thead class="theadInvoices text-center">
      <tr>
        <th class="py-1 bg-tertiary-BHEC">Numero</th>
        <th class="bg-tertiary-BHEC">Usuario</th>
        <th class="bg-tertiary-BHEC">Precio</th>
        <th class="bg-tertiary-BHEC">Fecha</th>
        <th class="bg-tertiary-BHEC">Operaciones</th>
      </tr>
      </thead>
      <tbody class="text-center">
      <tr v-for="invoice in filteredInvoices" :key="invoice.id">
        <td data-title="Numero:">{{ invoice.number }}</td>
        <td data-title="Usuario:">{{ invoice.customer.name }}</td>
        <td data-title="Precio:">{{ invoice.price }}</td>
        <td data-title="Fecha:">{{ invoice.date.date.substring(0, 10) }}</td>
        <td class="py-3">
          <button class="btn btn-success mx-1" @click="openShowModal(invoice.id)"><i class="fas fa-eye"></i></button>
          <button class="btn btn-info mx-1" @click="openEditModal(invoice.id)"><i class="fas fa-pencil-alt"></i>
          </button>
          <button class="btn btn-danger mx-1" @click="showDeleteInvoice(invoice.id)"><i class="fas fa-trash"></i></button>

        </td>
      </tr>
      </tbody>
    </table>
  </div>


  <div id="accordion" class="accordion accordion-flush d-flex justify-content-center d-sm-none d-flex flex-wrap text-center">
    <div v-for="invoice in filteredInvoices" :key="invoice.id">
      <div class="card" style="width: 18rem;">
        <div class="card-header" id="heading{{ invoice.id }}">
          <h2 class="mb-0">
            <button class="btn " type="button" data-bs-toggle="collapse"  :data-bs-target="'#collapse' + invoice.id" aria-expanded="false" :aria-controls="'collapse' + invoice.id">
              Nº: {{ invoice.number }}
            </button>
          </h2>
        </div>
        <div :id="'collapse' + invoice.id" class="collapse" aria-labelledby="heading{{ invoice.id }}" data-parent="#accordion">
          <div class="card-body text-center">
            <p data-title="Usuario:">Nom: {{ invoice.customer.name }}</p>
            <p data-title="Precio:">Preu: {{ invoice.price }}</p>
            <p data-title="Fecha:">Data: {{ invoice.date.date.substring(0, 10) }}</p>
            <button class="btn btn-success mx-1" @click="openShowModal(invoice.id)"><i class="fas fa-eye"></i></button>
            <button class="btn btn-info mx-1" @click="openEditModal(invoice.id)"><i class="fas fa-pencil-alt"></i></button>
            <button class="btn btn-danger mx-1" @click="showDeleteInvoice(invoice.id)"><i class="fas fa-trash"></i></button>
          </div>
        </div>
      </div>
    </div>
  </div>



  <div class="modal" v-if="selectedInvoice !== null">
      <div class="modal-content">
        <button @click="closeModal">Cerrar</button>
      </div>
    </div>

</template>

