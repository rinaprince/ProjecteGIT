<script setup>
const {vehicles} = defineProps(['vehicles']);

import {ref, onMounted, computed} from 'vue';
import axios from "axios";
import Swal from 'sweetalert2';


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
      axios.post(`/vehicles/${id}/delete`)
          .then(response => {
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

  <div class="row d-flex justify-content-between align-items-center mb-2 px-3">
    <form method="get" role="search" class="d-flex col-12 col-md-4 p-2 justify-content-center mb-3">
      <div class="input-group">
        <input type="search" class="form-control" placeholder="Matrícula, combustible, color..." name="q" aria-label="Matrícula, combustible, color..." aria-describedby="basic-addon1">
        <span class="input-group-text" id="basic-addon1"><button type="submit" class="btn btn-sm"><i class="bi bi-search"></i></button></span>
      </div>
    </form>
    <div class="mb-3 col-12 col-md-4 d-flex justify-content-center">
      <input type="search" class="border border-0 rounded p-2 w-100" id="global-filter" v-model="filters.global.value"
             @input="applyFilters" placeholder="Kilòmetres, data..."/>
    </div>

    <a :href="vehiclesCreatePath" class="col-12 col-md-4 p-2 mb-3 d-flex justify-content-center justify-content-md-end text-decoration-none">
      <button class="btn btn-warning">Crea nou</button>
    </a>
  </div>

  <div class="col-12 p-3 d-flex justify-content-center">

    <div class="container-fluid">
      <div class="row">
        <div class="col-12 col-md-6 col-lg-4 p-3" v-for="vehicle in vehicles">
          <div class="card h-100">
            <img :src="'/equip3/img/vehicles/' + vehicle.images[0].filename" :alt="'Imatge Vehicle' + vehicle.images[0].filename" class="card-img-top" :style="{ width: '100%', height: 'auto' }">
            <div class="card-body">
              <div class="d-flex justify-content-end">
                <a :href="vehiclesShowPath(vehicle.id)" class="btn btn-sm btn-link">
                  <i class="bi bi-eye"></i>
                </a>
                <a :href="vehiclesEditPath(vehicle.id)" class="btn btn-sm btn-link">
                  <i class="bi bi-pencil-square"></i>
                </a>
                <button @click="sweetAlertDelete(vehicle.id)" class="btn btn-sm btn-link">
                  <i class="bi bi-trash"></i>
                </button>
                <a :href="vehiclesAddImagePath(vehicle.id)" class="btn btn-sm btn-link">
                  <i class="bi bi-image"></i>
                </a>
              </div>
              <h5 class="card-title text-center fw-bold mt-2">{{ vehicle.model.brand.name }}</h5>
              <div class="d-flex justify-content-between">
                <p class="card-text text-center">{{ vehicle.model.name }}</p>
                <p class="card-text text-center">{{ vehicle.fuel }}</p>
              </div>
              <div class="d-flex justify-content-between">
                <p class="card-text">{{ vehicle.plate }}</p>
                <p class="card-text">{{ vehicle.registrationDate ? new Date(vehicle.registrationDate.date).toLocaleDateString() : '' }}</p>
                <p class="card-text">{{ vehicle.kilometers }}</p>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
</template>
