<template>
    <div class="flex w-full flex-wrap lg:flex-nowrap gap-3">
        <div>
            <label for="" class="block mb-2 text-sm font-medium text-gray-900">Nyelv</label>
            <select v-model="exam.nyelv" class="w-[300px]" name="egyetem" id="">
                <option value="angol">Angol</option>
                <option value="német">Német</option>
            </select>
        </div>
        <div>
            <label for="" class="block mb-2 text-sm font-medium text-gray-900">Szint</label>
            <select v-model="exam.tipus" class="w-[100px]" name="egyetem" id="">
                <option value="B2">B2</option>
                <option value="C1">C1</option>
            </select>
        </div>
        <div>
            <label for="" class="block mb-2 text-sm font-medium text-gray-900">Művelet</label>
            <button type="button" @click="$emit('remove', index)" class="font-bold text-red-500 mt-2">törlés</button>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import { ILanguageExam } from '../types';
import type { PropType } from 'vue';

export default defineComponent({
    props: {
        exam: {type: Object as PropType<ILanguageExam>, required: true},
        index: {type: Number, required: true}
    }
});
</script>