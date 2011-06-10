(function($) {
$(document).ready(function() {
  $('.socials a').hover(
  function() {
    this.tip = this.title;
    $(this).append('<div class="tipbox">' + this.tip + '</div>');
    $('.tipbox').fadeIn('500');
    $(this).removeAttr('title');
  },
  function() {
    $('.tipbox').fadeOut('250');
    $(this).children().remove();
    this.title = this.tip;
  }
  );
});
})(jQuery);