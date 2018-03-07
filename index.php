<?php require_once './WhatconvertsAPI.php' ?>
<?php
try {
	$wcapi = new WhatconvertsAPI();
	$leads = $wcapi->getLeadByLandingPage(date('Y-m-d', strtotime('-60 days')));
} catch (Exception $ex) {
	$leads = $ex->getMessage();	
}
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Whatconverts Leads by Landing Page</title>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		
		<!-- Bootstrap -->
		<!-- Latest compiled and minified CSS -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

		<!-- Optional theme -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

		<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
		<script type="text/javascript">
			// Load the Visualization API and the corechart package.
			google.charts.load('current', {'packages':['corechart', 'table']});

			// Set a callback to run when the Google Visualization API is loaded.
			google.charts.setOnLoadCallback(drawChart);
			
			function drawChart() {
				var googledata = loadData();
				
				// Create the data table.
				var data = new google.visualization.DataTable();
				data.addColumn('string', 'Landing Page');
				data.addColumn('number', 'Transactions');
				data.addColumn('number', 'Chats');
				data.addColumn('number', 'Events');
				data.addColumn('number', 'Forms');
				data.addColumn('number', 'Calls');
		
				data.addRows(googledata);

				// Set chart options
				var options = {
						height: 400,
						title:'Leads by Landing Page',
						isStacked: true,
					};

				// Instantiate and draw our chart, passing in some options.
				var chart = new google.visualization.BarChart(document.getElementById('google_chart'));
				chart.draw(data, options);
				
				var view = new google.visualization.DataView(data);
				view.setColumns([0, 1, 2, 3, 4, 5, {
						calc: function(dataTable, row) {
							return dataTable.getValue(row, 1) 
									+ dataTable.getValue(row, 2)
									+ dataTable.getValue(row, 3)
									+ dataTable.getValue(row, 4)
									+ dataTable.getValue(row, 5);
						},
						type: 'number',
						label: 'Total'
					}]);

				var table = new google.visualization.Table(document.getElementById('google_table'));
				table.draw(view, {width: '100%'});
			}
			function loadData() {
				var data = <?php echo $leads ?>;
				
				var output = [];
				for ( url in data ) {
					if(data.hasOwnProperty(url)) {
						// ['Landing Page URL', 'Transaction', 'Chat', 'Event', 'Web Form', 'Phone Call'];
						output.push([url, 
								data[url]['Transaction'],
								data[url]['Chat'],
								data[url]['Event'],
								data[url]['Web Form'],
								data[url]['Phone Call'],
							]);
					}
				}
				
				return output;
			}
		</script>
	</head>
	<body>
		<div class="container">
			<div class="row">
				<div class="col-xs-12 col-md-8 col-md-offset-2">
					<div id="google_chart"></div>
					<div id="google_table"></div>
					
					<pre><?php echo $leads ?></pre>
				</div>
			</div>
		</div>
	</body>
</html>
