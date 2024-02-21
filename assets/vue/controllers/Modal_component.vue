<script setup>
const Routes = {
  home: "/",
  back: "/backoffice",
  login: "login",
  logout: "/logout",
  comandes: "/",
  news: "/news",
  userProfile: "/profile"
};
defineProps({
  Routes: Object,
  user: {
    type: Object,
    default: false
  }
});

</script>

<template>

  <div class="offcanvas offcanvas-end bg-light" tabindex="-1" id="offcanvasExample"
       aria-labelledby="offcanvasExampleLabel">
    <div class="offcanvas-header p-0">
      <div class="container p-0">
        <!-- CON SESION -->

        <div class="row px-4 pb-4 pt-4" v-if="user">

          <div class="col-10">
            <img class="rounded-5 float-start me-3" src="/equip2/img/usuari.png" alt="Icon d'Usuari">
            <h5 class="offcanvas-title fw-bold" id="offcanvasExampleLabel">{{ user?.username }}</h5>
            <span v-if="user?.customer">{{ user?.customer.email }}</span>
          </div>
          <div class="col-2">
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"
                    aria-label="Tancar"></button>
          </div>
        </div>
        <!-- SIN SESION -->
        <div class="row px-4 pb-4 pt-4" v-else>
          <div class="row px-4 pb-4 pt-4">
            <div class="col-10">
              <img class="rounded-5 float-start me-3" src="/equip2/img/usuari.png" alt="Icon d'Usuari">
              <h5 class="offcanvas-title fw-bold" id="offcanvasExampleLabel">nom Usuari</h5>
            </div>
            <div class="col-2">
              <button type="button" class="btn-close" data-bs-dismiss="offcanvas"
                      aria-label="Tancar"></button>
            </div>
          </div>
        </div>

        <div class="offcanvas-body mt-0 p-0">
          <div>
            <div class="px-4" v-if="user">
              <a :href="Routes.home"
                 class="w-100 d-inline-block link-dark link-underline link-underline-opacity-0 link-opacity-50-hover">Pagina Principal</a>
              <a :href="Routes.back"
                 class="w-100 d-inline-block mt-1 link-dark link-underline link-underline-opacity-0 link-opacity-50-hover">Pagina de Administració</a>
              <a :href="Routes.userProfile"
                 class="w-100 d-inline-block mt-1 link-dark link-underline link-underline-opacity-0 link-opacity-50-hover">Perfil de Usuari</a>
              <a href="#"
                 class="w-100 d-inline-block mt-1 link-dark link-underline link-underline-opacity-0 link-opacity-50-hover">Configuració</a>
            </div>
            <div class="px-4" v-else>
              <span v-if="!user"><a :href="Routes.login"
                 class="w-100 fs-3 mb-3 d-inline-block link-dark link-underline link-underline-opacity-0 link-opacity-50-hover">Iniciar Sesión</a></span>
              <a :href="Routes.comandes"
                 class="w-100 d-inline-block link-dark link-underline link-underline-opacity-0 link-opacity-50-hover">Comandes</a>
              <a :href="Routes.news"
                 class="w-100 d-inline-block mt-1 link-dark link-underline link-underline-opacity-0 link-opacity-50-hover">news</a>
              <a href="#"
                 class="w-100 d-inline-block mt-1 link-dark link-underline link-underline-opacity-0 link-opacity-50-hover">Administració</a>
            </div>
            <div class="vh-100 p-0">
              <div class="position-absolute bottom-0 mb-5 p-0 w-100">
                <div class="bg-primary-BHEC py-0 text-white ps-1 fs-2">
                  <img class="mt-2 rounded-5 bg-light float-start me-3 p-1"
                       src="/equip2/img/logoBHEC.png" alt="Logo BHEC"><span class="ps-0">BHEC</span>
                </div>
                <div class="m-0 p-0 w-100" v-if="user">
                  <a :href="Routes.logout"
                     class="ps-4 w-100 d-inline-block mt-5 link-dark link-underline link-underline-opacity-0 link-opacity-50-hover">Tancar
                    sessió</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
