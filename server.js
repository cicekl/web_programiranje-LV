const express = require('express');
const path = require('path');

const app = express();
const PORT = process.env.PORT || 3000;

app.set('view engine', 'ejs');
app.set('views', path.join(__dirname, 'views'));

app.use(express.static(path.join(__dirname, 'public')));

app.get('/', (req, res) => {
  res.sendFile(path.join(__dirname, 'public', 'index.html'));
});

app.get('/slike', (req, res) => {
  const images = Array.from({ length: 16 }, (_, index) => ({
    url: `https://unsplash.it/300/200?random=${index + 1}`,
    full: `https://unsplash.it/900/600?random=${index + 1}`,
    id: `img${index + 1}`,
    title: `Slika ${index + 1}`
  }));

  res.render('slike', { images });
});

app.listen(PORT, () => {
  console.log(`Server pokrenut na portu ${PORT}`);
});