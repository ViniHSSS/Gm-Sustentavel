<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $respostas_json = $_POST['respostas'];
    $respostas = json_decode($respostas_json, true);

    // Array para armazenar a contagem de cada resposta por pergunta
    $contagem_respostas = [];
    for ($i = 0; $i < 15; $i++) {
        $contagem_respostas[$i] = [];
        // Inicializa a contagem para cada opção
        $num_opcoes = count([
            ["Sempre", "Frequentemente", "De vez em quando", "Raramente", "Nunca/Não há latas de coleta seletiva nos meus arredores"],
            ["Sempre", "Regularmente (Utilizo por um mês)", "Ligeiramente (Utilizo por algumas semanas)", "Raramente (Utilizo por alguns dias)", "Nunca (Descarto após o primeiro uso)"],
            ["De 1 em 1 anos ou menos", "De 3 em 3 anos ou mais", "De 5 em 5 anos ou mais", "De 8 em 8 anos mais", "Nunca/Não tenho celular"],
            ["Transportes Públicos", "Possuo um veículo próprio", "Utilizo ambos", "Utilizo nenhum/Vou a pé ou de bicicleta"],
            ["Sim", "Não", "De vez em quando", "Nunca/Não há sacolas plásticas aonde eu realizo minhas compras"],
            ["Sempre (Diariamente)", "Frequentemente (Três ou mais vezes por semana)", "De vez em quando (Uma ou duas vezes por semana)", "Raramente (Duas vezes por mês)", "Nunca"],
            ["Sempre", "Frequentemente (1 ou 2 vezes por semana)", "De vez em quando (1 vez por semana)", "Raramente (2 vezes por mês ou menos)", "Nunca"],
            ["O dia todo", "10-12 horas por dia", "6-8 horas por dia", "3-4 horas por dia", "2 horas por dia ou menos"],
            ["Frequentemente (4 ou mais vezes por dia)", "Regularmente (3 vezes por dia)", "De vez em quando (2 vezes por dia)", "Raramente (1 vez por dia)"],
            ["8 horas por dia ou mais", "4-6 horas por dia", "2-3 horas por dia", "1 hora por dia ou menos", "Nunca/Não há eletrônicos na minha moradia"],
            ["5 horas por dia ou mais", "3-4 horas por dia", "2 horas por dia", "1 hora por dia", "50 minutos ou menos por dia"],
            ["Frequentemente", "Regularmente", "De vez em quando", "Raramente", "Nunca/Evito o máximo que é possível"],
            ["Frequentemente", "Regularmente", "De vez em quando", "Raramente", "Nunca"],
            ["Sim", "Não", "De vez em quando", "Já pratiquei anteriormente mas hoje não"],
            ["Sim", "Não", "Não, porém planejo utilizar no futuro", "Não, porém já utilizei no passado"]
        ][$i]);
        for ($j = 0; $j < $num_opcoes; $j++) {
            $contagem_respostas[$i][$j] = 0;
        }
    }

    // Conta as respostas
    foreach ($respostas as $pergunta_index => $resposta_index) {
        if (isset($contagem_respostas[$pergunta_index][$resposta_index])) {
            $contagem_respostas[$pergunta_index][$resposta_index]++;
        }
    }

    // Gere o HTML com o gráfico (usando uma biblioteca de gráficos JS como Chart.js)
    echo "<div class='caixa'>";
    echo "<h2>Resultados da Pesquisa</h2>";
    echo "<canvas id='graficoRespostas'></canvas>";
    echo "</div>";

    // Inclua a biblioteca Chart.js
    echo '<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>';
    echo "<script>";
    echo "const ctx = document.getElementById('graficoRespostas').getContext('2d');";
    echo "const myChart = new Chart(ctx, {";
    echo "    type: 'bar',";
    echo "    data: {";
    echo "        labels: [";
    foreach ($perguntas as $index => $pergunta_data) {
        echo "'" . str_replace("?", "", substr($pergunta_data['pergunta'], 0,