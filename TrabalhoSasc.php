<?php 
DEFINE("MAX_TRANSICOES", 1000);
$max = 0;
$matrizIdnt = "";
$matrizCtmc = "";
$matrizDtmc = "";
$posicao = "";
$visitas = "";

$arquivo = fopen("ctmc.txt", "r");
if ($arquivo) {

    // Monta a Matriz CTMC
    while (($linha = fgets($arquivo)) !== false) {
        $valores = str_replace(",",".",$linha);
        $matrizCtmc[] = explode(" ",$valores);
    }
    fclose($arquivo);
    
    // Escolhe um estado inicial e inicia o contador de cada estado
    $posicao = mt_rand(0, (count($matrizCtmc)-1)); //Linha
    echo "-----------------------------------<br/>";
    echo "Estado Inicial: $posicao";
    for($estado = 0;$estado < count($matrizCtmc);$estado++){
        $visitas[$estado] = 0;
    }
    
    // Gera a Matriz Identidade e acha o max
    foreach($matrizCtmc as $linha => $arrayCtmc){
        $diagonalP = 0;
        foreach($arrayCtmc as $coluna => $valor){
            $matrizIdnt[$linha][$coluna] = 0;
            if($linha == $coluna){ 
                $matrizIdnt[$linha][$coluna] = 1; 
            }
            else{ 
                $diagonalP += $matrizCtmc[$linha][$coluna]; 
            }
        }
        if($max < $diagonalP){ 
            $max = $diagonalP; 
        }
        $matrizCtmc[$linha][$linha] = $diagonalP * (-1);
    }

    $max = $max * (-1);

    // Gera a Matriz DTMC
    foreach($matrizCtmc as $linha => $arrayCtmc){
        foreach($arrayCtmc as $coluna => $valor){
            $divisao = $valor/$max;
            $valorIdnt = $matrizIdnt[$linha][$coluna];
            $valorDtmc = $valorIdnt - $divisao;
            $matrizDtmc[$linha][$coluna] = number_format($valorDtmc, 5);
        }
    }

    // Gera os Resultados
    for($transicao = 0; $transicao < MAX_TRANSICOES; $transicao++){
        $u = floatval(mt_rand( 0, 100 ) / 100);
        $proximaPosicao = 0;
        $arrayAux = "";
        $arrayDtmc = $matrizDtmc[$posicao];
        
        $limiteAnterior = 0;
        foreach($arrayDtmc as $estado => $valor){
            $limiteNovo = $limiteAnterior + $valor;
            $arrayAux[$estado] = [$limiteAnterior, $limiteNovo];
            $limiteAnterior = $limiteNovo;
        }

        foreach($arrayAux as $estado => $limites){
            if(($u > $limites[0]) && ($u <= $limites[1])){
                $visitas[$estado] = $visitas[$estado] + 1;
                $posicao = $estado;
                break;
            }
        }

    }

    // Mostra os Resultados
    echo "<br/>-----------------------------------<br/>";
    echo "RESULTADO<br/>";
    foreach($visitas as $estado => $visita){
        $resultado = $visita/MAX_TRANSICOES;
        $resultado = number_format($resultado,4);
        echo "Estado $estado: $resultado<br />";
    }

    echo "<br/>-----------------------------------<br/>";
    echo "Transições: $transicao";

    echo "<br/>-----------------------------------<br/>";
    echo "CTMC<br/>";
    foreach($matrizCtmc as $linha => $arrayCtmc){
        echo "<span style='background-color: yellow'>$linha</span> | ";
        foreach($arrayCtmc as $coluna => $valor){
            echo $valor." | ";
        }
        echo "<br />";
    }
    echo "<br />";

    echo "<br/>-----------------------------------<br/>";
    echo "IDENTIDADE<br/>";
    foreach($matrizIdnt as $linha => $arrayIdnt){
        echo "<span style='background-color: yellow'>$linha</span> | ";
        foreach($arrayIdnt as $coluna => $valor){
            echo $valor." | ";
        }
        echo "<br />";
    }
    echo "<br />";

    echo "<br/>-----------------------------------<br/>";
    echo "DTMC<br/>";
    foreach($matrizDtmc as $linha => $arrayDtmc){
        echo "<span style='background-color: yellow'>$linha</span> | ";
        foreach($arrayDtmc as $coluna => $valor){
            echo $valor." | ";
        }
        echo "<br />";
    }
} else {
    echo "Sem arquivo";
} 
?>
