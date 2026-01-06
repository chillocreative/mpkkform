
import React, { useState, useEffect } from 'react';
import { Layout } from './components/Layout';
import { FormState, MPKKRegistration, SubmissionStatus } from './types';
import { MPKK_LIST, JAWATAN_LIST } from './constants';
import { generateWelcomeNote } from './services/geminiService';

const App: React.FC = () => {
  const [formData, setFormData] = useState<FormState>({
    nama: '',
    noTel: '',
    noIC: '',
    mpkk: '',
    jawatan: ''
  });

  const [registrations, setRegistrations] = useState<MPKKRegistration[]>([]);
  const [status, setStatus] = useState<SubmissionStatus>('idle');
  const [aiNote, setAiNote] = useState<string | null>(null);

  // Load from Database
  useEffect(() => {
    fetch('/api/registrations')
      .then(res => res.json())
      .then(data => {
        if (Array.isArray(data)) {
          setRegistrations(data);
        } else {
          console.error('Failed to load registrations:', data);
        }
      })
      .catch(err => console.error('Failed to connect to API:', err));
  }, []);

  const handleChange = (e: React.ChangeEvent<HTMLInputElement | HTMLSelectElement>) => {
    const { name, value } = e.target;
    setFormData(prev => ({ ...prev, [name]: value }));
  };

  const validateIC = (ic: string) => {
    return /^\d{6}-\d{2}-\d{4}$/.test(ic) || /^\d{12}$/.test(ic);
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!validateIC(formData.noIC)) {
      alert("Sila masukkan No IC yang sah (e.g. 900101-01-5555)");
      return;
    }

    setStatus('submitting');

    // Call Gemini for a nice touch
    const note = await generateWelcomeNote(formData.nama, formData.mpkk, formData.jawatan);
    setAiNote(note);

    // Write to Database
    try {
      const response = await fetch('/api/registrations', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(formData)
      });

      if (!response.ok) throw new Error('Submission failed');

      const savedEntry = await response.json();
      const updatedRegistrations = [savedEntry, ...registrations];
      setRegistrations(updatedRegistrations);
    } catch (error) {
      console.error('Error saving registration:', error);
      alert('Gagal menyimpan pendaftaran. Sila cuba lagi.');
      setStatus('idle');
      return;
    }

    setTimeout(() => {
      setStatus('success');
      setFormData({
        nama: '',
        noTel: '',
        noIC: '',
        mpkk: '',
        jawatan: ''
      });
    }, 800);
  };

  return (
    <Layout>
      <div className="flex flex-col gap-6 max-w-lg mx-auto w-full">
        {/* Registration Form Section */}
        <section className="w-full">
          <div className="bg-white/80 backdrop-blur-xl rounded-2xl p-6 shadow-2xl border border-white/50">
            <div className="text-center mb-6">
              <div className="inline-flex items-center justify-center w-12 h-12 bg-blue-600 rounded-xl mb-3 shadow-lg shadow-blue-200">
                <span className="text-white font-bold text-sm">MPKK</span>
              </div>
              <h1 className="text-2xl font-bold text-slate-900 leading-tight">Pendaftaran Perjumpaan</h1>
              <p className="text-slate-500 text-sm mt-1">Sila lengkapkan butiran kehadiran anda</p>
            </div>

            <form onSubmit={handleSubmit} className="space-y-5">
              <div>
                <label className="block text-sm font-semibold text-slate-700 mb-1">Nama Penuh</label>
                <input
                  required
                  name="nama"
                  value={formData.nama}
                  onChange={handleChange}
                  placeholder="Contoh: Ahmad Bin Ibrahim"
                  className="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all bg-slate-50/50"
                />
              </div>

              <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label className="block text-sm font-semibold text-slate-700 mb-1">No. Telefon</label>
                  <input
                    required
                    type="tel"
                    name="noTel"
                    value={formData.noTel}
                    onChange={handleChange}
                    placeholder="012-3456789"
                    className="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all bg-slate-50/50"
                  />
                </div>
                <div>
                  <label className="block text-sm font-semibold text-slate-700 mb-1">No. Kad Pengenalan</label>
                  <input
                    required
                    name="noIC"
                    value={formData.noIC}
                    onChange={handleChange}
                    placeholder="900101075555"
                    className="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all bg-slate-50/50"
                  />
                </div>
              </div>

              <div>
                <label className="block text-sm font-semibold text-slate-700 mb-1">MPKK</label>
                <select
                  required
                  name="mpkk"
                  value={formData.mpkk}
                  onChange={handleChange}
                  className="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all bg-slate-50/50 appearance-none"
                >
                  <option value="">Pilih MPKK</option>
                  {MPKK_LIST.map(item => (
                    <option key={item} value={item}>{item}</option>
                  ))}
                </select>
              </div>

              <div>
                <label className="block text-sm font-semibold text-slate-700 mb-1">Jawatan</label>
                <select
                  required
                  name="jawatan"
                  value={formData.jawatan}
                  onChange={handleChange}
                  className="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all bg-slate-50/50 appearance-none"
                >
                  <option value="">Pilih Jawatan</option>
                  {JAWATAN_LIST.map(j => (
                    <option key={j} value={j}>{j}</option>
                  ))}
                </select>
              </div>

              <button
                type="submit"
                disabled={status === 'submitting'}
                className="w-full py-3 px-6 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg shadow-blue-200 transition-all transform hover:scale-[1.01] active:scale-95 disabled:opacity-50"
              >
                {status === 'submitting' ? 'Memproses...' : 'Daftar Kehadiran'}
              </button>
            </form>

            {status === 'success' && aiNote && (
              <div className="mt-8 p-4 bg-blue-50 rounded-xl border border-blue-100 flex items-start space-x-3 animate-in fade-in slide-in-from-top-4 duration-500">
                <div className="bg-blue-600 p-2 rounded-full">
                  <svg className="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={3} d="M5 13l4 4L19 7" />
                  </svg>
                </div>
                <div>
                  <h4 className="font-bold text-blue-900 text-sm">Pendaftaran Berjaya!</h4>
                  <p className="text-blue-800 text-sm mt-1 italic">"{aiNote}"</p>
                  <button
                    onClick={() => { setStatus('idle'); setAiNote(null); }}
                    className="mt-2 text-xs font-semibold text-blue-600 hover:underline"
                  >
                    Tutup
                  </button>
                </div>
              </div>
            )}
          </div>
        </section>

        {/* List Section */}
        <section className="w-full pb-8">
          <div className="bg-white/40 backdrop-blur-md rounded-2xl p-6 border border-white/50">
            <div className="flex items-center justify-between mb-4">
              <h2 className="text-lg font-bold text-slate-800">Senarai Terkini</h2>
              <span className="px-2.5 py-1 bg-white/60 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-500 uppercase tracking-wider">
                {registrations.length} Peserta
              </span>
            </div>

            <div className="space-y-3">
              {registrations.length === 0 ? (
                <div className="text-center py-10 text-slate-400">
                  <p className="text-sm">Belum ada pendaftaran</p>
                </div>
              ) : (
                registrations.map((reg) => (
                  <div key={reg.id} className="bg-white p-3.5 rounded-xl border border-slate-100 shadow-sm flex flex-col gap-2">
                    <div className="flex justify-between items-start">
                      <div>
                        <h3 className="font-bold text-slate-800 text-sm line-clamp-1">{reg.nama}</h3>
                        <div className="flex flex-wrap gap-1 mt-1">
                          <span className="inline-block px-1.5 py-0.5 bg-blue-50 text-blue-700 rounded text-[10px] font-bold uppercase">
                            {reg.jawatan}
                          </span>
                        </div>
                      </div>
                      <span className="text-[10px] text-slate-400 font-mono bg-slate-50 px-1.5 py-0.5 rounded">{reg.timestamp.split(',')[1]?.trim() || reg.timestamp}</span>
                    </div>
                    <div className="pt-2 border-t border-slate-50 flex justify-between items-center text-[10px] text-slate-500">
                      <span className="truncate max-w-[60%] font-medium">{reg.mpkk}</span>
                      <div className="flex space-x-2">
                        <span>{reg.noTel}</span>
                      </div>
                    </div>
                  </div>
                ))
              )}
            </div>
          </div>
        </section>
      </div>

      <style>{`
        .custom-scrollbar::-webkit-scrollbar {
          width: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
          background: transparent;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
          background: #e2e8f0;
          border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
          background: #cbd5e1;
        }
      `}</style>
    </Layout>
  );
};

export default App;
