if (document.getElementById('filmovi-tablica')) {

  let sviFilmovi = [];
  let kosarica = [];

  fetch('./movies.csv')
    .then(res => res.text())
    .then(csv => {
      const rezultat = Papa.parse(csv, {
        header: true,
        skipEmptyLines: true
      });

      sviFilmovi = rezultat.data.map((film, index) => ({
        id: index + 1,
        naslov: film.Naslov,
        zanr: film.Zanr,
        godina: Number(film.Godina),
        trajanje: Number(film.Trajanje_min),
        ocjena: Number(film.Ocjena),
        reziser: film.Rezisery,
        zemlja: film.Zemlja_porijekla
      }));

      prikaziTablicu(sviFilmovi);
    });

  function prikaziTablicu(filmovi) {
    const tbody = document.querySelector('#filmovi-tablica tbody');
    tbody.innerHTML = '';

    filmovi.forEach(film => {
      const row = document.createElement('tr');

      row.innerHTML = `
        <td>${film.id}</td>
        <td>${film.naslov}</td>
        <td>${film.godina}</td>
        <td>${film.zanr}</td>
        <td>${film.trajanje} min</td>
        <td>${film.zemlja}</td>
        <td>${film.ocjena}</td>
        <td><button>Dodaj u košaricu</button></td>
      `;

      row.querySelector('button').addEventListener('click', () => dodajUKosaricu(film));
      tbody.appendChild(row);
    });
  }

  function filtriraj() {
    const naslov = document.getElementById('filter-naslov').value.toLowerCase();
    const zanr = document.getElementById('filter-zanr').value.toLowerCase();
    const godina = Number(document.getElementById('filter-godina').value);
    const ocjena = Number(document.getElementById('filter-ocjena').value);

    const filtrirani = sviFilmovi.filter(film =>
      film.naslov.toLowerCase().includes(naslov) &&
      (!zanr || film.zanr.toLowerCase().includes(zanr)) &&
      (!godina || film.godina >= godina) &&
      film.ocjena >= ocjena
    );

    prikaziTablicu(filtrirani);
  }

  document.getElementById('primijeni-filtere').addEventListener('click', filtriraj);

  document.getElementById('filter-ocjena').addEventListener('input', e => {
    document.getElementById('ocjena-value').textContent = e.target.value;
  });

  function dodajUKosaricu(film) {
    if (kosarica.some(f => f.id === film.id)) {
      alert('Film je već u košarici!');
      return;
    }

    kosarica.push(film);
    osvjeziKosaricu();
  }

  function osvjeziKosaricu() {
    const lista = document.getElementById('lista-kosarice');
    lista.innerHTML = '';

    kosarica.forEach((film, index) => {
      const li = document.createElement('li');
      li.innerHTML = `${film.naslov} (${film.godina}) <button>Ukloni</button>`;

      li.querySelector('button').addEventListener('click', () => {
        kosarica.splice(index, 1);
        osvjeziKosaricu();
      });

      lista.appendChild(li);
    });
  }

  document.getElementById('potvrdi-kosaricu').addEventListener('click', () => {
    if (kosarica.length === 0) {
      document.getElementById('poruka-kosarice').textContent = 'Košarica je prazna!';
      return;
    }

    document.getElementById('poruka-kosarice').textContent =
      `Uspješno ste dodali ${kosarica.length} filmova u svoju košaricu za vikend maraton!`;
    kosarica = [];
    osvjeziKosaricu();
  });

}