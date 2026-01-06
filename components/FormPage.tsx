import React, { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { FormState, SubmissionStatus } from '../types';
import { MPKK_LIST, JAWATAN_LIST } from '../constants';
import { generateWelcomeNote } from '../services/geminiService';

export const FormPage: React.FC = () => {
    const navigate = useNavigate();
    const [formData, setFormData] = useState<FormState>({
        nama: '',
        noTel: '',
        noIC: '',
        mpkk: '',
        jawatan: ''
    });

    const [status, setStatus] = useState<SubmissionStatus>('idle');
    const [aiNote, setAiNote] = useState<string | null>(null);

    const handleChange = (e: React.ChangeEvent<HTMLInputElement | HTMLSelectElement>) => {
        const { name, value } = e.target;

        let newValue = value;

        // Apply transformations based on field name
        if (name === 'nama') {
            newValue = value.toUpperCase();
        } else if (name === 'noTel') {
            // Allow only digits
            newValue = value.replace(/\D/g, '');
        }

        setFormData(prev => ({ ...prev, [name]: newValue }));
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

        // Generate AI note
        const note = await generateWelcomeNote(formData.nama, formData.mpkk, formData.jawatan);
        setAiNote(note);

        try {
            const response = await fetch('/api/registrations', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(formData)
            });

            if (!response.ok) throw new Error('Submission failed');

            setStatus('success');
            // Form clearing is handled by the success view or navigation
        } catch (error) {
            console.error('Error saving registration:', error);
            alert('Gagal menyimpan pendaftaran. Sila cuba lagi.');
            setStatus('idle');
        }
    };

    return (
        <div className="w-full max-w-lg mx-auto">
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
                            placeholder="CONTOH: AHMAD BIN IBRAHIM"
                            className="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all bg-slate-50/50 uppercase"
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
                                placeholder="0123456789"
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

                {status === 'success' && (
                    <div className="mt-8 p-4 bg-blue-50 rounded-xl border border-blue-100 flex flex-col items-center text-center animate-in fade-in slide-in-from-top-4 duration-500">
                        <div className="bg-blue-600 p-3 rounded-full mb-3">
                            <svg className="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={3} d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <h4 className="font-bold text-blue-900 text-lg">Pendaftaran Berjaya!</h4>
                        {aiNote && <p className="text-blue-800 text-sm mt-2 italic">"{aiNote}"</p>}

                        <div className="flex gap-3 mt-4 w-full">
                            <button
                                onClick={() => {
                                    setStatus('idle');
                                    setFormData({ nama: '', noTel: '', noIC: '', mpkk: '', jawatan: '' });
                                    setAiNote(null);
                                }}
                                className="flex-1 py-2 text-sm font-semibold text-blue-700 bg-blue-100/50 hover:bg-blue-100 rounded-lg transition-colors"
                            >
                                Daftar Lagi
                            </button>
                            <button
                                onClick={() => navigate('/senarai')}
                                className="flex-1 py-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors shadow-sm"
                            >
                                Lihat Senarai
                            </button>
                        </div>
                    </div>
                )}
            </div>
        </div>
    );
};
