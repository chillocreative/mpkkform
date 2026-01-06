import mysql from 'mysql2/promise';
import dotenv from 'dotenv';
import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

dotenv.config();

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

async function setupDatabase() {
    // 1. Connect without Database to create it
    const connection = await mysql.createConnection({
        host: process.env.DB_HOST || 'localhost',
        user: process.env.DB_USER || 'root',
        password: process.env.DB_PASSWORD || ''
    });

    try {
        const dbName = process.env.DB_NAME || 'mpkkform';
        console.log(`Creating database '${dbName}' if not exists...`);

        await connection.query(`CREATE DATABASE IF NOT EXISTS \`${dbName}\``);
        console.log('Database created or already exists.');

        // 2. Connect to the database
        await connection.changeUser({ database: dbName });

        // 3. Read and execute schema.sql
        const schemaPath = path.join(__dirname, 'schema.sql');
        const schemaSql = fs.readFileSync(schemaPath, 'utf8');

        console.log('Executing schema.sql...');
        // Split by semicolon to handle multiple statements if any, though schema.sql usually has one table
        const statements = schemaSql.split(';').filter(stmt => stmt.trim().length > 0);

        for (const statement of statements) {
            await connection.query(statement);
        }

        console.log('Schema imported successfully.');

    } catch (error) {
        console.error('Error setting up database:', error);
    } finally {
        await connection.end();
    }
}

setupDatabase();
