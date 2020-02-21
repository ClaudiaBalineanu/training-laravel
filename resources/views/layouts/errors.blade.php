<?php /** @var Illuminate\Support\MessageBag $errors */ ?>

<?php if ($errors->any()) : ?>
    <div class="alert alert-danger">
        <ul>
            <?php foreach ($errors->all() as $error) : ?>
                <li>{{ $error }}</li>
            <?php endforeach ?>
        </ul>
    </div>
<?php endif ?>
