Compilador que não usa eval para executar os cálculos passados.

A ideia é poder fazer com que uma string construída com variáveis php possa executar o cálculo uma vez que essas variáveis esetam definidas, separadamente, no arquivo.

É útil para situações onde uma fórmula é escrita, mas as variáveis dessa fórmula dependem de outros fatores para receber seu valor.

Exemplo:
Fórmula: ($dre["beta"]["52"] + $dre["beta"]["53"] + $dre["beta"]["52"])', '$dre["beta"]["53"]-($dre["beta"]["52"]*$dre["beta"]["53"])
Variáveis reais:
$dre["beta"][52] = 10;
$dre['beta']["53"] = 20;

O compilador consegue executar os cálculos:
10 + 20 + 10, e 
20 - (10 * 20)

A descrição sobre uso está no própria arquivo index.php como comentário.
