<?php


    require_once './vendor/autoload.php';

    $loader = new \Twig\Loader\FilesystemLoader('.');
    $twig = new \Twig\Environment($loader, [
    ]);
    $template_data = [];
    $template_path = '404.twig.html';


    $template = $_REQUEST['template']??'home';
    if ($template == 'home') {
        $template_data ['articles'] = json_decode(file_get_contents('articles/_list.json'),true);
        $template_path = 'home.twig.html';
    }

    if ($template == 'article') {
        $article_path = $_REQUEST['path']??false;
        if ($article_path) {
            $template_data ['article'] = json_decode(file_get_contents('articles/'.$article_path.'.json'),true);
            $template_path = 'article.twig.html';
        }
        
    }

    


    
    print $twig->render($template_path,$template_data);