---PORTUGUES

Para vizualizar é necessário ter um banco de dados SQL e um software de comunicação entre o código fonte e o banco de dados. Eu utilizei o XAMPP e o MySQL, que podem ser obtidos respectivamente em www.apachefriends.org/pt_br/index.html e https://dev.mysql.com/downloads/windows/installer/8.0.html. 

É possível fazer via outros softwares, mas como desenvolvi utilizando o XAMPP e MySQL, segue uma explicação de como visualizar o site através desses softwares.

É necessário fazer a configuração da comunicação do banco de dados com o fonte responsável pela comunicação com o banco de dados.


*** Instalação do MySQL ***

Instale o MySQL em uma pasta de preferência sua.

Após a instalação, dentro do MySQL, crie um novo banco de dados.

Configure o banco de dados com:
	host = 127.0.0.1
	port = 3306
	username = root
	password = 

Em seguida copie ou abra o script do arquivo db_render.sql, e execute TUDO, para criar as tabelas que serão utilizadas. 


*** Instalação do XAMPP ***

Ele deve ser instalado no disco local do computador. Dentro da pasta de instalação haverá uma pasta 'htdocs'. É necessário mover o conteúdo obtido pelo GITHub para essa pasta, para que o XAMPP possa ter acesso aos códigos fonte. 

Ao executar o XAMPP, de um Start nos Modules 'Apache' e 'MySQL'.


*** Abrino a Pagina no Navegador ***

Digite no seu navegador: localhost/mew
Login de Administrador: admin Senha:123
Login de usuario: empregado Senha:123

----------

---ENGLISH

To visualize, you must have an SQL database and a source code and database comunication software. I used XAMPP and MySQL, which can be obtained respectively from www.apachefriends.org/pt_br/index.html andhttps://dev.mysql.com/downloads/windows/installer/8.0.html.

You may do it with other softwares, but as I made it using XAMPP and MySQL, here's a tutorial on how to use them.

You must configurate the database comunication with the source code with the database.


*** Installing MySQL ***

Install MySQL in a folder of your choice.

After the instalation, in MySQL, create a new database.

Configure the database as follows:
	host = 127.0.0.1
	port = 3306
	username = root
	password = 

After that copy or open the script in the archive db_render.sql, e execute ALL, to create the tables that will be used.

*** Installing XAMPP ***

It must be installed in the local computer. In the installation folder, there wil be the 'htdocs' folder. You must move what you downloaded from GITHub to that folder, to give XAMPP the access to those files.

Then, execute XAMPP, and Start the Modules 'Apache' and 'MySQL'.

*** Open your Browser ***

Type in the browse bar: localhost/mew

Admin Login: admin Password:123
User Login: empregado Password:123


