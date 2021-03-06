$(function () {
	$('#frmAddTicketReply .pull-left').append('<div class="input-group"><span class="input-group-addon" id="time-input-addon" style="color:red">Введи время!</span><input type="number" class="form-control YSsSx2Wdbn" placeholder="Время" aria-describedby="time-input-addon" min="0" value="0"></div>');
	$('#btnPostReply').on('click', function (event) {
		event.preventDefault();
		var reg = /https?:\/\/(?:www.|bill(?:ing)?.|my.|cp.)?([-a-zа-я\_\d]+)./i;
		var res = reg.exec(window.location.href);
		var service = res[1];
		var action_url = 'https://adminarea.secom.com.ua/store_stat.php';
		var name = $('#watch-ticket').data('adminFullName');
		var ticketid = $('#watch-ticket').data('ticketId');
		var subject = ($('#currentSubject').val()).trim();
		/*time 4 request*/
		var time = $('.YSsSx2Wdbn').val();
		var d = new Date();
		var d_h = ('0' + d.getHours()).slice(-2);
		var d_m = ('0' + d.getMinutes()).slice(-2);
		var d_s = ('0' + d.getSeconds()).slice(-2);
		var lastreply = d.toLocaleDateString('uk-ua') + ' ' + d_h + ':' + d_m + ':' + d_s;
		var data = {
			lastreply: lastreply,
			time_uses: time,
			subject: subject,
			admin: name,
			ticketid: ticketid
		};
		data = 'service=' + service + '&' + $.param(data);
		var xhr = new XMLHttpRequest();
		if (xhr) {
			xhr.open('POST', action_url, true);
			xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
			// 			xhr.setRequestHeader('Access-Control-Allow-Origin','*');
			xhr.send(data);
			xhr.onreadystatechange = function () { //Вызывает функцию при смене состояния.
				if (xhr.readyState == XMLHttpRequest.DONE && xhr.status == 200) {
					console.info(xhr.responseText);
				}
			}
		}
		$(this).parent().submit();
	})
});