<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Feeder Cloud - Upload</title>

	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<style type="text/css">
		.btn-file {
		    position: relative;
		    overflow: hidden;
		}
		.btn-file input[type=file] {
		    position: absolute;
		    top: 0;
		    right: 0;
		    min-width: 100%;
		    min-height: 100%;
		    font-size: 100px;
		    text-align: right;
		    filter: alpha(opacity=0);
		    opacity: 0;
		    outline: none;
		    background: white;
		    cursor: inherit;
		    display: block;
		}
	</style>
</head>
<body>
	<header>
		<nav class="navbar navbar-default">
			<div class="container">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span> 
					</button>
					<a class="navbar-brand" href="#">Cloud Feeder</a>
				</div>
				<div class="collapse navbar-collapse" id="myNavbar">
					<ul class="nav navbar-nav">
						<li class="active"><a href="#">Upload</a></li>
					</ul>
				</div>
			</div>
		</nav>
	</header>

	<div class="container">
		<div class="row">
			<div class="col-md-6 col-md-offset-3">
				<div class="panel panel-default">
					<div class="panel-heading">
						Upload Files
					</div>
					<div class="panel-body">
						<form method="post" action="" enctype="multipart/form-data">
							<div class="form-group">
								<div class="input-group">
									<span class="input-group-btn">
										<span class="btn btn-default btn-file">
											Browse
											<input type="file" id="file" name="file" required>
										</span>
									</span>
									<input type="text" class="form-control" name="filename" required readonly>
									<span class="input-group-btn">
										<button type="submit" class="btn btn-primary">Upload <span class="glyphicon glyphicon-cloud-upload"></span></button>
									</span>
								</div>
							</div>
						</form>
						<div id="detail" style="display: none;">
							<p><b>Detail file</b></p>
							<ul style="padding-left: 25px">
								<li>Filename : <span id="filename"></span></li>
								<li>Size : <span id="filesize"></span></li>
								<li>Type : <span id="filetype"></span></li>
							</ul>
							<div class="progress" style="display: none;">
								<div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:0%">
									0%
								</div>
							</div>
							<div class="msg alert alert-success alert-dismissable" style="display:none">
								<button class="btn btn-link close" aria-label="close">&times;</button>
								<span class="text"></span>
								<p>Akan dialihkan ke halaman utama setelah 3 detik</p>
							</div>
							<div class="msg alert alert-danger alert-dismissable" style="display:none">
								<button class="btn btn-link close" aria-label="close">&times;</button>
								<span class="text"></span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<script>
		$(document).ready( function() {
            $(document).on('change', '.btn-file :file', function() {
            	var input = $(this),
                label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
            	input.trigger('fileselect', [label]);
            });

            $('.btn-file :file').on('fileselect', function(event, label) {
                var input = $(this).parents('.input-group').find(':text'), log = label;
                if( input.length ) {
                    input.val(log);
                } else {
                    if( log ) alert(log);
                }
            });

            $("#file").change(function(){
                var file = this.files[0];
                var filename = file.name;
                var filesize = file.size;
                var filetype = file.type;

                $('#filename').text(filename);
                $('#filesize').text(filesize + ' bytes');
                $('#filetype').text(filetype);
                $('#detail').show();
                $('.msg').hide();
            });

            $(".close").click(function(){
            	$('#detail').hide();
            	$('.msg').hide();
            });

            $(document).ready(function() {
            	$('form').on('submit', function(event){
            		event.preventDefault();
            		var formData = new FormData($('form')[0]);

            		$('.msg').hide();
            		$('.progress').show();

            		$.ajax({
            			xhr : function() {
            				var xhr = new window.XMLHttpRequest();
            				xhr.upload.addEventListener('progress', function(e){
            					if(e.lengthComputable){
									// console.log('Bytes Loaded : ' + e.loaded);
									// console.log('Total Size : ' + e.total);
									// console.log('Persen : ' + (e.loaded / e.total));
									var percent = Math.round((e.loaded / e.total) * 100);
									$('.progress-bar').attr('aria-valuenow', percent).css('width', percent + '%').text(percent + '%');
								}
							});
            				return xhr;
            			},

            			type : 'POST',
            			url : 'upload.php',
            			data : formData,
            			processData : false,
            			contentType : false,
            			success : function(response){
            				console.log(response);
            				$('form')[0].reset();
            				$('.progress').hide();
            				$('.progress-bar').attr('aria-valuenow', 0).css('width', '0%').text('0%');
            				if (response == 'success') {
	            				var msg = '<strong>Sukses!</strong> File berhasil di upload.';
            					$('.msg.alert-success').show();
	            				$('.msg.alert-success .text').html(msg);
	            				setTimeout("location.href = 'index.php'", 3000);
            				} else {
	            				var msg = '<strong>Error!</strong> Gagal meng-upload file.';
            					$('.msg.alert-danger').show();
	            				$('.msg.alert-danger .text').html(msg);
            				}
            			},
            		});
            	});
            });

        });
	</script>
</body>
</html>