<script setup>
//const q = props.q;
import {ref, onMounted, computed} from 'vue';
//Importem axios
import axios from 'axios';
import $ from 'jquery';
import Swal from 'sweetalert2';

const {orders} = defineProps(['orders']);


const orderShowPath = (id) => `/orders/${id}`;
const orderCreatePath = '/orders/new';
const orderEditPath = (id) => `/orders/${id}/edit`;
const orderDeletePath = (id) => `/orders/${id}/delete`;

const filters = ref({
  global: {value: null, matchMode: 'CONTAINS'},
  customer: {value: null, matchMode: 'STARTS_WITH'},
  state: {value: null, matchMode: 'EQUALS'},
});

const applyFilters = (data, filters) => {
  return data.filter((order) => {
    return (
        (!filters.global.value || JSON.stringify(order).toLowerCase().includes(filters.global.value.toLowerCase())) &&
        (!filters.customer.value || order.customer.name.toLowerCase().startsWith(filters.customer.value.toLowerCase())) &&
        (!filters.state.value || order.state.toLowerCase() === filters.state.value.toLowerCase())
    );
  });
};

const filteredOrders = computed(() => {
  return applyFilters(orders, filters.value);
});

const selectedOrder = ref(null);

const openEditModal = (id) => {
  selectedOrder.value = orders.find(ord => ord.id === id);
  showEditModal(id);
};

const openShowModal = (id) => {
  selectedOrder.value = orders.find(ord => ord.id === id);
  showDetailsModal(id);
};

const closeModal = () => {
  selectedOrder.value = null;
  let modal = document.querySelector('.modal');
  modal.style.display = 'none';

};

function openNewModal() {
  axios.get(`/orders/new`)
      .then(response => {
        const modalBody = document.querySelector('.modal-body');
        const parsedHTML = new DOMParser().parseFromString(response.data, 'text/html');
        const detailsDiv = parsedHTML.querySelector('#newBody');

        if (detailsDiv) {
          modalBody.innerHTML = detailsDiv.outerHTML;
        }

        const modal = document.querySelector('.modal');
        modal.classList.add('show'); // Agregar la clase 'show' al modal
        modal.style.display = 'block';
      })
      .catch(error => {
        console.error('Error fetching order details:', error);
      });
}

function showDetailsModal(id) {
  axios.get(`/orders/${id}`)
      .then(response => {
        const modalBody = document.querySelector('.modal-body');
        const parsedHTML = new DOMParser().parseFromString(response.data, 'text/html');
        const detailsDiv = parsedHTML.querySelector('.contingut');

        if (detailsDiv) {
          modalBody.innerHTML = detailsDiv.outerHTML;

          // Agregar botón "Close" al modal de detalles
        }

        const modal = document.querySelector('.modal');
        modal.classList.add('show'); // Agregar la clase 'show' al modal
        modal.style.display = 'block';
      })
      .catch(error => {
        console.error('Error fetching order details:', error);
      });
}

function showEditModal(id) {
  axios.get(`/orders/${id}/edit`)
      .then(response => {
        const modalBody = document.querySelector('.modal-body');
        const parsedHTML = new DOMParser().parseFromString(response.data, 'text/html');
        const formDiv = parsedHTML.querySelector('#form');
        formDiv.querySelector("form").action = `/orders/${id}/edit`;

        if (formDiv) {
          modalBody.innerHTML = formDiv.outerHTML;
          const modal = document.querySelector('.modal');
          modal.classList.add('show');
          modal.style.display = 'block';
        } else {
          console.error('No "form" div found in the response.');
        }
      })
      .catch(error => {
        console.error('Error fetching modal content:', error);
      });
}
function confirmDelete(id) {
  Swal.fire({
    title: '¿Estàs segur?',
    text: 'No podràs desfer aquesta acció',
    icon: 'warning',
    showCancelButton: true,
    customClass: {
      confirmButton: 'sweetConfirm',

    },
    confirmButtonText: 'Sí, esborrar',
  }).then((result) => {
    if (result.isConfirmed) {
      // Llama a la función deleteOrder con el id del pedido a eliminar
      deleteOrder(id);

      const rowId = '#row' + id;
      const tableRow = document.querySelector((rowId));
      if( tableRow){
        tableRow.remove();
      }
    }
  });
}


