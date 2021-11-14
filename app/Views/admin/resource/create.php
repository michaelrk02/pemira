<div class="container">
    <h3>Create Resource</h3>
    <form method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure?')">
        <div class="input-field">
            <input id="input-id" type="text" name="id" value="<?php echo esc($input['id']); ?>">
            <label for="input-id">ID</label>
        </div>
        <div class="input-field">
            <input id="input-mime" type="text" name="mime" value="<?php echo esc($input['mime']); ?>">
            <label for="input-mime">Content (MIME) Type</label>
        </div>
        <p><label><input id="input-autodetectMIME" type="checkbox" class="filled-in" name="autodetectMIME" <?php echo $input['autodetectMIME'] ? 'checked' : ''; ?>><span>Autodetect MIME</span></label></p>
        <div class="input-field">
            <input id="input-cacheDuration" type="number" name="cacheDuration" value="<?php echo esc($input['cacheDuration']); ?>">
            <label for="input-cacheDuration">Cache Duration (seconds)</label>
        </div>
        <p><label><input id="input-disableCaching" type="checkbox" class="filled-in" name="disableCaching" <?php echo $input['disableCaching'] ? 'checked' : ''; ?>><span>Disable Caching</span></label></p>
        <div class="file-field input-field">
            <div class="btn">
                <span>FILE</span>
                <input type="file" name="file">
            </div>
            <div class="file-path-wrapper">
                <input class="file-path" type="text" placeholder="Upload resource file">
            </div>
        </div>
        <div><button type="submit" class="btn green" name="submit" value="1"><i class="fa fa-plus left"></i> CREATE</button></div>
    </form>
</div>
<script>

function updateInputFields() {
    if (document.getElementById('input-autodetectMIME').checked) {
        $('#input-mime').attr('disabled', 'disabled');
    } else {
        $('#input-mime').attr('disabled', null);
    }
    if (document.getElementById('input-disableCaching').checked) {
        $('#input-cacheDuration').attr('disabled', 'disabled');
    } else {
        $('#input-cacheDuration').attr('disabled', null);
    }
}

$(document).ready(function() {
    updateInputFields();
    $('#input-autodetectMIME').on('change', function(e) {
        updateInputFields();
    });
    $('#input-disableCaching').on('change', function(e) {
        updateInputFields();
    });
});

</script>
