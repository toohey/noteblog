function modalAlert($title, $body, callback)
{
    if($body===undefined)
    {
        $body = $title;
        $title = "<span style='font-variant:small-caps'>Message</span>";
    }
    confirm($title, $body, undefined, undefined, callback);
}
function waitAlert($body, callback)
{
        modalAlert('<img src="/access/img/wait_anim.gif" style="height:40px;width:40px" />Please Wait...',$body, callback);
}
function confirm(heading, question, cancelButtonTxt, okButtonTxt, callback)
{
    if(cancelButtonTxt===undefined)
        cancelButtonTxt = "Close";
    $id = "modal_"+Math.floor(Math.random()*1000);
    var confirmModal = 
      $('<div class="modal hide fade" id='+$id+'><div class="modal-dialog">' +    
          '<div class="modal-header">' +
            '<a class="close" data-dismiss="modal" >&times;</a>' +
            '<h3 style="font-variant:small-caps">' + heading +'</h3>' +
          '</div>' +

          '<div class="modal-body">' +
            '<p>' + question + '</p>' +
          '</div>' +

          '<div class="modal-footer">' +
            '<a href="#" class="btn'+(okButtonTxt===undefined?(' btn-primary'):'')+'" onclick="$(\'#'+$id+'\').modal(\'hide\');setTimeout(function(){$(\'#'+$id+'\').remove();},1000);">' + 
              cancelButtonTxt + 
            '</a>' +(okButtonTxt!==undefined?(
            '<a href="#" id="okButton" class="btn btn-primary">' + 
              okButtonTxt + 
            '</a>'):'') +
          '</div>' +
        '</div></div>');

    confirmModal.find('#okButton').click(function(event) {
        callback();
        confirmModal.modal('hide');
    });
    confirmModal.modal({keyboard:true});     
};