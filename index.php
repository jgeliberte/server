<?php $session = mt_rand(1,999); ?>
<!DOCTYPE html>
<html>
<head>
	<title>Chat</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width">
	<script src="js/jquery.js" type="text/javascript"></script>
	<style type="text/css">
	* {margin:0;padding:0;box-sizing:border-box;font-family:arial,sans-serif;resize:none;}
	html,body {width:100%;height:100%;}
	#wrapper {position:relative;margin:auto;max-width:1000px;height:100%;}
	#chat_output {position:absolute;top:0;left:0;padding:20px;width:100%;height:calc(100% - 100px);z-index: -1;}
	#chat_input {position:absolute;bottom:0;left:0;padding:10px;width:100%;height:100px;border:1px solid #ccc;}
	#send {z-index: 1000000;}
	</style>
</head>
<body>
	<div id="wrapper">
		<div id="chat_output"></div>
		<textarea id="chat_input" placeholder="No one will know that you're here.."></textarea>
		<input type="button" id="send" value="SEND" />
		<script type="text/javascript">
		jQuery(function($){
			// Websocket
			var websocket_server = new WebSocket("ws://localhost:8080/");
			websocket_server.onopen = function(e) {
				websocket_server.send(
					JSON.stringify({
						'type':'socket',
						'user_id':<?php echo $session ?>
					})
				);
			};
			websocket_server.onerror = function(e) {
				// Errorhandling
			}
			websocket_server.onmessage = function(e)
			{
				var json = JSON.parse(e.data);
				switch(json.type) {
					case 'chat':
						$('#chat_output').append(json.msg);
						break;
				}
			}
			// Events
			$('#send').click(function(){
					var chat_msg = $('#chat_input').val();
					websocket_server.send(
						JSON.stringify({
							'type':'chat',
							'user_id': <?php echo $session ?>,
							'chat_msg':chat_msg
						})
					);
					$('#chat_input').val('');
			})
		});
		</script>
	</div>
</body>
</html>