<?php
	$teste = array('($dre["beta"]["52"] + $dre["beta"]["53"])', '$dre["beta"]["53"]-$dre["beta"]["52"]');

	foreach ($teste as $value)
		$tokens[] = mb_str_split($value);

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

	$dre["beta"]["52"] = 10;
	$dre["beta"]["53"] = 20;
	//saida -> [(10 + 20), 20 - 10];

	$result = "";
	$posicao_completa = "";
	$confirmacoes = [
		"aguarda_fim" 				=> false,
		"aguarda_fechar_aspas" 		=> false,
		"aguarda_fechar_chaves" 	=> false,
		"aguarda_fechar_parenteses" => false
	];

	foreach ($tokens as $tk) {
		$n = count($tk);
		
		for ($i = 0; $i < $n; $i++) {

			if ($tk[$i] == " " || $tk[$i] == "  " || $tk[$i] == "") {
				//echo $tk[$i];
				//echo gettype($tk[$i]);
				continue;
			}

			echo $i;

			//if ($tk[$i] == ABRE_PAREN)
			//	continue;

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

				//acessar $z vai dar no espaco em branco. Devo criar uma forma de verificar q o $z existe na string criada ou ignorar se $z for em branco.
				//possibilidade: criar array com $i e ai validar se $z existe na array criada com os $i's corretos.
	
				$z = $i - 1;
				if ($tk[$i] == " " || $tk[$i] == "  " || $tk[$i] == "") {
					$z = $i - 2;
				}

				if ($tk[$z] == ADD || $tk[$z] == DM || $tk[$z] == MULT || $tk[$z] == DIV) {
					$confirmacoes["aguarda_fim"] = false;
					//checkVariable($posicao_completa, $dre);
					//printWhatWeHave($posicao_completa, $dre);
				}

				/*$aguarda_fim = false;

				$posicao_completa = rtrim($posicao_completa, ADD);
				$posicao_completa = rtrim($posicao_completa, DM);
				$posicao_completa = rtrim($posicao_completa, MULT);
				$posicao_completa = rtrim($posicao_completa, DIV);*/

				checkVariable($confirmacoes, $posicao_completa, $dre);
				printWhatWeHave($posicao_completa, $dre);
			}
				
			/*if ($tk[$i] == CIFRAO && !$aguarda_fim)
				$aguarda_fim = true;*/

			$posicao_completa .= $tk[$i];
		}
	}

	function printWhatWeHave($str_var, $dre)
	{
		//$result = eval('return '. $str_var . ';'); //echo ' -> '; echo $dre["beta"]["52"];
		
		//echo '<hr>' . $str_var;

		exit;
	}

	function checkVariable($confirmations, $str_var, $dre)
	{
		/*echo '<pre>';
		print_r($confirmations);

		print_r($dre);

		$str_var = rtrim($str_var, ADD);
		$str_var = rtrim($str_var, DM);
		$str_var = rtrim($str_var, MULT);
		$str_var = rtrim($str_var, DIV);

		print_r($str_var);*/
	}
	
	