<script setup>
defineProps({
  vehicles: Array
});

const vehiclesCreatePath = `/vehicles/new`;

const vehiclesShowPath = (id) => `/vehicles/${id}`;

const vehiclesEditPath = (id) => `/vehicles/${id}/edit`;

</script>

<template>
  <h1>Vehicle index</h1>

  <div class="d-flex justify-content-between align-items-center">
    <form method="get" role="search">
      <input type="search" name="q" placeholder="Search..." aria-label="Search">
      <button type="submit">Search</button>
    </form>
    <a :href="vehiclesCreatePath">
      <button>Create new</button>
    </a>
  </div>

  <table class="table">
    <thead>
    <tr>
      <th class="d-none">Id</th>
      <th>Plate</th>
      <th class="d-none">ObservedDamages</th>
      <th>Kilometers</th>
      <th>BuyPrice</th>
      <th>SellPrice</th>
      <th>Fuel</th>
      <th>Iva</th>
      <th class="d-none">Description</th>
      <th>ChassisNumber</th>
      <th>GearShit</th>
      <th>IsNew</th>
      <th>TransportIncluded</th>
      <th>Color</th>
      <th>RegistrationDate</th>
      <th>actions</th>
    </tr>
    </thead>
    <tbody>
    <tr v-for="vehicle in vehicles">
      <td class="d-none">{{ vehicle.id }}</td>
      <td>{{ vehicle.plate }}</td>
      <td class="d-none">{{ vehicle.observedDamages }}</td>
      <td>{{ vehicle.kilometers }}</td>
      <td>{{ vehicle.buyPrice }}</td>
      <td>{{ vehicle.sellPrice }}</td>
      <td>{{ vehicle.fuel }}</td>
      <td>{{ vehicle.iva }}</td>
      <td class="d-none">{{ vehicle.description }}</td>
      <td>{{ vehicle.chassisNumber }}</td>
      <td>{{ vehicle.gearShit }}</td>
      <td>{{ vehicle.isNew ? 'Yes' : 'No' }}</td>
      <td>{{ vehicle.transportIncluded ? 'Yes' : 'No' }}</td>
      <td>{{ vehicle.color }}</td>
      <td>{{ vehicle.registrationDate ? new Date(vehicle.registrationDate.date).toLocaleDateString() : '' }}</td>
      <td>
        <a :href="vehiclesShowPath(vehicle.id)">
          <button class="details-button"><i class="fas fa-eye"></i></button>
        </a>
        <a :href="vehiclesEditPath(vehicle.id)">
          <button class="edit-button"><i class="fas fa-pencil-alt"></i></button>
        </a>
      </td>
    </tr>
    </tbody>
  </table>
</template>