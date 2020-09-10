<?php


# Carrega driver do MongoDB
require_once('./vendor/autoload.php');

use MongoDB\Client as MongoDbClient;

#Faz operações no SQL cache local
class Database
{

    public function connect()
    {
        $servername = "localhost";
        $database = "habeas";
        $username = "root";
        $password = "";
        // Create connection
        $conn = mysqli_connect($servername, $username, $password, $database);
        // Check connection
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        } else {
            return $conn;
        }
    }

    public function query($conn, $table)
    {

        $result = mysqli_query($conn, "SELECT * FROM $table;");
        return $result;
    }

    public function insert($conn, $table, $column, $insert)
    {

        mysqli_query($conn, "INSERT INTO $table ($column) VALUES $insert;");
    }

    public function clean($conn)
    {

        if (mysqli_num_rows($result = mysqli_query($conn, "SELECT * FROM `mongodata`")) > 30) {

            mysqli_query($conn, "DELETE FROM `mongodata` WHERE `id` = ( SELECT MIN(`id`) FROM `mongodata` )");
        }
    }
}

#Acessa o servidor Mongo remoto
class Mongo
{

    public $mongoDbClient;
    public $index_varas;

    public function __construct()
    {

        try {
            $this->mongoDbClient = new MongoDbClient('String_de_conexão');
        } catch (Exception $error) {
            echo $error->getMessage();
            die(1);
        }
    }

    // public function connect() {

    //     try {
    //         $mongoDbClient = new MongoDbClient('mongodb://electrolite:Tupolev1997@habeasdata.fearp.usp.br:27017/geral?authSource=admin&readPreference=primary&appname=MongoDB%20Compass&ssl=false');
    //         return $mongoDbClient;
    //     } catch (Exception $error) {
    //         echo $error->getMessage(); die(1);
    //     }
    // }

    function get_string_between($string, $start, $end)
    {
        $string = ' ' . $string;
        $ini = strpos($string, $start);
        if ($ini == 0) return '';
        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;
        return substr($string, $ini, $len);
    }

    // PARSEIA A INFORMAÇÃO DE VARA DA STRING Dados_Fórum
    // O MOTIVO DE NÃO UTILIZAR REGEX É QUE STRPOS E SUBSTR APRESENTAM UM TEMPO
    // MELHOR, E PERMITEM PARSEAR MAIS RAPIDAMENTE OS DADOS
    public function get_vara($entry)
    {

        if ($posix = strpos($entry['Dados_Fórum'], 'ª VARA')) {

            $posix -= 2;
            $vara = substr($entry['Dados_Fórum'], $posix);
            //$vara = substr($entry['Dados_Fórum'], $posix);

            if ($posix = strstr($vara, 'JUIZ(A)', true)) {
                $vara = trim($posix);
            } else if ($posix = strstr($vara, 'JUIZ', true)) {
                $vara = trim($posix);
            } else if ($posix = strstr($vara, 'JUIZA', true)) {
                $vara = trim($posix);
            } else if ($posix = strstr($vara, 'JUÍZA', true)) {
                $vara = trim($posix);
            } else if ($posix = strstr($vara, 'JUIZES', true)) {
                $vara = trim($posix);
            } else if ($posix = strstr($vara, 'JUÍZES', true)) {
                $vara = trim($posix);
            } else {
                $vara = $entry['Dados_Fórum'];
            }
        } else {

            if (strpos($entry['Dados_Fórum'], 'JUIZ(A)'))
                $vara = trim('VARA ' . $this->get_string_between($entry['Dados_Fórum'], 'VARA', 'JUIZ(A)'));
            else if (strpos($entry['Dados_Fórum'], 'JUIZ'))
                $vara = trim('VARA ' . $this->get_string_between($entry['Dados_Fórum'], 'VARA', 'JUIZ'));
            else if (strpos($entry['Dados_Fórum'], 'JUIZA'))
                $vara = trim('VARA ' . $this->get_string_between($entry['Dados_Fórum'], 'VARA', 'JUIZA'));
            else if (strpos($entry['Dados_Fórum'], 'JUÍZA'))
                $vara = trim('VARA ' . $this->get_string_between($entry['Dados_Fórum'], 'VARA', 'JUÍZA'));
            else if (strpos($entry['Dados_Fórum'], 'JUIZES'))
                $vara = trim('VARA ' . $this->get_string_between($entry['Dados_Fórum'], 'VARA', 'JUIZES'));
            else if (strpos($entry['Dados_Fórum'], 'JUÍZES'))
                $vara = trim('VARA ' . $this->get_string_between($entry['Dados_Fórum'], 'VARA', 'JUÍZES'));
            else
                $vara = $entry['Dados_Fórum'];
            // $vara = strstr($entry['Dados_Fórum'], "VARA"); //pega todo texto a partir da agulha
            // $vara = strstr($vara, "JUIZ(A)", true); //pega todo texto antes da agulha
        }

        return $vara;
    }

