<template>
    <div :class="[isSuccess ? 'success-panel' : 'error-panel']" role="alert">
        <div class="flex items-center">
            <div class="py-1"><svg :class="[isSuccess ? 'text-teal-500' : 'text-red-800']" class="fill-current h-6 w-6 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z"/></svg></div>
            <div>
                <p v-show="isSuccess" class="font-bold">Gratulálunk a felvételi pontjaid száma: {{ message }}</p>
                <p v-show="!isSuccess" class="font-bold">{{ message }}</p>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue';

export default defineComponent({
    props: {
        isSuccess: {type: Boolean, required: true},
        message: {type: String, required: true}
    },
});

</script>