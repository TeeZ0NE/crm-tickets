$(".rename-admin").on("click", function (event) {
	event.preventDefault();
	var old_name = $(this).data('name');
	var new_name = prompt('Rename admin', old_name);
	if (new_name !== old_name && new_name !== null && new_name !== '') {
		$("input[name='_method']").val('PUT');
		$(this).parent().prepend("<input type=\"hidden\" name=\"name\" value=\"" + new_name + "\">");
		$(this).parent().submit();
	}
});