    // SIMILAR AO GET_VARA, MAS PARA PEGAR O NOME DO JUIZ
    public function get_juiz($entry)
    {

        if ($posix = strpos($entry['Dados_Fórum'], 'JUIZ(A)')) {

            $juiz = trim(substr($entry['Dados_Fórum'], $posix + 7));

            if ($posix = strpos($juiz, 'ESCRIVÃ(O)'))
                $juiz = substr($juiz, 0, $posix);

            else if ($posix = strpos($juiz, 'ESCRIVÃO'))
                $juiz = substr($juiz, 0, $posix);

            else if ($posix = strpos($juiz, 'ESCRIVÃ'))
                $juiz = substr($juiz, 0, $posix);

            else
                $juiz = 'Falhou (interior): ' . $entry['Dados_Fórum'];
        } else if ($posix = strpos($entry['Dados_Fórum'], 'JUIZ')) {

            $juiz = trim(substr($entry['Dados_Fórum'], $posix + 4));

            if ($posix = strpos($juiz, 'ESCRIVÃ(O)'))
                $juiz = substr($juiz, 0, $posix);

            else if ($posix = strpos($juiz, 'ESCRIVÃO'))
                $juiz = substr($juiz, 0, $posix);

            else if ($posix = strpos($juiz, 'ESCRIVÃ'))
                $juiz = substr($juiz, 0, $posix);

            else
                $juiz = 'Falhou (interior else if 1): ' . $entry['Dados_Fórum'];
        } else if ($posix = strpos($entry['Dados_Fórum'], 'JUIZA')) {

            $juiz = trim(substr($entry['Dados_Fórum'], $posix + 5));

            if ($posix = strpos($juiz, 'ESCRIVÃ(O)'))
                $juiz = substr($juiz, 0, $posix);

            else if ($posix = strpos($juiz, 'ESCRIVÃO'))
                $juiz = substr($juiz, 0, $posix);

            else if ($posix = strpos($juiz, 'ESCRIVÃ'))
                $juiz = substr($juiz, 0, $posix);

            else
                $juiz = 'Falhou (interior, else if 2): ' . $entry['Dados_Fórum'];
        } else
            $juiz = 'Falhou (exterior): ' . $entry['Dados_Fórum'];

        if ($juiz == '') {
            $juiz = 'Falhou (Vazio): ' . $entry['Dados_Fórum'];
        }

        $juiz = trim(str_replace('DE DIREITO', '', $juiz));

        return $juiz;
    }

    // LISTA E PARSEIA AS COLEÇÕES NO MONGODB, GERANDO O NOME DAS COMARCAS NO MENU DE SELEÇÃO
    public function list_collection()
    {

        //$client = $this->connect();
        $database = $this->mongoDbClient->sao_paulo;
        $i = 0;
        $names = array();
        $nomes = array();

        foreach ($database->listCollectionNames() as $collectionName) {
            $entry[] = $collectionName;
        }

        #Coloca comarcas em ordem alfabética
        function compareASCII($a, $b)
        {
            $at = iconv('UTF-8', 'ASCII//TRANSLIT', $a);
            $bt = iconv('UTF-8', 'ASCII//TRANSLIT', $b);
            return strcmp($at, $bt);
        }
        uasort($entry, 'compareASCII');

        foreach ($entry as $collectionName) {

            $names[$i]['non-parsed'] = $collectionName;
            $a = $collectionName;
            if (strpos($a, '_foro_')) {
                list($a, $b) = explode('_foro_', $collectionName);
            }
            $a = str_replace('_', ' ', $a);
            $a = mb_convert_case($a, MB_CASE_TITLE, "UTF-8");

            $c = ' Foro ' . $b;
            $c = str_replace('_', ' ', $c);
            $c = mb_convert_case($c, MB_CASE_TITLE, "UTF-8");

            $b = str_replace('regional_', '', $b);
            $b = str_replace('_', ' ', $b);
            $b = mb_convert_case($b, MB_CASE_TITLE, "UTF-8");

            $names[$i]['parsed'] = $a;
            $names[$i]['parsed2'] = $b;
            $names[$i]['parsed3'] = $c;

            array_push($nomes, $a);

            //print_r($a . '<br>');
            $i++;
            $a = '';
            $b = '';
            $c = '';
        }

        return $names;
    }

