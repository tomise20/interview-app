<template>
  <label class="block mb-2 text-sm font-medium text-gray-900">{{ subject.nev }}</label>
  <div class="flex items-center flex-col">
        <div class="flex items-center">
            <input type="number" name="eredmeny" required :value="subject.eredmeny.slice(0, -1)" @input="update" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" /> %
        </div>
      <div class="w-full flex items-center my-2">
        <input id="default-checkbox" type="checkbox" name="tipus" @input="update" :checked="subject.tipus === 'emelt'" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
        <label for="default-checkbox" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Emelt</label>
    </div>
    <div v-if="error.length > 0" class="text-red-500 font-bold text-sm">{{ error }}</div>
  </div>
</template>

<script lang="ts">
import {PropType, defineComponent} from 'vue';
import { SubjectResult } from '../types';

export default defineComponent ({
    data() {
        return {
            error: ''
        }
    },
    emits: {
        updateResult(payload: SubjectResult) {
            return payload && payload.eredmeny.length > 0;
        }
    },
    props: {
        subject: {type: Object as PropType<SubjectResult>, required: true}
    },
    methods: {
        update(event: Event) {
            this.error = '';
            let value: string;
            const target = event.target as HTMLInputElement;
            

            if(target.name === "tipus") {
                value = target.checked ? 'emelt' : 'közép';
            } else {
                if(!target.value) return;
                
                if(parseInt(target.value) <= 20) {
                    this.error = 'Túl alacsony vizsga eredmény!';
                }
                
                value = target.value + '%'
            }

            this.$emit('updateResult', {
               ...this.subject,
               [target.name]: value
            }
            );
        }
    }
})
</script>