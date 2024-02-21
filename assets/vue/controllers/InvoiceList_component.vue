
<script setup>
const {invoices} = defineProps(['invoices']);
//const q = props.q;
import {ref, onMounted, computed} from 'vue';
//Importem axios
import axios from 'axios';
import $ from 'jquery';
import Swal from 'sweetalert2';


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
};

/*function openNewModal() {
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
}*/

function showDetailsModal(id) {
  axios.get(`/invoices/${id}`)
      .then(response => {
        const modalBody = document.querySelector('.modal-body');
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

function confirmDelete(id) {
  Swal.fire({
    title: '¿Estàs segur?',
    text: 'No podràs desfer aquesta acció',
    icon: 'warning',
    showCancelButton: true,
    buttons: {
      confirm: {
        text: 'Sí, eliminar',
        class: 'sweetConfirm',
      }
    },
    confirmButtonText: 'Sí, esborrar',
    cancelButtonColor: '#d33',
  }).then((result) => {
    if (result.isConfirmed) {
      // Llama a la función deleteInvoice solo si el usuario confirma la eliminación
      deleteInvoice(id);
    }
  });
}


async function deleteInvoice(id) {
  try {
    const response = await axios.post('/invoices/' + id + '/delete');
    if (response.data.success) {
      // Eliminar la factura del arreglo invoices de manera reactiva solo si la eliminación en el servidor fue exitosa
      const index = invoices.findIndex(inv => inv.id === id);
      if (index !== -1) {
        invoices.splice(index, 1);

        // Eliminar la fila correspondiente de la tabla en la interfaz de usuario
        const tableRow = document.querySelector(`#invoicesTable tr[data-invoice-id="${id}"]`);
        if (tableRow) {
          tableRow.remove();
        }
      }
      Swal.fire(
          'Eliminado!',
          'El registro ha sido eliminado correctamente.',
          'success'
      );
    }
  } catch (error) {
    Swal.fire(
        'Error!',
        'Hubo un problema al eliminar el registro.',
        'error'
    );
    console.error('Error al eliminar el registro:', error);
  }
}



$(document).ready( function () {

  //----------------------------------------------------------------------------
  $('.sweetConfirm').on('click', function () {
    let tr = this.closest('tr'); // Troba el 'Tr' de la taula més proper al botó prés(pulsado)
    let employeeId = $(tr).find('td:eq(0)').text(); // Obté la id del registre a eliminar
    tr.remove(); // Elimina directament la fila de la taula
  })
  //-----------------------------------------------------------------------------
} );




</script>

<template>
  <div>
    <div>
      <div class="d-flex justify-content-between align-items-center bg-quaternary-BHEC ">
        <form method="POST" role="search">
          <div class="d-flex my-3 justify-content-right"><input name="q" type="search"
                                           class="rounded-start-pill border border-secondary-subtle px-4 py-2 "
                                           placeholder="Buscar..." aria-label="Search">
            <button type="submit" class="rounded-end-pill bg-tertiary-BHEC border border-0 px-2"><i class="bi bi-search"></i>
            </button>
          </div>
        </form>
        <!--        <a href="#" @click="openNewModal" class="button-text-primary-BHEC btn bg-tertiary-BHEC">
                  <i class="bi bi-plus-square me-1"></i><p class="d-sm-inline d-none">Nueva Factura</p>
                </a>-->
      </div>
    </div>
  </div>

  <div>
    <table class="table table-striped table-responsive w-100 m-0 bg-tertiary-BHEC d-sm-table d-none ">
      <thead class="theadInvoices text-center">
      <tr>
        <th class="py-1 bg-tertiary-BHEC d-none">ID</th>
        <th class="bg-tertiary-BHEC">Número</th>
        <th class="bg-tertiary-BHEC">Usuari</th>
        <th class="bg-tertiary-BHEC">Preu</th>
        <th class="bg-tertiary-BHEC">Data</th>
        <th class="bg-tertiary-BHEC">Operacions</th>
      </tr>
      </thead>
      <tbody class="text-center">
      <tr v-for="invoice in filteredInvoices">
        <td data-title="ID:" class="d-none">{{ invoice.id }}</td>
        <td data-title="Numero:" class="p-auto">{{ invoice.number }}</td>
        <td data-title="Usuario:">{{ invoice.customer.name }}</td>
        <td data-title="Precio:">{{ invoice.price }}</td>
        <td data-title="Fecha:">{{ invoice.date.date.substring(0, 10) }}</td>
        <td class="py-3">
          <button class="btn btn-success mx-1" @click="openShowModal(invoice.id)"><i class="fas fa-eye"></i></button>
          <button class="btn btn-primary mx-1" @click="openEditModal(invoice.id)"><i class="fas fa-pencil-alt"></i>
          </button>
          <button class="btn btn-danger mx-1 delete" @click="confirmDelete(invoice.id)"><i class="fas fa-trash"></i></button>
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
            <button class="btn btn-danger mx-1 delete"><i class="fas fa-trash"></i></button>
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