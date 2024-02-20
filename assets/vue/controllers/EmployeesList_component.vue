<template>
  <div class="d-flex align-items-center">
    <a @click="modalNewEmployee()" class="m-2 btn btn-success">Create new</a>
    <form method="GET" class="col-12 col-lg-auto mb-3 mb-lg-0 me-lg-3 mb-lg-1" role="search">
      <input name="q" type="search" class="form-control form-control-light text-dark"
             placeholder="Buscar..." aria-label="Search">
    </form>
  </div>
  <table class="table" id="employeesTable">
    <thead>
    <tr>
      <th class="d-none">Id</th>
      <th>Nom</th>
      <th>Cognom</th>
      <th>Tipus</th>
      <th>Accions</th>
    </tr>
    </thead>
    <tbody>
    <tr v-for="employee in employees">
      <td class="d-none">{{employee.id}}</td>
      <td>{{ employee.name }}</td>
      <td>{{ employee.lastname }}</td>
      <td>{{ employee.type }}</td>
      <td>
        <a @click="showModal(employee.id)" class="btn btn-outline-dark">Mostrar</a>
        <a :href="employeeEditPath(employee.id)" class="btn btn-primary">Editar</a>
        <a class="btn btn-danger delete">Esborrar</a>
      </td>
    </tr>
    </tbody>
  </table>
  <!-- Modal -->
  <div class="modal" style="background-color: rgba(0,0,0,0.5)" ref="myModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Empleat</h5>
          <button type="button" class="btn-close" @click="hideModal" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">

          <!-- Content goes here -->
          <!-- You can add form elements, text, etc. -->
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
let table = new DataTable('#employeesTable', {

});

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
$(document).ready( function () {
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
} );

// ... (resto del código)
// Hacer la solicitud Axios aquí
function showModal(id) {
  axios.get('/employees/'+id+'/details')
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

function modalNewEmployee(){
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


function softDeleteEmployee(employeeId){
  axios.post('/employees/'+employeeId+'/delete',{
    employeeId: employeeId
  })
.then(function (response) {
    console.log(response)
  })
  .catch(function (error) {
    console.log('Error: '+error);
    console.log('Id: '+employeeId);
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