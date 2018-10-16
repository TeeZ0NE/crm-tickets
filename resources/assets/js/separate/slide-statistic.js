/*
 * This is a JavaScript Scratchpad.
 *
 * Enter some JavaScript, then Right Click or choose from the Execute Menu:
 * 1. Run to evaluate the selected text (Ctrl+R),
 * 2. Inspect to bring up an Object Inspector on the result (Ctrl+I), or,
 * 3. Display to insert the result in a comment after the selection. (Ctrl+L)
 */

$('.A4WJF').on('click',function(event){
  event.preventDefault();
  if($(this).find('.fa-plus-square').hasClass('d-none')){
    $(this).find('.fa-plus-square').removeClass('d-none');
    $(this).find('.fa-minus-square').addClass('d-none');
    $(this).parent().next().slideUp('fast');
  }
  else{
    $(this).find('.fa-minus-square').removeClass('d-none');
    $(this).find('.fa-plus-square').addClass('d-none');
    $(this).parent().next().slideDown('fast');
  }
});




