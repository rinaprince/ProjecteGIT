<script setup>
const Routes = {
  home: "/",
  back: "/back",
  catalogue: "/catalogue",
  contacte: "/contacte",
  garage: "/garage",
  preferits: "/preferits",
  notifications: "/",
};
defineProps({
  Routes: Object
})
</script>

<template>
  <!-- HEADER -->
    <nav class="navbar navbar-expand-sm py-0">
      <div class="d-flex justify-content-between w-100 py-0 align-items-center py-1 row g-0">
        <div class="d-flex align-items-center col-2 col-sm-3">
          <a class="navbar-brand ps-2 d-inline-block" :href="Routes.home"><img src="/equip2/img/logoBHEC.png"
             alt="logo BHEC"></a>
          <a class="navbar-brand d-inline-block text-secondary-BHEC fs-2 font-front d-none d-sm-block"
             :href="Routes.catalogue">Catàleg</a>
          <a class="navbar-brand ps-3 d-inline-block text-secondary-BHEC fs-2 font-front d-none d-sm-block"
             :href="Routes.contacte">Contacte</a>
        </div>
        <div class="d-none d-sm-block d-flex justify-content-center col col-sm-6">
          <div class="input-group w-50 d-flex justify-content-center mx-auto">
            <input type="text" class="form-control  rounded-start-5" placeholder="Cercar..."
                   aria-label="Cercar" aria-describedby="button-addon2">
            <button class="btn bg-tertiary-BHEC rounded-end-5 px-4" type="button" id="button-addon2"><i
                class="bi bi-search fw-bold"></i></button>
          </div>
        </div>
        <!-- Iconos -->
        <div class="pe-md-4 d-flex justify-content-center col-8 col-sm-2">

          <div class="dropdown">
            <a class="px-0 btn d-inline-block notificacion dropdown-toggle" type="button"
               data-bs-toggle="dropdown" aria-expanded="false">
              <img src="/equip2/img/icon/notificacio-Groc.png"></a>
            <ul class="dropdown-menu dropdown-menu-end">
              <li><a class="dropdown-item" href="#"><i class="bi bi-bag-check text-success"></i> La compra de tu coche ha sido exitosa</a></li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li><a class="dropdown-item" href="#">Tu coche ya esta en camino</a></li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li><a class="dropdown-item" href="#">Mira nuestras novedades</a></li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li><a class="dropdown-item d-flex align-items-center" href="#"><i class="bi bi-exclamation-octagon-fill text-warning"></i> Has la verificacion en 2 pasos por tu seguridad</a></li>

            </ul>
          </div>
          <a class="btn d-inline-block px-2" :href="Routes.preferits">
            <img src="/equip2/img/icon/llistaDeDesitjos-Groc.png">
          </a>
          <a class="btn d-inline-block d-flex align-items-center position-relative me-2" :href="Routes.garage">
            <img src="/equip2/img/icon/garatge-Groc.png">
            <span
                class="position-absolute start-100 translate-middle badge rounded-pill bg-light text-secondary badge-garatge">
                            2
                            <span class="visually-hidden">cotxes al garatge</span>
                        </span>
          </a>
          <a class="btn d-inline-block px-2" data-bs-toggle="offcanvas" href="#offcanvasExample" role="button"
             aria-controls="offcanvasExample">
            <i class="fs-3 fs-md-2 bi bi-person-square text-secondary-BHEC"></i>
          </a>
        </div>
        <!-- menú hamburguesa -->
        <button class="navbar-toggler bg-secondary-BHEC border border-0 me-2 px-1 col" type="button"
                data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Desplegar navegació">
          <span class="navbar-toggler-icon"></span>
        </button>
      </div>
      <!-- desplegable menú hamburguesa -->
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-lg-0 d-block d-sm-none bg-light p-0 m-0 fs-5 dropdown-menu w-100">
          <li class="">
            <a class="dropdown-item bg-tertiary-BHEC text-white" aria-current="page" :href="Routes.catalogue"><i
                class="bi bi-book px-2 py-2"></i> Catàleg</a>
          </li>
          <li class="">
            <a class="dropdown-item bg-tertiary-BHEC text-white" aria-current="page" :href="Routes.contacte"><i
                class="bi-envelope px-2 py-2"></i> Contacte</a>
          </li>
        </ul>
      </div>
    </nav>
    <div class="d-flex justify-content-center d-block d-sm-none">
      <form class="input-group d-flex align-items-center px-4 py-2">
        <input type="text" class="form-control  rounded-start-5" placeholder="Cercar..." aria-label="Cercar"
               aria-describedby="button-addon2">
        <button class="btn bg-tertiary-BHEC rounded-end-5 px-4" type="submit" id="button-addon3"><i
            class="bi bi-search fw-bold"></i></button>
      </form>
    </div>

</template>