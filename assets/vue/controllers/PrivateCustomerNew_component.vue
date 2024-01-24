<template>
  <div>
    <h1>Create new PrivateCustomer</h1>

    <form name="private_customer" method="POST" novalidate="novalidate" @submit.prevent="submitForm">
      <div id="private_customer">
        <div class="mb-3">
          <label class="form-label required" for="name">Name:</label>
          <input v-model="nameInput" class="form-control" type="text" id="name" name="name" required maxlength="255">
        </div>
        <div class="mb-3">
          <label class="form-label required" for="lastname">Lastname:</label>
          <input v-model="lastnameInput" class="form-control" type="text" id="lastname" name="lastname" required maxlength="255">
        </div>
        <div class="mb-3">
          <label class="form-label required" for="address">Address:</label>
          <input v-model="addressInput" class="form-control" type="text" id="address" name="address" required maxlength="255">
        </div>
        <div class="mb-3">
          <label class="form-label required" for="dni">DNI:</label>
          <input v-model="dniInput" class="form-control" type="text" id="dni" name="dni" required maxlength="20">
        </div>
        <div class="mb-3">
          <label class="form-label required" for="phone">Phone:</label>
          <input v-model="phoneInput" class="form-control" type="text" id="phone" name="phone" required maxlength="20">
        </div>
        <div class="mb-3">
          <label class="form-label required" for="email">Email:</label>
          <input v-model="emailInput" class="form-control" type="email" id="email" name="email" required maxlength="255">
        </div>
        <div class="mb-3">
          <label class="form-label required" for="username">Username:</label>
          <input v-model="usernameInput" class="form-control" type="text" id="username" name="username" required maxlength="255">
        </div>
        <div class="mb-3">
          <label class="form-label required" for="password">Password:</label>
          <input v-model="passwordInput" class="form-control" type="text" id="password" name="password" required maxlength="255">
        </div>
      </div>
      <button class="btn btn-success" type="submit">Save</button>
    </form>
  </div>
</template>

<script setup>
import { ref } from "vue";
import axios from "axios";

const nameInput = ref(null);
const lastnameInput = ref(null);
const addressInput = ref(null);
const dniInput = ref(null);
const phoneInput = ref(null);
const emailInput = ref(null);
const usernameInput = ref(null);
const passwordInput = ref("1234");

const submitForm = async () => {
  const data = {
    name: nameInput.value,
    lastname: lastnameInput.value,
    address: addressInput.value,
    dni: dniInput.value,
    phone: phoneInput.value,
    email: emailInput.value,
    username: usernameInput.value,
    password: passwordInput.value,
  };

  try {
    const response = await axios.post('/private/customer/new', data);
    console.log('Response from server:', response.data);

    window.location.href = '/customer';

  } catch (error) {
    console.error('Error:', error);
  }
};
</script>
