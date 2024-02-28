<template>
  <div>
      <div class="d-flex justify-content-between align-items-center m-3 flex-column flex-sm-row">
        <form method="POST" role="search">
          <div class="d-flex my-3 justify-content-right mw-75"><input name="q" type="search"
                                                                      class="rounded-start-pill border border-secondary-subtle px-4 py-2 "
                                                                      placeholder="Buscar..." aria-label="Search">

            <button id="searcher" class="btn bg-tertiary-BHEC rounded-end-5 px-4" aria-label="Buscar"><i
                class="bi bi-search"></i></button>
            <span id="searcher-label" class="sr-only">Buscar</span>
          </div>
        </form>
      <a @click="modalNewEmployee()" class="text-white btn bg-secondary-BHEC p-3 mb-3" href="#"><i
          class="bi bi-plus-square me-1"></i>Nou Employee</a>
    </div>
    <div>
      <table class="table table-striped table-hover table-responsive w-100 m-0 d-sm-table d-none" id="employeesTable">
        <thead>
        <tr>
          <th class="d-none text-center">Id</th>
          <th class="bg-tertiary-BHEC text-center">Nom</th>
          <th class="bg-tertiary-BHEC text-center">Cognom</th>
          <th class="bg-tertiary-BHEC text-center">Tipus</th>
          <th colspan="3" class="bg-tertiary-BHEC text-center">Accions</th>
        </tr>
        </thead>
        <tbody class="text-center">
        <tr v-for="employee in employees">
          <td class="d-none">{{ employee.id }}</td>
          <td>{{ employee.name }}</td>
          <td>{{ employee.lastname }}</td>
          <td>{{ employee.type }}</td>
          <td>
            <a @click="showModal(employee.id)" class="btn btn-success" href="#"><i class="bi bi-eye-fill"></i></a>
          </td>
          <td>
            <a :href="employeeEditPath(employee.id)" class="btn btn-primary" aria-label="Editar empleat"><i
                class="bi bi-pencil-fill"></i></a>
          </td>
          <td>
            <a class="btn btn-danger delete" href="#"><i class="bi bi-trash-fill"></i></a>
          </td>

        </tr>
        </tbody>
      </table>
    </div>
    <div id="accordion"
         class="accordion accordion-flush d-flex justify-content-center d-sm-none d-flex flex-wrap text-center">
      <div v-for="employee in employees" :key="employee.id">
        <div class="card" style="width: 18rem;">
          <div class="card-header" :id="'heading' + employee.id">
            <h2 class="mb-0">
              <button class="btn" type="button" data-bs-toggle="collapse" :data-bs-target="'#collapse' + employee.id"
                      aria-expanded="false" :aria-controls="'collapse' + employee.id">
                Empleat: {{ employee.name }}
              </button>
            </h2>
          </div>
          <div :id="'collapse' + employee.id" class="collapse" :aria-labelledby="'heading' + employee.id"
               data-parent="#accordion">
            <div class="card-body text-center">
              <p data-title="Usuario:">Nom: {{ employee.name }}</p>
              <p data-title="Precio:">Cognom: {{ employee.lastname }}</p>
              <p data-title="Fecha:">Tipus: {{ employee.type }}</p>
              <button class="btn btn-success mx-1" @click="showModal(employee.id)"><i class="fas fa-eye"></i></button>
              <button class="btn btn-primary mx-1" :href="employeeEditPath(employee.id)"><i
                  class="fas fa-pencil-alt"></i>
              </button>
              <button class="btn btn-danger mx-1 delete"><i class="fas fa-trash"></i>
              </button>
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
            <h5 class="modal-title">Empleat</h5>
            <button type="button" class="btn-close" @click="hideModal" data-bs-dismiss="modal"
                    aria-label="Close"></button>
          </div>
          <div class="modal-body">

            <!-- Content goes here -->
            <!-- You can add form elements, text, etc. -->
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import axios from 'axios';
import DataTable from 'datatables.net-dt';
import $ from "jquery"; //Importació de jquery NECESSARIA per poder fer funcionar l'eliminació
/**
 * Per poder fer funcionar el codi per eliminar la fila del registre en temps real has de:
 *
 * posar la classe 'delete' al botó d'eliminar;
 * Anyadir el camp id en la taula amb un display none '<th class="d-none">Id</th>, <td class="d-none">employee.id</td>'
 *
 */

//Datatables
/*let table = new DataTable('#employeesTable', {
    searching: true, // Activar la funcionalidad de búsqueda
    paging: false, // Desactivar paginación
    info: false, // Desactivar información de la tabla
    autoWidth: false, // Desactivar auto-ancho de columnas
});*/

defineProps({
  employees: Array,
  e: String
});

const employeeShowPath = (id) => `/employees/${id}/details`;
const employeeEditPath = (id) => `/employees/${id}/edit`;
const employeeNewPath = `/employees/new`;
const employeeDeletePath = (id) => `/employees/${id}`;

/**
 * Event que s'executa quan es carrega el document
 *
 *
 */
$(document).ready(function () {
  $('#employeesTable').DataTable(); // NO IMPLEMENTAR MENYS QUE VULGES IMPLEMENTAR DataTables

  //----------------------------------------------------------------------------
  $('.delete').on('click', function () {
    let tr = this.closest('tr'); // Troba el 'Tr' de la taula més proper al botó prés(pulsado)
    let employeeId = $(tr).find('td:eq(0)').text(); // Obté la id del registre a eliminar
    tr.remove(); // Elimina directament la fila de la taula

    //No implementar anoser que es tinga implementat la funcionalitat de soft delete ...
    // -> (el camp en la base de dades i el mètodo delete del controlador configurat)
    softDeleteEmployee(employeeId);
  })
  //-----------------------------------------------------------------------------
});

// ... (resto del código)
// Hacer la solicitud Axios aquí
function showModal(id) {
  axios.get('/employees/' + id + '/details')
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

function hideModal() {
  const myModal = document.querySelector('.modal');
  myModal.style.display = 'none';
}

function modalNewEmployee() {
  axios.get('/employees/new')
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


function softDeleteEmployee(employeeId) {
  axios.post('/employees/' + employeeId + '/delete', {
    employeeId: employeeId
  })
      .then(function (response) {
        console.log(response)
      })
      .catch(function (error) {
        console.log('Error: ' + error);
        console.log('Id: ' + employeeId);
      });
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