<?php
	$teste = array('($dre["beta"]["52"] + $dre["beta"]["53"])', '$dre["beta"]["53"]-$dre["beta"]["52"]');

	foreach ($teste as $value) {
		$tokens[] = mb_str_split($value);
	}

	//sinais
	const INICIO = "$";
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
	$aguarda_fim = false;
	$aguarda_fechar_aspas = false; //ok
	$aguarda_fechar_chaves = false; //ok
	$aguarda_fechar_parenteses = false; //ok

	foreach ($tokens as $tk) {
		$n = count($tk);
		
		for ($i = 0; $i < $n; $i++) {

			if ($tk[$i] == " " || $tk[$i] == "  ")
				continue;

			//if ($tk[$i] == ABRE_PAREN)
			//	continue;

			/*
			 * Validações de caracteres não alfanuméricos
			 */
			if ($tk[$i] == FECHA_PAREN && $aguarda_fechar_parenteses)
				$aguarda_fechar_parenteses = false;
				
			if ($tk[$i] == ABRE_PAREN && !$aguarda_fechar_parenteses)
				$aguarda_fechar_parenteses = true;

			if (($tk[$i] == INDICA_STR || $tk[$i] == INDICA_STR_1) && $aguarda_fechar_aspas)
				$aguarda_fechar_aspas = false;
				
			if (($tk[$i] == INDICA_STR || $tk[$i] == INDICA_STR_1) && !$aguarda_fechar_aspas)
				$aguarda_fechar_aspas = true;

			if ($tk[$i] == FECHA_CHAVE && $aguarda_fechar_chaves)
				$aguarda_fechar_chaves = false;

			if ($tk[$i] == ABRE_CHAVE && !$aguarda_fechar_chaves)
				$aguarda_fechar_chaves = true;

			if ($aguarda_fim && $tk[$i] == INICIO && strlen($posicao_completa) > 1)  {
				$aguarda_fim = false;

				$posicao_completa = rtrim($posicao_completa, ADD);
				$posicao_completa = rtrim($posicao_completa, DM);
				$posicao_completa = rtrim($posicao_completa, MULT);
				$posicao_completa = rtrim($posicao_completa, DIV);

				printWhatWeHave($posicao_completa, $dre);
				exit;
			}
				
			if ($tk[$i] == INICIO && !$aguarda_fim)
				$aguarda_fim = true;

			$posicao_completa .= $tk[$i];
		}
	}

	function printWhatWeHave($str_var, $dre)
	{
		$result = eval('return '. $str_var . ';'); //echo ' -> '; echo $dre["beta"]["52"];
		
		echo $result;
	}
	
	