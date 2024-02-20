<script setup>
const {vehicles} = defineProps(['vehicles']);

import {ref, onMounted, computed} from 'vue';
import axios from "axios";
import Swal from 'sweetalert2';
import $ from "jquery";


//Tipus de filtració
const filters = ref({
  global: {value: null, matchMode: 'CONTAINS'},
  kilometers: {value: null, matchMode: 'CONTAINS'},
  ChassisNumber: {value: null, matchMode: 'IN'},
  date: {value: null, matchMode: 'EQUALS'},
});

//Filtrador
const applyFilters = (data, filters) => {
  return data.filter((vehicle) => {
    return (
        (!filters.global.value || JSON.stringify(vehicle).toLowerCase().includes(filters.global.value.toLowerCase())) &&
        (!filters.kilometers.value || vehicle.kilometers.toLowerCase().includes(filters.kilometers.value.toLowerCase())) &&
        (!filters.ChassisNumber.value || filters.ChassisNumber.value.includes(vehicle.ChassisNumber.toString())) &&
        (!filters.date.value || vehicle.date.date.substring(0, 10) === filters.date.value)
    );
  });
};

//Constants
const filteredVehicles = computed(() => {
  return applyFilters(vehicles, filters.value);
});

const vehiclesCreatePath = `/vehicles/new`;

const vehiclesShowPath = (id) => `/vehicles/${id}`;

const vehiclesEditPath = (id) => `/vehicles/${id}/edit`;

const vehiclesAddImagePath = (id) => `/vehicles/${id}/images/add`;

/*$(document).ready( function () {

  //----------------------------------------------------------------------------
  $('.delete').on('click', function () {
    let divContainer = $(this).closest('.col-12'); // Encuentra el contenedor más cercano con la clase '.col-12'
    let vehicleId = divContainer.attr('data-vehicle-id'); // Obtiene el ID del vehículo del atributo de datos 'data-vehicle-id' en el contenedor
    divContainer.remove();

    //No implementar anoser que es tinga implementat la funcionalitat de soft delete ...
    // -> (el camp en la base de dades i el mètodo delete del controlador configurat)
    sweetAlertDelete(vehicleId);
  })
  //-----------------------------------------------------------------------------
} );*/


function sweetAlertDelete(vehicleId) {
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
      axios.post('/vehicles/' + vehicleId + '/delete')
          .then(response => {
            const elementsToDelete = document.querySelectorAll('.vehicle');
            elementsToDelete.forEach(element => {
              if (element.dataset.vehicleId === vehicleId) {
                element.remove();
              }
            });
            Swal.fire({
              title: "Eliminat!",
              text: "El vehicle ha sigut eliminat.",
              icon: "success"
            });
          })
          .catch(error => {
            console.error(error);
            Swal.fire({
              title: "Error",
              text: "S'ha produït un error en eliminar el vehicle.",
              icon: "error"
            });
          });

    }
  });
}

</script>

<template>

  <div class="d-flex justify-content-between align-items-center mb-2 px-3">
    <form method="get" role="search" class="d-flex">
      <input type="search" class="form-control" name="q" placeholder="Matrícula, combustible, color..."
             aria-label="Search">
      <button type="submit" class="btn btn-outline-dark">Search</button>
    </form>
    <input type="search" class="border border-0 rounded p-1" id="global-filter" v-model="filters.global.value"
           @input="applyFilters" placeholder="Kilòmetres, data..."/>
    <a :href="vehiclesCreatePath">
      <button class="btn btn-warning">Crea nou</button>
    </a>
  </div>

  <div class="col-12 p-3 d-flex justify-content-center">

    <div class="container-fluid">
      <div class="row">
        <div class="col-12 col-md-6 col-lg-4 p-3" v-for="vehicle in vehicles">
          <div>
            <img :src="'/equip3/img/vehicles/' + vehicle.images[0].filename" :alt="'Imatge Vehicle' + vehicle.images[0].filename" width="100%"
            class="rounded-top-3 object-fit-container">
            <div class="bg-white mt-1">
              <div class="d-flex align-items-center justify-content-end">
                <a :href="vehiclesShowPath(vehicle.id)">
                  <button class="border-0 bg-transparent p-1">
                    <i class="bi bi-eye fnt-tertiary-BHEC"></i>
                  </button>
                </a>
                <a :href="vehiclesEditPath(vehicle.id)">
                  <button class="border-0 bg-transparent p-1">
                    <i class="bi bi-pencil-square fnt-tertiary-BHEC"></i>
                  </button>
                </a>
                <a @click="sweetAlertDelete(vehicle.id)"  class="delete">
                  <button class="border-0 bg-transparent p-1 fnt-tertiary-BHEC">
                    <i class="bi bi-trash"></i>
                  </button>
                </a>

                <a :href="vehiclesAddImagePath(vehicle.id)">
                  <button class="border-0 bg-transparent p-1 fnt-tertiary-BHEC">
                    <i class="bi bi-image"></i>
                  </button>
                </a>

              </div>
              <p class="text-center fw-bold pt-1 m-0">{{ vehicle.model.brand.name }}</p>
              <div class="d-flex justify-content-between">
                <p class="text-center ps-4">{{ vehicle.model.name }}</p>
                <p class="text-center pe-4">{{ vehicle.fuel }}</p>
              </div>
              <div class="d-flex justify-content-between px-4">
                <p>{{ vehicle.plate }}</p>
                <p>{{
                    vehicle.registrationDate ? new Date(vehicle.registrationDate.date).toLocaleDateString() : ''
                  }}</p>
                <p>{{ vehicle.kilometers }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
