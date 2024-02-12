<script setup>

</script>

<template>
    <nav class="navbar navbar-expand-sm py-0">
      <div class="d-flex justify-content-between w-100 py-0 align-items-center py-1">
        <div class>
          <a class="navbar-brand px-3 d-inline-block" href="/back"><img src="/equip2/img/logoBHEC.png"
                                                                    alt="logo BHEC"></a>
        </div>
        <div class>
          <a class="btn d-inline-block" data-bs-toggle="offcanvas" href="#offcanvasExample" role="button"
             aria-controls="offcanvasExample">
            <i class="fs-1 fs-md-2 bi bi-person-square text-secondary-BHEC"></i>
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
          <li class>
            <a class="dropdown-item bg-tertiary-BHEC text-white" aria-current="page" href="#">
              <i class="fas fa-file-invoice px-2 py-2 w-14 text-center"></i><span>Factures</span></a>
          </li>
          <li class>
            <a class="dropdown-item bg-tertiary-BHEC text-white" aria-current="page" href="#">
              <i class="fas fa-users px-2 py-2 w-14 text-center"></i><span>Clients</span>
            </a>
          </li>
          <li class="nav-item dropdown bg-tertiary-BHEC">
            <a class="nav-link dropdown-toggle bg-tertiary-BHEC text-white ps-3" href="#" role="button"
               data-bs-toggle="dropdown" aria-expanded="false">
              <i class="fas fa-user-tie px-2 py-2 w-14 text-center"></i><span>Empleats</span></a>
            <ul class="dropdown-menu p-0 m-0">
              <li class="ps-4 bg-tertiary-BHEC">
                <a class="dropdown-item bg-tertiary-BHEC text-white ps-5" href="#">Alta Proveïdor</a>
              </li>
              <li class="ps-4 bg-tertiary-BHEC">
                <a class="dropdown-item bg-tertiary-BHEC text-white ps-5 link-underline link-underline-opacity-0 link-opacity-50-hover"
                   href="#">Visualitzar Proveïdors</a>
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </nav>
</template>
