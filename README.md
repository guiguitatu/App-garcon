# App-garcom

<h4>Para melhor entendimento vamos ao entendimento do manual.</h4><br>

Isso é um passo a seguir:
<li>Passo</li>

Isso é uma seção:
> Seção


Isso é uma Observação:

>> Observação

<br>

>Agora, vamos para o manual 

<br>

> Antes da instalação do app, é necessário configurar o ambiente para ele funcionar. <br> Para isso você deve:

<li>Criar uma pasta dentro da pasta "Astracon" chamada appgarcom.</li>
<li>Descompactar a pasta App-garcon-xx.xx.xx.zip para a pasta anteriormente criada. </li>
<li>Extrair por inteiro a pasta compactada <b>php.zip</b> que está dentro da pasta do app para o disco local C ' C:/ '</li>
<li>Copiar o arquivo FBCLIENT.dll para a pasta ' <b>C:/Windows/System32 </b>'</li><br>

<li><b>Se o IP do SERVIDOR estiver fixo, ir para a próxima seção, se não seguir os passos abaixo:</b></li>

<li> Para fixar o ip vá em configurações -> Rede e Internet</li> <br> 
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
<li>Clicando nele vai aparecer as opções de internet que o SERVIDOR está utilizando, clicar com o botão direito na opção que o SERVIDOR estiver utilizando e clicar em <b>Propriedades</b></li><br>
<img src="./imgs/Readme/prop.png">
<li> Depois de clicado em Propriedades, procurar na tela o <b>Protocolo de IP versão 4</b> e clicar duas vezes nele.</li><br>
<img src="./imgs/Readme/ip4.png">
<li> Depois de aberto clicar em <b>Usar o seguinte endereço de ip</b> e colcoar o ip anotado anteriormente, porém, no lugar da bola vermelha, colocar .200 no campo <b>Endereço de ip</b>, depois de colocado é só apertar a tecla <b>TAB</b> no teclado e apertar em ok, ele vai preencher automáticamente o campo de baixo.</li><br>
<img src="./imgs/Readme/ip42.png"><br>
<li>Depois clique em ok na janela de propriedade e feche a janela do adaptador</li><br>
<img src="./imgs/Readme/ok.png"><br><br><br>

> Após o preparo do ambiente, fazer os seguintes passos para a instalação:

<li>Verificar o nome do computador SERVIDOR e colocar um nome fácil, pois vai ser usado para configuração.</li><br>

<li>Entrar na pasta ' <b>C:/Astracon/appgarcom </b>'</li>
<li> Clicar com o botão direito em <b>.htaccess</b> e clicar em <b>Abrir com</b> e escolher o bloco de notas.
<li> Depois trocar o texto <b>ipcomputador</b> para o ip do SERVIDOR.</li>

>>Você pode abrir os outros arquivos clicando com o botão direito e em <b>Abrir com...</b>, selecionando o bloco de notas, isso serve para os próximos arquivos que possam ser abertos mais para frente.
<li> Abrir com o bloco de notas o arquivo <b>trocanome.php</b> e procure por duas linhas iguais à essa: 

>>//Troque essa linha de baixo:

<li> Troque a linha de baixo delas os textos "nomepc", que é o nome do SERVIDOR, e "caminhoarquivoFDBsistema" para o arquivo do arquivo do FDB no SERVIDOR e salvar o arquivo.</li>

> exemplo, se o nome do computador for 'Servidor' e o arquivo FDB estiver na pasta 'C:/Astracon/Dados', irá ficar assim: <br> firebird:host=Servidor;dbname=C:/Astracon/Dados/NOMEDOSEUARQUIVOFDB.fdb;charset=utf8

<li> Abrir com o bloco de notas o arquivo <b>serv.ps1</b> e trocar "ipdocomputador" para o ip do SERVIDOR.</li>

<br>

>    terminado a instalação, agora: <br><br>Para fazer o app rodar você deve:

<li> Clicar com o botão direito no arquivo, ou atalho, do arquivo serv.ps1 e clicar em <b>executar com o powershell</b>, assim vai abrir uma janela azul ou preta, que é a janela de controle do app, você pode minimizar essa página.</li><br>
<img src="./imgs/Readme/abrirpower.png">

>> Ele vai abrir assim: <br> <img src="./imgs/Readme/power.png">

>>não fechar essa janela, se fechar ela, o app não ai rodar.

<br><br>

>    Para acessar o app você deve:
<li>Entrar em um navegador de sua preferência e colocar o ip do SERVIDOR previamente configurado + ":3030"</li>

>>Exemplo, se o IP do SERVIDOR for: 192.168.1.50 colocar no navegador: <br> 192.168.1.50:3030

>  Pronto, o app já está no ar e você pode acessá-lo de qualquer dispositívo conectado
na mesma internet do SERVIDOR que está rodando o app.
