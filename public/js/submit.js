$(function(){
  $('#chat-form').on('submit', function (event) {

    let postTable = $('#post-table');
    let chatForm = $('#chat-form');
    chatForm.find('.invalid-feedback').remove();
    chatForm.removeClass('was-validated');

    event.preventDefault();

    $.ajax({
      type: chatForm.attr('method'),
      url: chatForm.attr('action'),
      data: chatForm.serialize(),
    }).done(function(data) {
      postTable.html(data);
      chatForm[0].reset();
      chatForm.find('.invalid-feedback').remove();
    }).fail(function(jqXHR, textStatus, errorThrown) {
      let $field, fieldName, $feedback;

      chatForm.addClass('was-validated');

      for (fieldName in jqXHR.responseJSON) {
        $field = $('[name*="[' + fieldName + ']"]');
        $feedback = $('<div class="invalid-feedback"></div>');
        $feedback.html(jqXHR.responseJSON[fieldName]);
        $field.parent().append($feedback);
      }
    });

  });
});
