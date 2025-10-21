<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Teste Contador</title>
</head>
<body>
  <h1>Contador até o sorteio</h1>
  <div id="timer">
    <span id="dias">00</span> dias :
    <span id="horas">00</span> horas :
    <span id="minutos">00</span> minutos :
    <span id="segundos">00</span> segundos
  </div>

  <script>
    document.addEventListener("DOMContentLoaded", function () {
      const dataSorteio = new Date("2025-12-24T00:00:00");

      function atualizarContador() {
        const agora = new Date();
        const diff = dataSorteio - agora;

        if (diff <= 0) {
          document.getElementById("timer").textContent = "🎉 O sorteio começou!";
          return;
        }

        const dias = Math.floor(diff / (1000 * 60 * 60 * 24));
        const horas = Math.floor((diff / (1000 * 60 * 60)) % 24);
        const minutos = Math.floor((diff / (1000 * 60)) % 60);
        const segundos = Math.floor((diff / 1000) % 60);

        document.getElementById("dias").textContent = dias.toString().padStart(2, "0");
        document.getElementById("horas").textContent = horas.toString().padStart(2, "0");
        document.getElementById("minutos").textContent = minutos.toString().padStart(2, "0");
        document.getElementById("segundos").textContent = segundos.toString().padStart(2, "0");
      }

      atualizarContador();
      setInterval(atualizarContador, 1000);
    });
  </script>
</body>
</html>