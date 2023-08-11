export interface ILanguageExam {
    kategoria: string
    nyelv: 'angol' | 'német';
    tipus: 'B2' | 'C1';
}
  
export type SubjectResult = {
    nev: string;
    tipus: 'közép' | 'emelt';
    eredmeny: string;
}

export type CalculationRequest = {
    'valasztott-szak': {
        egyetem: string;
        kar: string;
        szak: string
    };
    'erettsegi-eredmenyek': SubjectResult[];
    tobbletpontok?: ExtraPoint[]
}

export type CalculationResponse = ({
    isSuccess: true,
    points: number
}|{
    isSuccess: false,
    errors: string
})

type ExtraPoint = {
    kategoria: string;
    tipus: 'B2' | 'C1';
    nyelv: string;
}

export {}