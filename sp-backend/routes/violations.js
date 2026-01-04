const router = require('express').Router();
const db = require('../db');

router.get('/', (req, res) => {
  db.query('SELECT * FROM violations', (err, results) => {
    if (err) return res.status(500).json({ message: err.message });
    res.json(results);
  });
});

router.post('/', (req, res) => {
  const { name, points } = req.body;

  db.query(
    'INSERT INTO violations (name, points) VALUES (?, ?)',
    [name, points],
    err => {
      if (err) return res.status(500).json({ message: err.message });
      res.json({ message: 'Violation added' });
    }
  );
});

module.exports = router;
