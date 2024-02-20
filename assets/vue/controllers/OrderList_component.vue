<script setup>
const { orders } = defineProps(['orders']);
import {computed, ref} from 'vue';
import axios from 'axios';

const orderShowPath = (id) => `/orders/${id}`;
const orderCreatePath = '/orders/new';
const orderEditPath = (id) => `/orders/${id}/edit`;
const orderDeletePath = (id) => `/orders/${id}/delete`;

const filters = ref({
  global: { value: null, matchMode: 'CONTAINS' },
  customer: { value: null, matchMode: 'STARTS_WITH' },
  state: { value: null, matchMode: 'EQUALS' },
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
  window.location.reload();
};

function openNewModal() {
  axios.get(`/orders/new`)
      .then(response => {
        const modalBody = document.querySelector('.modal-content');
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
        const modalBody = document.querySelector('.modal-content');
        const parsedHTML = new DOMParser().parseFromString(response.data, 'text/html');
        const detailsDiv = parsedHTML.querySelector('.contingut');

        if (detailsDiv) {
          modalBody.innerHTML = detailsDiv.outerHTML;

          // Agregar botón "Close" al modal de detalles
          const closeButton = document.createElement('button');
          closeButton.textContent = 'Close';
          closeButton.addEventListener('click', closeModal); // Cierra el modal al hacer clic en el botón
          modalBody.appendChild(closeButton);
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
        const modalBody = document.querySelector('.modal-content');
        const parsedHTML = new DOMParser().parseFromString(response.data, 'text/html');
        const formDiv = parsedHTML.querySelector('#form');
        formDiv.querySelector("form").action = `/orders/${id}/edit`;

        if (formDiv) {
          modalBody.innerHTML = formDiv.outerHTML;
          const modal = document.querySelector('.modal');
          modal.classList.add('show'); // Agregar la clase 'show' al modal
          modal.style.display = 'block';
          const closeButton = document.createElement('button');
          closeButton.textContent = 'Close';
          closeButton.addEventListener('click', closeModal); // Cierra el modal al hacer clic en el botón
          modalBody.appendChild(closeButton);
        } else {
          console.error('No "form" div found in the response.');
        }
      })
      .catch(error => {
        console.error('Error fetching modal content:', error);
      });
}
function confirmationDeleteModal(id) {
  const modal = document.querySelector('.modal');
  if (!modal) {
    console.error('Modal not found.');
    return;
  }

  axios.get(`/orders/${id}`)
      .then(response => {
        const parsedHTML = new DOMParser().parseFromString(response.data, 'text/html');
        const detailsDiv = parsedHTML.querySelector('.contingut');

        if (detailsDiv) {
          const modalBody = modal.querySelector('.modal-content');
          modalBody.innerHTML = detailsDiv.outerHTML;

          // Cambiar el título del modal y el texto del botón de eliminación
          const modalTitle = modal.querySelector('.modal-title');
          const deleteButton = modal.querySelector('.btn-danger');
          modalTitle.textContent = 'Confirm Deletion';
          deleteButton.textContent = 'Delete';

          // Agregar evento de clic al botón de eliminación
          deleteButton.addEventListener('click', () => deleteOrder(id));

          // Agregar botón "Close" al modal de detalles
          const closeButton = document.createElement('button');
          closeButton.textContent = 'Close';
          closeButton.addEventListener('click', closeModal); // Cierra el modal al hacer clic en el botón
          modalBody.appendChild(closeButton);
        } else {
          console.error('No "contingut" div found in the response.');
        }
      })
      .catch(error => {
        console.error('Error fetching order details:', error);
      });
}





</script>

<template>
    <div class="my-3  " >
        <a href="/orders/new" class="button-text-primary-BHEC btn bg-tertiary-BHEC "><i
            class="bi bi-plus-square me-1"></i>
          <p class="d-sm-inline ">New Order</p></a>
    </div>

  <div class="col-10 text-sm-center">
    <!-- Utilizamos la clase d-none para ocultar la tabla en dispositivos pequeños -->
    <table class="table table-striped w-100 m-0 bg-tertiary-BHEC d-sm-table d-none">
      <thead class="theadOrders text-center">
      <tr>
        <th class="py-1 bg-tertiary-BHEC text-center">Customer Name</th>
        <th class="bg-tertiary-BHEC text-center">State</th>
        <th class="bg-tertiary-BHEC text-center">Actions</th>
      </tr>
      </thead>
      <tbody class="text-center">
      <tr v-for="order in filteredOrders" :key="order.id">
        <td data-title="Customer Name:">{{ order.customer.name }}</td>
        <td data-title="State:">{{ order.state }}</td>
        <td class="py-3">
          <button class="btn btn-success mx-1" @click="openShowModal(order.id)"><i class="fas fa-eye"></i> View</button>
          <button class="btn btn-info mx-1" @click="openEditModal(order.id)"><i class="fas fa-pencil-alt"></i> Edit
          </button>
            <button class="btn btn-danger mx-1" @click="confirmationDeleteModal(order.id)"><i class="fas fa-trash"></i> Delete</button>
        </td>
      </tr>
      </tbody>
    </table>

    <!-- Utilizamos la clase d-sm-none para ocultar las cards en pantallas grandes -->
    <div class="row d-sm-none">
      <div class="col-md-6 mb-4" v-for="order in filteredOrders" :key="order.id">
        <div class="card w-100">
          <div class="card-header">
            {{ order.customer.name }}
          </div>
          <div class="card-body">
            <p class="card-text">State: {{ order.state }}</p>
            <div class="text-center">
            <button class="btn btn-success " @click="openShowModal(order.id)"><i class="fas fa-eye"></i> </button>
            <button class="btn btn-info mx-3" @click="openEditModal(order.id)"><i class="fas fa-pencil-alt"></i> </button>
            <a :href="orderDeletePath(order.id)">
              <button class="btn btn-danger "><i class="fas fa-trash"></i></button>
            </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="modal"  v-if="selectedOrder !== null" tabindex="-1" aria-labelledby="exampleModalLabel"
       aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Order Details</h5>
          <button type="button" class="btn-close" @click="closeModal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <!-- Aquí puedes mostrar los detalles de la orden -->
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" @click="closeModal">Close</button>
        </div>
      </div>
    </div>
  </div>


</template>

