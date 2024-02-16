<template>
  <p class="display-4">Registre</p>
    <form method="post" novalidate="novalidate">
      <div class="container">
        <div class="row">
          <div class="col-12 col-sm-7 px-0 px-sm-5 border-end border-black border-opacity-25">
            <div class="row my-3">
              <div class="col-sm-4 col-6">
                <div class="m-2">
                  <fieldset>
                    <legend class="fs-5 p-0 m-0">Nom</legend>
                    <input v-model="nameInput" type="text" class="form-control w-100 rounded-2 border border-black border-opacity-50 p-1"
                           placeholder="Nom" name="name" id="name">
                  </fieldset>
                </div>
              </div>
              <div class="col-sm-4 col-6">
                <div class="m-2">
                  <fieldset>
                    <legend class="fs-5 p-0 m-0">Cognom</legend>
                    <input v-model="lastnameInput" type="text" placeholder="Cognom"
                           class="form-control w-100 rounded-2 border border-black border-opacity-50 p-1" name="lastName"
                           id="lastname">
                  </fieldset>
                </div>
              </div>
              <div class="col-sm-4 col-6">
                <div class="m-2">
                  <fieldset class="w-100">
                    <legend class="fs-5 p-0 m-0">DNI</legend>
                    <input v-model="dniInput" type="text" placeholder="Dni" name="dni"
                           class="form-control w-100 rounded-2 border border-black border-opacity-50 p-1" id="dni"
                           max="9"
                           min="9">
                  </fieldset>
                </div>
              </div>
            </div>
            <div class="row my-3">
              <div class="col-sm-8 col-12">
                <div class="m-2">
                  <fieldset class="w-100">
                    <legend class="fs-5 p-0 m-0">Correu electrònic</legend>
                    <input v-model="emailInput" type="email" placeholder="exemple@gmail.com" name="email"
                           class="form-control w-100 rounded-2 border border-black border-opacity-50 p-1" id="email">
                  </fieldset>
                </div>
              </div>
              <div class="col-sm-4 col-6">
                <div class="m-2">
                  <fieldset class="w-100">
                    <legend class="fs-5 p-0 m-0">Telèfon</legend>
                    <input v-model="phoneInput" type="text" placeholder="Telèfon" name="phone" id="phone"
                           class="form-control w-100 rounded-2 border border-black border-opacity-50 p-1" max="9"
                           min="9">
                  </fieldset>
                </div>
              </div>
            </div>
            <div class="row my-3">
              <div class="col-sm-12 col-12">
                <div class="m-2">
                  <fieldset class="w-100">
                    <legend class="fs-5 p-0 m-0">Adreça</legend>
                    <input v-model="addressInput" type="text" placeholder="Carrer Ramon y Cajal, N12, Dénia, Alacant, Espanya "
                           class="form-control w-100 rounded-2 border border-black border-opacity-50 p-1" name="address"
                           id="address">
                  </fieldset>
                </div>
              </div>
            </div>
          </div>
          <div class="col-sm-5 col-12 my-5 d-flex justify-content-center px-0">
            <div class="border border-black border-opacity-25 py-5 rounded-2 shadow">
              <div class="p-3 d-flex flex-column align-items-center">
                <h2 class="fs-4">Credencials d'inici de sessió</h2>
                <input type="hidden" name="_csrf_token" value="{{ csrf_token('authenticate') }}">
                <div class="py-2">
                  <label for="username">Usuari:</label>
                  <div class="position-relative">
                    <input v-model="usernameInput" class="form-control border border-black border-opacity-25 ps-5" type="text"
                           id="username"
                           name="_username" required autofocus placeholder="Nom d'usuari">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                         class="bi bi-person position-absolute top-50 translate-middle-y ms-3"
                         viewBox="0 0 16 16">
                      <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10s-3.516.68-4.168 1.332c-.678.678-.83 1.418-.832 1.664z"/>
                    </svg>
                  </div>
                </div>

                <div class="py-2">
                  <label for="password">Contrasenya:</label>
                  <div class="position-relative" id="passwordController">
                    <input v-model="passwordInput" class="form-control border border-black border-opacity-25 ps-5"
                           type="text" id="registerPassword"
                           name="password" required
                           data-action="click->registerPassword-toggle#togglePassword"
                           data-target="registerPassword-toggle.icon" placeholder="Contrasenya">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                         class="bi bi-key position-absolute top-50 translate-middle-y ms-3"
                         viewBox="0 0 16 16">
                      <path d="M0 8a4 4 0 0 1 7.465-2H14a.5.5 0 0 1 .354.146l1.5 1.5a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0L13 9.207l-.646.647a.5.5 0 0 1-.708 0L11 9.207l-.646.647a.5.5 0 0 1-.708 0L9 9.207l-.646.647A.5.5 0 0 1 8 10h-.535A4 4 0 0 1 0 8m4-3a3 3 0 1 0 2.712 4.285A.5.5 0 0 1 7.163 9h.63l.853-.854a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .708 0l.646.647.793-.793-1-1h-6.63a.5.5 0 0 1-.451-.285A3 3 0 0 0 4 5"/>
                      <path d="M4 8a1 1 0 1 1-2 0 1 1 0 0 1 2 0"/>
                    </svg>
                  </div>
                </div>
                <div class="py-3">
                  <label for="password">Confirma contrasenya:</label>
                  <div class="position-relative" id="passwordController">
                    <input v-model="confirmPasswordInput" class="form-control border border-black border-opacity-25 ps-5"
                           type="password" id="password"
                           name="confirm_password" required
                           data-action="click->password-toggle#togglePassword"
                           data-target="password-toggle.icon" placeholder="Confirma contrasenya">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                         class="bi bi-key position-absolute top-50 translate-middle-y ms-3"
                         viewBox="0 0 16 16">
                      <path d="M0 8a4 4 0 0 1 7.465-2H14a.5.5 0 0 1 .354.146l1.5 1.5a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0L13 9.207l-.646.647a.5.5 0 0 1-.708 0L11 9.207l-.646.647a.5.5 0 0 1-.708 0L9 9.207l-.646.647A.5.5 0 0 1 8 10h-.535A4 4 0 0 1 0 8m4-3a3 3 0 1 0 2.712 4.285A.5.5 0 0 1 7.163 9h.63l.853-.854a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .708 0l.646.647.793-.793-1-1h-6.63a.5.5 0 0 1-.451-.285A3 3 0 0 0 4 5"/>
                      <path d="M4 8a1 1 0 1 1-2 0 1 1 0 0 1 2 0"/>
                    </svg>
                    <div class="hideEye">
                      <i class='bi bi-eye position-absolute top-50 end-0 translate-middle-y me-3 small'></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row border-top border-black border-opacity-25 mt-5">
        <div class="col-12">
          <div class="m-2 d-flex justify-content-center align-items-center">
            <button class="btn btn-success" @click="submitForm">Crear Usuari</button>
          </div>
        </div>
      </div>
    </form>
</template>

<script setup>
import { ref } from "vue";
import axios from "axios";


const nameInput = ref('asdasdasd');
const lastnameInput = ref('asdasd');
const addressInput = ref('asdasdasdasd');
const dniInput = ref('12345678Z');
const phoneInput = ref('123123123');
const emailInput = ref('asdasd@gmail.com');
const usernameInput = ref('telopidoporfavor');
const passwordInput = ref('1234');
const confirmPasswordInput = ref('1234');

const data = {
  name: nameInput.value,
  lastname: lastnameInput.value,
  address: addressInput.value,
  dni: dniInput.value,
  phone: phoneInput.value,
  email: emailInput.value,
  username: usernameInput.value,
  password: passwordInput.value,
  confirmPassword: confirmPasswordInput.value,
};

function submitForm() {
  axios.post('/register/particular',{
    data: data
  })
      .then(function (response) {
        console.log(data);
      })
      .catch(function (error) {
        console.log(error);
      });
}
</script>