<template>
  <div class="container">
    <div class="row">
      <div class="col-12">
        <div class="d-flex justify-content-between align-items-center m-3 flex-column flex-sm-row">
          <form method="GET" role="search">
            <div class="d-flex my-3">
              <input name="q" type="search" class="rounded-start-pill border border-secondary-subtle ps-3"
                     placeholder="Buscar..." aria-label="Search">
              <button type="submit" class="border border-0 rounded-end-pill button-searcher-BHEC p-2"><i
                  class="bi bi-search"></i>
              </button>
            </div>
          </form>
          <div class="d-xl-flex">
          <a @click="modalNewProvider()" class="button-text-primary-BHEC btn button-primary-BHEC p-3 mb-3"><i
              class="bi bi-plus-square me-1"></i>Nou Proveïdor</a>
          </div>
        </div>
        <!--<form class="mb-3 mb-lg-0 me-lg-3 mb-lg-1">
          <input v-model="filters.email.value" type="text" class="form-control" placeholder="Email...">
        </form>
        <form class="mb-3 mb-lg-0 me-lg-3 mb-lg-1">
          <input v-model="filters.phone.value" type="text" class="form-control" placeholder="Mòbil..">
        </form>
        <form class="mb-3 mb-lg-0 me-lg-3 mb-lg-1">
          <input v-model="filters.businessName.value" type="text" class="form-control" placeholder="Nom de l'empresa...">
        </form>-->
      </div>
    </div>
  </div>
  <div class="container">
    <div class="row">
      <div class="col-12">
        <div class="table-responsive">
          <table class="table table-striped table-hover d-sm-table d-none">
            <thead>
            <tr>
              <th class="text-center">Email</th>
              <th>Telèfon</th>
              <th class="d-sm-none">Dni</th>
              <th class="d-sm-none">Cif</th>
              <th class="text-center">Nom de l'empresa</th>
              <th class="d-sm-none d-md-none">Adreça</th>
              <th class="d-sm-none d-md-none">Títol bancari</th>
              <th class="d-sm-none">Nif del jerent</th>
              <th class="d-sm-none d-md-none">document LOPD</th>
              <th class="d-sm-none d-md-none">Article de la constitució</th>
              <th colspan="3" class="text-center">Accions</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="provider in filteredProviders" :key="provider.id">
              <td class="text-center">{{ provider.email }}</td>
              <td class="text-center">{{ provider.phone }}</td>
              <td class="d-sm-none">{{ provider.dni }}</td>
              <td class="d-sm-none">{{ provider.cif }}</td>
              <td class="text-center">{{ provider.businessName }}</td>
              <td class="d-sm-none d-md-none">{{ provider.address }}</td>
              <td class="d-sm-none d-md-none">{{ provider.bankTitle }}</td>
              <td class="d-sm-none">{{ provider.managerNif }}</td>
              <td class="d-sm-none d-md-none">{{ provider.LOPDdocFile }}</td>
              <td class="d-sm-none d-md-none">{{ provider.constitutionArticle }}</td>
              <td>
                <button class="btn btn-success">
                  <a @click="modalShow(provider.id)">
                    <i class="bi bi-eye-fill"></i>
                  </a>
                </button>
              </td>
              <td>

                <button class="btn btn-primary">
                  <a @click="modalEdit(provider.id)">
                    <i class="bi bi-pencil-fill"></i>
                  </a></button>

              </td>
              <td>
                <button class="btn btn-danger" @click="sweetAlertDelete(provider.id)">
                  <i class="bi bi-trash-fill"></i>
                </button>
              </td>
            </tr>
            </tbody>
          </table>

          <div id="accordion" class="accordion accordion-flush d-flex justify-content-center d-sm-none d-flex flex-wrap text-center">
            <div v-for="provider in filteredProviders" :key="provider.id">
              <div class="card" style="width: 18rem;">
                <div class="card-header" id="heading{{ provider.id }}">
                  <h2 class="mb-0">
                    <button class="btn " type="button" data-bs-toggle="collapse"  :data-bs-target="'#collapse' + provider.id" aria-expanded="false" :aria-controls="'collapse' + provider.id">
                      Nom de l'empresa: {{ provider.businessName }}
                    </button>
                  </h2>
                </div>
                <div :id="'collapse' + provider.id" class="collapse" aria-labelledby="heading{{ provider.id }}" data-parent="#accordion">
                  <div class="card-body text-center">
                    <p data-title="Email:">Correu: {{provider.email }}</p>
                    <p data-title="Phone:">Telèfon: {{ provider.phone }}</p>
                    <button class="btn btn-success mx-1" @click="modalShow(provider.id)"><i class="bi bi-eye-fill"></i></button>
                    <button class="btn btn-primary mx-1" @click="modalEdit(provider.id)"><i class="bi bi-pencil-fill"></i></button>
                    <button class="btn btn-danger mx-1" @click="sweetAlertDelete(provider.id)"><i class="bi bi-trash-fill"></i></button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Modal -->
  <div class="modal" style="background-color: rgba(0,0,0,0.5)" ref="myModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title fw-bold fs-3">Detalls:</h5>
          <button type="button" class="btn-close" @click="hideModal" data-bs-dismiss="modal"
                  aria-label="Close"></button>
        </div>
        <div class="modal-body">
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import axios from 'axios';
import {ref, computed, watch} from 'vue';
import Swal from 'sweetalert2';
import vehicle from "primevue/menu";

