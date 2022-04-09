<div id="chat"></div>
<script>
(function() {
	const ww = $(window).width();

	const $chat_big = $('<script>');
	$chat_big.attr('id', 'b10221165cf6c0d1a68697387f9b5581');
	$chat_big.attr('src', 'https://www.amplitur.com.br/chat/script.php?id=b10221165cf6c0d1a68697387f9b5581');

	const $chat_small = $('<script>');
	$chat_small.attr('id', 'ab4841bf4e5485807b0a743d97ad13f7');
	$chat_small.attr('src', 'https://www.amplitur.com.br/chat/script.php?id=ab4841bf4e5485807b0a743d97ad13f7');

	if(ww < 768) {
		$('#chat').append($chat_small);
	} else {
		$('#chat').append($chat_big);
	}
})();
</script>