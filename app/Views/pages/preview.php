<!DOCTYPE html>
<html>

<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title><?= $title ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <link rel="stylesheet" href="<?= base_url() ?>/assets/css/style.css">
    <link rel="stylesheet" href="<?= base_url() ?>/assets/css/components.css">
    <meta name='viewport' content='width=device-width, initial-scale=1'>
</head>

<body>
    <?php if ($extension == 'pdf') { ?>
        <div class="embed-responsive embed-responsive-16by9">
            <iframe class="embed-responsive-item" src="<?= $file ?>" allowfullscreen></iframe>
        </div>
    <?php } else { ?>
        <img src="<?= $file ?>" class="img-fluid" alt="Responsive image" />
    <?php } ?>


    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="<?= base_url() ?>/assets/js/stisla.js"></script>
    <script src="<?= base_url() ?>/assets/js/scripts.js"></script>
    <script src="<?= base_url() ?>/assets/js/custom.js"></script>
</body>

</html>