<script setup>
const Routes = {
  factures: "/invoices",
  clients: "/customers",
  empleats: "/employees",
  proveidors: "/providers",
  comandes: "/orders",
  vehicles: "/vehicles"
};
defineProps({
  Routes: Object,
});
</script>

<template>
  <nav>
    <ul class="m-0 list-unstyled fs-5 d-flex flex-column text-sm-start text-center">
      <div class="pe-3 py-4 bg-aside-BHEC ps-sm-5">
        <li class="fas fa-file-invoice">
          <a :href="Routes.factures" class="text-decoration-none text-white font"> Factures</a>
        </li>
      </div>
      <div class="pe-3 py-4 bg-aside-BHEC ps-sm-5">
        <li class="fas fa-users">
          <a :href="Routes.clients" class="text-decoration-none text-white font"> Clients</a>
        </li>
      </div>
      <div class="pe-3 py-4 bg-aside-BHEC ps-sm-5">
        <li class="fas fa-user-tie">
          <a :href="Routes.empleats" class="text-decoration-none text-white font"> Empleats</a>
        </li>
      </div>
      <div class="pe-3 py-4 bg-aside-BHEC ps-sm-5">
        <li class="fas fa-truck ">
          <a :href="Routes.proveidors" class="text-decoration-none text-white font"> Proveidors</a>
        </li>
      </div>
      <div class="pe-3 py-4 bg-aside-BHEC ps-sm-5">
        <li class="fas fa-shopping-cart">
          <a :href="Routes.comandes" class="text-decoration-none text-white font"> Comandes</a>
        </li>
      </div>
      <div class="pe-3 py-4 bg-aside-BHEC ps-sm-5">
        <li class="fas fa-car">
          <a :href="Routes.vehicles" class="text-decoration-none text-white font"> Vehicles</a>
        </li>
      </div>
    </ul>
  </nav>
</template>
