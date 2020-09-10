<?php
class Graphs extends Controller
{

    // DEFINE AS CORES DOS GRÁFICOS
    private function colors()
    {

        $colors['extincao_do_processo'] = '#5652AF';
        $colors['improcedente'] = '#FF4F00';
        $colors['nc'] = '#9FA2A4';
        $colors['sem_merito'] = '#BD2F2B';
        $colors['parcialmente_procedente'] = '#FFC200';
        $colors['procedente'] = '#0e84cc';
        $colors['homologado'] = '#00AC6B';

        return $colors;
    }

    // RECEBE UMA LEGENDA E DEVOLVE O CÓDIGO DA COR CORRESPONDENTE
    public function get_color($color)
    {

        $colors = $this->colors();
        if (isset($colors[$color]))
            return $colors[$color];
        else
            return '#BC243C';
    }

    // PARSEIA AS LEGENDAS, RETIRANDO '_' E COLOCANDO ACENTOS
    public function word_parser($word)
    {

        $dict['extincao_do_processo'] = 'Extinção do Processo';
        $dict['improcedente'] = 'Improcedente';
        $dict['nc'] = 'NC (Não Computado)';
        $dict['sem_merito'] = 'Sem Mérito';
        $dict['parcialmente_procedente'] = 'Parcialmente Procedente';
        $dict['procedente'] = 'Procedente';
        $dict['homologado'] = 'Homologado';

        if (isset($dict[$word]))
            return $dict[$word];
        else
            return 'Legenda não encontrada';
    }

    // CALCULA PORCENTAGEM DOS VALORES, FUNÇÃO NÃO É MAIS USADA
    // OS VALORES SÃO CALCULADOS DENTRO DO FORMATTER DO DATALABELS (NA FUNC plot_JS)
    public function percentage_calculator($array) {

        $total = array_sum($array);
        $return = array();

        foreach($array as $value) {

            if($total != 0)
                $return[] = floor((($value/$total) * 100)+0.5);
            else
                $return[] = 0;
        }

        return $return;
    }

    // FUNÇÃO SIMPLES PARA ORDENAR AS LEGENDAS (7 POSSIBILIDADES)
    // AS LEGENDAS DEVEM TER UMA ORDEM ESPECÍFICA DE ACORDO COM A SITUAÇÃO DO PROCESSO
    public function order($array) {

        $temp = array();
        
        if(isset($array['homologado']))
        $temp['homologado'] = $array['homologado'];

        if(isset($array['procedente']))
            $temp['procedente'] = $array['procedente'];

        if(isset($array['parcialmente_procedente']))
            $temp['parcialmente_procedente'] = $array['parcialmente_procedente'];

        if(isset($array['improcedente']))
            $temp['improcedente'] = $array['improcedente'];

        if(isset($array['sem_merito']))
            $temp['sem_merito'] = $array['sem_merito'];

        if(isset($array['extincao_do_processo']))
            $temp['extincao_do_processo'] = $array['extincao_do_processo'];

        if(isset($array['nc']))
            $temp['nc'] = $array['nc'];

        return $temp;

    }

    #CRIA OS GRÁFICOS
    public function plot_js($data, $id_canvas) {

        // ORDENA AS LEGENDAS
        $data = $this->order($data);
        
        // INICIO DO JS PARA GERAR A PLOTAGEM
        echo"
            <script>

            // DETERMINA AQUE O GRÁFICO SERÁ INSERIDO NO CHART_'ID_CANVAS'
            var ctx = document.getElementById('chart_$id_canvas').getContext('2d');
            var chart = new Chart(ctx, {
                type: 'horizontalBar',

                // INSERE OS DADOS NOS GRÁFICOS, COMO LEGENDA E VALORES
                data: {
                    labels: [ ";
                        foreach ($data as $key => $situacao)
                            echo" '".$this->word_parser($key)."',";
                    echo"],
                    datasets: [{
                        maxBarThickness: 30,
                        label: 'My First dataset',
                        backgroundColor: [";
                        foreach ($data as $key => $situacao)
                            echo" '".$this->get_color($key)."',";
                        echo"],
                        data: [";
                        foreach($data as $value)
                            echo"$value, ";
                        echo"]
                    }]
                },

                // OPTIONS DO GRÁFICO
                options: {
                    plugins: {
                        // GERA O NÚMERO AO LADO DA BARRA
                        datalabels: {
                            color: '#808080',
                            anchor: 'end',
                            align: 'right',
                            offset: '-2',
                            // CÁLCULA A PORCENTAGEM DOS VALORES ABSOLUTOS
                            formatter: (value, ctx) => {
                                let datasets = ctx.chart.data.datasets;
                                if (datasets.indexOf(ctx.dataset) === datasets.length - 1) {
                                  let sum = datasets[0].data.reduce((a, b) => a + b, 0);
                                  let percentage = Math.round((value / sum) * 100) + '%';
                                  return percentage;
                                } else {
                                  return percentage;
                                }
                            }
                        }
                    },
                    // OCULTA LEGENDA
                    legend: {
                        display: false
                    },
                    // POSICIONA O GRÁFICO QUE OS VALORES CAIBAM NO CANVAS
                    layout: {
                        padding: {
                            right: 35,
                        }
                    },
                    
                    // CUSTOMIZA AS TOOLTIPS DO GRÁFICO
                    tooltips: {
                        mode: 'index',
                        position: 'nearest',
                        callbacks: {
                            title: function() {},
                            label: function(tooltipItems, data) { 
                                return data.labels[tooltipItems.index] +': ' + data.datasets[tooltipItems.datasetIndex].data[tooltipItems.index];
                            }
                        }
                    },

                    //CONFIGURAÇÕES PARA ESCALAS E EIXOS DOS GRÁFICOS, REMOVE AS LINHAS QUADRICULADAS DAS PLOTAGENS
                    'scales': {
                        'xAxes': [{
                            'ticks': {
                                'beginAtZero': true,
                                min: 0,	
								beginAtZero: true,
                                callback: function(value, index, values) {
									return value;
								}
                            },
                            'gridLines': {
                                'drawOnChartArea': false
                            }
                        }],
                        'yAxes': [{
                            'ticks': {
                                'beginAtZero': true
                            },
                            'gridLines': {
                                'drawOnChartArea': false
                            }
                        }]
                    }
                }
            });
            </script>
        ";

    }
}
