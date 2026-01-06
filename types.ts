
export interface MPKKRegistration {
  id: string;
  nama: string;
  noTel: string;
  noIC: string;
  mpkk: string;
  jawatan: string;
  timestamp: string;
}

export interface FormState {
  nama: string;
  noTel: string;
  noIC: string;
  mpkk: string;
  jawatan: string;
}

export type SubmissionStatus = 'idle' | 'submitting' | 'success' | 'error';
