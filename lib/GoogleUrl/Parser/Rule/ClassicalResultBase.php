<?php

namespace GoogleUrl\Parser\Rule;


use GoogleUrl\GoogleDOM;
use GoogleUrl\Result\ClassicalResult;
use GoogleUrl\Result\ResultSetInterface;

abstract class ClassicalResultBase extends AbstractNaturalRule {

    protected function _parseItem(GoogleDOM $googleDOM, \DomElement $itemDom, ResultSetInterface $resultSet, $currentPosition){

        $xpath = $googleDOM->getXpath();

        // query to find the tilte/url
        /* @var $aTag \DOMElement */
        $aTag=$xpath->query("descendant::h3[@class='r'][1]/a",$itemDom)
            //take the first element, because anyway only one can be found
            ->item(0);

        if (!$aTag) {
            return $currentPosition;
        }

        $url=$aTag->getAttribute("href"); // get the link of the result

        $title=$aTag->nodeValue; // get the title of the result

        $currentPosition++;
        $truePosition = $currentPosition + ($googleDOM->getNumberResults() * $googleDOM->getPage());

        $item = new ClassicalResult();
        $item->setPosition($truePosition);
        $item->setSnippet($itemDom->C14N());
        $item->setTitle($title);
        $item->setTargetUrl($url);

        $resultSet->addItem($item);

        return $currentPosition;

    }

}