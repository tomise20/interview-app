<template>
  <div class="container w-full max-w-[1200px] mx-auto my-10">
    <div class="text-center text-lg uppercase">Felvételi pont kalkulátor</div>
    <CalculationForm />
  </div>
</template>

<script lang="ts">
import CalculationForm from './components/CalculationForm.vue';

export default {
  name: 'App',
  components: {CalculationForm}
}
</script>