const props = defineProps(['providers', 'q']);

const providerIdToDelete = ref(null);
const providerShowPath = (id) => `/providers/${id}`;
const providerEditPath = (id) => `/providers/${id}/edit`;

const providerNewPath = `/providers/new`;
const providerDeletePath = (id) => `/providers/${id}/delete`;

/* Filtratge dels camps de la taula */

const filters = ref({
  global: {value: null, matchMode: 'CONSTRAINS'},
  email: {value: null, matchMode: 'CONSTRAINS'},
  phone: {value: null, matchMode: 'CONSTRAINS'},
  businessName: {value: null, matchMode: 'CONSTRAINS'},
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

// Hacer la solicitud Axios aquí para mostrar los detalles de los proveedores
function modalShow(id) {
  axios.get('/providers/' + id)
      .then(response => {
        // Actualitzar el contingut del modal
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


function modalNewProvider() {
  axios.get('/providers/new')
      .then(response => {
        // Actualitzar el contingut del modal
        const modalBody = document.querySelector('.modal-body');
        modalBody.innerHTML = response.data;

        // Mostrar el modal
        const myModal = document.querySelector('.modal');
        myModal.style.display = 'block';

        const form = myModal.querySelector('form');
        form.action = '/providers/new';
      })
      .catch(error => {
        console.error('Error modal: ', error);
      })
}

function modalEdit(id) {
  axios.get('/providers/' + id + '/edit')
      .then(response => {
        // Actualitzar el contingut del modal
        const modalBody = document.querySelector('.modal-body');
        modalBody.innerHTML = response.data;

        // Mostrar el modal
        const myModal = document.querySelector('.modal');
        myModal.style.display = 'block';

        const form = myModal.querySelector('form');
        form.action = '/providers/' + id + '/edit';
      })
      .catch(error => {
        console.error('Error modal: ', error);
      })
}

/* Funció per a eliminar al proveïdor */
/*function deleteProvider(providerId, token) {
  axios.post(`/providers/${providerId}/delete`, {
    id: providerId,
    token: token
  })
      .then(function (response) {
        console.log('id: ' + providerId)
        console.log(token);
      })
      .catch(function (error) {
        console.error('Error deleting provider:', error);
      });
}*/

// Sweet Alert per a eliminar
function sweetAlertDelete(id) {
  Swal.fire({
    title: 'Estàs segur?',
    text: "No podras desfer la teua decissió!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#aa8e31ff',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Sí, elimina definitivament!'
  }).then((result) => {
    if (result.isConfirmed === true) {
      axios.post(`/providers/${id}/delete`)
          .then(response => {
            Swal.fire({
              title: "Eliminat!",
              text: "El proveïdor ha sigut eliminat.",
              icon: "success"
            });
          })
          .catch(error => {
            console.error(error);
            Swal.fire({
              title: "Error",
              text: "S'ha produït un error en eliminar el proveïdor.",
              icon: "error"
            });
          });
    }
  });
}

</script>