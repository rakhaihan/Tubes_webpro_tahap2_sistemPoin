import express from 'express';
const router = express.Router();

let sample = [
  { id: 1, siswa: 'Budi', jenis: 'Ringan', poin: 1 },
  { id: 2, siswa: 'Siti', jenis: 'Sedang', poin: 3 }
];

// List all
router.get('/', (req, res) => res.json(sample));

// Get by id
router.get('/:id', (req, res) => {
  const id = parseInt(req.params.id);
  const item = sample.find((i) => i.id === id);
  if (!item) return res.status(404).json({ error: 'Not found' });
  res.json(item);
});

// Create
router.post('/', (req, res) => {
  const { siswa, jenis, poin } = req.body;
  if (!siswa || !jenis || typeof poin !== 'number') {
    return res
      .status(400)
      .json({ error: 'Invalid payload. Required: siswa (string), jenis (string), poin (number)' });
  }
  const id = sample.length ? Math.max(...sample.map((i) => i.id)) + 1 : 1;
  const newItem = { id, siswa, jenis, poin };
  sample.push(newItem);
  res.status(201).json(newItem);
});

// Update
router.put('/:id', (req, res) => {
  const id = parseInt(req.params.id);
  const item = sample.find((i) => i.id === id);
  if (!item) return res.status(404).json({ error: 'Not found' });
  const { siswa, jenis, poin } = req.body;
  if (siswa !== undefined) item.siswa = siswa;
  if (jenis !== undefined) item.jenis = jenis;
  if (poin !== undefined) item.poin = poin;
  res.json(item);
});

// Delete
router.delete('/:id', (req, res) => {
  const id = parseInt(req.params.id);
  const idx = sample.findIndex((i) => i.id === id);
  if (idx === -1) return res.status(404).json({ error: 'Not found' });
  sample.splice(idx, 1);
  res.status(204).end();
});

export default router;
