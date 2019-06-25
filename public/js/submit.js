$('.js-btn-submit').on('click', function (event) {
  let postTable = $('#post-table');
  event.preventDefault();

  $.get(this.href, function (data) {
    postTable.html(data);
  });
});