    // LISTA OS ASSUNTOS DOS PROCESSOS DE UMA VARA
    public function list_assunto($collection_filter, $vara = 'VARA')
    {

        #Query assuntos
        $collection = $this->mongoDbClient->sao_paulo->$collection_filter;

        $result = $collection->aggregate([
            ['$match' => [
                'Assunto' => ['$nin' => [null]],
                "Dados_Fórum" => ['$regex' => "$vara"]
            ]],
            ['$sortByCount' => '$Assunto'],
            ['$limit' => 200],
            ['$sort' => ['Count' => -1]]
        ]);

        //Converte $result, um conjunto de documentos BSON em array
        $array = json_decode(json_encode($result->toArray(), true), true);

        return $array;
    }

    // LISTA SITUAÇÕES DOS PROCESSOS
    public function list_situacao($collection_filter)
    {

        #Query
        $collection = $this->mongoDbClient->sao_paulo->$collection_filter;
        $result = $collection->find(
            [
                'Classe' => 'Cumprimento de sentença',
                "Dados_Fórum" => ['$regex' => "VARA"]
            ]
        );

        foreach ($result as $entry) {
            echo $entry['_id'], ': ', $entry['Classe'], ': ', $entry['Sentenca'][0]['Situacao'], "<br>";
        }
    }

    // FUNC OBSOLETA
    public function list_varas($path)
    {

        $this->index_varas = file($path, FILE_IGNORE_NEW_LINES);
    }

    // PEGA OS PROCESSOS AGRUPADOS PELA VARA
    public function vara($collection_filter, $assunto = null)
    {

        $collection = $this->mongoDbClient->sao_paulo->$collection_filter;
        if ($assunto == null)
            $result = $collection->find(
                [

                    "Dados_Fórum" => ['$regex' => "VARA"],

                ],
                [
                    'projection' => [
                        'Sentenca' => 1,
                        'Dados_Fórum' => 1
                    ],
                ]
            );
        else
            $result = $collection->find(
                [

                    "Dados_Fórum" => ['$regex' => "VARA"],
                    "Assunto" => ['$in' => ["$assunto"]]

                ],
                [
                    'projection' => [
                        'Sentenca' => 1,
                        'Dados_Fórum' => 1
                    ],
                ]
            );

        $situacao = array();
        foreach ($result as $entry) {

            $vara = $this->get_vara($entry);

            if (!isset($situacao[$vara])) {
                $situacao[$vara] = array();
            }

            if (isset($situacao[$vara][$entry['Sentenca'][0]['Situacao']])) {

                $situacao[$vara][$entry['Sentenca'][0]['Situacao']] += 1;
            } else {

                $situacao[$vara][$entry['Sentenca'][0]['Situacao']] = 1;
            }
        }

        return $situacao;
    }

    // PEGA OS PROCESSOS AGRUPADOS PELA VARA E PELO JUIZ RESPONSÁVEL
    public function juiz($collection_filter, $assunto = null)
    {

        $collection = $this->mongoDbClient->sao_paulo->$collection_filter;
        if ($assunto == null) {
            $result = $collection->find(
                [

                    "Dados_Fórum" => ['$regex' => "VARA"],

                ],
                [
                    'projection' => [
                        'Sentenca' => 1,
                        'Dados_Fórum' => 1
                    ],
                ]
            );
        } else {
            $result = $collection->find(
                [

                    "Dados_Fórum" => ['$regex' => "VARA"],
                    "Assunto" => ['$in' => ["$assunto"]]

                ],
                [
                    'projection' => [
                        'Sentenca' => 1,
                        'Dados_Fórum' => 1
                    ],
                ]
            );
        }

        $situacao = array();
        foreach ($result as $entry) {

            $vara = $this->get_vara($entry);

            $juiz = $this->get_juiz($entry);

            if (!isset($situacao[$vara][$juiz])) {
                $situacao[$vara][$juiz] = array();
            }

            if (isset($situacao[$vara][$juiz][$entry['Sentenca'][0]['Situacao']])) {

                $situacao[$vara][$juiz][$entry['Sentenca'][0]['Situacao']] += 1;
            } else {

                $situacao[$vara][$juiz][$entry['Sentenca'][0]['Situacao']] = 1;
                //$situacao[$vara][$juiz]['count'][] = 1;

            }
        }

        return $situacao;
    }

