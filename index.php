<?php
require_once("lib/simple_html_dom.php");
require_once("lib/indexClass.php");

$confDescLength = 250;

?>
<html>
    <head>
        <style>
            textarea {
                width: 640px;
                height: 240px;
            }
        </style>
    </head>
    <body>
        <h1>Crawling Experiments</h1>
        <p>Test documents: <a href="source/test-01.html">1st test document</a> | <a href="source/test-02.html">2nd test document</a> | <a href="source/test-03.html">3rd test document</a></p>
        <h2>Experiment 1: Plain HTML</h2>
        <textarea>
            <?php

            $uri = "source/test-01.html";
            
            $html = file_get_html($uri);

            $ex1 = new indexClass();
            $mTitle = $html->find("title");
            $ex1->title = $mTitle[0]->plaintext;

            $mYears = array();

            $mDescSources = $html->find("h1, h2, h3, p");
            
            foreach ($mDescSources as $mDescSource) {
                if (strlen($ex1->description) <= $confDescLength) {
                    $ex1->description .= $mDescSource->plaintext . ". ";
                }
                
                // Extract years
                preg_match("/(\d{4})/", $mDescSource->plaintext, $matches);
                $mYears = array_merge($mYears, $matches);
            }
            
            $ex1->description = substr($ex1->description, 0, $confDescLength);
            
            $ex1->date = array_unique($mYears);
            
            $mImages = $html->find("img");
            foreach ($mImages as $mImage) {
            $ex1->image[] .= $mImage->src;                
            }
            echo $ex1->asXml();
            ?>
        </textarea> 
        
        <h2>Experiment 2: RDFa</h2>
        <textarea>
            <?php

            $uri = "source/test-02.html";
            
            $html = file_get_html($uri);

            $ex1 = new indexClass();
            $mTitle = $html->find("*[property=dc:title]");
            $ex1->title = $mTitle[0]->plaintext;

            $mYears = array();

            $mDescSources = $html->find("*[property=dc:description]");
            
            foreach ($mDescSources as $mDescSource) {
                if (strlen($ex1->description) <= $confDescLength) {
                    $ex1->description .= $mDescSource->plaintext . ". ";
                }
            }
            
            $ex1->description = substr($ex1->description, 0, $confDescLength);
            
            $mBirth = $html->find("*[property=dbp:dateOfBirth]");
            $ex1->date[] = "Born: ".$mBirth[0]->plaintext;
            $mDeath = $html->find("*[property=dbp:dateOfDeath]");
            $ex1->date[] = "Died: ".$mBirth[0]->plaintext;
            
            $mImages = $html->find("img[property=dcmi:Image]");
            foreach ($mImages as $mImage) {
            $ex1->image[] .= $mImage->src;                
            }
            $mPlaces = $html->find("*[property=dc:spatial]");
            foreach ($mPlaces as $mPlace) {
                $ex1->addPoint($mPlace->plaintext);
            }

            echo $ex1->asXml();
            
            
            ?>
        </textarea> 
        
        <h2>Experiment 3: Schema.org, microformats</h2>
        <textarea>
            <?php

            $uri = "source/test-03.html";
            
            $html = file_get_html($uri);

            $ex1 = new indexClass();
            $mTitle = $html->find("*[property=dc:title]");
            $ex1->title = $mTitle[0]->plaintext;

            $mYears = array();

            $mDescSources = $html->find("*[property=dc:description]");
            
            foreach ($mDescSources as $mDescSource) {
                if (strlen($ex1->description) <= $confDescLength) {
                    $ex1->description .= $mDescSource->plaintext . ". ";
                }
            }
            
            $ex1->description = substr($ex1->description, 0, $confDescLength);
            
            $mBirth = $html->find("*[property=dbp:dateOfBirth]");
            $ex1->date[] = "Born: ".$mBirth[0]->plaintext;
            $mDeath = $html->find("*[property=dbp:dateOfDeath]");
            $ex1->date[] = "Died: ".$mBirth[0]->plaintext;
            
            $mImages = $html->find("img[property=dcmi:Image]");
            foreach ($mImages as $mImage) {
            $ex1->image[] .= $mImage->src;                
            }
            $mPlaces = $html->find("*[class=h-geo] span");
            $ex1->addPoint("", $mPlaces[0]->plaintext, $mPlaces[1]->plaintext);

            echo $ex1->asXml();
            
            
            ?>
        </textarea> 

    </body>
</html>
