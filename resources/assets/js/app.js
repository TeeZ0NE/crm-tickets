/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

// window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

// Vue.component('example-component', require('./components/ExampleComponent.vue'));

// const app = new Vue({
//     el: '#app'
// });

// rename admin name in showAllAdmins page
$(".rename-admin").on("click", function (event) {
	event.preventDefault();
	let old_name = $(this).attr('data-name');
	let new_name = prompt('Rename admin', old_name);
	if (new_name!==old_name && new_name!==null && new_name!==''){
		$("input[name='_method']").val('PUT');
		$(this).parent().prepend("<input type=\"hidden\" name=\"name\" value=\"" + new_name + "\">");
		$(this).parent().submit();
	}
});