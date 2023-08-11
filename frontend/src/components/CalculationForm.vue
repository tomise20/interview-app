<template>
  <form @submit.prevent="onSubmit">
    <div class="w-full md:w-4/12 mb-5">
      <label for="countries" class="block mb-2 text-sm font-medium text-gray-900">Válassz egyetemet</label>
      <select name="egyetem" required v-model="egyetem" id="">
        <option value="ELTE">ELTE - IK</option>
        <option value="PPKE">PPKE - BTK</option>
      </select>
    </div>
    <div class="w-full mb-5">
      <div class="font-bold mb-2">Kötelező tantárgyak eredményei:</div>
      <div class="flex flex-wrap lg:flex-nowrap justify-between gap-3">
        <div v-for="subject in subjectResults">
          <Subject :subject="subject" @updateResult="handleChange" />
        </div>
      </div>
    </div>
    <div class="w-full mb-5">
      <div class="font-bold mb-2">Kötelezően választható tantárgy eredménye:</div>
      <div class="flex flex-wrap lg:flex-nowrap justify-between items-center gap-3">
        <div class="w-full md:w-6/12">
          <label for="" class="block mb-2 text-sm font-medium text-gray-900">Tantrágy</label>
          <select v-model="optionalSubject.nev" id="">
            <option v-for="subject in optionalSubjects" :value="subject">{{ subject }}</option>
          </select>
        </div>
        <div class="w-full md:w-6/12">
          <label class="block mb-2 text-sm font-medium text-gray-900">eredmény</label>
          <div class="flex items-center">
              <input
                type="number"
                required
                :value="optionalSubject.eredmeny.slice(0, -1)"
                @input="event => optionalSubject.eredmeny = (event.target as HTMLInputElement).value + '%'"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" /> %
          </div>
        </div>
        <div class="w-full md:w-6/12">
          <label for="" class="block mb-2 text-sm font-medium text-gray-900">Emelet?</label>
          <div class="w-full flex items-center my-2">
            <input id="default-checkbox" type="checkbox" name="level" @input="updateOptionalSubjectType" :checked="optionalSubject.tipus === 'emelt'" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
            <label for="default-checkbox" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Igen</label>
        </div>
        </div>
      </div>
    </div>
    <div class="w-full mb-5">
      <div class="font-bold mb-2">Nyelvvizsga eredmények:</div>
      <button type="button" class="p-3 text-sky-400 hover:bg-gray-100 mb-3" @click.prevent="addLangExam">+ Nyelvvizsga hozzáadása</button>
      <div class="flex flex-col flex-wrap lg:flex-nowrap justify-between gap-3">
        <LanguageExam v-for="(exam, index) in languageExams" :index="index" :exam="exam" @remove="removeLangExam" />
      </div>
    </div>

    <button type="submit" class="bg-sky-400 hover:bg-sky-500 text-white py-2 px-4 rounded">Kalkuláció</button>
  </form>

  <div v-if="Object.keys(response).length > 0" class="mt-5">
    <InfoPanel :isSuccess="response.isSuccess" :message="response.isSuccess ? response.points.toString() : response.errors" />
  </div>
</template>

<script lang="ts">
import {defineComponent} from 'vue';
import Subject from './Subject.vue';
import LanguageExam from './LanguageExam.vue';
import InfoPanel from './InfoPanel.vue';
import {SubjectResult, ILanguageExam, CalculationRequest, CalculationResponse} from '../types'
import axios from 'axios';

export default defineComponent ({
  data() {
    return {
      egyetem: '',
      response: {} as CalculationResponse,
      optionalSubjectResult: 0,
      optionalSubjects: [] as String[],
      subjectResults: [
        {
          nev: 'magyar nyelv és irodalom',
          tipus: 'közép',
          eredmeny: ''
        },
        {
          nev: 'történelem',
          tipus: 'közép',
          eredmeny: ''
        },
        {
          nev: 'matematika',
          tipus: 'közép',
          eredmeny: ''
        },
        {
          nev: 'angol nyelv',
          tipus: 'közép',
          eredmeny: ''
        }
      ] as SubjectResult[],
      languageExams: [] as ILanguageExam[],
      optionalSubject: {
        nev: '',
        tipus: 'közép',
        eredmeny: ''
      } as SubjectResult
    }
  },

  methods: {
    async onSubmit() {
      const data: CalculationRequest = {
        "valasztott-szak": {
          egyetem: this.egyetem,
          kar: this.egyetem === 'ELTE' ? 'IK' : 'BTK',
          szak: ''
        },
        'erettsegi-eredmenyek': [...this.subjectResults, this.optionalSubject],
        tobbletpontok: this.languageExams
      }

      try {
        const resp = await axios.post<CalculationResponse>('http://localhost:8000/api/calculation', data);

        this.response = resp.data;
      } catch(err: any) {
        this.response = err.response.data
      }

    },
    addLangExam() {
      const newExam: ILanguageExam = {
        kategoria: 'Nyelvvizsga',
        nyelv: 'angol',
        tipus: 'B2'
      }

      this.languageExams.push(newExam);
    },
    removeLangExam(index: number) {
      this.languageExams = this.languageExams.filter((_, i) => index !== i);
    },
    handleChange(payload: SubjectResult) {
      const newResults: SubjectResult[] = [...this.subjectResults];
      const searchedIndex = newResults.findIndex(s => s.nev === payload.nev);
      newResults[searchedIndex] = payload;

      this.subjectResults = newResults;
    },
    updateOptionalSubjectType(e: Event) {
      const target = e.target as HTMLInputElement;

      this.optionalSubject.tipus = target.checked ? 'emelt' : 'közép';
    },
  },
  updated() {
    if(this.egyetem === 'ELTE') {
      this.optionalSubjects = ['biológia', 'fizika', 'informatika', 'kémia']
    } else {
        this.optionalSubjects = ['francia', 'német', 'olasz', 'orosz', 'spany', 'történelem']
    }
  },
  components: { Subject, LanguageExam, InfoPanel }
})
</script>