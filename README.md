# App-garcon

>> Antes da instalação do app, é necessário configurar o ambiente para ele funcionar. <br> Para isso você deve:

<li>Criar uma pasta dentro da pasta "Atracon" chamada appgarcon.</li>
<li>Descompactar a pasta da última versão do app para a pasta anteriormente criada.</li>
<li>Extrair a pasta compactada <b>php.zip</b> que está dentro da pasta do app para o disco local C ' C:/ '</li>
<li>Copiar o arquivo FBCLIENT.dll para a pasta ' C:/Windows/System32 '</li><br>

>>    Para a instalação é preciso seguir esses passos:

<li> <b>Verificar o endereço de ip do computador (Se ele não estiver fixo, fixar ele)</b></li>
<li> Para isso vá em configurações -> Rede e Internet</li> <br> 
<img src="./imgs/Readme/rede.png" style="width: 800px; margin-left: 50px"> <br>
<li> Em Status -> Propriedades</li> <br>
<img src="./imgs/Readme/proprede.png" style="width: 300px; margin-left: 50px"> <br>
<li> E verifique se a Configuração de ip está em automático:</li> <br>
<img src="./imgs/Readme/auto.png" style="height: 400px; margin-left: 50px"> <br>
<li>Se estiver automático fazer o seguinte:  <br>
Clicar na tecla Windows e digitar cmd, vai aparecer isso: <br>
Clique em abrir.</li>
<img src="./imgs/Readme/cmd.png" style="width: 300px; margin-left: 50px"> <br>
<li> Depois de aberto, digitar o comando <b>IPCONFIG</b>:
<br> Vai aparecer essa tela, anote esse número, com os pontos, o IPv4.</li>
<img src="./imgs/Readme/cmd2.png" style="width: 700px"><br>
<li> Depois de anotado o número ir emConfigurações -> Rede -> Status e clicar em <b>Alterar opções do adaptador</b></li><br>
<img src="./imgs/Readme/adap.png" style="width: 500px"><br>
<li>Clicando nele vai aparecer as opções de internet que o Computador está utilizando, clicar com o botão direito na opção que o computador estiver utilizando e clicar em <b>Propriedades</b></li><br>
<img src="./imgs/Readme/prop.png">
<li> Depois de clicado em Propriedades, procurar na tela o <b>Protocolo de IP versão 4</b> e clicar duas vezes nele.</li><br>
<img src="./imgs/Readme/ip4.png">
<li> Depois de aberto clicar em <b>Usar o seguinte endereço de ip</b> e colcoar o ip anotado anteriormente no campo <b>Endereço de ip</b>, depois de colocado é só apertar a tecla <b>TAB</b> no teclado e apertar em ok, ele vai preencher automáticamente o campo de baixo.</li><br>
<img src="./imgs/Readme/ip42.png"><br>
<li>Depois clique em ok na janela de propriedade e fechar a janela do adaptador</li><br>
<img src="./imgs/Readme/ok.png">
<li> Depois de fechado, ir em <b>.htaccess</b> na pasta do app e trocar o texto <b>ipcomputador</b> para o ip anotado e previamente configurado.</li>
 
>Se você clicar e ele não abrir, clique com o botão direito nele e clique em <b>Abrir com...</b>, depois procure o bloco de notas, isso serve para os próximos arquivos que possam ser abertos mais para frente.
<li> Procure essas linhas em <b>trocanome.php</b>: 

>//Troque essa linha de baixo:
 
<li> Troque a linha de baixo nela os textos "nomedopc", que é o nome do computador e o texto "caminhoarquivoFDBnosistema" para o arquivo do arquivo do FDB no computador, o mesmo que acessa o sistema.</li>

> exemplo, se o nome do computador for Servidor e o arquivo FDB estiver na pasta C:/Astracon/Dados, irá ficar assim: <br> firebird:host=Servidor;dbname=C:/Astracon/Dados;charset=utf8

<li> ir para o arquivo <b>serv.ps1</b> e trocar "caminhoparaapastadoserv" para o caminho da pasta que está o app e "ipdocomputador" para o ip do computador fixado anteriormente.</li>

<br><br>

 >>    terminado a instalação, agora: <br><br>Para fazer o app rodar você deve:

<li> Clicar com o botão direito no arquivo, ou atalho, do arquivo serv.ps1 e clicar em <b>executar com o powershell</b>, assim vai abrir uma janela azul ou preta, que é a janela de controle do app, você pode minimizar essa página.</li><br>
<img src="./imgs/Readme/abrirpower.png">

> Ele vai abrir assim: <br> <img src="./imgs/Readme/power.png">

>não fechar essa janela, se fechar ela, o app não ai rodar.

<br><br>

>>    Para acessar o app você deve:
<li>Entrar em um navegador de sua preferência e colocar o ip do computador previamente configurado + ":3030"</li>

>Exemplo, se o IP do computador for: 192.168.1.50 colocar no navegador: <br> 192.168.1.50:3030 

>>  Pronto, o app já está no ar e você pode acessá-lo de qualquer dispositívo conectado
    na mesma internet do computador que está rodando o app.
