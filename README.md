# App-garcon

    Para a instalação é preciso seguir esses passos:

<li> Verificar o endereço de ip do computador (Se ele não estiver fixo, fixar ele)</li>
<li> Para isso vá em configurações -> Rede e Internet</li> <br> 
<img src="./imgs/Readme/rede.png" style="width: 600px; margin-left: 50px"> <br>
<li> Em Status -> Propriedades</li> <br>
<img src="./imgs/Readme/proprede.png" style="width: 300px; margin-left: 50px"> <br>
<li> E verifique se a Configuração de ip está em automático:</li> <br>
<img src="./imgs/Readme/auto.png" style="height: 400px; margin-left: 50px"> <br>
<li>Se estiver automático fazer o seguinte:  <br>
Clicar na tecla Windows e digitar cmd, vai aparecer isso: <br>
Clique em abrir.</li>
<img src="./imgs/Readme/cmd.png" style="width: 300px; margin-left: 50px"> <br>
<li> Depois de aberto, digitar o comando <b>IPCONFIG</b>:
<br> Vai aparecer essa tela, anote os números, com os pontos, desses dois elementos, IPv4 e Sub-rede.</li>
<img src="./imgs/Readme/cmd2.png" style="width: 500px">

<li> Mudar em <b>.htaccess</b> o texto ipcomputador para o ip novo</li>
<li> Trocar em <b>trocanome.php</b> os texto "hostnoserv" para o nome do computador configurado e "caminhoparaoarquivo" para o arquivo do banco de dados do cliente.</li>

    Para fazer o app ir para o ar você deve:

<li> ir para o arquivo <b>serv.ps1</b> e trocar "caminhoparaapastadoserv" para o caminho da pasta que está o app e "ipdocomputador" para o ip do computador fixado anteriormente.</li>
<li> Clicar com o botão direito no arquivo, ou atalho, do arquivo serv.ps1 e clicar em "executar com o powershell", assim vai abrir uma janela azul, que é a janela de controle do app, você pode minimizar essa página.</li>
<u><b>obs:</b></u> não fechar essa janela, se fechar ela, o app não ai rodar.

    Para abrir o app você deve:
<li>Entrar em um navegador de sua preferência e colocar o ip do computador mais ":3030"</li>

    Pronto, o app já está no ar e você pode acessá-lo de qualquer dispositívo conectado
    na mesma internet do computador que está rodando o app.

