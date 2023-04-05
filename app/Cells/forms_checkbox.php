<div class="form-check <?= $hidden ? 'visually-hidden' : ''; ?>">
    <input 
        class="form-check-input" 
        type="checkbox" 
        value="<?= $value ?>" 
        name="<?= $value ?>"
        id="<?= $value ?>" 
        <?= $checked ? 'checked' : ''?>
        <?= $disabled ? 'disabled' : ''?>
    >
    <label class="form-check-label" for="<?= $value ?>">
        <?= $label == '' ? ucfirst($value) : $label ?>
    </label>
</div>
