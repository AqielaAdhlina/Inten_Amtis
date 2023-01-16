<html>

<head>
	<title>Calculate</title>
	<style type="text/css">
		table {
			border-collapse: collapse;
			margin-top: 10px;
		}

		th,
		td {
			padding: 8px 15px;
    		text-align: left;
		}

		th {
			border-bottom: 1px solid black;
		}

		th.th-right {
			min-width: 150px;
		}

		td {
			border-bottom: 1px solid grey;
		}

		p {
			font-weight: bold;
		}

		.error {
			color: red;
		}
	</style>
</head>

<body>

	<form method="POST">
		<fieldset>
			<legend>Calculate</legend>

			<p><b>Voltage:</b> <input type="text" name="voltage" size="20" maxlength="40" value="<?php if (isset($_POST['submit'])) echo $_POST['voltage'] ?>" /> Voltage(v)</p>
			<p><b>Current :</b> <input type="text" name="current" size="20" maxlength="40" value="<?php if (isset($_POST['submit'])) echo $_POST['current'] ?>"  /> Ampere (A)</p>
			<p><b>Current Rate:</b> <input type="text" name="currentrate" size="20" maxlength="40" value="<?php if (isset($_POST['submit'])) echo $_POST['currentrate'] ?>"  /> sen /kWh</p>
			<input type="submit" name="submit" value="Calculate" />
		</fieldset>
	</form>

	<?php 
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		
		$errors = null;

		// Validate voltage action
		if (!empty($_POST['voltage'])) {
			$voltage = $_POST['voltage'];
		} else {
			$errors[] = 'You forgot to enter your voltage!';
		}

		// Validate current 
		if (!empty($_POST['current'])) {
			$current = $_POST['current'];
		} else {
			$errors[] = 'You forgot to enter your current!';
		}

		// Validate current rate 
		if (!empty($_POST['currentrate'])) {
			$currentrate = $_POST['currentrate'];
		} else {
			$errors[] = 'You forgot to enter your current rate!';
		}
	
		if(empty($errors)) { ?>

	<table>
			<thead>
				<tr>
					<th>#</th>
					<th>Hour</th>
					<th class="th-right">Energy (kWh)</th>
					<th class="th-right">Total (RM)</th>
				</tr>
			</thead>
			<tbody>
				<?php
				// If everything is okay, print the message. 
				if ($voltage && $current) {
					$i = 0;

					// Calculation
					$power = $voltage * $current; // 61.56
					$powerkwh = $power / 1000; // 0.06156
					$rate = $currentrate / 100; // 21.80 / 100 = 0.218
				?>

				<fieldset>
					<p>POWER: <?= $powerkwh ?>kw</p>
					<p>RATE: <?= $rate ?>RM</p>
				</fieldset>

				<?php for ($hours = 1; $hours <= 24; $hours++) {
						$i++;

						// Calculate the total. 
						$energy = round(($powerkwh * $hours) * 1000, 2); //61.56
						$energykwh = $energy / 1000; // 0.06156
						$total = round($energykwh * ($currentrate / 100), 2); //0.01
				?>
					<tr>
						<td><p><?= $i ?></p></td>
						<td><?= $hours ?></td>
						<td><?= $energykwh ?></td>
						<td><?= $total ?></td>
					</tr>
				<?php } ?>
			</tbody>
		</table>
	<?php }
		} 
	} ?>

		<?php if (!empty($errors)) {
                foreach ($errors as $msg) {
                    echo "<span class='error'>- <b>$msg</b><br />\n</span>";
                }
            }
            ?>
</body>

</html>