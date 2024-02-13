<script setup>
const Routes = {
  factures: "/",
  clients: "/",
  empleats: "",
  proveidors: "/",
  comandes: "/",
  vehicles: "/"
};
defineProps({
  Routes: Object,
});
</script>

<template>
    <nav>
      <ul class="list-unstyled fs-5 d-flex flex-column text-sm-start text-center">
        <div class="pe-3 py-4 bg-aside-BHEC ps-sm-5">
          <li class="fas fa-file-invoice">
            <a href="#" class="text-decoration-none text-white font"> Factures</a>
          </li>
        </div>
        <div class="pe-3 py-4 bg-aside-BHEC ps-sm-5">
          <li class="fas fa-users">
            <a href="#" class="text-decoration-none text-white font"> Clients</a>
          </li>
        </div>
        <div class="pe-3 py-4 bg-aside-BHEC ps-sm-5">
          <li class="fas fa-user-tie">
            <a href="#" class="text-decoration-none text-white font"> Empleats</a>
          </li>
        </div>
        <div class="pe-3 py-4 bg-aside-BHEC ps-sm-5">
          <li class="fas fa-truck ">
            <a href="#" class="text-decoration-none text-white font"> Proveidors</a>
          </li>
        </div>
        <li class="fas fa-shopping-cart pe-3 py-4 bg-aside-BHEC ps-sm-5">
          <a href="#" class="text-decoration-none text-white font"> Comandes</a>
        </li>
        <div class="pe-3 py-4 bg-aside-BHEC ps-sm-5">
          <li class="fas fa-car">
            <a href="#" class="text-decoration-none text-white font"> Vehicles</a>
          </li>
        </div>
      </ul>
    </nav>
</template>
