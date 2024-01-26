<template>
  <div class="d-flex align-items-center">
    <a :href="providerNewPath" class="m-2 btn btn-success">Create new</a>
    <form method="GET" class="col-12 col-lg-auto mb-3 mb-lg-0 me-lg-3 mb-lg-1" role="search">
      <input name="q" type="search" class="form-control form-control-light text-dark"
             placeholder="Buscar..." aria-label="Search">
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
    <tr v-for="provider in providers">
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
        <a @click="showModal(provider.id)" class="btn btn-outline-dark">show</a>
      </td>
      <td>
        <a :href="providerEditPath(provider.id)" class="btn btn-primary">edit</a>
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
          <button type="button" class="btn-close" @click="hideModal" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">

          <!-- Content goes here -->
          <!-- You can add form elements, text, etc. -->
          <div class="modal-confirmation"></div>
        </div>
        <div class="modal-footer">
          <form method="post" action="{{ path('app_provider_delete', {'id': provider.id}) }}" onsubmit="return confirm('Are you sure you want to delete this item?');">
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

defineProps({
  providers: Array,
  q: String
});

const providerShowPath = (id) => `/providers/${id}`;
const providerEditPath = (id) => `/providers/${id}/edit`;
const providerNewPath = `/providers/new`;


// ... (resto del código)
    // Hacer la solicitud Axios aquí
    function showModal(id) {
      axios.get('/providers/'+id)
          .then(response => {
            // Actualizar el contenido del modal
            const modalBody = document.querySelector('.modal-body');
            modalBody.innerHTML = response.data;

            // Mostrar el modal
            const myModal = document.querySelector('.modal');
            myModal.style.display = 'block';

            /**const confirmModal = document.querySelector('.modal-confirmation')


            const deleteButton = document.getElementById('#deleteProvider')
            deleteButton.addEventListener('click',confirmationModal(id))
                */
          })
          .catch(error => {
            console.error('Error fetching modal content:', error);
          });
    }
    function hideModal(){
      const myModal = document.querySelector('.modal');
      myModal.style.display = 'none';
    }

    /**
    function confirmationModal(id){
      axios.get('/providers/'+id)
          .then(response => {
            // Actualizar el contenido del modal
            const confirmModal = document.querySelector('.modal-confirmation')
            confirmModal.innerHTML = response.data;

          })
          .catch(error => {
            console.error('Error fetching modal content:', error);
          });
    }*/
</script>