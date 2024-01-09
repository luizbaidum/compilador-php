<?php
	$teste = array('($dre ["beta"][52] + $dre["beta"]["53"])', '$dre["beta"][53]-$dre["beta"]["52"]');
	$tokens = array();

	foreach ($teste as $value)
		$tokens[] = str_split($value, 1);

	echo '<pre>';
	print_r($tokens);

	//sinais
	const CIFRAO = "$";
	const ABRE_CHAVE = "[";
	const FECHA_CHAVE = "]";
	const INDICA_STR = "'";
	const INDICA_STR_1 = '"';
	const ADD = "+";
	const DM = "-";
	const MULT = "*";
	const DIV = "/";
	const ABRE_PAREN = "(";
	const FECHA_PAREN =")";

	$dre['beta']['52'] = 10;
	$dre["beta"][53] = 20;

	$result = "";
	$posicao_completa = "";
	$posicao_indice = array();
	$confirmacoes = [
		"aguarda_fim" 				=> false,
		"aguarda_fechar_aspas" 		=> false,
		"aguarda_fechar_chaves" 	=> false,
		"aguarda_fechar_parenteses" => false
	];

	foreach ($tokens as $tk) {

		$n = count($tk);
		
		for ($i = 0; $i < $n; $i++) {

			if ($tk[$i] == " " || $tk[$i] == "  " || $tk[$i] == "")
				continue;

			if ($tk[$i] == CIFRAO)
				$confirmacoes["aguarda_fim"] = true;

			/*
			 * Validações de caracteres não alfanuméricos
			 */
			if ($tk[$i] == FECHA_PAREN/* && $aguarda_fechar_parenteses*/)
				$confirmacoes["aguarda_fechar_parenteses"] = false;
				
			if ($tk[$i] == ABRE_PAREN/* && !$aguarda_fechar_parenteses*/)
				$confirmacoes["aguarda_fechar_parenteses"] = true;

			if (($tk[$i] == INDICA_STR || $tk[$i] == INDICA_STR_1) && !$confirmacoes["aguarda_fechar_aspas"]) {
				$confirmacoes["aguarda_fechar_aspas"] = true;
			}

			if (($tk[$i] == INDICA_STR || $tk[$i] == INDICA_STR_1)/* && $aguarda_fechar_aspas*/)
				$confirmacoes["aguarda_fechar_aspas"] = false;

			if ($tk[$i] == FECHA_CHAVE /*&& $aguarda_fechar_chaves*/)
				$confirmacoes["aguarda_fechar_chaves"] = false;

			if ($tk[$i] == ABRE_CHAVE /*&& !$aguarda_fechar_chaves*/)
				$confirmacoes["aguarda_fechar_chaves"] = true;

			if ($confirmacoes["aguarda_fim"] && $tk[$i] == CIFRAO && strlen($posicao_completa) > 1)  {

				//$z = end($posicao_indice);
				if (isset($tk[$i]) && $tk[$i] == ADD || $tk[$i] == DM || $tk[$i] == MULT || $tk[$i] == DIV)
					$confirmacoes["aguarda_fim"] = false;
				if (isset($tk[$i]) && $tk[$i] == CIFRAO)
					$confirmacoes["aguarda_fim"] = false;

				VarValidation($confirmacoes, $posicao_completa, $dre);
				printWhatWeHave($posicao_completa, $dre);
			}

			$posicao_indice[$i] = $i;
			$posicao_completa .= $tk[$i];
		}
	}

	function printWhatWeHave($str_var, $dre)
	{
		var_dump($str_var);
	}

	function VarValidation($confirmations, $str_var, $dre)
	{
		$pendencias = array();

		foreach ($confirmations as $key => $value)
			if ($value == 1)
				$pendencias[] = $key;

		if (!empty($pendencias)) {
			echo "Pendências na construção da string: <br> <pre>";
			foreach ($pendencias as $value)
				echo $value . "<br>";
		}

		$clean_var = rtrim($str_var, ADD);
		$clean_var = rtrim($clean_var, DM);
		$clean_var = rtrim($clean_var, MULT);
		$clean_var = rtrim($clean_var, DIV);
		$clean_var = ltrim($clean_var, ABRE_PAREN);
		$clean_var = rtrim($clean_var, FECHA_PAREN);

		echo ' <br> ';
		var_dump($clean_var);

		$result = eval('return ' . $clean_var . ';');

		if (!isset($result))
			echo "Variável $clean_var não encontrada.";	
	}