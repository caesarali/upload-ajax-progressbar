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
					setTimeout("location.href = 'index2.php'", 3000);
				} else {
					var msg = '<strong>Error!</strong> Gagal meng-upload file.';
					$('.msg.alert-danger').show();
					$('.msg.alert-danger .text').html(msg);
				}
			},
		});
	});
});