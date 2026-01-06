
import { GoogleGenAI } from "@google/genai";

const ai = new GoogleGenAI({ apiKey: process.env.API_KEY });

export async function generateWelcomeNote(nama: string, mpkk: string, jawatan: string) {
  try {
    const response = await ai.models.generateContent({
      model: "gemini-3-flash-preview",
      contents: `Tulis satu ucapan aluan ringkas dan profesional (maksimum 30 patah perkataan) untuk ${nama} yang memegang jawatan ${jawatan} dari ${mpkk} kerana telah mendaftar untuk Perjumpaan MPKK. Gunakan nada yang mesra.`,
      config: {
        temperature: 0.7,
      }
    });
    return response.text;
  } catch (error) {
    console.error("Gemini Error:", error);
    return `Selamat datang, ${nama}! Terima kasih kerana mendaftar.`;
  }
}
