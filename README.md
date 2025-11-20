Compilador que não utiliza eval para executar os cálculos fornecidos.

A ideia é possibilitar que uma string construída com variáveis PHP possa executar o cálculo após essas variáveis serem definidas separadamente no arquivo.

É útil para situações em que uma fórmula é definida, mas as variáveis dessa fórmula dependem de outros fatores para receber seus valores.

Exemplo:
Fórmula: '($dre["beta"]["52"] + $dre["beta"]["53"] + $dre["beta"]["52"])', '$dre["beta"]["53"]-($dre["beta"]["52"]*$dre["beta"]["53"])'
Variáveis reais:
$dre["beta"][52] = 10;
$dre['beta']["53"] = 20;

O compilador é capaz de executar os cálculos:
10 + 20 + 10, e
20 - (10 * 20)

A descrição completa sobre o uso está no próprio arquivo index.php como comentário.
