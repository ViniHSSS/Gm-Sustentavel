const perguntas = [
  { pergunta: "Qual o seu gênero?", opcoes: ["Masculino", "Feminino"] },
  { pergunta: "Você mora em uma zona urbana ou rural?", opcoes: ["Zona Urbana", "Zona Rural"] },
  { pergunta: "Quantas pessoas moram com você?", opcoes: ["Moro sozinho", "Moro com uma pessoa", "Moro com duas pessoas", "Moro com três ou mais"] },
  { pergunta: "Qual a sua idade?", opcoes: ["Menor de 18 anos", "18-30 anos", "30-50 anos", "60 ou mais"] },
  { pergunta: "Qual é o seu nível de escolaridade no momento?", opcoes: ["Ensino Fundamental", "Ensino Médio", "Faculdade", "Não estou estudando"] },
  { pergunta: "No momento, você está estudando sobre um ensino público ou privado?", opcoes: ["Público", "Privado", "Educação em casa", "Não estudo no momento"] },
  { pergunta: "Você está trabalhando no momento?", opcoes: ["Sim", "Não"] },
  { pergunta: "Em lugares públicos, você utiliza as latas de coleta seletiva apropriadamente?", opcoes: ["Sempre", "Frequentemente", "De vez em quando", "Raramente", "Nunca"] },
  { pergunta: "Você reutiliza talheres, pratos, copos e garrafas descartáveis?", opcoes: ["Sempre", "Regularmente", "Ligeiramente", "Raramente", "Nunca"] },
  { pergunta: "Você troca de celular com que frequência?", opcoes: ["1 ano", "3 anos", "5 anos", "8 anos ou mais", "Nunca"] },
  { pergunta: "Você utiliza transportes públicos ou o seu próprio veículo?", opcoes: ["Transportes Públicos", "Veículo próprio", "Nenhum"] },
  { pergunta: "Você utiliza sacolas plásticas recicláveis quando realiza compras?", opcoes: ["Sim", "Não"] },
  { pergunta: "Com que frequência você come alimentos processados?", opcoes: ["Sempre", "Frequentemente", "De vez em quando", "Raramente", "Nunca"] },
  { pergunta: "Com que frequência você joga lixo em locais inadequados?", opcoes: ["Sempre", "Frequentemente", "De vez em quando", "Raramente", "Nunca"] },
  { pergunta: "Quanto tempo as luzes da sua casa passam acesas?", opcoes: ["O dia todo", "10-12 horas", "6-8 horas", "3-4 horas", "2 horas ou menos"] },
  { pergunta: "Com que frequência você toma banho?", opcoes: ["4 ou mais vezes", "3 vezes", "2 vezes", "1 vez"] },
  { pergunta: "Quanto tempo você usa eletrônicos diariamente?", opcoes: ["8 horas ou mais", "4-6 horas", "2-3 horas", "1 hora ou menos", "Nunca"] },
  { pergunta: "Quanto tempo você consome água diariamente?", opcoes: ["5 horas ou mais", "3-4 horas", "2 horas", "1 hora", "50 minutos ou menos"] },
  { pergunta: "Com que frequência você usa produtos de plástico?", opcoes: ["Frequentemente", "Regularmente", "De vez em quando", "Raramente", "Nunca"] },
  { pergunta: "Com que frequência você usa produtos biodegradáveis?", opcoes: ["Frequentemente", "Regularmente", "De vez em quando", "Raramente", "Nunca"] },
  { pergunta: "Você já praticou compostagem?", opcoes: ["Sim", "Não"] },
  { pergunta: "Você utiliza energia renovável?", opcoes: ["Sim", "Não", "Planejo utilizar", "Já utilizei no passado"] }
];

let respostas = [];
let indiceAtual = 0;

const startBtn = document.getElementById("startBtn");
const questionario = document.getElementById("questionario");

startBtn.addEventListener("click", () => {
  document.getElementById("intro").classList.add("hidden");
  mostrarPergunta();
});

function mostrarPergunta() {
  if (indiceAtual < perguntas.length) {
    const questao = perguntas[indiceAtual];
    let html = `<p>${questao.pergunta}</p><form id="formPergunta">`;

    questao.opcoes.forEach((opcao, idx) => {
      const id = `opcao${indiceAtual}_${idx}`; // ID único por pergunta
      html += `
        <label class="opcao-btn" for="${id}">
          <input type="radio" id="${id}" name="opcao" value="${opcao}" />
          ${opcao}
        </label>`;
    });

    html += `
      <button type="button" id="btnProximo" disabled>Próximo</button>
      </form>`;

    questionario.innerHTML = html;
    questionario.classList.remove("hidden");

    const form = document.getElementById("formPergunta");
    const btnProximo = document.getElementById("btnProximo");
    const inputs = form.querySelectorAll('input[name="opcao"]');

    inputs.forEach(input => {
      input.addEventListener("change", () => {
        btnProximo.disabled = false;
      });
    });

    btnProximo.addEventListener("click", () => {
      const selecionado = form.opcao.value;
      if (selecionado) {
        respostas.push(selecionado);
        indiceAtual++;
        mostrarPergunta();
      }
    });
  } else {
    enviarRespostas();
  }
}

function enviarRespostas() {
  console.log("Respostas enviadas:", respostas);

  fetch("php/process.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json"
    },
    body: JSON.stringify({ respostas })
  })
  .then(response => response.json())
  .then(data => {
    console.log("Resposta do servidor:", data);
    questionario.innerHTML = "<p>Obrigado por responder o questionário!</p>";
  })
  .catch(err => {
    console.error("Erro:", err);
    questionario.innerHTML = "<p>Erro ao enviar respostas. Tente novamente.</p>";
  });
}
