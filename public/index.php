<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sorteio Beneficente - IPR São Miguel Paulista</title>
  <link rel="stylesheet" href="css/style.css">
  <link rel="icon" href="https://i.imgur.com/MfoSWRz.png" type="image/png">
</head>
<body>
  <header>
    <div class="logos">
      <img src="https://i.imgur.com/P763ZTp.png" alt="Logo IPR São Miguel" class="logo-ipr">
      <img src="https://i.imgur.com/MfoSWRz.png" alt="Logo IPR Kids" class="logo-kids">
    </div>
    <h1>Sorteio Beneficente</h1>
    <p>Participe do sorteio beneficente do IPR Kids e ajude a transformar as salas do Ministério Infantil em um espaço ainda mais acolhedor e cheio de amor. ❤️ <br>Cada cota é um gesto de fé que nos aproxima desse sonho! 🙏🏽</p>
  </header>

  <section class="banner">
    <h2>✨ Participe e abençoe nossa Obra! ✨</h2>
  </section>

  <section class="rifa">
    <h2>🎟️ Participar é simples e faz o bem!</h2>
    <p>Escolha a cota que quiser, preencha seus dados e faça o pagamento rapidinho por Pix ou cartão de crédito.<br>
    Assim que o pagamento for confirmado, você recebe seus números da sorte e já pode começar a torcer! 🙌🏾</p>

    <div id="contador">
      <h3>⏳ Faltam:</h3>
      <div id="timer">
        <span id="dias">00</span>dias :
        <span id="horas">00</span>horas :
        <span id="minutos">00</span>minutos :
        <span id="segundos">00</span>segundos
      </div>
    </div>

    <div class="opcoes-cotas">
      <button onclick="comprarCota(10, 20)">Cota 10 números - R$ 20,00</button>
      <button onclick="comprarCota(20, 30)">Cota 20 números - R$ 30,00</button>
      <button onclick="comprarCota(30, 50)">Cota 30 números - R$ 50,00</button>
      <button onclick="comprarCota(70, 100)">Cota 70 números - R$ 100,00</button>
    </div>

    <!-- Pop-up de dados -->
    <div id="popup" class="popup" style="display:none;">
      <div class="popup-content">
        <h3>Preencha seus dados</h3>
        <label for="nome">Nome completo</label>
        <input type="text" id="nome" placeholder="Seu nome completo" required>
        <label for="telefone">Telefone com DDD</label>
        <input type="tel" id="telefone" placeholder="Seu telefone com DDD" required>
        <div class="popup-botoes">
          <button id="confirmarBtn" type="button">Confirmar</button>
          <button id="cancelarBtn" type="button">Cancelar</button>
        </div>
      </div>
    </div>

    <!-- Pop-up de termos -->
    <div id="popup-termos" class="popup" style="display:none;">
      <div class="popup-content">
        <h3>Termos de participação do Sorteio</h3>
        <p>🏆 <strong>Prêmios</strong><br>Os prêmios oferecidos serão descritos na página oficial, com suas respectivas fotos e descrições. Cada prêmio será entregue exatamente conforme anunciado, sem substituições ou trocas.</p>
        <p>🗓️ <strong>Data e Local do Sorteio</strong><br>O sorteio será realizado no dia 24 de dezembro de 2025, durante o Culto de Cantata de Natal na Igreja Presbiteriana Renovada de São Miguel Paulista (IPRSM). 🎄✨<br>O culto será aberto ao público e transmitido on-line pelas redes sociais da igreja.</p>
        <p>📦 <strong>Entrega e Retirada dos Prêmios</strong><br>Os prêmios estarão disponíveis para retirada presencial na IPRSM, a partir do dia do sorteio, na Rua Avinhado, 222 – São Miguel Paulista, São Paulo – SP. O envio, caso solicitado, será por conta do ganhador.</p>
        <p>✅ <strong>Validação e Responsabilidade</strong><br>A confirmação do pagamento garante a participação. É de responsabilidade do participante o correto preenchimento dos dados pessoais.</p>
        <p>📣 <strong>Divulgação dos Resultados</strong><br>Os resultados serão divulgados nas redes sociais da IPRSM e diretamente aos ganhadores.</p>
        <label><input type="checkbox" id="aceito-termos"> Eu aceito os termos</label>
        <div class="popup-botoes">
          <button id="aceitarTermosBtn" type="button" disabled>Aceitar e pagar</button>
        </div>
      </div>
    </div>

    <p class="info-sorteio">
      📅 Sorteio: 24 de Dezembro de 2025<br>
      📍 Local: R. Avinhado, 222 - Vila Nova Curuca, São Paulo - SP
    </p>
  </section>

  <main>
    <section class="premios">
      <h2>🎁 Prêmios</h2>
      <div class="cards">
        <div class="card"> <img src="https://i.imgur.com/EGRgT48.png" alt="Moto Elétrica"> <img src="https://i.imgur.com/t2Qz4Et.png" alt="Smart TV 50"> <h3>🏍️ 1º Sorteado</h3> <p><strong>Moto Elétrica + Smart TV 50"</strong><br> <small>(Imagens meramente ilustrativas)</small><br> Uma incrível moto elétrica ecológica e moderna, acompanhada de uma Smart TV 50" 4K para você curtir seus momentos com estilo!</p> </div> <div class="card"> <img src="https://i.imgur.com/u2YFdu0.png" alt="Smartphone Motorola G24"> <h3>📱 2º Sorteado</h3> <p><strong>Smartphone Motorola G24</strong><br> <small>(Imagens meramente ilustrativas)</small><br> Desempenho, câmera de qualidade e bateria duradoura — perfeito para o seu dia a dia!</p> </div> <div class="card"> <img src="https://i.imgur.com/VJeVZj2.png" alt="Microondas"> <h3>🍲 3º Sorteado</h3> <p><strong>Microondas</strong><br> <small>(Imagens meramente ilustrativas)</small><br> Facilidade e agilidade na sua cozinha — ideal para o dia a dia e para quem busca mais conforto no lar.</p> </div> <div class="card"> <img src="https://i.imgur.com/93GdIG3.png" alt="Liquidificador"> <h3>🍹 4º Sorteado</h3> <p><strong>Liquidificador</strong><br> <small>(Imagens meramente ilustrativas)</small><br> Prepare sucos, vitaminas e receitas deliciosas com rapidez e praticidade. Um aliado indispensável na sua cozinha!</p> </div> <div class="card"> <img src="https://i.imgur.com/ffmCXC5.png" alt="Voucher de compra Wearever"> <h3>💳 5º Sorteado</h3> <p><strong>Voucher de R$ 200,00</strong><br> <small>(Imagens meramente ilustrativas)</small><br> Utilize seu vale-compras na loja <a href="https://www.wearever.com.br/" target="_blank">Wearever</a> e escolha o que desejar!</p> </div> <div class="card"> <img src="https://i.imgur.com/To24doC.png" alt="Saída de Maternidade Baby Mary"> <h3>👶 6º Sorteado</h3> <p><strong>Saída de Maternidade Baby Mary</strong><br> <small>(Imagens meramente ilustrativas)</small><br> Um lindo e delicado conjunto para celebrar a chegada do seu bebê com muito carinho e estilo.</p> </div> <div class="card"> <img src="https://i.imgur.com/PRFXGbt.png" alt="Kit Felicidade"> <h3>🎂 7º Sorteado</h3> <p><strong>Kit Felicidade</strong><br> <small>(Imagens meramente ilustrativas)</small><br> Bombom no pote: morango, brigadeiro de ninho cremoso e chocolate<br> Coxinha de morango com nutella<br> Bombom de morango com brigadeiro de ninho e chocolate<br> Mini bolo vulcão de brigadeiro</p> </div> <div class="card"> <img src="https://i.imgur.com/KuQ8LUE.png" alt="Playstation 5"> <h3>🎮 8º Sorteado</h3> <p><strong>Playstation 5</strong><br> <small>(Imagens meramente ilustrativas)</small><br> Momentos de pura diversão e lazer em família! Com o PlayStation 5, cada jogo se transforma em uma experiência incrível, cheia de risadas, desafios e muita emoção para todas as idades.</p> </div> <div class="card"> <!--img src="https://i.imgur.com/t2Qz4Et.png" alt="Smart TV 50"> <h3>📺 9º Sorteado</h3> <p><strong>Smart TV 50"</strong><br> <small>(Imagens meramente ilustrativas)</small><br> Alta definição, conectividade e qualidade de imagem para transformar sua sala em um verdadeiro cinema!</p--> 
        </div>
        </div>
      </section>
      </div>
    </section>
  </main>

  <footer>
    <p>&copy; Sorteio Beneficente. Desenvolvido pela IPR de São Miguel Paulista. Todos os direitos reservados.</p>
  </footer>

  <a href="https://wa.me/5511979654420?text=Olá! Quero participar do sorteio beneficente da IPR São Miguel Paulista!" class="whatsapp" target="_blank" aria-label="WhatsApp">💬</a>

  <script src="js/script.js"></script>
  <script src="js/sorteio.js"></script>
</body>
</html>