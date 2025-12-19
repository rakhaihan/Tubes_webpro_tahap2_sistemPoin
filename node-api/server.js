import express from 'express';
import cors from 'cors';
import dotenv from 'dotenv';
import pelanggaranRouter from './routes/pelanggaran.js';

dotenv.config();
const app = express();
app.use(cors());
app.use(express.json());

app.get('/api/ping', (req, res) => res.json({ pong: true }));
app.use('/api/pelanggaran', pelanggaranRouter);

const port = process.env.PORT || 3001;
app.listen(port, () => console.log(`Node API listening on ${port}`));
