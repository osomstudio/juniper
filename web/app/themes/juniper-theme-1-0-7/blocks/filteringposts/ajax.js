jQuery('.testform').submit(
  (event) => {
    event.preventDefault();

    const ajax_data = {
      action: 'filteringposts',
      nonce: ajax.nonce,
      // custom fields below.
      inputajax1: jQuery('input[name="inputajax1"]').val(),
      inputajax2: jQuery('input[name="inputajax2"]').val(),
      inputajax3: jQuery('input[name="inputajax3"]').val(),
    };

    jQuery.ajax(
      {
        type: 'POST',
        url: ajax.ajax_url,
        data: ajax_data,
        success(data) {
          console.log(data);
        },
        error(xhr, status, errorThrown) {
          console.log('Custom ajax error');
        },
      },
    );
  },
);
