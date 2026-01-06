import React from 'react';
import { BrowserRouter, Routes, Route } from 'react-router-dom';
import { Layout } from './components/Layout';
import { FormPage } from './components/FormPage';
import { ListPage } from './components/ListPage';

const App: React.FC = () => {
  return (
    <BrowserRouter>
      <Layout>
        <div className="flex flex-col gap-6 max-w-lg mx-auto w-full">
          <Routes>
            <Route path="/" element={<FormPage />} />
            <Route path="/senarai" element={<ListPage />} />
          </Routes>
        </div>
      </Layout>
    </BrowserRouter>
  );
};

export default App;
