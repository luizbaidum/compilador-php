<?php
    // $teste deve ser alimentada com as strings que contenham as variáveis php (em string) que serão calculadas.
    // As variáveis, por sua vez, devem ser entituladas como dre, exemplo: $dre.
    // Se array as variáveis string também devem ser construídas obrigatóriamente com aspas duplas (").
    $teste = array('($dre["beta"]["52"] + $dre["beta"]["53"] + $dre["beta"]["52"])', '$dre["beta"]["53"]-($dre["beta"]["52"]*$dre["beta"]["53"])'); // Funciona
    // $teste = array('($dre[beta]["52"] + $dre["beta"]["53"]'); // Não funciona => Chave de array sem aspas duplas
    // $teste = array('($dre["beta"]["52"] + $dre["beta"][53])'); // Não funciona => Chave de array sem aspas duplas
    // $teste = array('$dre["beta"]["52"] + $dre["beta"]["53"]'); // Funciona
    // $teste = array('($dre["beta"]["52"] + $dre["beta"]["53"]'); // Não funciona => falta fechar parênteses

    // Aqui cada variável real deve ser instanciada com o seu respectivo valor.
    // As variáveis reais não precisam ter aspas duplas.
	$dre["beta"][52] = 10;
	$dre['beta']["53"] = 20;

	$resultados = array();

	function extrairVariaveis($expressao) {
		preg_match_all('/\$dre\["([^"]+)"\]\["([^"]+)"\]/', $expressao, $matches, PREG_SET_ORDER);
		return $matches;
	}

	function substituirVariaveis($expressao, $dre) {
		$variaveis = extrairVariaveis($expressao);

		foreach ($variaveis as $var) {
			$var_completa = $var[0]; // Ex: $dre["beta"]["52"]
			$chave1 = $var[1];       // Ex: beta
			$chave2 = $var[2];       // Ex: 52

			$valor = $dre[$chave1][$chave2];
			$expressao = str_replace($var_completa, $valor, $expressao);
		}

		return $expressao;
	}

	function calcularParenteses($expressao) {
		// Processa os parênteses mais internos primeiro
		while (strpos($expressao, '(') !== false) {
			$expressao = preg_replace_callback(
				'/\(([^()]+)\)/',
				function($matches) {
					return calcularExpressaoSimples($matches[1]);
				},
				$expressao
			);
		}
		return $expressao;
	}

	function calcularExpressaoSimples($expressao) {
		// Remove espaços
		$expressao = str_replace(' ', '', $expressao);

		// Primeiro processa multiplicação e divisão
		$expressao = preg_replace_callback(
			'/([\d.]+)([\*\/])([\d.]+)/',
			function($matches) {
				$num1 = floatval($matches[1]);
				$operador = $matches[2];
				$num2 = floatval($matches[3]);

				switch ($operador) {
					case '*': return $num1 * $num2;
					case '/': return $num2 != 0 ? $num1 / $num2 : 0;
				}
			},
			$expressao
		);

		// Depois processa adição e subtração
		preg_match_all('/([+\-]?[\d.]+)/', $expressao, $matches);
		$numeros = $matches[1];

		$resultado = 0;
		foreach ($numeros as $numero) {
			$resultado += floatval($numero);
		}

		return $resultado;
	}

	function calcularExpressao($expressao) {
		// Remove o $ do início se existir
		$expressao = ltrim($expressao, '$');

		// Primeiro processa os parênteses
		$expressao = calcularParenteses($expressao);

		// Depois calcula a expressão resultante
		return calcularExpressaoSimples($expressao);
	}

	foreach ($teste as $expressao) {
		// Substitui variáveis por valores
		$expressao_substituida = substituirVariaveis($expressao, $dre);

		// Calcula o resultado
		$resultado = calcularExpressao($expressao_substituida);
		$resultados[] = $resultado;

		echo "Expressão: $expressao <br>";
		echo "Substituída: $expressao_substituida <br>";
		echo "Resultado: $resultado <br><br>";
	}

	echo "Resultados finais: ";
	print_r($resultados);
?>