    // PEGA OS PROCESSOS AGRUPADOS PELA VARA E A PESSOA DO AUTOR
    public function pessoa_autor($collection_filter, $assunto = null)
    {

        $collection = $this->mongoDbClient->sao_paulo->$collection_filter;
        if ($assunto == null) {
            $result = $collection->find(
                [

                    "Dados_Fórum" => ['$regex' => "VARA"],
                    "Autor.Pessoa" => [
                        '$in' => ["Jurídica", "Física"]
                    ]

                ],
                [
                    'projection' => [
                        'Autor' => 1,
                        'Sentenca' => 1,
                        'Dados_Fórum' => 1
                    ],
                ]
            );
        } else {
            $result = $collection->find(
                [

                    "Dados_Fórum" => ['$regex' => "VARA"],
                    "Assunto" => ['$in' => ["$assunto"]],

                    "Autor.Pessoa" => [
                        '$in' => ["Jurídica", "Física"]
                    ]

                ],
                [
                    'projection' => [
                        'Autor' => 1,
                        'Sentenca' => 1,
                        'Dados_Fórum' => 1
                    ],
                ]
            );
        }

        $situacao = array();
        foreach ($result as $entry) {

            $vara = $this->get_vara($entry);

            if (!isset($situacao[$vara])) {
                $situacao[$vara] = array(

                    'fisica' => array(),
                    'juridica' => array()

                );
            }

            if ($entry['Autor']['Pessoa'] == 'Física') {

                if (isset($situacao[$vara]['fisica'][$entry['Sentenca'][0]['Situacao']])) {

                    $situacao[$vara]['fisica'][$entry['Sentenca'][0]['Situacao']] += 1;
                } else {

                    $situacao[$vara]['fisica'][$entry['Sentenca'][0]['Situacao']] = 1;
                }
            } else {

                if (isset($situacao[$vara]['juridica'][$entry['Sentenca'][0]['Situacao']])) {

                    $situacao[$vara]['juridica'][$entry['Sentenca'][0]['Situacao']] += 1;
                } else {

                    $situacao[$vara]['juridica'][$entry['Sentenca'][0]['Situacao']] = 1;
                }
            }
        }

        return $situacao;
    }

    // PEGA OS PROCESSOS AGRUPADOS PELA VARA E O GENERO DO AUTOR
    public function genero_autor($collection_filter, $assunto = null)
    {

        $collection = $this->mongoDbClient->sao_paulo->$collection_filter;
        if ($assunto == null) {
            $result = $collection->find(
                [

                    "Dados_Fórum" => ['$regex' => "VARA"],
                    "Autor.Pessoa" => [
                        '$in' => ["Física"]
                    ]

                ],
                [
                    'projection' => [
                        'Autor' => 1,
                        'Sentenca' => 1,
                        'Dados_Fórum' => 1
                    ],
                ]
            );
        } else {
            $result = $collection->find(
                [

                    "Dados_Fórum" => ['$regex' => "VARA"],
                    "Autor.Pessoa" => [
                        '$in' => ["Física"]
                    ],

                    "Assunto" => ['$in' => ["$assunto"]]

                ],
                [
                    'projection' => [
                        'Autor' => 1,
                        'Sentenca' => 1,
                        'Dados_Fórum' => 1
                    ],
                ]
            );
        }

        $situacao = array();
        foreach ($result as $entry) {

            $vara = $this->get_vara($entry);

            if (!isset($situacao[$vara])) {
                $situacao[$vara] = array(

                    'feminino' => array(),
                    'masculino' => array()

                );
            }

            if ($entry['Autor']['Genero'] == 'Feminino') {

                if (isset($situacao[$vara]['feminino'][$entry['Sentenca'][0]['Situacao']])) {

                    $situacao[$vara]['feminino'][$entry['Sentenca'][0]['Situacao']] += 1;
                } else {

                    $situacao[$vara]['feminino'][$entry['Sentenca'][0]['Situacao']] = 1;
                }
            } else {

                if (isset($situacao[$vara]['masculino'][$entry['Sentenca'][0]['Situacao']])) {

                    $situacao[$vara]['masculino'][$entry['Sentenca'][0]['Situacao']] += 1;
                } else {

                    $situacao[$vara]['masculino'][$entry['Sentenca'][0]['Situacao']] = 1;
                }
            }
        }

        return $situacao;
    }

