const shows = [
  {
    nome: "Natiruts",
    data: new Date("2024-08-30"),
    opcao: "Pista Premium",
    link: "https://backstagemirante.com/natiruts/"
  },
  {
    nome: "Ivete Sangalo",
    data: new Date("2024-12-21"),
    opcao: "Pit PicPay A",
    link: "https://backstagemirante.com/ivete-sangalo/"
  }
];

function formatarData(stringData) {
  var partes = stringData.split(' ');
  var meses = {
    "JANEIRO": 0, "FEVEREIRO": 1, "MARÇO": 2, "ABRIL": 3, "MAIO": 4, "JUNHO": 5,
    "JULHO": 6, "AGOSTO": 7, "SETEMBRO": 8, "OUTUBRO": 9, "NOVEMBRO": 10, "DEZEMBRO": 11
  };
  var dia = parseInt(partes[0]);
  var mes = meses[partes[2].toUpperCase()];
  var ano = parseInt(partes[4]);
  var data = new Date(ano, mes, dia);
  return data;
}


function adicionarShowsOrdenados() {
  const listaEventos = document.querySelector('.list');

  shows.forEach(show => {
    const novoEvento = document.createElement('div');
    novoEvento.classList.add('ings-home-event-list-item');

    const conteudoEvento = `
          <div class="ings-home-event-list-item-show">
              <p class="data">${show.data.toLocaleDateString('pt-BR', { day: '2-digit', month: 'long', year: 'numeric' })}</p>
              <p class="show">${show.nome}</p>
          </div>
          <div class="ings-home-event-list-item-options">
              <ul>
              <li><i class="fa fa-check icon"></i>
                            <span class="text-icon">Acesso Exclusivo</span>
                        </li>
                        <li><i class="fa fa-check icon"></i>
                            <span class="text-icon">Open Bar & Open Food</span>
                        </li>
                        <li><i class="fa fa-check icon"></i>
                            <span class="text-icon">Banheiros Exclusivos</span>
                        </li>
                  <li><i class="fa fa-check icon"></i>
                      <span class="text-icon">${show.opcao}</span>
                  </li>
              </ul>
          </div>
          <div class="ings-home-event-list-buttom button-wrapper">
              <a class="button" href="${show.link}" target="_self">COMPRE AQUI <i class="fa fa-arrow-right"></i></a>
          </div>
      `;

    novoEvento.innerHTML = conteudoEvento;

    let inserido = false;
    const eventosExistentes = listaEventos.querySelectorAll('.ings-home-event-list-item');
    for (let i = 0; i < eventosExistentes.length; i++) {
      const dataExistente = formatarData(eventosExistentes[i].querySelector('.data').textContent);
      if (show.data < dataExistente) {
        listaEventos.insertBefore(novoEvento, eventosExistentes[i]);
        inserido = true;
        break;
      }
    }

    if (!inserido) {
      listaEventos.appendChild(novoEvento);
    }

    if (show.nome === "Ivete Sangalo") {
      const hr = document.createElement('hr');
      const paragrafo = document.createElement('p');
      paragrafo.classList.add('data');
      paragrafo.style.fontSize = '50px';
      paragrafo.textContent = '2025';
      paragrafo.style.marginLeft = '25px';

      listaEventos.insertBefore(hr, novoEvento.nextSibling);
      listaEventos.insertBefore(paragrafo, novoEvento.nextSibling);
    }
  });
}


adicionarShowsOrdenados();

function encontrarIndiceNatiruts() {
  const listaEventos = document.querySelector('.list');
  const eventos = listaEventos.querySelectorAll('.ings-home-event-list-item');
  
  for (let i = 0; i < eventos.length; i++) {
    const nomeEvento = eventos[i].querySelector('.show').textContent;
    if (nomeEvento.includes('Natiruts')) {
      return i + 1;
    }
  }

  return -1; 
}

const indiceNatiruts = encontrarIndiceNatiruts();





function alterarDataNatiruts(novaData,indice) {
  const listaEventos = document.querySelector('.list');
  const dataNatiruts = listaEventos.querySelector(`.ings-home-event-list-item:nth-child(${indice}) .data`);
  dataNatiruts.textContent = novaData;
}

const novaDataNatiruts = "29 e 30 de agosto de 2024";
alterarDataNatiruts(novaDataNatiruts,indiceNatiruts);
