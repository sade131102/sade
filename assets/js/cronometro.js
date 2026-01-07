let timer = null;
let inicio = null;
let segundos = 0;
let pausado = false;

function atualizarDisplay() {
  const h = String(Math.floor(segundos / 3600)).padStart(2, '0');
  const m = String(Math.floor((segundos % 3600) / 60)).padStart(2, '0');
  const s = String(segundos % 60).padStart(2, '0');
  document.getElementById('display').innerText = `${h}:${m}:${s}`;
}

function iniciar() {
  const atividade = document.getElementById('atividade');
  if (!atividade.value) {
    alert('Selecione uma atividade');
    return;
  }

  if (timer !== null) return;

  inicio = new Date();
  segundos = 0;
  pausado = false;
  atualizarDisplay();

  timer = setInterval(() => {
    if (!pausado) {
      segundos++;
      atualizarDisplay();
    }
  }, 1000);

  document.getElementById('btnPausar').disabled = false;
  document.getElementById('btnParar').disabled = false;
}

function pausar() {
  pausado = !pausado;
  document.getElementById('btnPausar').innerText = pausado ? 'Retomar' : 'Pausar';
}

function parar() {
  if (timer === null) return;

  clearInterval(timer);
  timer = null;

  const TEMPO_MINIMO = 300; // 5 minutos

  if (segundos < TEMPO_MINIMO) {
    alert('O estudo não foi salvo porque teve menos de 5 minutos.');
    segundos = 0;
    atualizarDisplay();
    return;
  }

  const fim = new Date();
  const form = document.getElementById('formCronometro');
  const dados = new FormData(form);

  dados.append('hora_inicio', inicio.toTimeString().slice(0, 8));
  dados.append('hora_fim', fim.toTimeString().slice(0, 8));
  dados.append('data', fim.toISOString().slice(0, 10));

  fetch('salvar_estudo.php', {
    method: 'POST',
    body: dados
  })
  .then(r => r.json())
  .then(resp => {
    alert(resp.mensagem);
    location.reload();
  });
}
window.addEventListener('beforeunload', function () {
  if (timer === null) return;

  const TEMPO_MINIMO = 300; // 5 minutos
  if (segundos < TEMPO_MINIMO) return;

  const fim = new Date();
  const form = document.getElementById('formCronometro');
  const dados = new FormData(form);

  dados.append('hora_inicio', inicio.toTimeString().slice(0, 8));
  dados.append('hora_fim', fim.toTimeString().slice(0, 8));
  dados.append('data', fim.toISOString().slice(0, 10));

  navigator.sendBeacon('salvar_estudo.php', dados);
});


