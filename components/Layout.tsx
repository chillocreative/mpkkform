
import React from 'react';

export const Layout: React.FC<{ children: React.ReactNode }> = ({ children }) => {
  return (
    <div className="flex flex-col min-h-screen bg-transparent">
      <main className="flex-grow w-full max-w-lg mx-auto px-4 py-8">
        {children}
      </main>
    </div>
  );
};
