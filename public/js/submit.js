$('.js-btn-submit').on('click', function (event) {

  let postTable = $('#post-table');
  let chatForm = $('#chat-form');

  event.preventDefault();
  chatForm.submit();

  $.get(this.href, function (data) {
    postTable.html(data);
  });
});
