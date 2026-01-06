import express from 'express';
import cors from 'cors';
import dotenv from 'dotenv';
import pool from './db.js';

dotenv.config();

import path from 'path';
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const app = express();
const PORT = process.env.PORT || 3001;

app.use(cors());
app.use(express.json());
app.use(express.static(path.join(__dirname, 'dist')));

// API Routes
app.get('/api/registrations', async (req, res) => {
    try {
        const [rows] = await pool.query('SELECT id, nama, no_tel AS noTel, no_ic AS noIC, mpkk, jawatan, created_at AS timestamp FROM registrations ORDER BY created_at DESC');
        res.json(rows);
    } catch (error) {
        console.error(error);
        res.status(500).json({ error: 'Database error' });
    }
});

app.post('/api/registrations', async (req, res) => {
    const { nama, noTel, noIC, mpkk, jawatan } = req.body;

    // Server-side validation
    if (!nama || !noTel || !noIC || !mpkk || !jawatan) {
        return res.status(400).json({ error: 'All fields are required' });
    }

    try {
        const [result] = await pool.query(
            'INSERT INTO registrations (nama, no_tel, no_ic, mpkk, jawatan) VALUES (?, ?, ?, ?, ?)',
            [nama, noTel, noIC, mpkk, jawatan]
        );

        const newEntry = {
            id: result.insertId,
            nama,
            noTel,
            noIC,
            mpkk,
            jawatan,
            timestamp: new Date().toLocaleString()
        };

        res.status(201).json(newEntry);
    } catch (error) {
        console.error(error);
        res.status(500).json({ error: 'Database error' });
    }
});

// Serve index.html for any other routes (SPA)
app.get(/.*/, (req, res) => {
    res.sendFile(path.join(__dirname, 'dist', 'index.html'));
});

app.listen(PORT, () => {
    console.log(`Server running on port ${PORT}`);
});
