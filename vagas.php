<!-- Layout -->
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">

<?php

/* Valores recebidos do formulário  */
$arquivo = $_FILES['arquivo'];
$nome = $_POST['name'];
$replyto = $_POST['email']; // Email que será respondido
$vaga = $_POST['vaga'];
$assunto = 'Vaga para ' . $vaga;

$to = "rh@recautomacao.com.br";
$remetente = "recmes@recautomacao.com"; // Deve ser um email válido do domínio






    /* Cabeçalho da mensagem  */
    $boundary = "XYZ-" . date("dmYis") . "-ZYX";
    $headers = "MIME-Version: 1.0\n";
    $headers .= "From: $remetente\n";
    $headers .= "Reply-To: $replyto\n";
    $headers .= "Content-type: multipart/mixed; boundary=\"$boundary\"\r\n";
    $headers .= "$boundary\n";

    /* Layout da mensagem  */
    $corpo_mensagem = " 
<br>VAGAS - REC
<br>--------------------------------------------<br>
<br><strong>Nome:</strong> $nome
<br><strong>Email:</strong> $replyto
<br><strong>Vaga:</strong> $vaga
<br><strong>Currículo:</strong> Anexado
<br><br>--------------------------------------------
";

    /* Função que codifica o anexo para poder ser enviado na mensagem  */
    if (file_exists($arquivo["tmp_name"]) and !empty($arquivo)) {

        $fp = fopen($_FILES["arquivo"]["tmp_name"], "rb"); // Abri o arquivo enviado.
        $anexo = fread($fp, filesize($_FILES["arquivo"]["tmp_name"])); // Le o arquivo aberto na linha anterior
        $anexo = base64_encode($anexo); // Codifica os dados com MIME para o e-mail 
        fclose($fp); // Fecha o arquivo aberto anteriormente
        $anexo = chunk_split($anexo); // Divide a variável do arquivo em pequenos pedaços para poder enviar
        $mensagem = "--$boundary\n"; // Nas linhas abaixo possuem os parâmetros de formatação e codificação, juntamente com a inclusão do arquivo anexado no corpo da mensagem
        $mensagem .= "Content-Transfer-Encoding: 8bits\n";
        $mensagem .= "Content-Type: text/html; charset=\"utf-8\"\n\n";
        $mensagem .= "$corpo_mensagem\n";
        $mensagem .= "--$boundary\n";
        $mensagem .= "Content-Type: " . $arquivo["type"] . "\n";
        $mensagem .= "Content-Disposition: attachment; filename=\"" . $arquivo["name"] . "\"\n";
        $mensagem .= "Content-Transfer-Encoding: base64\n\n";
        $mensagem .= "$anexo\n";
        $mensagem .= "--$boundary--\r\n";
    } else // Caso não tenha anexo
    {
        $mensagem = "--$boundary\n";
        $mensagem .= "Content-Transfer-Encoding: 8bits\n";
        $mensagem .= "Content-Type: text/html; charset=\"utf-8\"\n\n";
        $mensagem .= "$corpo_mensagem\n";
    }

    /* Função que envia a mensagem  */
    
    $voltar = "<a style=\'background-color: white; padding: 1%;'\ href='vagas.html'>Voltar para Vagas</a>";
    $voltarREC = "<a style=\'background-color: white; padding: 1%;'\ href='index.html'>Voltar para REC</a>";

    if (isset($_POST['email']) && !empty($_POST['email'])) {
        if (isset($_POST['name']) && !empty($_POST['name'])) {
            if (isset($_POST['vaga']) && !empty($_POST['vaga'])) {
                
                if (mail($to, $assunto, $mensagem, $headers)) {
                    echo "<br><br><center><b><font color='green'>Enviado com sucesso! <br>" . $voltarREC;
                    header('Refresh: 10; URL=http://recautomacao.com.br/');
                } else {
                    echo "<br><br><center><b><font color='red'>Ocorreu um erro ao enviar!";
                }

                
            
                
                
                
            }else{
            echo "<br><br><center><b><font color='red'>Ocorreu um erro ao enviar a mensagem!<br>Vaga Vazia<br>" . $voltar;
            }
        }else{
        echo "<br><br><center><b><font color='red'>Ocorreu um erro ao enviar a mensagem!<br>Nome Vazio <br>" .$voltar;
        }
    }else{
    echo "<br><br><center><b><font color='red'>Ocorreu um erro ao enviar a mensagem!<br>Email Vazio<br>" . $voltar;
    }
?>

