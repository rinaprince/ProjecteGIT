<template>
  <div>
    <div id="div-table">
      <table class="table mt-3 contingut">
        <thead>
        <tr>
          <th class="d-none">id</th>
          <th v-for="(label, key) in config['fields']">{{ label }}</th>
          <th>Operations</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="element in elements" :key="element.id">
          <td class="d-none">{{ element.id }}</td>
          <td v-for="(label, key) in config['fields']" :data-title="label">{{ element[key] }}</td>
          <td>
            <template v-if="element['customer_type'] === 'private'">
              <a :href="showRoute('private', element.id)" class="btn btn-success mx-1"><i class="bi bi-eye"></i></a>
              <a :href="editRoute('private', element.id)" class="btn btn-primary mx-1"><i class="bi bi-pencil-square"></i></a>
              <a class="btn btn-danger mx-1 private" @click="softDelete('private', element.id)"><i class="bi bi-trash"></i></a>
            </template>
            <template v-else-if="element['customer_type'] === 'professional'">
              <a :href="showRoute('professional', element.id)" class="btn btn-success mx-1"><i class="bi bi-eye"></i></a>
              <a :href="editRoute('professional', element.id)" class="btn btn-primary mx-1"><i class="bi bi-pencil-square"></i></a>
              <a class="btn btn-danger mx-1 professional" @click="softDelete('professional', element.id)"><i class="bi bi-trash"></i></a>
            </template>
            <template v-else>
              <a :href="showRoute(type, element.id)" class="btn btn-success mx-1"><i class="bi bi-eye"></i></a>
              <a :href="editRoute(type, element.id)" class="btn btn-primary mx-1"><i class="bi bi-pencil-square"></i></a>
              <a class="btn btn-danger mx-1 delete" @click="softDelete(type, element.id)"><i class="bi bi-trash"></i></a>
            </template>
          </td>
        </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
import { defineProps } from 'vue';
import $ from "jquery";
import axios from 'axios';


defineProps({
  elements: Array,
  config: Object,
  type: String,
});


const showRoute = (route, id) => {
  let path;
  switch (route) {
    case "private":
      path = "/customers/" + route + "/" + id;
      break;
    case "professional":
      path = "/customers/" + route + "/" + id;
      break;
    default:
      path = "/" + route + "/" + id;
      break;
  }
  return path;
};


const editRoute = (route, id) => {
  let path;
  switch (route) {
    case "private":
      path = "/customers/" + route + "/" + id  + "/edit";
      break;
    case "professional":
      path = "/customers/" + route + "/" + id + "/edit";
      break;
    default:
      path = "/" + route + "/" + id + "/edit"
      break;
  }
  return path;
};




$(document).ready( function () {
  //----------------------------------------------------------------------------
  $('.delete').on('click', function () {
    let tr = this.closest('tr'); // Troba el 'Tr' de la taula més proper al botó prés(pulsado)
    let id = $(tr).find('td:eq(0)').text(); // Obté la id del registre a eliminar
    tr.remove(); // Elimina directament la fila de la taula

    //No implementar anoser que es tinga implementat la funcionalitat de soft delete ...
    // -> (el camp en la base de dades i el mètodo delete del controlador configurat)

  })

  $('.professional').on('click', function () {
    let tr = this.closest('tr'); // Troba el 'Tr' de la taula més proper al botó prés(pulsado)
    let id = $(tr).find('td:eq(0)').text(); // Obté la id del registre a eliminar
    tr.remove(); // Elimina directament la fila de la taula

    //No implementar anoser que es tinga implementat la funcionalitat de soft delete ...
    // -> (el camp en la base de dades i el mètodo delete del controlador configurat)

  })

  $('.private').on('click', function () {
    let tr = this.closest('tr'); // Troba el 'Tr' de la taula més proper al botó prés(pulsado)
    let id = $(tr).find('td:eq(0)').text(); // Obté la id del registre a eliminar
    tr.remove(); // Elimina directament la fila de la taula

    //No implementar anoser que es tinga implementat la funcionalitat de soft delete ...
    // -> (el camp en la base de dades i el mètodo delete del controlador configurat)

  })
  //-----------------------------------------------------------------------------
} );



function softDelete(route, id){
  let path
  switch (route) {
    case "private":
      path = "/customers/" + route + "/" + id  + "/delete";
      break;
    case "professional":
      path = "/customers/" + route + "/" + id + "/delete";
      break;
    default:
      path = "/" + route + "/" + id + "/delete"
      break;
  }

  axios.post(path,{
    id: id
  })
      .then(function (response) {
        console.log(response)
      })
      .catch(function (error) {
        console.log('Error: '+ error);
        console.log('Id: '+ id);
      });
}


</script>
