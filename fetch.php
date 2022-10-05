<?php

    

    require_once 'vendor/autoload.php';

    $loader = new \Twig\Loader\FilesystemLoader('template');
    $twig = new \Twig\Environment($loader, [
    ]);
    $fetch = true;

    // fetch articles
    if($fetch) {
        $articles_url = "https://api.cosmicjs.com/v2/buckets/theindependencyco-production/objects?pretty=true&query=%7B%22type%22%3A%22articles%22%7D&read_key=bodqgUVirgLMpeR5tkqVSNdjUDLrFkESRbJ3PeArsLVYgIZcZb&limit=20&props=slug,title,content,metadata,";
        $articles_data = file_get_contents($articles_url);
        file_put_contents( 'source/_articles.json', $articles_data );
    }
    $articles_data = json_decode( file_get_contents('source/_articles.json'), true );
    
    // generate flat articles
    foreach($articles_data['objects'] as $article) {
        $html = $twig->render("article.twig.html", ['article'=>$article] );
       
        // template override?
        $template_override = $article['metadata']['templateoverride']??false;
        if ($template_override) {
            $html = $twig->render($template_override, ['article'=>$article] );    
        } else {
            $html = $twig->render("article.twig.html", ['article'=>$article] );    
        }

        file_put_contents ( "docs/articles/{$article['slug']}.html", $html );

    }


    // generate homepage
    $html = $twig->render("home.twig.html", ['articles'=>$articles_data['objects']] );
    file_put_contents('docs/index.html',$html);


    print $html;


    
    exit; 