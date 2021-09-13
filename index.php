<?php 
$arquivo = fopen("gerint_solicitacoes_mod.csv", "r"); // abre o arquivo CSV
$quant_homens = 0;
$quant_mulheres = 0;                        // Variaveis de contagem de pascientes e seus generos e idades
$idade_homens = 0;
$idade_mulheres = 0;                           
$municipio = $_POST["municipio"];
$solicitante = $_POST["solicitante"];      
$ano_um = 0;
$ano_dois = 0;
$ano_tres = 0;                    // Contagem dos anos, ano_um = 2018, ano_dois = 2019, ano_tres = 2020, ano_quatro = 2021
$ano_quatro = 0;
?>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
        <title>Registro de Internacoes</title>
</head>
<body>
    <?php 
    echo "<div class='card-header'><h1>Registro de Internacoes do Estado do Rio Grande do Sul</h1><br>
    </div>"; echo "<h2>Municipio de " . $municipio . "</h2><br>";
    while(!feof($arquivo)){
        $linhas = fgetcsv($arquivo, 1000, ";");
        $num = count($linhas);                                // Percorrendo o documento até o final
        for($i=0; $i < $num; $i++){
            if($linhas[$i] == $municipio){    // Teste com o nome do municipio informado 
                           
                if($linhas[$i-2] == "MASCULINO"){
                    $quant_homens++;
                    $idade_homens += $linhas[$i-1];       // Pega o genero e a idade do paciente
                }
                if($linhas[$i-2] == "FEMININO"){
                    $quant_mulheres++;
                    $idade_mulheres += $linhas[$i-1];
                }
                if($linhas[$i+8] >= "2018-01-01" && $linhas[$i+8] <= "2018-12-31"){
                    $ano_um++;
                }
                if($linhas[$i+8] >= "2019-01-01" && $linhas[$i+8] <= "2019-12-31"){      // Teste para pegar o ano de internação
                    $ano_dois++;
                }
                if($linhas[$i+8] >= "2020-01-01" && $linhas[$i+8] <= "2020-12-31"){
                    $ano_tres++;
                }
                if($linhas[$i+8] >= "2021-01-01" && $linhas[$i+8] <= "2021-12-31"){
                    $ano_quatro++;
                }
    
            }
            if($linhas[$i] == $solicitante  && $linhas[$i-1] == $municipio){
                $data_internacao = new DateTime($linhas[$i+7]);
                $data_alta = new DateTime($linhas[$i+8]);
                $tempo = $data_internacao->diff($data_alta);
                echo "<div class='form-control'><li>Idade do Paciente " . $linhas[$i-2] . "</li>
                <li>Municipio Residencial " . $linhas[$i-1] . "</li>
                <li>Municipio Solicitante " . $linhas[$i+1] . "</li>                         
                <li>Data de Autorizacao " . $linhas[$i+6] . "</li>
                <li>Data de Internacao " . $data_internacao->format("Y-m-d") . "</li>
                <li>Data de Alta " . $data_alta->format("Y-m-d") . "</li>
                <li>Executante " . $linhas[$i+9] . "</li>
                <li>Tempo de Internacao " . $tempo->days . " dias</li></div><br>";             // Dados do paciente em formato de tabela
                
                
            }
            
        }
    }
    $count = $quant_homens+$quant_mulheres;
    $media_homens = $idade_homens/$quant_homens;
    $media_mulheres = $idade_mulheres/$quant_mulheres;   // Calculo das medias de idade total e por Genero
    $media_total = ($media_homens+$media_mulheres)/2;
    fclose($arquivo);
    
    
    
    
    echo "<li>Numero de Pacientes " . $count . "</li>";
    if($quant_homens>0){
    echo "<li>Numero de Homens ". $quant_homens . "</li>";
    echo "<li>Media de Idade dos Homens Internados ". number_format($media_homens,2) . "</li>";
    } 
    if($quant_mulheres>0){
    echo "<li>Numero de Mulheres ". $quant_mulheres . "</li>";
    echo "<li>Media de Idade das Mulheres Internadas ". number_format($media_mulheres,2) . "</li>";
    } 
    if($quant_mulheres>0 && $quant_homens>0){
    echo "<li>Media total dos Pacientes " . number_format($media_total,2) . "</li>" ; 
    } else if($quant_mulheres==0){
       echo "<li>Media de Idade dos Homens Internados ". number_format($media_homens,2) . "</li>";}
     else if($quant_homens==0){
        echo "<li>Media de Idade das Mulheres Internadas ". number_format($media_mulheres,2) . "</li>";

     }  
     echo "<li>Pacientes Internados no ano de 2018: " . $ano_um . "</li>";
     echo "<li>Pacientes Internados no ano de 2019: " . $ano_dois . "</li>";
     echo "<li>Pacientes Internados no ano de 2020: " . $ano_tres . "</li>";
     echo "<li>Pacientes Internados no ano de 2021: " . $ano_quatro . "</li><br>";
     
     
     ?>
     <form action="retorna.php" method="POST">
         <label class="form-label"><input type="submit" name="voltar" value="Voltar a Pagina Inicial"></label>
    </form>    
</body>
</html>
