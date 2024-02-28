<script setup>
const Routes = {
  faq: "/faq",
  terms: "/terms",
  privacy: "/privacy",
};

defineProps({
  Routes: Object
})
</script>

<template>
    <div class="row">
      <div class="col-12 d-flex justify-content-center align-items-center pt-3">
        <img src="/equip2/img/logoBHECFooter.png" alt="Logo de BHEC">
        <h2 class="fs-2 text-secondary-BHEC ps-2 mb-0 font-title">BHEC</h2>
      </div>
    </div>
    <div class="row pt-3">
      <div class="col-12">
        <hr class="border-secundary-BHEC border-2 opacity-100 m-0">
        <ul
            class=" d-flex text-center justify-content-center flex-column flex-sm-row   pt-3 list-unstyled">
          <li class="px-2 text-secondary-BHEC py-1"><a :href="Routes.faq" class="text-decoration-none text-secondary-BHEC">FAQ</a></li>
          <li class="px-2 text-secondary-BHEC py-1"><a :href="Routes.terms" class="text-decoration-none text-secondary-BHEC">Terminis i Condicions</a></li>
          <li class="px-2 text-secondary-BHEC py-1"><a :href="Routes.privacy" class="text-decoration-none text-secondary-BHEC">Política de Privacitat</a></li>

        </ul>
      </div>
    </div>
</template>