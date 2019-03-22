<!DOCTYPE html>
<html>
<head>
	<title>Persamaan Non Linear</title>
	<link href="assets/css/bootstrap.css" rel="stylesheet"/>
</head>
<body>
	<div class="container" style="margin-top: 50px;">
		<div id="loading" style="display: none;">
			<span style="z-index: 9999; top: 50%; left: 50%; position: fixed; border: 1px solid">Processing ...</span>
		</div>
		<form id="form_input" class="form-inline">
			<div class="row mb-2">
				<div class="col">
					<input type="text" class="form-control" name="fungsi" id="fungsi" placeholder="Fungsi">
				</div>
				<div class="col">
					<input type="text" class="form-control" name="esilon" id="esilon" placeholder="Esilon">
				</div>
				<div class="col">
					<input type="text" class="form-control" name="selang_a" id="selang_a" placeholder="Selang A">
				</div>
				<div class="col">
					<input type="text" class="form-control" name="selang_b" id="selang_b" placeholder="Selang B">
				</div>
				<div class="col">
					<select name="metode" class="form-control">
						<option value="">-- Pilih Metode --</option>
						<option value="biseksi">Biseksi Bagi Dua</option>
						<option value="regula">Regula Falsi</option>
					</select>
				</div>
			</div>
			<div class="row">
				<div class="col">
					<button class="btn btn-primary" type="submit" name="btn_submit" id="btn_submit">Proses</button>
				</div>
			</div>
		</form>
		
		<table id="tbl_data" class="table table-bordered table-striped" style="max-width: 1200px;">
			<thead></thead>
			<tbody></tbody>
		</table>

		<div style="font-size: 2em; border: 1px solid; padding: 10px; border-radius: 10px;" class="text-center mt-2">
			<span class="hasil"></span>
		</div>
	</div>
	
	<script src="assets/js/jquery-3.3.1.js"></script>
	<script src="assets/js/bootstrap.js"></script>
	<script>
		$(document).ready(function() {
			$("#btn_submit").on("click", function(e) {
				e.preventDefault();

				$.ajax({
					url: "proses.php",
					type: "POST",
					data: $("#form_input").serialize(),
					dataType: "json",
					beforeSend: function() { $("#loading").show(); },
					success: function(result) {
						$("#tbl_data").find("thead").empty();
						$("#tbl_data").find("tbody").empty();
						$(".hasil").html("");
						setTimeout(function() {
							var row_table_body = "";
							row_table_head = `
							<tr>
							<th>Iterasi</th>
							<th>Nilai a</th>
							<th>Nilai b</th>
							<th>Nilai c</th>
							<th>Nilai f(a)</th>
							<th>Nilai f(b)</th>
							<th>Nilai f(c)</th>
							<th>Tanda f(c) ke f(a)</th>
							<th>f(c) <= Esilon ?</th>
							</tr>
							`;

							if (result !=  null) {
								$.each(result, function(key, value) {
									if (key <= 30) {
										row_table_body += "<tr>";
										row_table_body += "<td>" + key + "</td>";
										row_table_body += "<td>" + parseFloat(value.selang_a) + "</td>";
										row_table_body += "<td>" + parseFloat(value.selang_b) + "</td>";
										row_table_body += "<td>" + parseFloat(value.c) + "</td>";
										row_table_body += "<td>" + parseFloat(value.fungsi_a) + "</td>";
										row_table_body += "<td>" + parseFloat(value.fungsi_b) + "</td>";
										row_table_body += "<td>" + parseFloat(value.fungsi_c) + "</td>";
										row_table_body += "<td>" + value.tanda + "</td>";
										row_table_body += "<td>" + value.memenuhi + "</td>";
										row_table_body += "</tr>";
										$(".hasil").html("Nilai C = " + value.c);
									} else {
										row_table_body += "<tr><td colspan=9>" + value + "</td></tr>";
										$(".hasil").html("");
									}
								});
							} else {
								row_table_body += "<tr><td colspan=9 class=text-center>Tidak Ada Data / Ada Kesalahan Input</td></tr>";
							}

							$("#tbl_data").find("thead").html(row_table_head);
							$("#tbl_data").find("tbody").html(row_table_body);
						}, 1000);
					},
					complete: function() {
						setTimeout(function() {
							$("#loading").hide();
						}, 1000);
					}
				});
			});
		});
	</script>
</body>
</html>