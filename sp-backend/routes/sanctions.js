const router = require('express').Router();
const db = require('../db');

router.get('/', (req, res) => {
  db.query('SELECT * FROM sanctions', (err, results) => {
    if (err) return res.status(500).json({ message: err.message });
    res.json(results);
  });
});

router.post('/', (req, res) => {
  const { name, min_point, max_point } = req.body;

  db.query(
    'INSERT INTO sanctions (name, min_point, max_point) VALUES (?, ?, ?)',
    [name, min_point, max_point],
    err => {
      if (err) return res.status(500).json({ message: err.message });
      res.json({ message: 'Sanction added' });
    }
  );
});

module.exports = router;
