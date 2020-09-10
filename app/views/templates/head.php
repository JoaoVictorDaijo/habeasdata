<!DOCTYPE html>

<!--
|
| HEAD TEMPLATE
|
| A head template to show how to load assets and other stuff.
| You can load assets using the url on config.
| @usage:   $this->config[url];/assets/css/nameofyourcss
| @warning: Do not forget php tag, look the example below.

-->

<html lang="pt-br">
<head>
	
	<!-- Meta Info -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	
	<title><?=$this->config['label'];?></title>

	<!-- Bootstrap CSS -->
	<link href="<?=$this->config['url'];?>/assets/css/bootstrap.css" rel="stylesheet">

	<!-- Favicon -->
	<link href="<?=$this->config['url'];?>/assets/img/favicon.png" rel="shortcut icon">

	<!-- Your CSS Here -->
	<link href="<?=$this->config['url'];?>/assets/css/style.css" rel="stylesheet">

	<!-- jQuery -->
	<script src="assets\js\jquery-3.5.1.min.js"></script>

	<!-- ChartJS -->
	<script src="assets\js\Chart.bundle.min.js"></script>

	<!-- DataLabels -->
	<script src="assets\js\chartjs-plugin-datalabels.min.js"></script>
	
	<!-- Karl Popper -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>

</head>

<body>

