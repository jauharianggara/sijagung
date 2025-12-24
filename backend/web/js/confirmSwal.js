yii.confirm = function (message, okCallback, cancelCallback) {
    swal({
            title: 'Konfirmasi',
            text: message,
            type: 'warning',
            showCancelButton: true,
    }).then((selection) => {
     if(selection.value == true){
       okCallback();
     }else{
       cancelCallback();
     }
    });
}
