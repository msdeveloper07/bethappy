<!--hide fade-->
<div id="UserNotedialog" class="modal" tabindex="-1" role="dialog" aria-labelledby="windowTitleLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <!--<a href="#" class="close" data-dismiss="modal">&times;</a>-->
                <h5 class="modal-title"><?= _('Please enter a note.'); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="<?= _('Close'); ?>">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="divDialogElements">
                    <input id="user_id" name="user_id" type="hidden" value="<?php echo $user_id; ?>" />
                    <textarea class="form-control" id="note" name="note" placeholder="<?= _('Note'); ?>.."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><?= _('Cancel'); ?></button>
                <button class="btn btn-success" onclick="noteokClicked();"><?= _('OK'); ?></button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#UserNotedialog').bind('show', function () {

        });
    });
    function notecloseDialog() {
        $('#UserNotedialog').modal('hide');
        location.reload();
    }
    ;

    function noteokClicked() {
        note = document.getElementById("note").value;
        user_id = document.getElementById("user_id").value;
        console.log(note);   console.log(user_id);
        $.post("/admin/notes/add", {note: note, user_id: user_id}, function (data) {
            console.log(data);
            notecloseDialog();
        });

    }
    ;
</script>