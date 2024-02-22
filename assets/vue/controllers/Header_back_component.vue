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
    <nav class="navbar navbar-expand-sm py-0">
      <div class="d-flex justify-content-between w-100 py-0 align-items-center">
        <div class>
          <a class="navbar-brand px-3 d-inline-block" href="/backoffice"><img src="/equip2/img/logoBHEC.png"
                                                                    alt="logo BHEC"></a>
        </div>
        <div class>
          <a class="btn d-inline-block px-2" data-bs-toggle="offcanvas" href="#offcanvasExample" role="button"
             aria-controls="offcanvasExample">
            <i class="fs-3 fs-md-2 bi bi-person-square text-secondary-BHEC"></i>
          </a>
        </div>
        <!-- menú hamburguesa -->
        <button class="navbar-toggler bg-secondary-BHEC border border-0 mx-2" type="button"
                data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Desplegar navegació">
          <span class="navbar-toggler-icon"></span>
        </button>
      </div>
      <!-- desplegable menú hamburguesa -->
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-lg-0 d-block d-sm-none bg-light p-0 m-0 fs-5 dropdown-menu w-100">
          <li>
            <a class="dropdown-item bg-tertiary-BHEC text-white" aria-current="page" :href="Routes.factures">
              <i class="fas fa-file-invoice px-2 py-2 w-14 text-center"></i><span>Factures</span></a>
          </li>
          <li>
            <a class="dropdown-item bg-tertiary-BHEC text-white" aria-current="page" :href="Routes.clients">
              <i class="fas fa-users px-2 py-2 w-14 text-center"></i><span>Clients</span>
            </a>
          </li>
          <li>
            <a class="dropdown-item bg-tertiary-BHEC text-white" :href="Routes.empleats">
              <i class="fas fa-user-tie px-2 py-2 w-14 text-center"></i><span>Empleats</span></a>
          </li>
          <li>
            <a class="dropdown-item bg-tertiary-BHEC text-white" :href="Routes.proveidors">
              <i class="fas fa-truck px-2 py-2 w-14 text-center"></i><span>Proveidors</span></a>
          </li>
          <li>
            <a class="dropdown-item bg-tertiary-BHEC text-white" :href="Routes.comandes">
              <i class="fas fa-shopping-cart px-2 py-2 w-14 text-center"></i><span>Comandes</span></a>
          </li>
          <li>
            <a class="dropdown-item bg-tertiary-BHEC text-white" :href="Routes.vehicles">
              <i class="fas fa-car px-2 py-2 w-14 text-center"></i><span>Vehicles</span></a>
          </li>
        </ul>
      </div>
    </nav>
</template>
