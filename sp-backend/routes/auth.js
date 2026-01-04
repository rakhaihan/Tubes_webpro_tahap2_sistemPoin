const router = require('express').Router();
const jwt = require('jsonwebtoken');

router.post('/login', (req, res) => {
  const { username, password } = req.body;

  if (password !== '1234') {
    return res.status(401).json({ message: 'Login gagal' });
  }

  let role = 'murid';
  if (username === 'admin') role = 'admin';
  if (username === 'guru') role = 'guru';

  const token = jwt.sign({ username, role }, 'SECRET_KEY', { expiresIn: '1d' });

  res.json({ token, role });
});

module.exports = router;
