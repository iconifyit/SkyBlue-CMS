<!DOCTYPE html>
<html lang="[[site.site_lang]]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php the_page_title(); ?></title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Custom Skin Styles -->
    <link rel="stylesheet" href="[[skin.path]]css/main.css">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="[[skin.path]]images/favicon.ico">
    <link rel="icon" type="image/png" sizes="32x32" href="[[skin.path]]images/favicon-32x32.png">

    [[page.head_elements]]
    [[page.metadata]]
</head>
<body class="[[page.css_class]]" id="[[page.css_id]]">
