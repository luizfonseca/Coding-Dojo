<?php
error_reporting(E_ALL);
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
   $msg = null;
   
   if (is_array($_POST))
   {
      $post = $_POST;
      foreach ($post as $key => $value)
      {
         if ($value == '')
         {
            $msg = array('msg' => '<li>Você deve preencher todos os campos.</li>');
            echo json_encode($msg);
            exit();
            
         }
      }
      $name    = (isset($post['name']) && strlen($post['name']) > 10) ? $post['name'] : $msg .= '<li>Nome muito curto.</li>';
      $mail    = (isset($post['mail']) && filter_var($post['mail'], FILTER_VALIDATE_EMAIL)) ? $post['mail'] : $msg .= '<li>Email inválido.</li>'; 
      $lang    = (isset($post['lang'])) ? $post['lang'] : $msg .= '<li>Você preencheu as linguagens?</li>';
      $city    = (isset($post['city'])) ? $post['city'] : $msg .= '<li>Cidade deve ser preenchida conforme exemplo.</li>';
      
      $pdo = new PDO('mysql:host=bm84.webservidor.net;dbname=weblexia_dojo;charset=UTF-8', 'weblexia_dev', '5G8~bnzuXZt7');
      //$pdo = new PDO('mysql:host=localhost;dbname=dojo_joiners;charset=UTF-8', 'root', '');
      
      $query = $pdo->prepare('SELECT * FROM person WHERE person.mail = :email');
      $query->execute(array(':email' => $mail));
      
      if ($query->rowCount() > 0) $msg .= '<li>E-mail já cadastrado.</li>';
      
      if ($msg == null)
      {
         $query = $pdo->prepare('INSERT INTO person(name, mail, lang_1, city ) VALUES (:name, :mail, :lang_1, :city)');
         $query->execute(array(
         ':name' => $name,
         ':mail' => $mail,
         ':lang_1' => $lang,
         ':city' => $city
         ));
         $header = 'From: Contato Weblexia <contato@weblexia.com>'. "\r\n";
         $header .= "Content-type: text/html; charset=iso-8859-1" . "\r\n";
         $message = '
         <html>
         <body>
            Ola!<br/>
            Obrigado por entrar em contato. Em breve lhe retornaremos com maiores detalhes.<br/>
            <br/><br/>
            
            <br/><br/><br/>
            
            Atenciosamente, <br/><br/>
            
            Luiz Fonseca<br/>
            CEO @ Weblexia<br/>
            <a href="http://facebook.com/theweblexia">Weblexia facebook page</a><br/>
            runeroniek @ gmail.com<br/>
         </body>
         </html>
         ';
         mail($mail, "Coding Dojo em Cuiabá/MT", $message, $header);
         mail('runeroniek@gmail.com', "Coding Dojo em Cuiabá/MT", 'O seguinte e-mail foi cadastrado: '.$mail, $header);
         $response = array('success' => true);
         $response = json_encode($response);
         echo $response;
      }
      else
      {
         $response = array('msg' => $msg);
         $response = json_encode($response);
         echo $response;
      }   
   }
}

?>