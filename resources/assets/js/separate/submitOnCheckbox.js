$('input.submit[type="checkbox"]').on('click', function () {
	$(this).parent().submit();
});