/*
 * This is a JavaScript Scratchpad.
 *
 * Enter some JavaScript, then Right Click or choose from the Execute Menu:
 * 1. Run to evaluate the selected text (Ctrl+R),
 * 2. Inspect to bring up an Object Inspector on the result (Ctrl+I), or,
 * 3. Display to insert the result in a comment after the selection. (Ctrl+L)
 */
var wr_content = document.getElementById('wr-content');
var buttons = '.l-buttons[data-type="buttons"]';
var observer = new MutationObserver(function (mutations) {
  mutations.forEach(function (mutation) {
    if ($.find('#wr-content textarea[name="message"]').length && $('.lv-29').parent().parent().css('display') !== 'none') {
      //       $('#wr-content').has('.l-buttons[data-type="buttons"]').find('.l-buttons[data-type="buttons"]').addClass('square');
      if (!$('#wr-content').has(buttons).find(buttons).hasClass('square')) {
        $('.b-button[data-name="ok_message"]').add('.b-button[data-name="ok"]').one('click', sendData);
        $(buttons).addClass('square').append('<p style="color:green">◼</p>');
      }
    }
  })
});
var config = {
  attributes: true,
  childList: true,
  characterData: true
};
observer.observe(wr_content, config);
function sendData() {
  /*var reg = /https?:\/\/(?:www.|my.)?([-a-zа-я\_\d]+)./i;
  var res = reg.exec(window.location.href);
  var service = res[1];
  var action_url = 'https://adminarea.secom.com.ua/store_stat.php';
  var name = $('#topLinks').find('.b-user-menu__header').text();
  var ticketid = $('input[name="elid"]').val();
  var subject = $('h2[data-mn="title"]').text();
  var d = new Date;
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
  var xhr = new XMLHttpRequest;
  if (xhr) {
    xhr.open('POST', action_url, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.send(data);
    xhr.onreadystatechange = function () {
      if (xhr.readyState == XMLHttpRequest.DONE && xhr.status == 200) {
        console.info(xhr.responseText)
      }
    }
  }*/
  console.info('clk');
} /*
Exception: SyntaxError: expected expression, got '.'
@Scratchpad/4:15
*/
