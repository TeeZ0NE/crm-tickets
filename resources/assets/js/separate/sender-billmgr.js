$('.b-button[data-name="ok_message"]','.b-button[data-name="ok"]').on('click', function () {
	var reg = /https?:\/\/(?:www.)?(?:\w+\.)?([-a-zа-я_\d.]+)\//i;
	var res = reg.exec(window.location.href);
	var service = res[1];
	var action_url = 'https://adminarea.secom.com.ua/store_stat.php';
	var name = $('#topLinks').find('.b-user-menu__header').text();
	var ticketid = $('input[name="elid"]').val();
	var subject = $('h2[data-mn="title"]').text();
	var d = new Date();
	var d_h = ('0' + d.getHours()).slice( - 2);
	var d_m = ('0' + d.getMinutes()).slice( - 2);
	var d_s = ('0' + d.getSeconds()).slice( - 2);
	var lastreply = d.toLocaleDateString('uk-ua') + ' ' + d_h + ':' + d_m + ':' + d_s;
	var data = {
		lastreply: lastreply,
		subject: subject,
		admin: name,
		ticketid: ticketid
	};
	data = 'service=' + service + '&' + $.param(data);
	var xhr = new XMLHttpRequest();
	if (xhr) {
		xhr.open('POST', action_url, true);
		xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		// 						xhr.setRequestHeader('Access-Control-Allow-Origin','*');
		xhr.send(data);
		xhr.onreadystatechange = function () { //Вызывает функцию при смене состояния.
			if (xhr.readyState == XMLHttpRequest.DONE && xhr.status == 200) {
				console.info(xhr.responseText);
			}
		}
	}
	// return false;
});