    // PEGA OS PROCESSOS, AGRUPADOS PELA VARA E O GENERO DO REU
    public function genero_reu($collection_filter, $assunto = null)
    {

        $collection = $this->mongoDbClient->sao_paulo->$collection_filter;
        if ($assunto == null) {
            $result = $collection->find(
                [

                    "Dados_Fórum" => ['$regex' => "VARA"],
                    "Reu.Pessoa" => [
                        '$in' => ["Física"]
                    ]

                ],
                [
                    'projection' => [
                        'Reu' => 1,
                        'Sentenca' => 1,
                        'Dados_Fórum' => 1
                    ],
                ]
            );
        } else {
            $result = $collection->find(
                [

                    "Dados_Fórum" => ['$regex' => "VARA"],
                    "Reu.Pessoa" => [
                        '$in' => ["Física"]
                    ],

                    "Assunto" => ['$in' => ["$assunto"]]

                ],
                [
                    'projection' => [
                        'Reu' => 1,
                        'Sentenca' => 1,
                        'Dados_Fórum' => 1
                    ],
                ]
            );
        }

        $situacao = array();
        foreach ($result as $entry) {

            $vara = $this->get_vara($entry);

            if (!isset($situacao[$vara])) {
                $situacao[$vara] = array(

                    'feminino' => array(),
                    'masculino' => array()

                );
            }

            if ($entry['Reu']['Genero'] == 'Feminino') {

                if (isset($situacao[$vara]['feminino'][$entry['Sentenca'][0]['Situacao']])) {

                    $situacao[$vara]['feminino'][$entry['Sentenca'][0]['Situacao']] += 1;
                } else {

                    $situacao[$vara]['feminino'][$entry['Sentenca'][0]['Situacao']] = 1;
                }
            } else {

                if (isset($situacao[$vara]['masculino'][$entry['Sentenca'][0]['Situacao']])) {

                    $situacao[$vara]['masculino'][$entry['Sentenca'][0]['Situacao']] += 1;
                } else {

                    $situacao[$vara]['masculino'][$entry['Sentenca'][0]['Situacao']] = 1;
                }
            }
        }

        return $situacao;
    }

    // BUSCA UM PROCESSO ESPECÍFICO COM BASE EM SEU NÚMERO
    public function busca($collection_filter, $processo)
    {

        //$client = $this->connect();
        $collection = $this->mongoDbClient->sao_paulo->$collection_filter;
        $result = $collection->find(
            [
                'Processo' => $processo
            ],
            [
                'projection' => [
                    'Assunto' => 1,
                    'Processo' => 1,
                    'Data' => 1,
                    'Conteúdo' => 1,
                    'Sentenca' => 1,
                    'Dados_Fórum' => 1,
                ],
            ]
        );
        $resp = array();

        foreach ($result as $entry) {

            $resp['vara'] = $this->get_vara($entry);

            if (isset($entry['Assunto']))
                $resp['assunto'][] = $entry['Assunto'];

            $resp['processo'][] = $entry['Processo'];
            $resp['data'][] = $entry['Data'];
            $resp['conteudo'][] = $entry['Conteúdo'];
            $resp['situacao'][] = $entry['Sentenca'][0]['Situacao'];
        }

        return $resp;
    }

    //Deletar depois
    public function connect_find($collection_filter)
    {

        //$client = $this->connect();
        $collection = $this->mongoDbClient->sao_paulo->$collection_filter;
        $result = $collection->find(['Classe' => 'Cumprimento de sentença']);
        foreach ($result as $entry) {
            echo $entry['_id'], ': ', $entry['Classe'], ':', $entry['Reu']['Nome'], "<br>";
        }
    }

    // APRESENTA UMA LISTA DE PROCESSOS COM BASE EM 3 FILTROS (ASSUNTO, VARA E SITUAÇÃO)
    public function consulta($collection_filter, $vara, $assunto, $resultado)
    {

        $filter = "Dados_Fórum";

        $collection = $this->mongoDbClient->sao_paulo->$collection_filter;

        $resp = array();

        $result = $collection->find(
            [

                "Dados_Fórum" => ['$regex' => "$vara"],
                "Assunto" => ['$regex' => "$assunto"],
                "Sentenca.0.Situacao" => ['$regex' => "$resultado"],

            ],
            [
                'projection' => [
                    'Dados_Fórum' => 1,
                    'Processo' => 1,
                    'Assunto' => 1,
                    'Sentenca' => 1,
                    'Processo' => 1,
                    'Data' => 1,
                    'Conteúdo' => 1
                ],
            ]
        );

        foreach ($result as $entry) {

            $vara = $this->get_vara($entry);

            array_push($resp, array(
                'vara' => $vara,
                'assunto' => $entry['Assunto'],
                'processo' => $entry['Processo'],
                'data' => substr($entry['Data'], 0, 4) . '/' . substr($entry['Data'], 4, 2) . '/' . substr($entry['Data'], 6, 2),
                'conteudo' => $entry['Conteúdo'],
                'resultado' => $entry['Sentenca'][0]['Situacao']
            ));
        }

        return $resp;
    }
}
