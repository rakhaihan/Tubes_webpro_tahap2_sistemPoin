const express = require('express');
const cors = require('cors');

const app = express();
app.use(cors());
app.use(express.json());

app.use('/api/auth', require('./routes/auth'));
app.use('/api/students', require('./routes/students'));
app.use('/api/violations', require('./routes/violations'));
app.use('/api/sanctions', require('./routes/sanctions'));

app.listen(3000, () => {
  console.log('API running on http://localhost:3000');
});
