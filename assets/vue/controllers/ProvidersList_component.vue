<template>
  <div class="d-flex align-items-center">
    <a :href="providerNewPath" class="m-2 btn btn-success">Crear</a>
    <form method="GET" class="col-12 col-lg-auto mb-3 mb-lg-0 me-lg-3 mb-lg-1" role="search">
      <input name="q" type="search" class="form-control form-control-light text-dark"
             placeholder="Buscar..." aria-label="Search">
    </form>
    <form class="mb-3 mb-lg-0 me-lg-3 mb-lg-1">
      <input v-model="filters.email.value" type="text" class="form-control" placeholder="Email...">
    </form>
    <form class="mb-3 mb-lg-0 me-lg-3 mb-lg-1">
      <input v-model="filters.phone.value" type="text" class="form-control" placeholder="Mòbil..">
    </form>
    <form class="mb-3 mb-lg-0 me-lg-3 mb-lg-1">
      <input v-model="filters.businessName.value" type="text" class="form-control" placeholder="Nom de l'empresa...">
    </form>
  </div>
  <table class="table">
    <thead>
    <tr>
      <th>Email</th>
      <th>Telèfon</th>
      <th class="d-sm-none">Dni</th>
      <th class="d-sm-none">Cif</th>
      <th>Nom de l'empresa</th>
      <th class="d-sm-none d-md-none">Adreça</th>
      <th class="d-sm-none d-md-none">Títol bancari</th>
      <th class="d-sm-none">Nif del jerent</th>
      <th class="d-sm-none d-md-none">document LOPD</th>
      <th class="d-sm-none d-md-none">Article de la constitució</th>
      <th colspan="2">Accions</th>
    </tr>
    </thead>
    <tbody>
    <tr v-for="provider in filteredProviders" :key="provider.id">
      <td>{{ provider.email }}</td>
      <td>{{ provider.phone }}</td>
      <td class="d-sm-none">{{ provider.dni }}</td>
      <td class="d-sm-none">{{ provider.cif }}</td>
      <td>{{ provider.businessName }}</td>
      <td class="d-sm-none d-md-none">{{ provider.address }}</td>
      <td class="d-sm-none d-md-none">{{ provider.bankTitle }}</td>
      <td class="d-sm-none">{{ provider.managerNif }}</td>
      <td class="d-sm-none d-md-none">{{ provider.LOPDdocFile }}</td>
      <td class="d-sm-none d-md-none">{{ provider.constitutionArticle }}</td>
      <td>
        <a @click="showModal(provider.id)" class="btn btn-outline-dark">Mostrar</a>
      </td>
      <td>
        <a :href="providerEditPath(provider.id)" class="btn btn-primary">Editar</a>
      </td>
    </tr>
    </tbody>
  </table>
  <!-- Modal -->
  <div class="modal" style="background-color: rgba(0,0,0,0.5)" ref="myModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Provider</h5>
          <button type="button" class="btn-close" @click="hideModal" data-bs-dismiss="modal"
                  aria-label="Close"></button>
        </div>
        <div class="modal-body">

          <!-- Content goes here -->
          <!-- You can add form elements, text, etc. -->
          <div class="modal-confirmation"></div>
        </div>
        <div class="modal-footer">
          <!-- Fer que borre -->
          <form method="post" action="{{ path('app_provider_delete', {'id': provider.id}) }}"
                onsubmit="return confirm('Are you sure you want to delete this item?');">
            <input type="hidden" name="_token" value="{{ csrf_token('delete', provider.id) }}">
            <button class="btn btn-danger" id="deleteProvider">Delete</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import axios from 'axios';
import {ref, computed} from 'vue';

const props = defineProps(['providers', 'q']);

const providerShowPath = (id) => `/providers/${id}`;
const providerEditPath = (id) => `/providers/${id}/edit`;
const providerNewPath = `/providers/new`;

const filters = ref( {
  global: { value: null, matchMode: 'CONSTRAINS' },
  email: { value: null, matchMode: 'CONSTRAINS' },
  phone: { value: null, matchMode: 'CONSTRAINS' },
  businessName: { value: null, matchMode: 'CONSTRAINS' },
});

const filteredProviders = computed(() => {
  return applyFilters(props.providers, filters.value);
});

const applyFilters = (data, filters) => {
  return data.filter((provider) => {
    return (
        (!filters.global.value || JSON.stringify(provider).toLowerCase().includes(filters.global.value.toLowerCase())) &&
        (!filters.email.value || provider.email.toLowerCase().includes(filters.email.value.toLowerCase())) &&
        (!filters.phone.value || provider.phone.toLowerCase().includes(filters.phone.value.toLowerCase())) &&
        (!filters.businessName.value || provider.businessName.toLowerCase().includes(filters.businessName.value.toLowerCase()))
    );
  });
};


// Hacer la solicitud Axios aquí
function showModal(id) {
  axios.get('/providers/' + id)
      .then(response => {
        // Actualizar el contenido del modal
        const modalBody = document.querySelector('.modal-body');
        modalBody.innerHTML = response.data;

        // Mostrar el modal
        const myModal = document.querySelector('.modal');
        myModal.style.display = 'block';

      })
      .catch(error => {
        console.error('Error fetching modal content:', error);
      });
}

function hideModal() {
  const myModal = document.querySelector('.modal');
  myModal.style.display = 'none';
}
</script>