// 🧭 Função global para abrir o pop-up ao clicar em uma cota
function comprarCota(qtd, valor) {
  window.qtdSelecionada = qtd;
  window.valorSelecionado = valor;

  const popup = document.getElementById("popup");
  if (popup) popup.style.display = "flex";
}

document.addEventListener("DOMContentLoaded", function () {
  // ⏳ Contador regressivo até o sorteio
  const dataSorteio = new Date("2025-12-24T00:00:00");

  function atualizarContador() {
    const agora = new Date();
    const diff = dataSorteio - agora;

    const diasEl = document.getElementById("dias");
    const horasEl = document.getElementById("horas");
    const minutosEl = document.getElementById("minutos");
    const segundosEl = document.getElementById("segundos");
    const timerEl = document.getElementById("timer");

    if (!diasEl || !horasEl || !minutosEl || !segundosEl || !timerEl) return;

    if (diff <= 0) {
      timerEl.textContent = "🎉 O sorteio começou!";
      return;
    }

    const dias = Math.floor(diff / (1000 * 60 * 60 * 24));
    const horas = Math.floor((diff / (1000 * 60 * 60)) % 24);
    const minutos = Math.floor((diff / (1000 * 60)) % 60);
    const segundos = Math.floor((diff / 1000) % 60);

    diasEl.textContent = dias.toString().padStart(2, "0");
    horasEl.textContent = horas.toString().padStart(2, "0");
    minutosEl.textContent = minutos.toString().padStart(2, "0");
    segundosEl.textContent = segundos.toString().padStart(2, "0");
  }

  atualizarContador();
  setInterval(atualizarContador, 1000);

  // 🎟️ Elementos de interface
  const confirmarBtn = document.getElementById("confirmarBtn");
  const cancelarBtn = document.getElementById("cancelarBtn");
  const aceitarTermosBtn = document.getElementById("aceitarTermosBtn");
  const checkboxTermos = document.getElementById("aceito-termos");
  const nomeInput = document.getElementById("nome");
  const telefoneInput = document.getElementById("telefone");
  const popup = document.getElementById("popup");
  const popupTermos = document.getElementById("popup-termos");

  // 📝 Coleta de dados
  if (confirmarBtn && nomeInput && telefoneInput && popup && popupTermos) {
    confirmarBtn.onclick = function () {
      const nome = nomeInput.value.trim();
      const telefone = telefoneInput.value.trim().replace(/\D/g, "");
      const qtd = window.qtdSelecionada || 1;
      const valor = window.valorSelecionado || 10;

      if (!nome || !telefone) {
        alert("Por favor, preencha seu nome e telefone.");
        return;
      }

      popup.style.display = "none";
      popupTermos.style.display = "flex";

      window.dadosPagamento = { nome, telefone, qtd, valor };
      console.log("Dados preparados para envio:", window.dadosPagamento);
    };
  }

  // ❌ Cancelar pop-up
  if (cancelarBtn && popup) {
    cancelarBtn.onclick = function () {
      popup.style.display = "none";
    };
  }

  // ✅ Aceitar termos e enviar
  if (checkboxTermos && aceitarTermosBtn && popupTermos) {
    checkboxTermos.onchange = function () {
      aceitarTermosBtn.disabled = !this.checked;
    };

    aceitarTermosBtn.onclick = function () {
      popupTermos.style.display = "none";

      const dados = window.dadosPagamento;
      if (!dados || !dados.nome || !dados.telefone || !dados.qtd || !dados.valor) {
        alert("Dados de pagamento ausentes ou incompletos.");
        return;
      }

      // 🔄 Feedback visual opcional
      console.log("Enviando dados para o servidor...");

      fetch("criar_pagamento.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(dados)
      })
      .then(async res => {
        const text = await res.text();

        if (!res.ok) {
          console.error("Erro HTTP:", res.status);
          throw new Error(`Erro HTTP ${res.status}`);
        }

        try {
          const resposta = JSON.parse(text);
          if (resposta.error) throw new Error(resposta.error);

          console.log("Resposta recebida:", resposta);
          const telefone = dados.telefone || "desconhecido";
          window.location.href = `pagamento_concluido.php?telefone=${telefone}`;
        } catch (e) {
          alert("Erro ao interpretar resposta do servidor.");
          console.error("Erro:", e.message);
          console.warn("Resposta bruta recebida:", text);
        }
      })
      .catch(err => {
        alert("Erro de rede ou servidor fora do ar.");
        console.error("Erro de conexão:", err);
      });
    };
  }
});