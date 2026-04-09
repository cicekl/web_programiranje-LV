const express = require('express');
const path = require('path');
const fs = require('fs');

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

app.get('/top10', (req, res) => {
  const files = fs.readdirSync(path.join(__dirname, 'public/images'));

  const images = files
    .filter(f => /\.(jpg|jpeg|png|webp)$/i.test(f))
    .slice(0, 12)
    .map((f, i) => ({
      url: `/images/${f}`,
      full: `/images/${f}`,
      id: `film${i + 1}`,
      title: `Slika ${i + 1}`,
    }));

  res.render('top10', { images });
});

app.listen(PORT, () => {
  console.log(`Server pokrenut na portu ${PORT}`);
});