$('.js-btn-submit').on('click', function (event) {

  let postTable = $('#post-table');
  let chatForm = $('#chat-form');

  event.preventDefault();

  $.ajax({
    type: chatForm.attr('method'),
    url: chatForm.attr('action'),
    data: chatForm.serialize(),
  }).done(function(data) {
    postTable.html(data);
    chatForm[0].reset();
  });

});








