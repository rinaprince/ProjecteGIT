<script setup>

</script>


<template>
  <div class="modal" style="display:none">
    <div class="modal-content">
      <slot></slot>
    </div>
  </div>
</template>

  <style scoped>
    .modal {
      position: absolute;
      top: 10%;
      left: 40%;
      width: 20%;
      height: 70%;
      display: flex;
      justify-content: center;
      align-items: center;
      opacity:100%;
      border-radius: 8px;
    }

    .modal-content {
      background: white;
      border-radius: 8px;
      opacity:100% ;
      padding:10px;
    }

    @media (max-width: 768px) {
      .modal {
        width: 90vw;
        max-width: none;
        height: 90vh;
        max-height: none;
        left:5%;
      }
    }
  </style>


<style scoped>

</style>