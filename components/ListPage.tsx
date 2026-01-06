import React, { useEffect, useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { MPKKRegistration } from '../types';

export const ListPage: React.FC = () => {
    const navigate = useNavigate();
    const [registrations, setRegistrations] = useState<MPKKRegistration[]>([]);
    const [loading, setLoading] = useState(true);

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
            .catch(err => console.error('Failed to connect to API:', err))
            .finally(() => setLoading(false));
    }, []);

    return (
        <div className="w-full max-w-lg mx-auto">
            <div className="bg-white/40 backdrop-blur-md rounded-2xl p-6 border border-white/50 shadow-xl">
                <div className="flex items-center justify-between mb-6">
                    <div className="flex items-center gap-3">
                        <button
                            onClick={() => navigate('/')}
                            className="p-2 -ml-2 text-slate-500 hover:text-blue-600 hover:bg-white/50 rounded-full transition-all"
                        >
                            <svg className="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                        </button>
                        <h2 className="text-xl font-bold text-slate-800">Senarai Terkini</h2>
                    </div>
                    <span className="px-3 py-1 bg-white/60 border border-slate-200 rounded-lg text-xs font-bold text-slate-600 uppercase tracking-wider shadow-sm">
                        {registrations.length} Peserta
                    </span>
                </div>

                <div className="space-y-3 min-h-[300px]">
                    {loading ? (
                        <div className="flex flex-col items-center justify-center py-20 text-slate-400 animate-pulse">
                            <p>Memuatkan data...</p>
                        </div>
                    ) : registrations.length === 0 ? (
                        <div className="flex flex-col items-center justify-center py-20 text-slate-400">
                            <svg className="w-12 h-12 mb-4 opacity-20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1} d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <p>Belum ada pendaftaran</p>
                        </div>
                    ) : (
                        registrations.map((reg) => (
                            <div key={reg.id} className="bg-white p-4 rounded-xl border border-slate-100 shadow-sm flex flex-col gap-2 hover:shadow-md transition-shadow">
                                <div className="flex justify-between items-start">
                                    <div>
                                        <h3 className="font-bold text-slate-800 text-base line-clamp-1">{reg.nama}</h3>
                                        <div className="flex flex-wrap gap-2 mt-1.5">
                                            <span className="inline-block px-2 py-0.5 bg-blue-50 text-blue-700 rounded-md text-[10px] font-bold uppercase tracking-wide">
                                                {reg.jawatan}
                                            </span>
                                        </div>
                                    </div>
                                    <span className="text-[10px] text-slate-400 font-mono bg-slate-50 px-2 py-1 rounded-md border border-slate-100">
                                        {new Date(reg.timestamp).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}
                                    </span>
                                </div>
                                <div className="pt-3 border-t border-slate-50 flex justify-between items-center text-xs text-slate-500">
                                    <span className="truncate max-w-[60%] font-medium flex items-center gap-1">
                                        <svg className="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        {reg.mpkk}
                                    </span>
                                    <div className="flex items-center gap-1 font-mono">
                                        <svg className="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                        </svg>
                                        {reg.noTel}
                                    </div>
                                </div>
                            </div>
                        ))
                    )}
                </div>
            </div>
        </div>
    );
};