async function deleteOrder(id) {
  try {
    const response = await axios.post('/orders/' + id + '/delete');
    if (response.data.success) {
      // Eliminar la factura del arreglo invoices de manera reactiva solo si la eliminación en el servidor fue exitosa
      const index = orders.findIndex(inv => inv.id === id);
      if (index !== -1) {
        orders.splice(index, 1);

        // Eliminar la fila correspondiente de la tabla en la interfaz de usuario
        const tableRow = document.querySelector(`#ordersTable tr[data-order-id="${id}"]`);
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


  <div class="container-fluid">
    <div class="row">
      <div class="col-md-6  col-12 mb-3">
        <div class="d-flex  justify-md-content-around justify-content-center">
          <form method="GET" role="search">
            <div class="input-group">
              <input name="q" type="search" class="rounded-start-pill border border-secondary-subtle ps-3" placeholder="Buscar..." aria-label="Search">
              <button type="submit" class="border-0 rounded-end-pill bg-tertiary-BHEC p-2"><i class="bi bi-search"></i></button>
            </div>
          </form>
        </div>
      </div>
      <!--<div class="col-md-6 align-self-center col-12 mb-3 d-flex  justify-md-content-around justify-content-center">
        <a href="/orders/new" class="button-text-primary-BHEC btn bg-tertiary-BHEC btn-sm-small"><i class="bi bi-plus-square me-1"></i><span class="d-sm-inline">New Order</span></a>

      </div>-->

      <div class="col-12 d-flex justify-content-center ">
        <!-- Utilizamos la clase d-none para ocultar la tabla en dispositivos pequeños -->
        <table class="table table-striped  w-75 m-0 bg-tertiary-BHEC d-sm-table d-none">
          <thead class="theadOrders text-center">
          <tr>
            <th class="py-1 bg-tertiary-BHEC text-center">Customer Name</th>
            <th class="bg-tertiary-BHEC text-center">State</th>
            <th class="bg-tertiary-BHEC text-center">Actions</th>
          </tr>
          </thead>
          <tbody class="text-center">
          <tr v-for="order in filteredOrders" :id="'row'+order.id">
            <td data-title="ID:" class="d-none">{{ orders.id }}</td>
            <td data-title="Customer Name:">{{ order.customer.name }}</td>
            <td data-title="State:">{{ order.state }}</td>
            <td class="py-3">
              <button class="btn btn-success mx-1"  @click="openShowModal(order.id)"><i class="fas fa-eye"></i></button>
              <button class="btn btn-info mx-1" @click="openEditModal(order.id)"><i class="fas fa-pencil-alt"></i>
              </button>
              <button class="btn btn-danger mx-1" @click="confirmDelete(order.id)"><i
                  class="fas fa-trash"></i></button>
            </td>
          </tr>
          </tbody>
        </table>

        <!-- Utilizamos la clase d-sm-none para ocultar las cards en pantallas grandes -->
        <div class=" d-sm-none w-75">
          <div class=" mb-4" v-for="order in filteredOrders" :key="order.id">
            <div class="card ">
              <div class="card-header">
                {{ order.customer.name }}
              </div>
              <div class="card-body">
                <p class="card-text">State: {{ order.state }}</p>
                <div class="text-center">
                  <button class="btn btn-success " @click="openShowModal(order.id)"><i class="fas fa-eye"></i></button>
                  <button class="btn btn-info mx-3" @click="openEditModal(order.id)"><i class="fas fa-pencil-alt"></i></button>
                    <button class="btn btn-danger mx-1" @click="confirmDelete(order.id)"><i
                        class="fas fa-trash"></i></button>

                </div>
              </div>
            </div>
          </div>
        </div>
      </div>


    </div>
  </div>

  <div class="modal fade" data-bs-keyboard="false" style="background: rgba(0,0,0,0.5);">
    <div class="modal-dialog modal-dialog-centered  modal-dialog-scrollable"> <!-- Añadido modal-dialog-custom -->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="btn-close" @click="closeModal" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <!-- Aquí puedes mostrar los detalles de la orden -->
        </div>
        <div class="modal-footer d-flex justify-content-center">
          <button type="button" class="btn btn-secondary" @click="closeModal">Tancar</button>
        </div>

      </div>
    </div>
  </div>



</template>

