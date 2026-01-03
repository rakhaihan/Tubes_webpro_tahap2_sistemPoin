const router = require('express').Router();
const db = require('../db');

router.get('/', (req, res) => {
  db.query('SELECT * FROM students', (err, results) => {
    if (err) {
      return res.status(500).json({ message: err.message });
    }
    res.json(results);
  });
});

router.post('/', (req, res) => {
  const { name, class_name, nis, status } = req.body;

  const sql = `
    INSERT INTO students (name, class, nis, status)
    VALUES (?, ?, ?, ?)
  `;

  db.query(sql, [name, class_name, nis, status], (err, result) => {
    if (err) {
      return res.status(500).json({ message: err.message });
    }
    res.json({ message: 'Student added', id: result.insertId });
  });
});

router.put('/:id', (req, res) => {
  const { name, class_name, nis, status } = req.body;

  const sql = `
    UPDATE students
    SET name = ?, class = ?, nis = ?, status = ?
    WHERE id = ?
  `;

  db.query(sql, [name, class_name, nis, status, req.params.id], (err) => {
    if (err) {
      return res.status(500).json({ message: err.message });
    }
    res.json({ message: 'Student updated' });
  });
});

router.delete('/:id', (req, res) => {
  db.query('DELETE FROM students WHERE id = ?', [req.params.id], err => {
    if (err) {
      return res.status(500).json({ message: err.message });
    }
    res.json({ message: 'Student deleted' });
  });
});

module.exports = router;
